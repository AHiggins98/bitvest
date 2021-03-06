<?php
namespace App\Controller;

use PHPUnit_Framework_TestCase;
use App\Util\HeaderParams;
use App\Util\Config;

class ApiControllerTest extends PHPUnit_Framework_TestCase
{
    public function testUsersAction()
    {
        $mockConfig = $this->getMockBuilder(Config::class)
                ->getMock();
        
        $mockHeaderParams = $this->getMockBuilder(HeaderParams::class)
                ->getMock();
        
        $apiController = new ApiController($mockConfig, $mockHeaderParams);
        
        ob_start();
        $apiController->usersAction([1, 2, 3]);
        $out = ob_get_clean();
        
        $this->assertSame('[1,2,3]', $out);
    }
}
