<?php

date_default_timezone_set('Europe/Vilnius');
set_error_handler("customError");

define('SERVER', "localhost");
define('USER', "root");
define('PASS', "");
define('DATABASE', "fake");

function customError($error_level,$error_message, $error_file,$error_line,$error_context) {

	try {

	    $conn = new PDO("mysql:host=" . SERVER .";dbname=" . DATABASE . ";charset=utf8", USER, PASS);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $conn->query("INSERT INTO errors (level, tekstas, file, eilute) VALUES ('$error_level','$error_message','$error_file','$error_line')");
	    $customerFile = fopen("dir/DBerror.log", "a+");
    	fwrite($customerFile, $error_level.",".$error_message.",".$error_file.",".$error_line."\n");
    	fclose($customerFile);
	  

	} catch(PDOException $e) {
	    echo "Connection failed: " . $e->getMessage();
	}
}

try {

    $conn = new PDO("mysql:host=" . SERVER .";dbname=" . DATABASE . ";charset=utf8", USER, PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $q = $conn->prepare("SELECT * FROM errors"); 
    $q->execute();

    $errors = $q->fetchAll(PDO::FETCH_ASSOC);
    $customerFile = fopen("error.log", "a+");
    foreach ($errors as $error) {
    	fwrite($customerFile, $error['id'].",".$error['laikas'].",".$error['level'].",".$error['tekstas'].",".$error['file'].",".$error['eilute']."\n");
    	};
    	fclose($customerFile);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

echo $fsfdg;
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Error Log</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="row">
			<table class="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Time</th>
						<th>File</th>
						<th>Line</th>
						<th>Error</th>
					</tr>

				</thead>
				<tdata>
					<?php
						foreach ($errors as $error) {
							echo "<tr>
							<td>".$error['id']."</td>
							<td>".$error['laikas']."</td>
							<td>".$error['level']."</td>
							<td>".$error['tekstas']."</td>
							<td>".$error['file']."</td>
							<td>".$error['eilute']."</td>
							</tr>";
						}
					?>
				</tdata>
			</table>
		</div>
	</div>
	
</body>
</html>