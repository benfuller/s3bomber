<?php
/**
 * s3bomber bootstrap file
 * 
 * Kicks off the s3bomber using override settings found in this file, the arguments specified by the user, those found in an ini file or lastly from a default set of credentials.
 * 
 * CLI FORMAT index.php servername username password database table column awskey awspass s3bucket
 * 
 */

/**
 * You can override CLI variables here
 * @todo add commented out override variables
 * @todo mysql-read support
 * @todo mysql-test support
 * @todo phpunit tests
 * @todo travis-ci integration
 * @todo phalcon integration
 */


/**
 * Do Not Edit Below This Line
 * 
 */
 
require 'vendor/autoload.php';  //composer autoloader

require 'app/controllers/bomber/Bomber.php';  //our controller file

$bomber = new \S3Bomber\Bomber($argv); //load our controller
