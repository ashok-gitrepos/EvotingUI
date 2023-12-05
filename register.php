<?php
include 'db_conn.php';
include 'curl.php';
// Retrieve form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$studentid = $_POST['studentid'];
$phonenumber = $_POST['phonenumber'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];

// Validate form data (you can add more validation if needed)
if (empty($firstname) || empty($lastname) || empty($studentid) 
|| empty($phonenumber) || empty($password) || empty($confirmpassword)) {
  echo "<script>
    alert('Please fill in all the required fields.');
    window.location = 'register.html';
  </script>";
} elseif ($password !== $confirmpassword) {
  echo "<script>
  alert('Passwords do not match. Please try again.');
  window.location = 'register.html';
</script>";
} else {
  # check same sid/mobile available or not?
  $url_check = $node_url.'search';
  $params_check = '?keyword='.$studentid;
  $result_check = json_decode(sendGet($url_check, $params_check));
  
  if(count($result_check) == 0){
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //123456
    
    $params = 'firstname='.$firstname.'&lastname='.$lastname.'&studentid='.$studentid.
    '&phonenumber='.$phonenumber;
    
    $url = $node_url.'register';
    $result = json_decode(sendPost($url,$params));
    $asset_id = $result->id;
    
    if($asset_id && $asset_id != ''){
      // insert asset-id, pkey, publickey into mysql along with sid.
      // Prepare and execute the SQL query
      $payload = $result->payload;
      $sql = "INSERT INTO users_bg (studentid, asset_id, private_key, public_key, photo, password) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([ $studentid, $asset_id, $payload->private_key, $payload->public_key, '', $hashedPassword]);
      
      // Registration successful
      echo "<script>
      alert('Registration Successful!');
      window.location = 'login.html';
      </script>";
    }
  }else{
    echo "<script>
    alert('Failed!!! Duplicate student ID!');
    window.location = 'register.html';
    </script>";
  }
}
?>