<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

if($connection) {

  $threadID = $db -> quote($_POST["ThreadID"]);
  $senderID = $db -> quote($_POST["SenderID"]);
  $content = $db -> quote($_POST["Content"]);
  $targetPhoneNumber = $db -> quote($_POST["TargetNumber"]);
  $targetID = "";

  function getTargetID($targetID, $targetPhoneNumber) {

    $sql = "SELECT UserID
            FROM User
            WHERE PhoneNumber = '$targetPhoneNumber'";

    $targetID = $db -> query($sql);
  }
  getTargetID($targetID, $targetPhoneNumber);

  function sendMessage() {

    global $threadID, $senderID, $content, $db;

    $sql = "INSERT INTO Message
    (MessageID, ThreadID, SenderID, TargetID, Content)
    VALUES ('0', '$threadID', '$senderID', '$targetID', '$content');";

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

  if(sendMessage()) {
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
