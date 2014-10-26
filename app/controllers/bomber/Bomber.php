<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 10:49 AM
 */

namespace S3Bomber;


/**
 * Class Bomber
 *
 * @package S3Bomber
 */
class Bomber {

    private $s3factory;
    private $argv;
    private $config;
    private $s3Client;
    private $db;
    /**
     * Bomber (Constructor)
     *
     * Builds the Bomber
     *
     * @param $argv
     */
    public function __construct($argv){
        $this->argv = $argv;
        $this->requireCli();
        $cliAdapter = new CliAdapter($argv);
        $this->config = $cliAdapter->getArguments();
        $this->config["file"]                  = 's3bomb-config.ini';
        $this->config["user_supplied"]         = TRUE;
        $this->config["file_supplied"]         = FALSE;
        $this->config["user_supplied"]         = $cliAdapter->checkConfig($this->config);
        $this->checkGetConfig();


        $this->requireAwsS3();
        $s3 = new s3($this->config["aws_key"],$this->config["aws_pass"]);
        $s3Client = $s3->getFactory();

        $this->requireDatabase();
        $db = new s3bomberDatabase($this->config["mysql_server"], $this->config["mysql_db"],
                                $this->config["mysql_user"], $this->config["mysql_pass"]);
        $db->connect();
        $db->getConnection();



        $photosToDelete = $db->countPhotosToBeDeleted($this->config["mysql_id_column"],$this->config["mysql_table"]);
        $cliAdapter->cliEcho('Number of photos to delete ' . $photosToDelete );
        $this->bomb($db,$cliAdapter,$s3Client,$photosToDelete);



    }
    function requireCli ()
    {
        require 'app/models/cli/CliAdapter.php';;
    }
    function requireIni ()
    {
        require 'app/models/persistence/file-manager/fileManager.php';
        require 'app/models/persistence/files/ini/s3bomber_ini.php';
    }
    function requireAwsS3 ()
    {
        require 'app/models/aws/aws.php';
        require 'app/models/aws/s3.php';
    }
    function requireDatabase ()
    {
        require 'app/models/persistence/database/database.php';
        require 'app/models/persistence/database/mysqlDatabase.php';
        require 'app/models/persistence/database/s3bomberDatabase.php';
        require 'app/models/persistence/database/queryStrings.php';
    }
    function checkGetConfig ()
    {
        if (!$this->config["user_supplied"]) {
            $this->getFileConfig();
        }
        if (!$this->config["user_supplied"] && !$this->config["file_supplied"]){
            $this->setDefaultConfig();
        }
    }
    function getFileConfig()
    {
        $this->requireIni();
        $iniFile = new s3bomber_ini("s3bomb-config.ini");
        $tempConfig = $iniFile->getConfigFromLoadedIni();
        foreach ($tempConfig as $key=>$value){
            $this->config[$key] = $value;
        }
    }
    function setDefaultConfig()
    {
        $this->config["mysql_server"]      = 'localhost';
        $this->config["mysql_user"]        = 'user';
        $this->config["mysql_pass"]        = 'pass';
        $this->config["mysql_db"]          = 'database';
        $this->config["mysql_table"]       = 'table';
        $this->config["mysql_name_column"] = 'column';
        $this->config["aws_key"]           = '';
        $this->config["aws_pass"]          = '';
        $this->config["aws_s3bucket"]      = '';
    }
    function bomb ($db,$cliAdapter,$s3Client,$photosToDelete)
    {
        $z       = 0;
        for ($y=0;$y<$photosToDelete;$y+1000)
        {
            $started = microtime(TRUE);
            $result = $db->getBatchOfPhotosToBeDeleted($this->config["mysql_id_column"], $this->config["mysql_name_column"],
                $this->config["mysql_table"]);
            $a    = 0;
            $b    = 0;
            $keys = array();
            $db->startDeleteQuery($this->config["mysql_id_column"],$this->config["mysql_table"]);
            foreach ($result as $row) {
                array_push($keys,array("Key" => $row[ $this->config["mysql_name_column"] ]));
                $db->addPhotoToDeleteList($row[ $this->config["mysql_id_column"] ] );
                $a++;
                $z++;
                if ($a >= 999) {
                    $b++;
                    $s3Client->deleteObjects(array("Bucket" => $this->config["aws_s3bucket"], "Objects" => $keys));
                    if ($db->finishDeleteQuery())
                    {
                        $cliAdapter->cliEchoBomb();
                        $cliAdapter->cliEcho("Deleted ". number_format($a+1) . " photos in " . (microtime(TRUE)-$started) . " seconds, " . number_format(($photosToDelete-$z)) . " left.");
                    }
                    $a = 0;
                }

            }
        }
        $cliAdapter->cliEcho("Deleted a total of " . $z);
    }
} 
