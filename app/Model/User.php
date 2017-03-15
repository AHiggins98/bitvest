<?php
namespace App\Model;

use App\Util\Config;
use App\Util\Email;

/**
 * Class User
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $verifyCode
 */
class User
{
    use WithProperties;
    
    private $config;
    private $mailer;
   
    public function __construct(Config $config, Email $mailer)
    {
        $this->defineProperties([
           'id',
           'email',
           'password',
           'verifyCode',
        ]);
        
        $this->config = $config;
        $this->mailer = $mailer;
    }
    
    public function sendConfirmationEmail()
    {
        if (!isset($this->verifyCode)) {
            throw new \Exception('Cannot send confirmation without code');
        }
        
        $verifyLink = $this->config->get('baseUrl')
                . '/user/verify?verifyCode=' . $this->verifyCode
                . '&email=' . $this->email;
        
        $emailParams = [
            'email' => $this->email,
            'subject' => 'Bitvest - Please verify your email address',
            'verifyLink' => $verifyLink,
        ];
        
        $this->mailer->send('verify-code', $emailParams);
    }
}
