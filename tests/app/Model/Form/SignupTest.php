<?php
namespace App\Model\Form;

use App\Util\Validator;
use App\Util\Session;
use App\Model\Users;

class SignupTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $mockValidator = $this->getMockBuilder(Validator::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockSession = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockUsers = $this->getMockBuilder(Users::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $form = new Signup($mockValidator, $mockUsers, $mockSession);
        
        $params = [
            'email' => 'foo@bar.com',
            'password' => 'somepass123',
        ];
        
        $mockValidator->expects($this->once())
                ->method('isValidEmailString')
                ->willReturn(true);
        
        $mockValidator->expects($this->once())
                ->method('isValidPasswordString')
                ->willReturn(true);

        $mockUsers->expects($this->once())
                ->method('emailExists')
                ->willReturn(false);

        $form->validate($params);
        
        $this->assertFalse($form->hasErrors());        
        $this->assertEquals('foo@bar.com', $form->getValue('email'));
        $this->assertEquals('somepass123', $form->getValue('password'));
    }
}