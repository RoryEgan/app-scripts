<?php
class DatabaseController {

  protected static $connection;

  function connect() {

    if(!isset(self::$connection)) {

      $config = parse_ini_file('/var/www/app-config.ini');
      self::$connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);

    }

    if(self::$connection === false) {
      return false;
    }
    return self::$connection;
  }

  public function select($query) {

    $rows = array();
    $result = $this -> query($query);

    if($result === false) {
      return false;
    }

    while ($row = $result -> fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  }

  function quote($value){

    $connection = $this -> connect();
    return mysqli_real_escape_string($connection, $value);

  }

  function query($query){

    $connection = $this -> connect();
    $result  = $connection -> query($query);
    return $result;

  }

  function getLastInsertID() {

    $connection = $this -> connect();
    return $connection->insert_id;

  }
}
