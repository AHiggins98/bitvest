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
    
    /**
     * @group db
     */
    public function testAdd()
    {
        /** @var Users $users */
        $users = Di::getInstance()->get(Users::class);
        
        try {
            // Cleanup test
            $users->delete('support@whebsite.com');
        } catch (\Exception $e) {
        }
        
        $users->add('support@whebsite.com', 'pass123');
        
        // Verify
        $this->assertTrue($users->emailExists('support@whebsite.com'));
        
        // Cleanup test
        $users->delete('support@whebsite.com');
    }
}