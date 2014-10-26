<?php
/**
 * Created by PhpStorm.
 * User: Benjamin
 * Date: 10/19/2014
 * Time: 10:56 PM
 */

namespace S3Bomber;
use Aws\S3\S3Client;

class s3 extends aws {

    public function getFactory ()
    {

        return S3Client::factory($this->getAwsAuthArray());
    }
} 