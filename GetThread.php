<?php
    include('DatabaseController.class.php');
    $db = new DatabaseController();
    $connection = $db -> connect();

    if(isset($_POST['UserID'])) {

      $userID = $db->quote($_POST['UserID']);

      $sqlSelect = "SELECT * FROM Thread
      WHERE (UserOne = '$userID')
      OR (UserTwo = '$userID');";

      $result = $db->select($sqlSelect);
      $threadID = $result[0]['ThreadID'];
      $updatedAt = $result[1]['UpdatedAt'];
      $userOne = $result[2]['UserOne'];
      $userTwo = $result[3]['UserTwo'];

      $response = array();

      $response["success"] = false;

      if($result) {
        $response["success"] = true;
        $response["ThreadID"] = $threadID;
        $response["UpdatedAt"] = $updatedAt;
        $response["UserOne"] = $userOne;
        $response["UserTwo"] = $userTwo;
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
