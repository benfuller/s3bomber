<?php
/**
 * MysqlDatabase.php app/models/persistence/database/MysqlDatabase.php
 *
 * Flavors our database all MySQLy
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
namespace S3Bomber\models\persistence\database; //becomes \S3Bomber\persistence\database\MysqlDatabase

/**
 * Class MysqlDatabase (Model) FQSEN:\S3Bomber\persistence\database\MysqlDatabase
 *
 * Extends Database. Flavors our database all MySQLy, provides our connect, disconnect, select, update, and delete methods.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Persistence
 * @subpackage Database
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class MysqlDatabase extends Database
{

    /**
     * Connects to the constructed database
     *
     * Database connection errors are ugly echoed
     *
     * @return bool|\PDO PDO connection handle
     */
    public function connect ()
    {
        $this->type = 'mysql'; //set our database type to mysql, used for tracking
        try //try the pdo stuff because it throws PDOExceptions
        {
            /**
             * Try our PDO connection and set it to our handle
             */
            $this->handle = new \PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->database, $this->user, $this->pass);

            return $this->handle; //return the PDO handle
        }
        catch(PDOException $e) //there was a problem connecting
        {
            echo $e->getMessage(); //ugly echo the error message
            return false; //return false because we couldn't connect
        }
    }

    /**
     * Disconnect from the constructed database
     */
    public function disconnect ()
    {
        $this->handle = NULL; //setting the DB handle to NULL disconnects PDO
    }

    /**
     * Returns the database handle
     * @return \PDO database handle
     */
    public function getConnection ()
    {
        return $this->handle; //return our database handle
    }

    /**
     * Select Query Builder
     *
     * Provide your SQL statement as the first parameter. An optional second parameter is a replacement associative array.
     *
     * The second parameter, the optional associative array or replacement keys => values. PDO uses bound parameters
     * and makes your values safe for SQL.
     *
     * Example: "SELECT * FROM ourtable WHERE ourfirstcolumn = :ourfirstvalue AND deleted IS NULL" and array:
     * array (":ourfirstvalue"=>"user@email.com") will result in a PDO query similar to SELECT * FROM ourtable WHERE
     * ourfirstcolumn = 'user@email.com' AND deleted IS NULL"...PDO prepared statements never let the provided variables
     * touch the statement, so it is safe, but you get the idea
     *
     * @param string $sql SQL query that should be run with a SELECT context
     * @param array $vars replacement associative array with keys that should be replaced with the corresponding values
     *
     * @return array|bool associative array result set, or FALSE if problem occurred
     */
    public function select ($sql,$vars=array())
    {
        return $this->statement($sql,$vars); //nothing special just run the statement
    }

    /**
     * PDO statement function
     *
     * Used for selecting, updating, and deleting stuff.
     *
     * Provide your SQL statement as the first parameter. An optional second parameter is a replacement associative array.
     *
     * The second parameter, the optional associative array or replacement keys => values. PDO uses bound parameters
     * and makes your values safe for SQL.
     *
     * Example: "SELECT * FROM ourtable WHERE ourfirstcolumn = :ourfirstvalue AND deleted IS NULL" and array:
     * array (":ourfirstvalue"=>"user@email.com") will result in a PDO query similar to SELECT * FROM ourtable WHERE
     * ourfirstcolumn = 'user@email.com' AND deleted IS NULL"...PDO prepared statements never let the provided variables
     * touch the statement, so it is safe, but you get the idea
     *
     * @param string $sql SQL statement to be executed and fetched
     * @param array $vars associative array of keys that need to be replaced with he corresponding values
     *
     * @return array|bool associative array result set, or FALSE if failure
     */
    private function statement ($sql,$vars=array())
    {
        try //try this because PDO throws PDO Exceptions
        {
            $dbh = $this->handle; //get our handle
            $sth = $dbh->prepare($sql); //PDO prepare our SQL statement
            $sth->setFetchMode(\PDO::FETCH_ASSOC); //sets the fetch mode to associative array type being returned
            if (count($vars)>0) //if we have a replacements array
            {
                $sth->execute($vars); //run it with our statement
            }
            else
            {
                $sth->execute(); //if we don't have a replacements array run the statement without
            }
            $result = $sth->fetchAll(); //fetch all the results

            return $result; //return the results
        }
        catch (\PDOException $e) //an error occurred, likely bad sql
        {
            echo $e->getMessage(); //so ugly echo the error message
        }
        return FALSE; //if we get this far we should just return a FALSE message
    }

    /**
     * Delete Query Builder
     *
     * Provide your SQL statement as the first parameter. An optional second parameter is a replacement associative array.
     *
     * The second parameter, the optional associative array or replacement keys => values. PDO uses bound parameters
     * and makes your values safe for SQL.
     *
     * Example: "DELETE FROM ourtable WHERE ourfirstcolumn = :ourfirstvalue AND deleted IS NULL" and array:
     * array (":ourfirstvalue"=>"user@email.com") will result in a PDO query similar to DELETE FROM ourtable WHERE
     * ourfirstcolumn = 'user@email.com' AND deleted IS NULL"...PDO prepared statements never let the provided variables
     * touch the statement, so it is safe, but you get the idea
     *
     * @param string $sql SQL query that deletes stuff, and should be run in delete context
     * @param array $vars replacement associative array where the keys should be replaced by the values
     *
     * @return bool TRUE if deleted, FALSE if problem occurred
     */
    public function delete ($sql,$vars=NULL)
    {
        return (bool) $this->statement($sql,$vars); //nothing special just run the statement
    }

    /**
     * Returns our database connection information as a string, and if  the first parameter is set true, echos it out
     *
     * @param bool $echo set TRUE if should ugly echo the message, else, will only return the value
     *
     * @return string of connection information
     */
    public function toString($echo=FALSE)
    {
        /**
         * Our connection string string
         */
        $string = "Hostname: " . $this->hostname . " Database: " . $this->database . " Username: " . $this->user .
            " Type: " . $this->type;

        if ($echo) //if the parameter is set true we should echo it
        {
            echo $string; //so echo it
        }
        return $string; //always return it
    }
}
// - End MysqlDatabase.php app/models/persistence/database/MysqlDatabase.php