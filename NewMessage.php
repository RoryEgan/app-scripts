<?php

include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db->connect();
$response = array();
$response['success'] = false;

if($connection) {

  $threadID = $db->quote($_GET["ThreadID"]);
  $senderID = $db->quote($_GET["SenderID"]);
  $content = $db->quote($_GET["Content"]);
  $targetPhoneNumber = $db->quote($_GET["TargetNumber"]);
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

  function insertNewThread() {

    global $senderID, $targetID, $db;

    $insertion1 = "INSERT INTO Thread
    (ThreadID, UserOne, UserTwo)
    VALUES ('0', '$senderID', '$targetID');";
    $db->query($insertion1);

    $getThreadIDQuery = "SELECT ThreadID
    FROM Thread
    WHERE (UserOne = '$senderID' AND UserTwo = '$targetID')
    OR (UserOne = '$targetID' AND UserTwo = '$senderID');";
    $returned = $db->select($getThreadIDQuery);
    $threadID = $returned[0]['ThreadID'];
    error_log("ThreadID is: '$threadID'");

    $insertion2 = "INSERT INTO ThreadUser
    (ThreadID, UserID)
    VALUES ('$threadID', '$senderID');";
    $db->query($insertion2);

    $insertion3 = "INSERT INTO ThreadUser
    (ThreadID, UserID)
    VALUES ('$threadID', '$targetID');";
    $db->query($insertion3);
  }

  function updateMessageThreadID($messageID) {

    global $senderID, $targetID, $db;

      $getNewThreadID = "SELECT ThreadID FROM Thread WHERE
      (UserOne = '$targetID' AND UserTwo = '$senderID')
      OR (UserOne = '$senderID' AND UserTwo = '$targetID');";


      $returned = $db->select($getNewThreadID);
      $newThreadID = $returned[0]['ThreadID'];

      $updateQuery = "UPDATE Message
  	         SET ThreadID = '$newThreadID'
       WHERE MessageID = '$messageID';";

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

      return false;

    }
    else {

      return true;
      $one = count($result1);
      $two = count($result2);
    }

  }

  function sendMessage() {

    global $threadID, $senderID, $targetID, $content, $db;

    $threadExists = newThread();

    $sql = "INSERT INTO Message
    (MessageID, ThreadID, SenderID, TargetID, Content)
    VALUES ('0', '$threadID', '$senderID', '$targetID', '$content');";
    $result = $db->query($sql);

    $query = "SELECT MessageID
              FROM Message
              WHERE ThreadID = '$threadID'
              AND Content = '$content';";
    $results = $db->select($query);
    $messageID = $results[0]['MessageID'];

    if(!$threadExists) {
      insertNewThread();
    }

    updateMessageThreadID($messageID);

    if($result) {
      return true;
    }

    else {
      return false;

    }
  }

  if(sendMessage()) {
    $response["success"] = true;
    $response["UserID"] = $db -> getLastInsertID();
  }
}
echo json_encode($response);
?>
