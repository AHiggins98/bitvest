<?php

namespace App\Util;

use App\Util\Config;

class Mysql
{
    static $mysqli;
    private $host;
    private $user;
    private $pass;
    private $name;
    
    public function __construct(Config $config)
    {
        $this->host = $config->get('dbHost');
        $this->user = $config->get('dbUser');
        $this->pass = $config->get('dbPass');
        $this->name = $config->get('dbName');
    }

    private function getMysqli()
    {
        if (!isset(self::$mysqli)) {
            self::$mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->name);
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
        }
        return self::$mysqli;
    }

    public function query($query, $types = '', $params = [])
    {
        $stmt = $this->getMysqli()->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, $params);
        }
       
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $rows = [];
        
        while ($myrow = $result->fetch_assoc()) {
            $rows[] = $myrow;
        }
        
        $stmt->close();
        
        return $rows;
    }
}
