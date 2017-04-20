<?php
    include('DatabaseController.class.php');
    $db = new DatabaseController();
    $connection = $db -> connect();

    if(isset($_POST['ThreadID'])) {

      $threadID = $db->quote($_POST['ThreadID']);

      $sqlSelect = "SELECT * FROM Message WHERE ThreadID = '$threadID';";

      $result = $db->select($sqlSelect);
      $messageID = $result[0]['MessageID'];
      $senderID = $result[2]['SenderID'];
      $targetID = $result[3]['TargetID'];
      $sentDate = $result[4]['SentDate'];
      $content = $result[5]['Content'];

      $response = array();

      $response["success"] = false;

      if($result) {
        $response["success"] = true;
        $response["MessageID"] = $messageID;
        $response["SenderID"] = $senderID;
        $response["TargetID"] = $targetID;
        $response["SentDate"] = $sentDate;
        $response["Content"] = $content;
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
