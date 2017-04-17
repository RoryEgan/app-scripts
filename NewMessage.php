<?php

include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db->connect();

if($connection) {

  $threadID = $db->quote($_POST["ThreadID"]);
  $senderID = $db->quote($_POST["SenderID"]);
  $content = $db->quote($_POST["Content"]);
  $targetPhoneNumber = $db->quote($_POST["TargetNumber"]);
  $targetID = "0";

  function getTargetID() {

    global $targetPhoneNumber, $targetID,  $db;

    $sql = "SELECT UserID
    FROM User
    WHERE PhoneNumber = '$targetPhoneNumber';";
    $returned = $db->select($sql);
    $targetID = $returned[0]['UserID'];

    return $targetID;
  }

  $targetID = getTargetID();

  function sendMessage() {

    global $threadID, $senderID, $targetID, $content, $db;

    $sql = "INSERT INTO Message
    (MessageID, ThreadID, SenderID, TargetID, Content)
    VALUES ('0', '$threadID', '$senderID', '$targetID', '$content');";
    $result = $db->query($sql);

    if($result) {
      return true;
    }

    else {
      return false;

    }
  }

  function insertNewThread() {

    global $senderID, $targetID, $db;

    $insertion = "INSERT INTO Thread
    (ThreadID, UserOne, UserTwo)
    VALUES ('0', '$senderID', '$targetID');";
    $db->query($insertion);
  }

  function updateMessageThreadID() {

    global $senderID, $targetID, $db;

    $updateQuery = "UPDATE Message
	         SET ThreadID = (SELECT ThreadID
		 FROM Thread
		 WHERE Thread.UserOne = Message.SenderID
		 AND Thread.UserTwo = Message.TargetID);";

    $db->query($updateQuery);
  }

  function newThread() {

    global $senderID, $targetID, $db;

    $query1 = "SELECT *
    FROM Message
    WHERE SenderID = '$senderID' AND TargetID = '$targetID';";
    $result1 = $db->select($query1);

    $query2 = "SELECT *
    FROM Message
    WHERE SenderID = '$targetID' AND TargetID = '$senderID';";
    $result2 = $db->select($query2);

    if(count($result1) < 1 && count($result2) < 1) {

     insertNewThread();

    }
    else {
      $one = count($result1);
      $two = count($result2);

      error_log("Result 1: '$one' Result 2: '$two'");
    }

    updateMessageThreadID();


  }

  $response = array();
  $response["success"] = false;

  if(sendMessage()) {
    newThread();
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
