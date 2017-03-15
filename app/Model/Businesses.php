<?php

namespace App\Model;

use App\Util\Mysql;
use App\Util\Email;
use App\Util\Config;

class Businesses
{
    private $mysql;
    private $config;
    private $email;
    
    public function __construct(Mysql $mysql, Config $config, Email $email)
    {
        $this->mysql = $mysql;
        $this->config = $config;
        $this->email = $email;
    }
    
    public function add($foundername, $businessname, $shortname)
    {
        $sql = "INSERT INTO businesses (founder, businessname, shortname, created_ts)
        VALUES ('".$foundername."', '".$businessname."', '".$shortname."', NOW())";

        if ($this->mysql->query($sql) != 1) {
            throw new \Exception("Was not able to add businesses.");
        }
    }
    
    public function foundernameExists($foundername)
    {
        $result = "SELECT * FROM businesses WHERE founder = ('".$foundername."')";
        if ($this->mysql->query($result) > 0) {
            return true;
        }
    }

    public function businessnameExists($businessname)
    {
        $result = "SELECT * FROM businesses WHERE businessname = ('".$businessname."')";
        if ($this->mysql->query($result) > 0) {
            return true;
        }
    }

    public function shortnameExists($shortname)
    {
        $result = "SELECT * FROM businesses WHERE shortname = ('".$shortname."')";
        if ($this->mysql->query($result) > 0) {
            return true;
        }
    }

    public function listAll($userid, $sortbydate)
    {   
        if ($userid != null) {
                $sql = "SELECT * 
        FROM businesses WHERE 
        founder =  $userid" 
        ;
        }
        elseif ($sortbydate == true) {
        $sql = 'SELECT * FROM businesses 
        SORT BY created_ts';
        }
        else {
        $sql = "SELECT * 
        FROM businesses";
        }
        
        $rows = $this->mysql->query($sql);
        return $rows;
    }
}


