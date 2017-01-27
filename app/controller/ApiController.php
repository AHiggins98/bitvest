<?php
namespace App\Controller;

require_once '../app/util/HeaderParams.php';

use App\Util\HeaderParams;

class ApiController
{
  const CONTENT_TYPE_JSON = 'Content-type: application/json';

  private $headers;

  public function __construct()
  {
    $this->headers = new HeaderParams();
  }

  public function usersAction($p)
  {
    $this->headers->set(self::CONTENT_TYPE_JSON);
    echo json_encode($p);
  }
}
