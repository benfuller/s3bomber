<?php
/**
 * Created by PhpStorm.
 * User: benfuller
 * Date: 10/25/14
 * Time: 4:54 PM
 */

namespace S3Bomber;


class mysqlDatabase extends database {

    public function connect ()
    {
        $this->type = 'mysql';
        try
        {
            $this->handle = new \PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->database, $this->user, $this->pass);
            return $this->handle;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage(); //something went wrong
            return false;
        }
    }
    public function disconnect ()
    {
        $this->handle = NULL;
    }
    public function getConnection ()
    {
        return $this->handle;
    }
    public function select ($sql,$vars=array())
    {
        try
        {


            $dbh = $this->handle;
            $sth = $dbh->prepare($sql);

            # setting the fetch mode
            $sth->setFetchMode(\PDO::FETCH_ASSOC);
            if (count($vars)>0)
            {
                $sth->execute($vars);
            }
            else
            {
                $sth->execute();
            }
            $result = $sth->fetchAll();


            $numResults = $sth->rowCount();

            if ($numResults > 0)
            {
                return $result;
            }

        }
        catch (\PDOException $e)
        {
            echo $e->getMessage(); //something went wrong
        }


        return FALSE;
    }
    public function delete ($sql,$vars=NULL)
    {
        try
        {


            $dbh = $this->handle;
            $sth = $dbh->prepare($sql);

            # setting the fetch mode
            $sth->setFetchMode(\PDO::FETCH_ASSOC);
            if (count($vars)>0)
            {
                $sth->execute($vars);
            }
            else
            {
                $sth->execute();
            }



            $numResults = $sth->rowCount();

            if ($numResults > 0)
            {
                return TRUE;
            }

        }
        catch (\PDOException $e)
        {
            echo $e->getMessage(); //something went wrong
        }


        return FALSE;
    }
    public function filterMysqlVar ($var)
    {
        return mysql_real_escape_string($this->checkBanned($var));
    }
    public function toString($echo)
    {
        $string = "Hostname: " . $this->hostname . " Database: " . $this->database . " Username: " . $this->user . " Type: " . $this->type;
        if ($echo){
            echo $string;
        }
        return $string;
    }
} 