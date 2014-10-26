<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 9:24 PM
 */

namespace S3Bomber\Cli;

/**
 * Class CliAdapter
 *
 * @package S3Bomber
 */
class CliAdapter {
    private $config;
    private $argv;
    public function __construct($argv){
        $this->argv = $argv;
    }

    /**
     * @todo add hook for config file path, probably argv[1] if 2, 3, 4 are not found
     * @todo add hook for start and stop positions
     * @return mixed
     */
    public function getArguments(){
        $this->config["mysql_server"]          = $this->argv[1];
        $this->config["mysql_user"]            = $this->argv[2];
        $this->config["mysql_pass"]            = $this->argv[3];
        $this->config["mysql_db"]              = $this->argv[4];
        $this->config["mysql_table"]           = $this->argv[5];
        $this->config["mysql_name_column"]     = $this->argv[6];
        $this->config["aws_key"]               = $this->argv[7];
        $this->config["aws_pass"]              = $this->argv[8];
        $this->config["aws_s3bucket"]          = $this->argv[9];
        return $this->config;
    }
    public function checkConfig($config)
    {
        $this->config = $config;
        foreach ($config as $cfg){
            if (empty($cfg)){
                return FALSE;
            }
        }
        return TRUE;
    }
    public function cliEcho ($string)
    {
        echo $string . "\n";
        return true;
    }
    public function cliEchoBomb ()
    {
        echo "
        ,__\!/
     __/   -*-
   ,BOOM.  /!\
   SBOMB3
   `MOOB'\n";
    }
} 