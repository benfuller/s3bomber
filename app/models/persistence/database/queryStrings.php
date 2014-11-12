<?php
/**
 * QueryStrings.php app/models/persistence/database/QueryStrings.php
 *
 * Returns MySQL flavored SQL statements
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace \S3Bomber\persistence\database\QueryStrings
 */
namespace S3Bomber\models\persistence\database;

/**
 * Class QueryStrings (Model) FQSEN:\S3Bomber\persistence\database\QueryStrings
 *
 * Returns each of the queries needed to run the S3bomber, flavored for MySQL
 *
 * @category   S3Bomber
 * @package    S3Bomber\Persistence
 * @subpackage Database
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class QueryStrings
{

    /**
     * Used when an incrementing query is needed. E.g. when we keep adding stuff to a query, like a multi insert or delete
     *
     * @var string our working query
     */
    private $_queryBuilder;

    /**
     * The primary key is needed for faster deleting from the table.
     *
     * @var string the table primary_key
     */
    private $_id_column;

    /**
     * Returns a SQL statement that counts the number of items that are going to be deleted from S3.
     *
     * Limited by a start and end position :start_id and :end_id respectively
     *
     * @param string $column primary_key of the table holding things needing deleting from S3
     * @param string $table table holding all the keys needing deleting from S3
     *
     * @return string the sql statement that counts the number of items needing deleting from S3
     */
    public function countPhotosQuery ($column,$table)
    {
        /**
         * SQL Statement, placing the column and table parameters, with a WHERE clause limiting the start and end
         * positions.
         *
         */
        return "SELECT COUNT({$column}) as photosToDeleteCount FROM {$table} WHERE {$column} >= :start_id AND {$column} < :end_id;";
    }

    /**
     * Returns a SQL statement that counts the number of items that are going to be deleted from S3.
     *
     * @param string $column primary_key of the table holding things needing deleting from S3
     * @param string $table table holding all the keys needing deleting from S3
     *
     * @return string the sql statement that counts the number of items needing deleting from S3
     */
    public function countPhotosQueryNoLimit ($column,$table)
    {
        /**
         * SQL Statement, placing the column and table parameters
         */
        return "SELECT COUNT({$column}) as photosToDeleteCount FROM {$table};";
    }

    /**
     * Returns a SQL Statement returning a batch of items to be deleted quantity 1000 or less
     *
     * Needs :start_id and :end_id named parameters to be replaced, otherwise use getBatchOfPhotosNoLimit
     *
     * @param string $id_column primary_key column of the table holding the keys needing deleting
     * @param string $photo_column column name of the column holding the keys needing deleting
     * @param string $table table name of the table holding the keys needing deleting from S3
     *
     * @return string sql statement that returns a batch of items to be deleted limited by start and end positions and to 1000 or less
     */
    public function getBatchOfPhotos ($id_column,$photo_column, $table)
    {
        /**
         * Returns SQL statement needs id_column, key column, and table name parameters to work.
         *
         * Has LIMIT of 1000 and :start_id and :end_id for start and end positions. Used for better threading.
         */
        return "SELECT {$id_column} as delete_id, {$photo_column} as photo_url FROM {$table} WHERE {$id_column} >= :start_id AND {$id_column} < :end_id LIMIT 1000;"; //we bastardize the pdo parameters here :(
    }

    /**
     * Returns a SQL Statement returning a batch of items to be deleted quantity 1000 or less
     *
     * @param $id_column string
     * @param $photo_column string
     * @param $table string
     *
     * @return string sql statement that returns a batch of items to be deleted limited to 1000 or less
     */
    public function getBatchOfPhotosNoLimit ($id_column,$photo_column, $table){
        /**
         * Returns SQL statement with 1000 items to be deleted from the given table
         */
        return "SELECT {$id_column} as delete_id, {$photo_column} as photo_url FROM {$table} LIMIT 1000;";
    }

    /**
     * Starts are delete query loading the queryBuilder variable
     *
     * Need this in order to run addPhotoToDeleteQuery and finishPhotoDeleteQuery
     *
     * @param string $table table holding items to be deleted
     * @param string $id_column column name of the primary key of the table
     */
    public function startDeleteQuery ($table,$id_column)
    {
        $this->_queryBuilder = "DELETE FROM " . $table . " WHERE "; //start the DELETE SQL query
        $this->_id_column = $id_column; //load the _id_column variable
    }

    /**
     * Adds an item by primary_key value to the list/query of items being deleted from the table
     *
     * Need to run startDeleteQuery First!
     *
     * Don't forget $_queryBuilder can get overwritten carelessly.
     *
     * @param int$_item_id the primary_key integer value of an item needing deleting
     */
    public function addPhotoToDeleteQuery ($_item_id)
    {
        $this->_queryBuilder .= "`" . $this->_id_column . "` = '" . $_item_id . "' OR ";

    }

    /**
     * Returns the $_queryBuilder value with the lat OR value lopped off
     *
     * Need to run startDeleteQuery first, and add something to the list or you will make a malformed SQL statement!
     *
     * @return string $_queryBuilder value lopping off the last OR
     */
    public function finishPhotoDeleteQuery ()
    {
        return  rtrim($this->_queryBuilder, 'OR ');
    }
}
// - End QueryStrings.php app/models/persistence/database/QueryStrings.php