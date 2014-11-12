<?php
/**
 * S3.php app/models/aws/S3.php
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time.
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace
 */
namespace S3Bomber\models\aws;

/**
 * We need Aws's S3Client library
 *
 * Loaded via namespace, included via composer autoloader file and dependency
 */
use Aws\S3\S3Client;

/**
 * Class S3 (Model) FQSEN:\S3Bomber\Aws\S3
 *
 * Represents our AWS API. Currently only holds our key and secret information.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Aws
 * @subpackage S3
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class S3 extends \S3Bomber\models\aws\Aws
{
    /**
     * Returns an AWS S3Client Factory
     *
     * You need this to talk to the AWS S3 API
     *
     * @throws AWS Exceptions
     * @return S3Client
     */
    public function getFactory ()
    {
        return S3Client::factory($this->getAwsAuthArray()); // returns the AWS S3 Client Factory, will fail if no key &/or secret
    }
}
// - End S3.php app/models/aws/S3.php