<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 10:29 PM
 */

namespace S3Bomber\models\file;


class s3bomber_ini extends fileManager{
    private $config;
    public function __construct ($fileName="s3bomb-config.ini") {
        $iniArray = $this->readIni($fileName);
        foreach ($iniArray as $section => $prm){
            foreach ($prm as $param => $value){
                $this->config[$section . "_" . $param] = $value;
            }
        }
        $this->config["file_supplied"]=TRUE;
    }
    public function getConfigFromLoadedIni(){
        return $this->config;
    }
} 