<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

if($connection) {

  $threadID = $db -> quote($_POST["ThreadID"]);
  $senderID = $db -> quote($_POST["SenderID"]);
  $content = $db -> quote($_POST["Content"]);

  function sendMessage() {

    global $threadID, $senderID, $content, $db;

    $sql = "INSERT INTO Message
    (MessageID, ThreadID, SenderID, Content)
    VALUES ('0', '$threadID', '$senderID', '$content');";

    $result = $db -> query($sql);
    if($result) {
      return true;
    }
    else {
      return false;
    }
  }

  $response = array();
  $response["success"] = false;


  if(newMessage()) {
    $response["success"] = true;
    $response["UserID"] = $db -> getLastInsertID();
  }

  echo json_encode($response);
}
else {
  $response["success"] = false;
  echo json_encode($response);
}
?>
