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
	
	$sql = "SELECT * FROM scan_jobs";
	$result = $conn->query($sql);
	
	$arr = array();
	while($row = $result->fetch_assoc()) {
		array_push($arr, $row['image_name']);
	}
	
	echo json_encode($arr);
?>
