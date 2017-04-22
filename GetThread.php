<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

$response = array();
$response['success'] = false;

if(isset($_POST['UserID'])) {

  $userID = $db->quote($_POST['UserID']);

  $sqlSelect = "SELECT * FROM Thread
  WHERE (UserOne = '$userID')
  OR (UserTwo = '$userID');";

  $result = $db->select($sqlSelect);

  if($result) {
    $res = array();
    $response['success'] = true;
    $var = sizeOf($result);
    error_log("ARRAY SIZE: '$var'");
    for($i = 0; $i <= sizeof($result); $i++) {
      error_log("POSITION IN ARRAY: '$i'");
      error_log($result[$i]['ThreadID']);
      $res[$i] = array($result[$i]['ThreadID'], $result[$i]['UpdatedAt'] ,
      $result[$i]['UserOne'], $result[$i]['UserTwo']);
    }
    $response['result'] = $res;
  }
  else{
    echo "{'success': false}";
  }
}
else{
  echo "{'success': false}";
}
echo json_encode($response);
?>
