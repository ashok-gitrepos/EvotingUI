<?php
include '../db_conn.php';
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute the SQL query
$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$hashedPassword = $admin['password'];
if (password_verify($password, $hashedPassword)) {
  // Password is correct, log in the user
  echo "<script>
  alert('Login successful!');
  window.location='AdminPage.html';
  </script>";
} else {
  // Password is incorrect
  echo "<script>
  alert('Invalid credentials. Please try again');
  window.location='login.html';
  </script>";
}

?>