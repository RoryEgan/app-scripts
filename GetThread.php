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
  $response["success"] = false;
  
  if($result) {
    $response = array();
    $response['success'] = true;
    for($i = 0; $i < sizeof($result); $i++) {
      $response[$i] = array($result[$i]['ThreadID'], $result[$i]['UpdatedAt'] ,
      $result[$i]['UserOne'], $result[$i]['UserTwo']);
    }
    $response['result'] = $result;
  }
  else{
    echo "{'success': false}";
  }
}
else{
  echo "{'success': false}";
}
?>
