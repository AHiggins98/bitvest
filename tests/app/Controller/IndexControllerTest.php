<?php
namespace App\Controller;

use App\Util\Config;
use App\Util\View;
use App\Model\Menu;
use App\Util\Session;
use App\Model\Form\Login;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    use \Tests\WithMockHelper;
    
    private function newIndexController()
    {
        $this->setupMocks([
            Config::class,
            View::class,
            Menu::class,
            Session::class,
            Login::class,
        ]);
        
        $this->mocks[Config::class]->expects($this->any())
                ->method('getConfig')
                ->willReturn([]);
        
        return new IndexController(
            $this->mocks[Config::class],
            $this->mocks[View::class],
            $this->mocks[Menu::class],
            $this->mocks[Session::class],
            $this->mocks[Login::class]
        );
    }
    
    public function testIndexAction()
    {
        $indexController = $this->newIndexController();
        
        $params = [
        ];
        
        $this->mocks[View::class]->expects($this->once())
                ->method('render')
                ->with('index', ['message' => null, 'mailMessage' => null, 'loggedIn' => null]);
        
        $this->mocks[Login::class]->expects($this->once())
                ->method('getState')
                ->willReturn([]);
        
        $indexController->indexAction($params);
    }
}
