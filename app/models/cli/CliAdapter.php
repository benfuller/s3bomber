<?php
/**
 * CliAdapter.php app/models/cli/CliAdapter.php
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time.
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace
 */
namespace S3Bomber\Cli; //Namespaced under Cli so S3Bomber\Cli

/**
 * Class CliAdapter (Model) FQSEN:\S3Bomber\Cli\CliAdapter
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Cli
 * @subpackage CliAdapter
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class CliAdapter
{
    
    /**
     * @var mixed temp holds config array
     */
    private $_config;

    /**
     * @var mixed temp holds command line arguments array
     */
    private $_argv;

    /**
     * Loads the CLI arguments into our private variable. 
     * @param $argv 
     */
    public function __construct($argv)
    {

        $this->_argv = $argv;
    }

    /**
     * Get the CLI arguments and builds the config array, then returns the config array
     *
     * Supports a whole bunch of different configurations see link:
     * @link @todo link
     *
     * @return mixed
     */
    public function getArguments()
    {
        if (count($this->_argv)>=10) //if the argument count is greater than 10 we have enough arguments to start
        {
            $this->_config["mysql_server"]          = $this->_argv[1]; //usually the first argument is the mysql host
            $this->_config["mysql_user"]            = $this->_argv[2]; //usually the second argument is the mysql user
            $this->_config["mysql_pass"]            = $this->_argv[3]; //usually the third argument is the mysql password
            $this->_config["mysql_db"]              = $this->_argv[4]; //the fourth argument is the database name
            $this->_config["mysql_table"]           = $this->_argv[5]; //the fifth argument is the name of the table holding the list
            $this->_config["mysql_name_column"]     = $this->_argv[6]; //the sixth argument is for the key/name column
            $this->_config["mysql_id_column"]       = $this->_argv[7]; //the seventh argument is for the "id" or primary key
            $this->_config["aws_key"]               = $this->_argv[8]; //the eighth argument is for the AWS_AUTH_KEY
            $this->_config["aws_pass"]              = $this->_argv[9]; //the ninth argument is for the AWS_AUTH_SECRET
            $this->_config["aws_s3bucket"]          = $this->_argv[10]; //the tenth argument is for the AWS S3 bucket name
            if (count($this->_argv)>10) //if the argument count is greater than 10, we have a start/end id at least
            {
                /**
                 * The first/starting row that is deleted based upon the seventh argument column name
                 * e.g. WHERE column[7] >= this argument[11]
                 */
                $this->_config["start_id"] = $this->_argv[11]; //the eleventh argument is for the start position
                /**
                 * The last row that is deleted based upon the seventh argument column name
                 * e.g. WHERE column[7] <= this argument[12]
                 */
                $this->_config["end_id"] = $this->_argv[12]; //the twelfth argument is for the last position
            }
        }
        else if (count($this->_argv)<=4 && count($this->_argv)>0) //if less than 5 arguments are found we're looking at least a config file name
        {
            $this->_config["filePerUser"] = $this->_argv[1]; //the first argument : user specified config file name
            if (count($this->_argv)<=4 && count($this->_argv)>1) //config file and start stop positions specified
            {
                $this->_config["start_id"] = $this->_argv[2]; //the second argument : set the start position
                $this->_config["end_id"] = $this->_argv[3]; //the third argument : set the end position
            }
        }
        return $this->_config; //return our new config
    }

    /**
     * Echo's out the given string in a CLI friendly way
     *
     * If you specify a second paramenter, an associative array, we'll replace all instances of all the keys with the
     * corresponding value. E.g. For the string "Bob the Builder", we give the following associative array for replacements
     * array("Bob"=>"Ben","Builder"=>"Programmer"), will cli echo "Ben the Programmer\n"
     *
     * @param       $string
     * @param array $replacements
     *
     * @return bool
     */
    public function cliEcho ($string,$replacements=array())
    {
        if (count($replacements)>0) //if the replacements array exist we need to do that string replacement
        {
            foreach($replacements as $key=>$value){ //for each of the replacements array elements
                $string = str_replace($key,$value,$string); //string replace the key with the value
            }
        }
        echo $string . "\n"; //echo the new string with the new line character on the end.
        return true; //we return true for legacy support
    }

}
// - End CliAdapter.php app/models/cli/CliAdapter.php