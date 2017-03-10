<?php
namespace App\Util;

use PHPUnit_Framework_TestCase;
use App\Util\Di;
use App\Model\Users;
use App\Util\Mysql;

class UsersTest extends PHPUnit_Framework_TestCase
{   
    use \Tests\WithMockHelper;
    
    /**
     * @group db
     */
    public function testEmailExists()
    {
        $mockSession = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();
                
        Di::getInstance()->set(Session::class, $mockSession);
        $users = Di::getInstance()->get(Users::class);
        $this->assertTrue($users->emailExists('a@b.com'));
    }
    
    public function testAdd()
    {
        $this->setupMocks([Mysql::class, Config::class, Email::class]);
        
        $users = new Users($this->mocks[Mysql::class], $this->mocks[Config::class], $this->mocks[Email::class]);
        
        $this->mocks[Mysql::class]->expects($this->once())
                ->method('query')
                ->willReturn(1);
        
        $users->add('foo@bar.com', 'pass1234');
    }
    
    /**
     * @group db
     */
    public function testAddMysql()
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