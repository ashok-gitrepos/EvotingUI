<?php
include 'db_conn.php';
include 'curl.php';

// Retrieve form data
$studentid = $_POST['studentid'];
$password = $_POST['password'];

// Prepare and execute the SQL query
$sql = "SELECT * FROM users_bg WHERE studentid = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$studentid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a user with the provided student ID exists
if (!$user) {
  echo "<script>
  alert('Invalid student ID or password. Please try again.');
  window.location='login.html';
  </script>";
} else {
  // Verify the password
  $hashedPassword = $user['password'];
  
  if (password_verify($password, $hashedPassword)) {
    # fetch user details from bigchain db
    $url_check = $node_url.'transactions';
    $params_check = '?asset_id='.$user['asset_id'];
    $result_check = json_decode(sendGet($url_check, $params_check));
    
    $user_bg = $result_check->asset->data;
    
    // Password is correct, log in the user
    session_start();
    $_SESSION["user"]=$user['asset_id'];
    echo "<script>
    alert('Login successful! Welcome back ".$user_bg->firstname. ' ' .
    $user_bg->lastname ." !');
    window.location='home.html';
    </script>";
  } else {
    // Password is incorrect
    echo "<script>
    alert('Invalid student ID or password. Please try again');
    window.location='index.html';
    </script>";
  }
}

?>