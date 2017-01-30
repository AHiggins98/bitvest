<?php

namespace App\Model;

use App\Util\Mysql;

class Users
{
    private $mysql;
    
    public function __construct(Mysql $mysql)
    {
        $this->mysql = $mysql;
    }
    
    public function emailExists($email)
    {
        $query = 'select count(*) as cnt from users where email = ?';
        $params = [$email];
        $results = $this->mysql->query($query, 's', $params);
        return $results[0]['cnt'] ? true : false;
    }
}