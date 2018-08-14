<?php
	$servername = "localhost";
	$username = "scan";
	$password = "";
	$db = "print";

    ob_implicit_flush(true);
    ob_end_flush();

    $filename = uniqid() . ".jpg";

    //$cmd = "sudo scanimage -p --format png --resolution 300 -x 215 -y 280 > " . $filename;
	$cmd = "sudo scanimage -p --format tiff --resolution 300 -x 215 -y 280 | convert tiff:- " . $filename;

    $descriptorspec = array(
        0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
        2 => array("pipe", "w")    // stderr is a pipe that the child will write to
    );

    flush();

    $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./tmp'), array());
    if (is_resource($process)) {
        while (false !== ($char = fgetc($pipes[2]))) {
            //print $char;
            if(ord($char) == 13) {
                echo "\r";
            } else {
                echo $char;
            }
            flush();
        }
        echo "\r" . $filename;

		//Now add filename to MySQL
		$conn = new mysqli($servername, $username, $password, $db);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		$sql = "INSERT INTO scan_jobs (image_name) VALUES ('" . $filename . "');";
		$result = $conn->query($sql);
    }
?>
