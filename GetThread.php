<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

$response = array();
$response['success'] = false;

if(isset($_GET['UserID'])) {

  $userID = $db->quote($_GET['UserID']);

  $sqlSelect = "SELECT * FROM Thread
  WHERE (UserOne = '$userID')
  OR (UserTwo = '$userID');";

  $result = $db->select($sqlSelect);

  if($result) {
    $res = array();
    $response['success'] = true;
    $var = sizeOf($result);
    for($i = 0; $i < sizeof($result); $i++) {
      $res1[$i] = $result[$i]['ThreadID'];
      $res2[$i] = $result[$i]['UpdatedAt'];
      $res3[$i] = $result[$i]['UserOne'];
      $res4[$i] = $result[$i]['UserTwo'];
    }
    $response['ThreadIDArray'] = $res1;
    $response['UpdatedAtArray'] = $res2;
    $response['UserOneArray'] = $res3;
    $response['UserTwoArray'] = $res4;
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
