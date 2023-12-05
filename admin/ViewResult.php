<?php
include '../db_conn.php';
include '../curl.php';

$url_check = $node_url.'vote-count';
//get public address from assetid of election_id

$sql_elect = "SELECT *  FROM elections WHERE asset_id = ?";
$stmt = $pdo->prepare($sql_elect);
$stmt->execute([$_GET['id']]);
$election_details = $stmt->fetch(PDO::FETCH_ASSOC);
if($election_details){
$params_check = '?public_key='.$election_details['public_key'];

$result = (sendGet($url_check, $params_check));

if(!$result) {
  echo '<script>alert("invalid!");
    window.location.href = "election_schedule.php";
  </script>';
}
}else{
  echo '<script>alert("invalid!");
    window.location.href = "election_schedule.php";
  </script>';
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Voting Results</title>
  <link rel="stylesheet" type="text/css" href="../css/app.css">
</head>
<body>
  <div class="bg-image"></div>
  <div class="home-btns">
    <a class="btn btn1" href="CreateElection.html"> Create an Election</a>
    <a class="btn btn2" href="election_schedule.php"> Election schedule </a>
    <a class="btn btn3" href="logout.html"> Logout </a>
  </div>

  <div class="content-center" style="margin-top:70px;">  
  <h1>Voting Results</h1>
    <div class="box">
      <h3><?=$election_details['election_title']?></h3>
      <h5>Candidate name :  Votes</h5>
      <?php
      if($result){
        $sno= 1;
        print_r($result);
      }else
        echo '<h2>Not available!</h2>';
      ?>
  </div>
 
  <button onclick="goBack()" class="btn btn3" style="margin-top:20px">Go Back</button>
  </div>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>
</body>
</html>