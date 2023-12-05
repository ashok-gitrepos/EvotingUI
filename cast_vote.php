<?php
include 'db_conn.php';
include 'curl.php';

session_start();
if(!isset($_SESSION["user"]) ||  !$_SESSION["user"]){
  echo "<script>
  alert('User expired!');
  window.location='login.html';
  </script>";
}

// Retrieve form data
$election_id = $_POST['election_id'];
$person = $_POST['person'];
$user_id = $_SESSION["user"];
$url_check = $node_url.'meta-search';
$params_check = '?keyword='.$election_id;

$sql = "SELECT private_key, public_key FROM users_bg WHERE asset_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//check vote casted or not
$result_check = json_decode(sendGet($url_check, $params_check));
if($result_check){
  foreach($result_check as $res){
    try{
      if(
          (isset($res->metadata->key) && $res->metadata->key == 'cast_vote') && 
          (isset($res->metadata->from) && $res->metadata->from == $user['public_key']) 
        ){
        echo "<script>
          alert('invalid! your vote already casted!');
          window.location = 'ready_to_vote.php';
        </script>";
        die;
      }
    }catch(Exception $ex){}
  }
}

$sql_elect = "SELECT public_key FROM elections WHERE asset_id = ?";
$stmt = $pdo->prepare($sql_elect);
$stmt->execute([$election_id]);
$election_details = $stmt->fetch(PDO::FETCH_ASSOC);

$params = [
  "person" => $person,
  "election_id" => $election_id,
  "election_address" => $election_details['public_key'],
  "user_id" => $user_id,
  "public_key" => $user['public_key'],
  "private_key" => $user['private_key']
];
 
  $params = json_encode($params);
  $url = $node_url.'cast-vote';
  $result = json_decode(sendElectionPost($url,$params));
  $asset_id = $result->id;

  if($asset_id){
    echo "<script>
      alert('Your vote casted!');
      window.location = 'ready_to_vote.php';
    </script>";
  }else{
    echo "<script>
      alert('Failed! Try again later!!!');
      window.location = 'ready_to_vote.php';
    </script>";
  }
?>