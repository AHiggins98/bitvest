<?php
namespace App\Util;

use PHPUnit_Framework_TestCase;
use App\Util\Di;
use App\Model\Users;

class UsersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group db
     */
    public function testEmailExists()
    {
        $users = Di::getInstance()->get(Users::class);
        $this->assertTrue($users->emailExists('a@b.com'));
    }
}