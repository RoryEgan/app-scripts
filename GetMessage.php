<?php
include('DatabaseController.class.php');
$db = new DatabaseController();
$connection = $db -> connect();

$response = array();
$response['success'] = false;

if(isset($_GET['ThreadID'])) {

  $threadID = $db->quote($_GET['ThreadID']);

  $sqlSelect = "SELECT * FROM Message WHERE ThreadID = '$threadID';";

  $result = $db->select($sqlSelect);

  if($result) {
    $res = array();
    $response['success'] = true;
    $var = sizeOf($result);
    for($i = 0; $i < sizeof($result); $i++) {
      $res1[$i] = $result[$i]['MessageID'];
      $res2[$i] = $result[$i]['ThreadID'];
      $res3[$i] = $result[$i]['SenderID'];
      $res4[$i] = $result[$i]['TargetID'];
      $res5[$i] = $result[$i]['SentDate'];
      $res6[$i] = $result[$i]['Content'];
    }
    $response['MessageIDArray'] = $res1;
    $response['ThreadIDArray'] = $res2;
    $response['SenderIDArray'] = $res3;
    $response['TargetIDArray'] = $res4;
    $response['SentDateArray'] = $res5;
    $response['ContentArray'] = $res6;
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
