<?php
/**
 * Bomber.php app/controllers/bomber/Bomber.php
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace
 */
namespace S3Bomber; //our 'global' namespace is now set to S3Bomber

/**
 * Bomber Controller Class (S3Bomber\Bomber)
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time. Feed it a config array during
 * construction and it will run that, otherwise it tries to load s3bomb-config.ini from our root directory.
 * An example config file is in s3bomb-config.ini.example, and an example array is commented out in the index.php file.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class Bomber {

    /**
     * @var mixed command line arguments passed
     */
    private $_argv;

    /**
     * @var mixed our bomber configuration
     */
    private $_config;

    /**
     * @var \Aws\S3\S3Client the client connection to Amazon's AWS S3 service
     */
    private $_s3Client;

    /**
     * @var \S3Bomber\models\persistence\s3bomberDatabase our database connection
     */
    private $_db;

    /**
     * @var Cli\CliAdapter our command line adapter class, allows you to cliEcho, and read CLI arguments
     */
    private $_cliAdapter;

    /**
     * @var Aws\s3 need this to build s3Client
     */
    private $_s3;

    /**
     * @var int the number of photos needing deleting
     */
    public $photosToDelete;

    /**
     * Bomber (Constructor)
     *
     * Builds the Bomber, if passed an array that looks like valid command line arguments, it uses those, otherwise,
     * it gets its config from s3bomb-config.ini
     *
     * @param $argv
     */
    public function __construct($argv=NULL){
        $this->_argv = $argv; //set the command line arguments to our private variable.
        $this->_requireFiles(); //load up and require our files
        $this->_cliAdapter = new \S3Bomber\Cli\CliAdapter($this->_argv); //load command line interface adapter class
        $this->_config = $this->_cliAdapter->getArguments(); //load the command line arguments if any
        $this->_config["file"]          = 's3bomb-config.ini'; //change the ini file name here
        $this->_config["user_supplied"] = TRUE; //assume the config was entered by cli
        $this->_config["file_supplied"] = FALSE; //not from the ini file yet

        /**
         * Check to see if the config is cli supplied
         */
        $this->_config["user_supplied"]         = $this->_cliAdapter->checkConfig($this->_config);
        $this->_checkGetConfig(); //overrides and sets up the config from cli/ini

        /**
         * Load Amazon Web Services Simple Storage Service AWS S3
         */
        $this->_s3 = new \S3Bomber\Aws\s3($this->_config["aws_key"],$this->_config["aws_pass"]);
        $this->_s3Client = $this->_s3->getFactory(); //load the AWS S3 Factory, needed for interacting with their API

        /**
         * Setup the Database connection
         */
        $this->_db = new \S3Bomber\models\persistence\database\s3bomberDatabase (
            $this->_config["mysql_server"],
            $this->_config["mysql_db"],
            $this->_config["mysql_user"],
            $this->_config["mysql_pass"]
        );
        $this->_db->connect(); //connect to the database with the stored information

        /**
         * Count the number of items needing deleting
         *
         * Done by selecting the count of the id column specified in the table specified.
         */
        $this->photosToDelete = $this->_db->countPhotosToBeDeleted(
            $this->_config["mysql_id_column"],
            $this->_config["mysql_table"]
        );

        $this->bomb(); //start the cycle
    }

    /**
     * Loads/Requires the dependent files, usually models.
     */
    private function _requireFiles ()
    {
        $this->_requireCli(); //require the command line interface class
        $this->_requireAwsS3(); //require the AWS S3 Class
        $this->_requireDatabase(); //require the Database
    }

    /**
     * Loads/Requires the Command Line Interface (CLI) Class
     */
    private function _requireCli ()
    {
        require 'app/models/cli/cliAdapter.php'; //require the commadn line interface class
    }

    /**
     * Loads/Requires the ini file and the persistent file manager
     */
    private function _requireIni ()
    {
        require 'app/models/persistence/fileManager/fileManager.php'; //file manager is depended on by s3bomberIni.php
        require 'app/models/persistence/files/ini/s3bomberIni.php'; //require s3bomberIni file
    }

    /**
     * Loads/Requires AWS and S3 Classes
     */
    private function _requireAwsS3 ()
    {
        require 'app/models/aws/Aws.php'; //s3 depends on aws, so require it
        require 'app/models/aws/s3.php'; //require the S3 class
    }

    /**
     * Loads/Requires the database
     */
    private function _requireDatabase ()
    {
        require 'app/models/persistence/database/database.php'; //mysqlDatabase depends on database, so require it
        require 'app/models/persistence/database/mysqlDatabase.php'; //s3bomberDatabase depends on mysqlDatabase, so require it
        require 'app/models/persistence/database/queryStrings.php'; //s3bomberDatabase depends on queryStrings, so require it
        require 'app/models/persistence/database/s3bomberDatabase.php'; //require s3bomberDatabase
    }

    /**
     * Either loads the default config, or that found in the ini file
     */
    private function _checkGetConfig ()
    {
        if (!$this->_config["user_supplied"]) { //if it isn't user supplied lets try loading the ini file
            $this->_getFileConfig(); //load the ini file
        }
        if (!$this->_config["user_supplied"] && !$this->_config["file_supplied"]){ //if the ini load failed
            $this->_setDefaultConfig(); //set the default config, because no cli, and no ini file config found
        }
    }

    /**
     * Trys to load the s3bomb-config.ini file into our config
     */
    private function _getFileConfig()
    {
        $this->_requireIni(); //require the ini file handler class
        $iniFile = new \S3Bomber\models\file\s3bomber_ini( $this->_config["file"] ); //setup the ini file class
        $tempConfig = $iniFile->getConfigFromLoadedIni(); //try loading the ini into config
        foreach ($tempConfig as $key=>$value){ //loop through the config found in the ini
            $this->_config[$key] = $value; //set it to our config array, only proper matching keys will be used...
        }
    }

    /**
     * Sets the config to defaults, which are usually garbage unless you have a really insecure mysql setup
     */
    private function _setDefaultConfig()
    {
        $this->_config["mysql_server"]      = 'localhost';
        $this->_config["mysql_user"]        = 'user';
        $this->_config["mysql_pass"]        = 'pass';
        $this->_config["mysql_db"]          = 'database';
        $this->_config["mysql_table"]       = 'table';
        $this->_config["mysql_name_column"] = 'column';
        $this->_config["aws_key"]           = '';
        $this->_config["aws_pass"]          = '';
        $this->_config["aws_s3bucket"]      = '';
    }

    /**
     * Runs the bombing loop
     *
     * Loops through each of the items adding it to a list, the list gets to a thousand, it send the command to S3
     * to delete the entire batch of 1000 which is quicker than sending each file delete command one at a time.
     *
     * Once we get a return code from AWS we delete those same rows from the mysql database table.
     *
     */
    public function bomb ()
    {
        /**
         * Echo out the number of photos to delete in a nice friendly message.
         */
        $this->_cliAdapter->cliEcho('Number of photos to delete ' . number_format($this->photosToDelete) );

        $z = 0; //the count of photos "deleted"

        for ($y=0;$y<$this->photosToDelete;$y+1000) //loop until all the items are deleted, remember we deleted 1000
        {
            $started = microtime(TRUE); //we output the time it took, so grab the start time

            $a    = 0; //current iteration
            $b    = 0; //total batches of 1000
            $keys = array(); //setup the filename keys array

            /**
             * Start the delete query
             *
             * We append each file by id to this query as we go along, hence the start, add/build, finish steps.
             *
             */
            $this->_db->startDeleteQuery($this->_config["mysql_id_column"],$this->_config["mysql_table"]);

            /**
             * Get a batch of 1000 items to be deleted
             */
            $result = $this->_db->getBatchOfPhotosToBeDeleted(
                $this->_config["mysql_id_column"],
                $this->_config["mysql_name_column"],
                $this->_config["mysql_table"]
            );

            /**
             * Loop through the batch, adding the file to the array to be deleted from S3 and to the query to be deleted
             * from the database table.
             */
            foreach ($result as $row) {
                array_push($keys,array("Key" => $row[ $this->_config["mysql_name_column"] ])); //add to S3 delete stack
                $this->_db->addPhotoToDeleteList($row[ $this->_config["mysql_id_column"] ] ); //add to DB delete stack
                $a++; //increment our iterator
                $z++; //increment the number of items deleted
                if ($a >= 999) { //if this is the 1000th item
                    $b++; //incrememnt our batch iterator

                    /**
                     * Send the batch delete command to S3
                     *
                     * We only care that a response was received, not what the response is...
                     */
                    $this->_s3Client->deleteObjects(array("Bucket" => $this->_config["aws_s3bucket"], "Objects" => $keys));

                    if ($this->_db->finishDeleteQuery()) //finish up the delete query and run it
                    {
                        $this->_cliAdapter->cliEchoBomb(); //draws our bomb ASCII art

                        /**
                         * Echos the fact 1000 items were deleted, how many our left, and how long it took.
                         */
                        $this->_cliAdapter->cliEcho("Deleted ". number_format($a+1) . " items in " .
                            (microtime(TRUE)-$started) . " seconds, " . number_format(($this->photosToDelete-$z)) . " left.");

                    }
                    $a = 0; //set our iterator back to 0 since 1000 was reached.
                }

            }
        }

        /**
         * When we're completely done tell the user how many total were deleted
         */
        $this->_cliAdapter->cliEcho("Deleted a total of " . $z);

    }
} 
// - End Bomber.php app/controllers/bomber/Bomber.php