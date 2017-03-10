<?php
namespace App\Util;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidation()
    {
        $validator = new Validator();
        
        $this->assertTrue($validator->isEmailChars('foo@bar.com'));
        $this->assertFalse($validator->isEmailChars('foo@bar.com!@#'));
        
        $this->assertTrue($validator->isPasswordChars('g00dp@ssw0rd!'));
        $this->assertFalse($validator->isPasswordChars('no spaces'));
        
        $this->assertTrue($validator->isValidEmailString('foo@bar.com'));
        $this->assertFalse($validator->isValidEmailString('wei%%%n.com@x'));
        
        $this->assertTrue($validator->isValidPasswordString('g00dp@ssw0rd!'));
        $this->assertFalse($validator->isValidPasswordString('bad pass'));
    }
}
