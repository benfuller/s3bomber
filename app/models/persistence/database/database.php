<?php
/**
 * Database.php app/models/persistence/database/Database.php
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
namespace S3Bomber\models\persistence\database; //Database is part of our persistence layer

/**
 * Class Database (Model) FQSEN:\S3Bomber\persistence\database\Database
 *
 * Loads and passes messages to all the models to bomb the files from S3, 1000 at a time.
 *
 * @category   S3Bomber
 * @package    S3Bomber\Persistence
 * @subpackage Database
 * @license    MIT License
 * @version    Release: @package_version@
 * @link       https://github.com/benfuller/s3bomber
 * @since      Class available since Release 0.0.1
 */
class Database
{

    /**
     * @var string database hostname/ip address
     *
     */
    protected $hostname;

    /**
     * @var string database name
     */
    protected $database;

    /**
     * @var string type currently supported: "mysql"
     */
    protected $type;

    /**
     * @var string the username used for database connection
     */
    protected $user;

    /**
     * @var string the password used for the database connection
     */
    protected $pass;

    /**
     * @var \PDO our database handle
     */
    protected $handle;

    /**
     * Database Setup Constructor
     *
     * Feed it with the database server hostname, the database name, username, and password strings
     *
     * Don't forget to run connect()! In order to start the database connection, we aren't connected yet.
     *
     * @param string $hostname hostname or ip address we should try to connect to
     * @param string $database database name on that host we should associate the handle with right away
     * @param string $username username with access to that database from our host on that host
     * @param string $password password for that user with access
     */
    public function __construct($hostname, $database, $username, $password)
    {
        $this->hostname = $hostname; //set the hostname to our private hostname variable
        $this->database = $database; //set the database to our private database variable
        $this->user = $username;     //set the username to our private username variable
        $this->pass = $password;     //set the password to our private password variable
    }

    /**
     * Checks the given string for any banned words or symbols and replaces them appropriately
     *
     * DOES NOT PREVENT XSS OR INJECTION! Just meant to pretty things...
     *
     * @param string $string dirty string
     *
     * @return string cleaned string
     */
    public function checkBanned($string)
    {
        /**
         * Our list of US English banned words, mostly swear words and offensive words
         *
         * All instances of each of these are replaced with ***
         */
        $bannedWords = array('<script>','<object>','<style', 'fuck', 'shit', 'asshole', 'cunt', 'fag', 'fuk', 'fck', 'fcuk', 'assfuck', 'assfucker', 'fucker',
            'motherfucker', 'mother fucker', 'ass', 'cock', 'nigger', 'bastard', 'bitch', 'bitchtits',
            'bitches', 'brotherfucker', 'bullshit', 'bumblefuck', 'buttfucka', 'fucka', 'buttfucker', 'buttfucka', 'fagbag', 'fagfucker',
            'faggit', 'faggot', 'faggotcock', 'fagtard', 'fatass', 'fuckoff', 'fuckstick', 'fucktard', 'fuckwad', 'fuckwit', 'dick',
            'dickfuck', 'dickhead', 'dickjuice', 'dickmilk', 'doochbag', 'douchebag', 'douche', 'dickweed', 'dyke', 'dumbass', 'dumass',
            'fuckboy', 'fuckbag', 'gayass', 'gayfuck', 'gaylord', 'gaytard', 'nigga', 'niggers', 'niglet', 'paki', 'piss', 'prick', 'pussy',
            'poontang', 'poonany', 'porchmonkey', 'porch monkey', 'poon', 'queer', 'queerbait', 'queerhole', 'queef', 'renob', 'rimjob', 'ruski',
            'sandnigger', 'sand nigger', 'schlong', 'shitass', 'shitbag', 'shitbagger', 'shitbreath', 'chinc', 'carpetmuncher', 'chink', 'choad', 'clitface'
        , 'clusterfuck', 'cockass', 'cockbite', 'cockface', 'skank', 'skeet', 'skullfuck', 'slut', 'slutbag', 'splooge', 'twatlips', 'twat',
            'twats', 'twatwaffle', 'vaj', 'vajayjay', 'va-j-j', 'wank', 'wetback', 'whore'); //banned words array, every instance is replaced with ***

        /**
         * Our banned symbols list
         *
         * All instances are removed
         */
        $bannedSymbols = array('`'); //kinda deprecated now, but if we want to ban a symbol, put it here

        $string = str_replace($bannedWords, '***', $string); //replace the banned words
        $string = str_replace($bannedSymbols, '', $string); //remove the banned symbols
        return $string; //return the new clean string
    }
}
// - End Database.php app/models/persistence/database/Database.php