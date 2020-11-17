<?php
	session_start();
	include_once 'db.php';

	$obj = new DBH;
		$con = $obj->connect();

		$status = "";
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$name = $_POST['nameBusiness'];
			$email = $_POST['nameEmail'];
			$username = $_POST['nameUsername'];
			$password = $_POST['namePassword'];
			$passwordRepeat = $_POST['namePasswordRepeat'];
	  
			if(empty($name) || empty($email) || empty($username) || empty($password) || empty($passwordRepeat)) {
				$status = "All fields are requried";
			} else { 
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$status = "Please enter a valid email";
				} else {
					if ($passwordRepeat != $password) {
						$status = "Passwords must match";
					} else {
						$sql = "INSERT INTO business_user (name, email, username, password) 
						VALUES (:name, :email, :username, :password)";
				
						$stmt = $con->prepare($sql);
						$stmt->execute(['name' => $name, 'email' => $email, 'username' => $username, 'password' => $password]);
						
						$_SESSION['name'] = $name;
						header("location: home.php");

						#$name = "";
						$email = "";
						$username = "";
						$password = "";
						$passwordRepeat = "";
						}
		  			}
				}
	  		}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="createaccount.css">
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
		<button onclick="document.location='index.html'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
	</div>
</header>
	
	<div id="divCreateAccount" class="container">
		<div class="formDescription">
			<h1 class="description">Create Account</h1>
		</div>
		<form action="" method="POST" id="formCreateAccount" class="form">
			<input class="inputForm" type="text" id="idBusiness" name="nameBusiness" placeholder="Business Name" required>    
	    	<input class="inputForm" type="text" id="idUsername" name="nameUsername" placeholder="Username" required>
	    	<input class="inputForm" type="Email" id="idEmail" name="nameEmail" placeholder="Email" required>
	    	<input class="inputForm" type="Password" id="idPassword" name="namePassword" placeholder="Password" required>
	   		<input class="inputForm" type="Password" id="idPasswordRepeat" name="namePasswordRepeat" placeholder="Password" required>
			<input name="btnCreateAccount" id="btnCreateAccount" class="btnForm" type="submit" value="Submit">  
		</form>

		<div id="divPhpMessage">
			<h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
		</div>
	</div>
	
</body>
</html>

