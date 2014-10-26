<?php
/**
 * Aws.php app/models/aws/Aws.php
 *
 * The Amazon Web Service (AWS) Class
 *
 * @category   S3Bomber
 * @package    S3Bomber\Aws
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace
 */
namespace S3Bomber; //this will be \S3Bomber\Aws

/**
 * AWS Model Class (S3Bomber\Aws)
 *
 * Represents our AWS API. Currently only holds our key and secret information.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Aws
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class Aws {
    /**
     * @var String AWS AUTH KEY
     */
    private $key;
    /**
     * @var String AWS AUTH SECRET
     */
    private $secret;

    /**
     * Aws (Constructor)
     *
     * Feed it your AWS AUTH KEY and AWS AUTH SECRET. Directions on how to obtain these located at:
     * http://blogs.aws.amazon.com/security/post/Tx1R9KDN9ISZ0HF/Where-s-my-secret-access-key
     *
     * @link http://blogs.aws.amazon.com/security/post/Tx1R9KDN9ISZ0HF/Where-s-my-secret-access-key
     * @param $key
     * @param $secret
     */
    public function __construct ($key,$secret){
        $this->key = $key; //set the key to our private parameter
        $this->secret = $secret; //set the secret to our private parameter
    }

    /**
     * Returns the AWS AUTH array
     *
     * @link http://blogs.aws.amazon.com/security/post/Tx1R9KDN9ISZ0HF/Where-s-my-secret-access-key
     */
    public function getAwsAuthArray(){
        /**
         * Create and return an array formatted ["key"=>AWS_AUTH_KEY,"secret"=>AWS_AUTH_SECRET]
         */
        return array(
            'key'    => $this->key,
            'secret' => $this->secret
        );
    }
}
// - End Aws.php app/models/aws/Aws.php