<?php
/**
 * s3bomber bootstrap file
 * 
 * Kicks off the s3bomber using override settings found in this file, the arguments specified by the user, those found in an ini file or lastly from a default set of credentials.
 * 
 * CLI FORMAT index.php servername username password database table column awskey awspass s3bucket
 *
 * @todo mysql-read support
 * @todo mysql-test support
 * @todo phpunit tests
 * @todo travis-ci integration
 * @todo phalcon integration?
 */

/**
 * You can override CLI variables here
 */

//$argv[1] = "107.6.130.90" //writable host
//$argv[2] = "s3bomber" //writable username
//$argv[3] = "s3b0mb3r" //writable password
//$argv[4] = "ACTIVE_LISTINGS" //database
//$argv[5] = "delete_photos" //table
//$argv[6] = "photo_url" //key column
//$argv[7] = "delete_id" //id column

// AWS
//$argv[8] = "ABC123" //aws auth key
//$argv[9] = "SECRETKEY" //aws auth secret
//$argv[10] = "investabilityimg" //aws bucket

//Start Stop Positions
//$argv[11] = //start position
//$argv[12] = //stop position

// Read Only MySQL Server
//$argv[13] = "107.6.130.90" //readonly server
//$argv[14] = "s3bomber" //readonly user
//$argv[15] = "s3b0mb3r" //readonly password

//Phalcon
//$argv[16] = "FALSE" //use phalcon configuration, not yet implemented

/**
 * Do Not Edit Below This Line
 * 
 */
 
require 'vendor/autoload.php';  //composer autoloader

require 'app/controllers/bomber/Bomber.php';  //our controller file

$bomber = new \S3Bomber\Bomber($argv); //load our controller
