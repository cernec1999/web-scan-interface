<?php
    ini_set('max_execution_time', 0);

	$servername = "localhost";
	$username = "scan";
	$password = "";
	$db = "print";

    ob_implicit_flush(true);
    ob_end_flush();

    $conn = new mysqli($servername, $username, $password, $db);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM scan_jobs";
	$result = $conn->query($sql);

    $files = "";

	while($row = $result->fetch_assoc()) {
		$files .= $row['image_name'] . " ";
	}

    $filename = uniqid() . ".pdf";

    $cmd = "sudo convert -monitor " . $files . $filename;
    echo $cmd;

    $descriptorspec = array(
        0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
        2 => array("pipe", "w")    // stderr is a pipe that the child will write to
    );

    ob_flush();

    $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./tmp'), array());
    if (is_resource($process)) {
        while (false !== ($char = fgetc($pipes[2]))) {
            //print $char;
            if(ord($char) == 13) {
                echo "\r";
            } else {
                echo $char;
            }
            ob_flush();
        }
        echo "\r" . $filename;
    }
?>
