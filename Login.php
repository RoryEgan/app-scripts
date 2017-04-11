<?php
    include('DatabaseController.class.php');
    $db = new DatabaseController();
    $connection = $db -> connect();

    if(isset($_POST['phoneNumber']) && isset($_POST["password"])) {

      $phoneNumber = $db -> quote($_POST['phoneNumber']);
      $password = $db->quote($_POST["password"]);

      $sqlSelect = "SELECT * FROM User WHERE PhoneNumber = '$phoneNumber';";
      
      $result = $db -> select($sqlSelect);
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
