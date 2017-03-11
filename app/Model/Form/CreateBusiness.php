<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Users;
use App\Util\Session;
use App\Model\Businesses;

class CreateBusiness extends AbstractForm
{

    private $validator;
    private $users;
    private $session;
    private $businesses;

    public function __construct(Validator $validator, Users $users, Session $session, Businesses $businesses)
    {
        parent::__construct(['email', 'password']);
        $this->validator = $validator;
        $this->users = $users;
        $this->session = $session;
        $this->businesses = $businesses;
    }

    public function validate(array $params)
    {
        if (isset($params['foundername']) && isset($params['businessname']) && isset($params['shortname'])) {

//            if (!isset($params['']) ||
//                    !isset($params['']) ||
//                    !isset($params[''])) {
//
//                died('Fields marked "*" are required!');
//            }
//

            $foundername = $params['foundername'];
            $businessname = $params['businessname'];
            $shortname = $params['shortname'];
            
            $validFoundername = $this->validator->isValidFounderName($foundername);
            $validShortname = $this->validator->isValidShortName($shortname);
            $validBusinessname = $this->validator->isValidBusinessName($businessname);
            
            if (!$validFoundername) {
                $this->errors['foundername'] = 'The founder name you entered does not appear to be valid.<br />';
            }
            
            if (!$validShortname) {
                $this->errors['shortname'] = 'The short name you entered does not appear to be valid.<br />';
            }
            
            if (!$validBusinessname) {
                $this->errors['businessname'] = 'The business name you entered does not appear to be valid.<br />';
            }
            
            $foundernameExists = $this->businesses->foundernameExists($params['foundername']);
            
            if ($foundernameExists) {
                $this->errors['foundername'] = 'The founder name you used already exists. Please choose another one.';
            }
            
            $businessnameExists = $this->businesses->businessnameExists($params['businessname']);
            
            if ($businessnameExists) {
                $this->errors['businessname'] = 'The business name you used already exists. Please choose another one.';
            }
            
            $shortnameExists = $this->businesses->shortnameExists($params['shortname']);
            
            if ($shortnameExists) {
                $this->errors['shortname'] = 'The short name you used already exists. Please choose another one.';
            }
            
            $this->hasErrors = $validBusinessname && $validFoundername && $validShortname;
            
            if (!$this->hasErrors) {
                $this->values['foundername'] = $params['foundername'];
                $this->values['shortname'] = $params['shortname'];
                $this->values['businessname'] = $params['businessname'];
            }
            
        } else {
            throw new \Exception('Missing form parameters.');
        }
    }

}
