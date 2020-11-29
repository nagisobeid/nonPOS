<!--WORKS FROM LOGIN-->
<!--WORKS FROM CREATE ACCOUNT-->
<?php
	session_start();
	if (!isset($_SESSION['auth']))
	{
		header("location: login.php");
    	exit;
	}
	
  	include_once 'db.php';

  	$obj = new DBH;
  	$con = $obj->connect();

	$status = "";
	$_SESSION['currentEmployeePin'] = null;
	$_SESSION['currentEmployeePermissions'] = null;
   
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$pin = $_POST['namePin']; 
		$bID = $_SESSION['bid'];
		$sql = "SELECT * FROM employees WHERE ePass = '$pin' AND bID = '$bID'";
		$result = $con->prepare($sql);
		$result->execute();

		$employee = $result->fetch();
		#echo $user['bName'];
		if ($employee and $employee['ePass'] == $pin) {
			#$status = $employee['fName'];
			#header("location: home.php");
			$_SESSION['currentEmployeePin'] = $pin;
			$_SESSION['currentEmployeePermissions'] = $employee['permissions'];
			header("location: home.php");
		}
		else {
			$status = "Invalid Pin";
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="pin.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<header>
	<div id="main-bar">
		<img id="logo" src="./images/logo2.png"></img>
		<button type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button onclick="document.location='logout.php'" type="button" id="idBtnLogout" class="btn btn-link">Log Out</button>
	</div>
</header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description">Employee Pin</h1>
		</div>
		<form action="" method="POST" id="formPin" class="form">  
	    	<input type="Password" id="idPin" name="namePin" placeholder="Pin" require>
	  		<input type="submit" value="Submit">
		</form>
		<div id="divPhpMessage">
			<h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
		</div>
	</div>
		
</body>
</html>