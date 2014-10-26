<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 10:05 PM
 */

namespace S3Bomber\models\file;


class fileManager {
    public function openFile ($fileName){
        if ($this->verifyExistence($fileName)){
            $handle = fopen($fileName,"r");
            return fread($handle, filesize($fileName));
        }
        return FALSE;
    }

    /**
     * @param $fileName
     * @param $file
     * @todo
     */
    public function writeFile ($fileName,$file){

    }
    public function verifyExistence ($fileName){
        if (file_exists($fileName)){
            return TRUE;
        }
        return FALSE;
    }
    public function readIni ($fileName){
        $file = $this->openFile($fileName);
        if ($file !== FALSE){
            return parse_ini_string($file,TRUE);
        }
    }
} 