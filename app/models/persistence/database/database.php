<?php
/**
 * Created by PhpStorm.
 * User: benfuller
 * Date: 10/25/14
 * Time: 4:53 PM
 */

namespace S3Bomber\models\persistence\database;


class database {
    protected $hostname;
    protected $database;
    protected $type;
    protected $user;
    protected $pass;
    protected $handle;
    public function __construct ($hostname,$database,$username,$password)
    {
        $this->hostname = $hostname;
        $this->database = $database;
        $this->user = $username;
        $this->pass = $password;
    }
    public function checkBanned ($string)
    {
        //the swear array needs some work
        $bannedWords = array('fuck', 'shit', 'asshole', 'cunt', 'fag', 'fuk', 'fck', 'fcuk', 'assfuck', 'assfucker', 'fucker',
            'motherfucker', 'mother fucker', 'ass', 'cock', 'nigger', 'bastard', 'bitch', 'bitchtits',
            'bitches', 'brotherfucker', 'bullshit', 'bumblefuck', 'buttfucka', 'fucka', 'buttfucker', 'buttfucka', 'fagbag', 'fagfucker',
            'faggit', 'faggot', 'faggotcock', 'fagtard', 'fatass', 'fuckoff', 'fuckstick', 'fucktard', 'fuckwad', 'fuckwit', 'dick',
            'dickfuck', 'dickhead', 'dickjuice', 'dickmilk', 'doochbag', 'douchebag', 'douche', 'dickweed', 'dyke', 'dumbass', 'dumass',
            'fuckboy', 'fuckbag', 'gayass', 'gayfuck', 'gaylord', 'gaytard', 'nigga', 'niggers', 'niglet', 'paki', 'piss', 'prick', 'pussy',
            'poontang', 'poonany', 'porchmonkey','porch monkey', 'poon', 'queer', 'queerbait', 'queerhole', 'queef', 'renob', 'rimjob', 'ruski',
            'sandnigger', 'sand nigger', 'schlong', 'shitass', 'shitbag', 'shitbagger', 'shitbreath', 'chinc', 'carpetmuncher', 'chink', 'choad', 'clitface'
        , 'clusterfuck', 'cockass', 'cockbite', 'cockface', 'skank', 'skeet', 'skullfuck', 'slut', 'slutbag', 'splooge', 'twatlips', 'twat',
            'twats', 'twatwaffle', 'vaj', 'vajayjay', 'va-j-j', 'wank', 'wankjob', 'wetback', 'whore', 'whorebag', 'whoreface'); //banned words array, every instance is replaced with ***
        $bannedSymbols = array('`'); //kinda deprecated now, but if we want to ban a symbol, put it here
        $string = str_replace($bannedWords,'***',$string);
        $string = str_replace($bannedSymbols,'',$string);
        return $string;
    }
} 