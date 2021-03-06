<?php
namespace App\Controller;

use App\Util\HeaderParams;
use App\Util\Config;
use App\Util\View;
use App\Model\Menu;
use App\Util\Session;
use App\Model\Auth;
use App\Model\Form\Login;
use App\Model\Form\Signup;
use App\Model\Users;
use App\Model\User;
use App\Util\Validator;
use App\Util\Di;

class UserControllerTest extends \PHPUnit_Framework_TestCase
{
    use \Tests\WithMockHelper;
    
    private function newUserController()
    {
        $this->setupMocks([
            Config::class,
            View::class,
            Menu::class,
            Session::class,
            HeaderParams::class,
            Auth::class,
            Login::class,
            Signup::class,
            Users::class,
            Validator::class,
            Di::class,
        ]);
        
        $this->mocks[Config::class]->expects($this->any())
                ->method('getConfig')
                ->willReturn([]);
        
        $this->mocks[Di::class]->expects($this->any())
                ->method('getInstance')
                ->willReturn($this->mocks[Di::class]);
        
        return new UserController(
            $this->mocks[Config::class],
            $this->mocks[View::class],
            $this->mocks[Menu::class],
            $this->mocks[Session::class],
            $this->mocks[HeaderParams::class],
            $this->mocks[Auth::class],
            $this->mocks[Login::class],
            $this->mocks[Signup::class],
            $this->mocks[Users::class],
            $this->mocks[Validator::class],
            $this->mocks[Di::class]
        );
    }
    
    public function testLoginAction()
    {
        $userController = $this->newUserController();
        
        $this->mocks[Login::class]->expects($this->once())
                ->method('getState')
                ->willReturn([]);
                
        
        $params = [
        ];
        
        $this->mocks[View::class]->expects($this->once())
                ->method('render')
                ->with('user/login', []);
        
        $userController->loginAction($params);
    }
    
    public function testLoginSubmitAction()
    {
        $userController = $this->newUserController();
        
        $params = [
            'email' => 'a@b.com',
            'password' => 'foo123',
        ];
        
        $this->mocks[HeaderParams::class]->expects($this->once())
                ->method('redirect')
                ->with('user/account');
        
        $mockUser = $this->getMockBuilder(User::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->mocks[Di::class]->expects($this->once())
                ->method('create')
                ->with(User::class)
                ->willReturn($mockUser);
        
        $userController->loginSubmitAction($params);
    }
    
    public function testSignupAction()
    {
        $userController = $this->newUserController();
        
        $this->mocks[Signup::class]->expects($this->once())
                ->method('getState')
                ->willReturn([]);
                
        
        $params = [
        ];
        
        $this->mocks[View::class]->expects($this->once())
                ->method('render')
                ->with('user/signup', []);
        
        $userController->signupAction($params);
    }
    
    public function testSignupSubmitAction()
    {
        $userController = $this->newUserController();
        
        $params = [
            'email' => 'a@b.com',
            'password' => 'foo123',
        ];
        
        $this->mocks[HeaderParams::class]->expects($this->once())
                ->method('redirect')
                ->with('');
        
        $mockUser = $this->getMockBuilder(User::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->mocks[Di::class]->expects($this->once())
                ->method('create')
                ->with(User::class)
                ->willReturn($mockUser);
        
        $userController->signupSubmitAction($params);
    }
}
