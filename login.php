<?php
	session_start();
	if (isset($_SESSION['auth']) and ($_SESSION['currentEmployeePermissions'] == 1 or $_SESSION['currentEmployeePermissions'] == 2))
	{
		header("location: home.php");
    	exit;
	} elseif (isset($_SESSION['auth']) and ($_SESSION['currentEmployeePermissions'] == null))
	{
		header("location: pin.php");
    	exit;
	}
	
  	include_once 'db.php';

  	$obj = new DBH;
  	$con = $obj->connect();

	$status = "";
   
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['nameUsername'];
		$password = $_POST['namePassword']; 
		
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $con->prepare($sql);
		$result->execute();

		$user = $result->fetch();
		#echo $user['bName'];
		
		$hashed_password = crypt($password, 'CRYPT_BLOWFISH');
        if ($user and ($user['uPass'] == $hashed_password)) {	
			$bname = $user['bName'];
			$bID = $user['bID'];
			$username = $user['username'];
			$_SESSION['bname'] = $bname;
			$_SESSION['bid'] = $bID;
			$_SESSION['username'] = $username;
			$_SESSION['auth']=true;
			$status = "";
			$_SESSION['currentEmployeePermissions'] = 1;
			header("location: home.php");
		}
		else {
			$status = "Username and/or Password is Incorrect";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="login.css">
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
		<button onclick="document.location='index.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
	</div>
</header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description">Login</h1>
		</div>
		
		<form action="" method="POST" id="formLogin" class="form">
	    	<input class="inputForm" type="text" id="idUsername" name="nameUsername" placeholder="Username" require>
	    	<input class="inputForm" type="Password" id="idPassword" name="namePassword" placeholder="Password" require>
	  		<input type="submit" value="Submit">
		</form>
		<div id="divPhpMessage">
			<h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
		</div>
	</div>
		
</body>
</html>
