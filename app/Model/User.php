<?php
namespace App\Model;

/**
 * Class User
 * @property int $id
 * @property string $email
 * @property string $password
 * @method User fromJsonString
 */
class User
{
    use WithProperties;
   
    public function __construct()
    {
        $this->defineProperties([
           'id',
           'email',
           'password'
        ]);
    }
}
