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
    for($i = 0; $i < sizeof($result); $i++) {
      error_log("ARRAY SIZE: '$var'");
      $res[$i] = array($result[$i]['ThreadIDArray'], $result[$i]['UpdatedAtArray'] ,
      $result[$i]['UserOneArray'], $result[$i]['UserTwoArray']);
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
