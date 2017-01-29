<?php
namespace App\Util;

class Mysql
{
  private $conn;

  private function getConnection()
  {
    if (!isset(self::$conn)) {
      self::$conn = mysqli_connect();
    }
  }

  public function query($query, $params = [])
  {
    mysqli_query($this->getConnection(), $query, $params);
  }
}
