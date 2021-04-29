<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();	
	if (isset($_SESSION['auth']))		
	{						
		header("location: home.php");	
    	exit;	
    }	
    
    include_once 'db.php';
    
    $obj = new DBH;
    $con = $obj->connect();

    $status = "";
    $bID = $_SESSION['bID'];
        
    if(($_SERVER['REQUEST_METHOD'] == 'POST')) {
        $old = $_POST['currentPassword'];
        $new = $_POST['newPassword'];
        
        if(empty($new) || empty($old)) {
            $status = "Password requried";
        }
        elseif (!empty($new)) {
            $sql = "SELECT * FROM owners WHERE password = '$old'";
            $res = $con->prepare($sql);
            $res->execute();
      

            $sql = "UPDATE owners set password = '$new' WHERE bID = '$bID'";
            $res = $con->prepare($sql);
            $res->execute();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="editPassword.css">
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
		<button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
	</div>
</header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description">Change Password</h1>
		</div>
        <form action="" method="POST" id="formLogin" class="form">
	    	<input type="password" id="idPassword" name="currentPassword" placeholder="Current Password">
	    	<input type="password" id="idnewPassword" name="newPassword" placeholder="New Password">
	  		<input type="submit" value="Save Changes">
		</form>

	</div>
		
</body>
</html>
