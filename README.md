# The Amazon S3 Bomber

          ,__\!/
       __/   -*-
     ,BOOM.  /!\
     SBOMB3
     `BOOM'


Deletes objects from Amazon Simple Storage Server (S3) that are listed in a MySQL Table

## Requirements

1. PHP 5.2 or greater
2. MySQL Database With Select & Delete


## Installation

Clone & Pull The Repo On Something Running PHP 5

## Usage

php index.php server user pass db table column awskey awssecret awsbucket

_or use config file_

## The Config File

The directory s3bomber-config contains a file called s3bomb-config.ini.example

Remove the .example from that file name, edit it as needed with mysql credentials and table/column details

Don't forget to edit the ini file with the AWS Key, Secret, and S3 Bucket Name
