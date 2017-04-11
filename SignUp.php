<?php
    include('DatabaseController.class.php');
    $db = new DatabaseController();
    $connection = $db -> connect();

    if($connection) {

      $firstName = $db -> quote($_POST["FirstName"]);
      $lastName = $db -> quote($_POST["LastName"]);
      $phoneNumber = $db -> quote($_POST["PhoneNumber"]);
      $password = $db -> quote($_POST["Password"]);

       function signUp() {

          global $firstName, $lastName, $phoneNumber, $password, $db;
          $passwordHash = password_hash($password, PASSWORD_DEFAULT);

          $sql = "INSERT INTO User
          (UserID, FirstName, LastName, PhoneNumber, Password)
          VALUES ('0', '$firstName', '$lastName', '$phoneNumber', '$passwordHash');";

          $result = $db -> query($sql);
          if($result) {
            return true;
          }
          else {
            return false;
          }
      }

      function numberAvailable() {

          global $phoneNumber, $db, $password;
          $sqlSelect = "SELECT * FROM User WHERE PhoneNumber = '$phoneNumber' AND Password = '$password';";
          $result = $db -> select($sqlSelect);
          if(!$result){
            return true;
          }
      }

      $response = array();
      $response["success"] = false;

      if (numberAvailable()) {
          if(signUp()) {
             $response["success"] = true;
  	         $response["UserID"] = $db -> getLastInsertID();
          }
      }
      echo json_encode($response);
    }
    else {
      $response["success"] = false;
      echo json_encode($response);
    }
    echo "string";
?>
