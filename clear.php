<?php
	$servername = "localhost";
	$username = "scan";
	$password = "";
	$db = "print";
	
	$conn = new mysqli($servername, $username, $password, $db);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "DELETE FROM scan_jobs";
	$result = $conn->query($sql);
?>
