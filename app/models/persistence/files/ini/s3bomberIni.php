<?php
/**
 * S3bomber_ini.php app/models/persistence/files/ini/S3bomber_ini.php
 *
 * Opens and loads the ini file into our config array
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace \S3Bomber\models\persistence\file\S3bomber_ini
 */
namespace S3Bomber\models\persistence\file;

/**
 * Ini File to Config Array Class
 *
 * Provides functionality to open the ini file and read it into our config array
 *
 * @category   S3Bomber
 * @license    MIT License
 * @package    S3Bomber\persistence
 * @subpackage file\S3bomber_ini
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class S3bomber_ini extends fileManager
{

    /**
     *
     * @var mixed
     */
    private $_config;
    public function __construct ($fileName="s3bomb-config.ini")
    {
        $iniArray = $this->readIni($fileName);
        foreach ($iniArray as $section => $prm){
            foreach ($prm as $param => $value){
                $this->_config[$section . "_" . $param] = $value;
            }
        }
        $this->_config["file_supplied"]=TRUE;
    }
    public function getConfigFromLoadedIni(){
        return $this->_config;
    }
} 