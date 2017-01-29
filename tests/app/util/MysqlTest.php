<?php
namespace App\Util;

use PHPUnit_Framework_TestCase;

/**
 * @group db
 */
class MysqlTest extends PHPUnit_Framework_TestCase
{
    public function testQuery()
    {
        $config = new Config();
        $mysql = new Mysql($config);
        $users = $mysql->query('select * from users');
        $this->assertSame('a@b.com', $users[0]['email']);
    }
}