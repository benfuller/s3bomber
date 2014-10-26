<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 10:45 PM
 */

namespace S3Bomber;


class aws {
    private $key;
    private $secret;
    public function __construct ($key,$secret){
        $this->key = $key;
        $this->secret = $secret;
    }
    public function getAwsAuthArray(){
        return array(
            'key'    => $this->key,
            'secret' => $this->secret
        );
    }
} 