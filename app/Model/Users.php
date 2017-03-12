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
    
    public function add(User $user)
    {
        $verifyCode = bin2hex(openssl_random_pseudo_bytes(8));
        $query = 'insert into users(email, password, verify_code, created_ts) '
                . 'values (?, ?, ?, ?)';
        
        $hash = password_hash($user->password, PASSWORD_BCRYPT);
        
        $params = [$user->email, $hash, $verifyCode, date('c')];
        
        $added = $this->mysql->query($query, 'ssss', $params);
        
        if ($added != 1) {
            throw new \Exception('Failed to add user record');
        }
        
        $verifyLink = $this->config->get('baseUrl')
                . '/user/verify?verifyCode=' . $verifyCode . '&email=' . $user->email;
        
        $emailParams = [
            'email' => $user->email,
            'subject' => 'Bitvest - Please verify your email address',
            'verifyLink' => $verifyLink,
        ];
        
        $this->email->send('verify-code', $emailParams);
    }
    
    public function delete($email)
    {
        $query = 'delete from users where email = ? limit 1';
        $removed = $this->mysql->query($query, 's', [$email]);
        
        if ($removed != 1) {
            throw new \Exception('Failed to delete user record');
        }
    }
    
    public function loadUser(User $user)
    {
        if (!isset($user->email) && !isset($user->id)) {
            throw new \Exception('Can only load user by email or id, none specified.');
        }
        
        if (isset($user->email)) {
            $where = 'email = ?';
            $types = 's';
            $params = [$user->email];
        } else {
            $where = 'id = ?';
            $types = 'i';
            $params = [$user->id];
        }
        
        $query = "select id, email from users where $where limit 1";
        
        $rows = $this->mysql->query($query, $types, $params);
        
        if (empty($rows) || !isset($rows[0])) {
            throw new \Exception('Unable to find user.');
        }
        
        $user->id = $rows[0]['id'];
    }
}
