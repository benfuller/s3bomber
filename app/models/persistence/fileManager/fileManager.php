<?php
/**
 * FileManager.php app/models/persistence/fileManager/FileManager.php
 *
 * File handler class
 *
 * Provides File Existence Verification, File Open, Close, and Write commands, and a read Ini function
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace \S3Bomber\models\persistence\file\fileManager
 */
namespace S3Bomber\models\persistence\file;

/**
 * FileManager.php app/models/persistence/fileManager/FileManager.php
 *
 * File handler class
 *
 * Provides File Existence Verification, File Open, Close, and Write commands, and a read Ini function
 *
 * @category   S3Bomber
 * @license    MIT License
 * @package    S3Bomber\persistence
 * @subpackage file\FileManager
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class FileManager
{
    /**
     * Opens a file and returns the file handle
     *
     * Only does so if the file exists, if it doesn't returns FALSE.
     *
     * If provided a second parameter of FALSE, the file is opened in writable mode.
     *
     * @param string $fileName the file name to be opened
     * @param bool $readonly TRUE if readonly, FALSE if writable, defaults Read Only/TRUE
     *
     * @return bool|resource|string returns the file as a string if readonly, if writable returns the hanlde, if couldn't
     *                              be opened returns FALSE
     */
    public function openFile ($fileName,$readonly=TRUE){
        if ($this->verifyExistence($fileName))
        {
            if ($readonly) {
                $handle = fopen($fileName, "r");
                return fread($handle, filesize($fileName));
            }
            else
            {
                $handle = fopen($fileName, "w");
                return $handle;
            }

        }
        return FALSE;
    }

    /**
     *
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