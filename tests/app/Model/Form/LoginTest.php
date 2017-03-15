<?php
namespace App\Model\Form;

use App\Util\Validator;
use App\Util\Session;
use App\Model\Auth;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $mockValidator = $this->getMockBuilder(Validator::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockSession = $this->getMockBuilder(Session::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockAuth = $this->getMockBuilder(Auth::class)
                ->disableOriginalConstructor()
                ->getMock();
        
        $form = new Login($mockValidator, $mockSession, $mockAuth);
        
        $params = [
            'email' => 'foo@bar.com',
            'password' => 'somepass123',
        ];
        
        $mockValidator->expects($this->once())
                ->method('isEmailChars')
                ->willReturn(true);
        
        $mockValidator->expects($this->once())
                ->method('isPasswordChars')
                ->willReturn(true);

        $mockAuth->expects($this->once())
                ->method('checkPassword')
                ->willReturn(true);

        $form->validate($params);
        
        $this->assertFalse($form->hasErrors());
        $this->assertEquals('foo@bar.com', $form->getValue('email'));
        
        // The form does not set the password
        $this->assertEquals('', $form->getValue('password'));
    }
}
