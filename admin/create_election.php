<?php
include '../db_conn.php';
include '../curl.php';

// Retrieve form data
$electionTitle = $_POST['electionTitle'];
$electionDate = $_POST['electionDate'];
$resultDate = $_POST['resultDate'];

$incr = $_POST['incr'];
$candidates = [];
$infors = [];

for($i=0; $i <= $incr; $i++ ){
  $firstName = $_POST['firstName'.$i];
  $lastName = $_POST['lastName'.$i];
  $information = $_POST['information'.$i];

  array_push($candidates, $firstName.' '.$lastName);
  array_push($infors, $information);
}

// Validate form data (you can add more validation if needed)
if (empty($electionTitle) || empty($electionDate) || empty($resultDate) || 
    sizeof($candidates) < 1 ) {
  echo "<script>
      alert('Please fill in all the required fields.');
      window.location = 'CreateElection.html';
    </script>";
}else {
  $params = [
    "title" => $electionTitle,
    "date" => $electionDate,
    "result" => $resultDate,
    "candidates" =>$candidates,
    "information"=> $infors
  ];
  
  $params = json_encode($params);
  $url = $node_url.'create-election';
  $result = json_decode(sendElectionPost($url,$params));
  $asset_id = $result->id;
  $payload = $result->payload;

  if($asset_id){
    $sql = "INSERT INTO elections (asset_id, private_key, public_key) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$asset_id, $payload->private_key, $payload->public_key]);
    
    // Registration successful
    echo "<script>
      alert('Election Scheduled!');
      window.location = 'AdminPage.html';
    </script>";
  }else{
    echo "<script>
      alert('Failed! Try again later!!!');
      window.location = 'CreateElection.html';
    </script>";
  }
}
?>