<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

$response = array();
$response['success'] = false;

if(isset($_GET['UserID'])) {

  $userID = $db->quote($_GET['UserID']);

  $sqlSelect = "SELECT * FROM User;";

  $result = $db->select($sqlSelect);

  if($result) {
    $res = array();
    $response['success'] = true;
    $var = sizeOf($result);
    for($i = 0; $i < sizeof($result); $i++) {
      $res1[$i] = $result[$i]['UserID'];
      $res2[$i] = $result[$i]['FirstName'];
      $res3[$i] = $result[$i]['LastName'];
      $res4[$i] = $result[$i]['PhoneNumber'];
      $res5[$i] = $result[$i]['Password'];
    }
    $response['UserIDArray'] = $res1;
    $response['FirstNameArray'] = $res2;
    $response['LastNameArray'] = $res3;
    $response['PhoneNumberArray'] = $res4;
    $response['PasswordArray'] = $res5;
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
