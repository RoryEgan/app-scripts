<?php
    include('DatabaseController.class.php');
    $db = new DatabaseController();
    $connection = $db -> connect();

    if(isset($_POST['PhoneNumber']) && isset($_POST['Password'])) {

      $phoneNumber = $db->quote($_POST['PhoneNumber']);
      $password = $db->quote($_POST['Password']);

      $sqlSelect = "SELECT * FROM User WHERE PhoneNumber = '$phoneNumber';";

      $result = $db->select($sqlSelect);
      $colPassword = $result[0]['Password'];
      $response = array();
      $response["success"] = false;

      if(password_verify($password, $colPassword)) {
        $response["success"] = true;
        $response["UserID"] = $result[0]["UserID"];
        echo  json_encode($response, JSON_FORCE_OBJECT);
      }
      else{
             echo "{'success': false}";
      }
    }
    else{
      echo "{'success': false}";
    }
?>

