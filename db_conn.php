<?php
	// $conn = new mysqli('localhost', 'root', '', 'votesystem');

	// if ($conn->connect_error) {
	//     die("Connection failed: " . $conn->connect_error);
	// }
  
  // Database configuration

  $host = 'localhost';
  $dbName = 'votesystem'; //database name
  $username = 'root';
  $password = '';
  $node_url = 'http://localhost:3002/';
// Establish a database connection
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

	
?>