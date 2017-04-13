<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

if($connection) {

  $threadID = $db -> quote($_POST["ThreadID"]);
  $senderID = $db -> quote($_POST["SenderID"]);
  $content = $db -> quote($_POST["Content"]);
  $targetPhoneNumber = $db -> quote($_POST["TargetNumber"]);
  $targetID = "0";

  function getTargetID() {

    global $targetPhoneNumber, $targetID,  $db;

    $sql = "SELECT UserID
    FROM User
    WHERE PhoneNumber = '$targetPhoneNumber';";

    $returned = $db -> select($sql);
    $targetID = $returned[0]['UserID'];
    return $targetID;
  }

  $targetID = getTargetID();

  function sendMessage() {

    global $threadID, $senderID, $targetID, $content, $db;

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

  function newThread() {

    global $senderID, $targetID, $db;

    $query = "SELECT *
    FROM Message
    WHERE SenderID = '$senderID' AND TargetID = '$targetID';";

    $result = $db -> select($query);
    if(is_null($result[0]['MessageID'])) {

     $insertion = "INSERT INTO Thread
     (ThreadID, UserOne, UserTwo)
     VALUES ('0', '$senderID', '$targetID');";


      $db -> query($insertion);

    }

   $updateQuery = "UPDATE Message
	SET ThreadID = (SELECT ThreadID
		 FROM Thread
		 WHERE Thread.UserOne = Message.SenderID
		 AND Thread.UserTwo = Message.TargetID);";

   $db -> query($updateQuery);


  }

  $response = array();
  $response["success"] = false;

  if(sendMessage()) {
    $response["success"] = true;
    $response["UserID"] = $db -> getLastInsertID();
    newThread();
  }
  echo json_encode($response);
}
else {
  $response["success"] = false;
  echo json_encode($response);
}
?>

