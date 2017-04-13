<?php
  $response = array();

  if(isset($_POST['UserID'])) {

    include('DatabaseHelper.Controller.php');
    $db = new DatabaseController();

    $userID = $db -> quote($_POST['UserID']);

    $sql = "SELECT * FROM Message WHERE SenderID = '$userID';";

    $result = $db -> select($sql);

    if($result) {

      $messageID = $result[0]['MessageID'];
      $threadID = $result[1]['ThreadID'];
      $senderID = $result[2]['SenderID'];
      $targetID = $result[3]['TargetID'];
      $sentDate = $result[4]['sentDate'];
      $content = $result[5]['Content'];

      $response['Message'] = $messageID;
      echo json_encode($response, JSON_FORCE_OBJECT);
    }
    else{
      $response['success'] = false;
      echo json_encode($response, JSON_FORCE_OBJECT);
    }
  }
  else{
    $response['success'] = false;
    echo json_encode($response, JSON_FORCE_OBJECT);
  }
 ?>