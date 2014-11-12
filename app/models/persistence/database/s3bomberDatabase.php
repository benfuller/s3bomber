<?php
/**
 * S3bomberDatabase.php app/models/persistence/database/S3bomberDatabase.php
 *
 * Contains the S3Bomber database interaction methods like:
 * Counting items to be deleted
 * Getting batch of items to be deleted
 * Starting a Delete Query
 * Building/Adding to Delete Query
 * Finishing and Running Delete Query
 *
 * @category   S3Bomber
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */

/**
 * Namespace \S3Bomber\models\persistence\database\S3bomberDatabase
 */
namespace S3Bomber\models\persistence\database;

/**
 * S3bomberDatabase.php app/models/persistence/database/S3bomberDatabase.php
 *
 * Contains the S3Bomber database interaction methods like:
 * Counting items to be deleted
 * Getting batch of items to be deleted
 * Starting a Delete Query
 * Building/Adding to Delete Query
 * Finishing and Running Delete Query
 *
 * @category   S3Bomber
 * @license    MIT License
 * @package    S3Bomber\persistence
 * @subpackage S3bomber Database
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class S3bomberDatabase extends MysqlDatabase
{
    /**
     * @var \S3Bomber\models\persistence\database\QueryStrings object containing the queries needed for S3Bomber
     */
    private $_query;

    /**
     * Counts and returns an integer number of the items needing deleting yet
     *
     * Runs a count against the table containing keys of items to be deleted from S3.
     *
     * Does the count by the primary_key column though for speed
     *
     * Is limited by the start and end positions!
     *
     * @param string $id_column primary_key column of table of keys
     * @param string $table_name table name that contains id_column
     * @param string $start_id int value of id_column to start at
     * @param int $end_id value of id_column to end at
     *
     * @return int the number of items that will be deleted
     */
    public function countPhotosToBeDeleted ($id_column,$table_name,$start_id,$end_id)
    {
        /**
         * Set start_id to 0 if it isn't set
         */
        if ($start_id <= 0)
        {
            $start_id = 0;
        }

        $query = new \S3Bomber\models\persistence\database\QueryStrings();; //start a new instance of our sql language file

        /**
         * Get our item counter query, fill in the id column and table name
         */
        $sql = $query->countPhotosQuery($id_column,$table_name);

        /**
         * If end_id isn't set, we should just use the NO LIMIT version of this function....
         */
        if ($end_id <= 0)
        {
            $sql = $query->countPhotosQueryNoLimit($id_column,$table_name); //load the no limit query language instead
        }

        /**
         * Run a MySQL SELECT on our query we built
         *
         * Replace :start_id and :end_id with the corresponding provided values, if none provided they're basically
         * ignored.
         */
        $result = $this->select($sql,array(":start_id"=>$start_id,":end_id"=>$end_id));

        return (int) $result[0]["photosToDeleteCount"]; //returns a casted integer of the result
    }

    /**
     * Returns a batch of up to 1000 items that need to be deleted
     *
     * Grabs the first 1000 items that need deleting unless it runs out, or the start_id and end_id limit it
     *
     * @param string $id_column primary_key column for the table containing the keys needing deleting
     * @param string $photo_column column name of the column holding the key names needing deleting
     * @param string $table_name the table name that holds the keys needing deleting
     * @param int $start_id the primary_key value to start at when deleting
     * @param int $end_id the primary_key value to end at while deleting
     *
     * @return array|bool an associative array of keys needing deleting from S3, or boolean FALSE if error or out
     */
    public function getBatchOfPhotosToBeDeleted($id_column,$photo_column,$table_name,$start_id,$end_id)
    {
        /**
         * Set start_id to 0 if it isn't set
         */
        if ($start_id <= 0)
        {
            $start_id = 0;
        }

        $query = new \S3Bomber\models\persistence\database\QueryStrings();; //start a new instance of our sql language file

        /**
         * Build the SQL statement that returns up to 1000 items needing deleting
         */
        $sql = $query->getBatchOfPhotos($id_column,$photo_column,$table_name);

        /**
         * If no end_id was set we should just use the NO LIMIT version of the query language
         */
        if ($end_id <= 0)
        {
            $sql = $query->getBatchOfPhotosNoLimit($id_column,$photo_column,$table_name); //load NO LIMIT version
        }

        /**
         * MySQL SELECT the query we just built replacing :start_id and :end_id if specified
         *
         */
        $result = $this->select($sql,array(":start_id"=>$start_id,":end_id"=>$end_id));

        return $result; //return our batch of items needing deleting, is an associative array
    }

    /**
     * Builds and starts the deleting query, the database table part of the delete process
     *
     * First we batch 1000 items into a group, send the batch to S3 to be deleted, wait, then when we know they're deleted
     * we runa big MySQL DELETE query with all 1000 primary keys in it. This starts that query language so we can add
     * each key to it as we go along.
     *
     * @param string $id_column primary_key column of the table containing the keys needing deleting
     * @param string $table_name table name of table containing keys needing deleting
     */
    public function startDeleteQuery($id_column,$table_name)
    {
        /**
         * Instantiate a new, shared instance of the query language file
         */
        $this->_query = new \S3Bomber\models\persistence\database\QueryStrings();

        /**
         * Starts the delete query and holds it in our local _query object
         */
        $this->_query->startDeleteQuery($table_name,$id_column);
    }

    /**
     * Adds an item to the list to be deleted from the table after its been already deleted from S3.
     * @param int $photo primary_key value of an item needing to be deleted from the table
     */
    public function addPhotoToDeleteList ($photo)
    {
        $this->_query->addPhotoToDeleteQuery($photo); //adds an item to the query that will be run when a batch is done
    }

    /**
     * Deletes the items from the table running the MySQL DELETE function with our built query
     *
     * @return array|bool result of the DELETE command
     */
    public function finishDeleteQuery ()
    {
        /**
         * Our _query object holds our list of items needing to be removed from the table. We're done adding to it
         * so run the DELETE, and return the result of that command. FALSE if fails
         */
        $result =  $this->delete($this->_query->finishPhotoDeleteQuery());

        return $result; //return the result of the MySQL DELETE
    }
}
// - End S3bomberDatabase.php app/models/persistence/database/S3bomberDatabase.php