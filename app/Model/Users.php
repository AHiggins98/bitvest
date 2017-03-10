<?php

namespace App\Model;

use App\Util\Mysql;
use App\Util\Email;
use App\Util\Config;

class Users
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
    
    public function emailExists($email)
    {
        $query = 'select count(*) as cnt from users where email = ?';
        $params = [$email];
        $results = $this->mysql->query($query, 's', $params);
        return $results[0]['cnt'] ? true : false;
    }
    
    public function add($email, $password)
    {
        $verifyCode = bin2hex(openssl_random_pseudo_bytes(8));
        $query = 'insert into users(email, password, verify_code, created_ts) '
                . 'values (?, ?, ?, ?)';
        
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        $params = [$email, $hash, $verifyCode, date('c')];
        
        $added = $this->mysql->query($query, 'ssss', $params);
        
        if ($added != 1) {
            throw new \Exception('Failed to add user record');
        }
        
        $emailParams = [
            'email' => $email,
            'subject' => 'Bitvest - Please verify your email address',
            'verifyLink' => $this->config->get('baseUrl') . '/user/verify?verifyCode=' . $verifyCode . '&email=' . $email,
        ];
        
        $this->email->send('verify-code', $emailParams);
    }
    
    public function delete($email)
    {
        $query = 'delete from users where email = ?';
        $removed = $this->mysql->query($query, 's', [$email]);
        
        if ($removed != 1) {
            throw new \Exception('Failed to delete user record');
        }
    }
}
