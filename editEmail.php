<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    if (!isset($_SESSION['auth']))
    {
        header("location: login.php");
        exit;
    }
    include_once 'db.php';
    $obj = new DBH;
    $con = $obj->connect();
    $bID = $_SESSION['bid'];
    $status = "";
        
    if(($_SERVER['REQUEST_METHOD'] == 'POST')) {
        $new = $_POST['newEmail'];
        
        if(empty($new)) {
            $status = "Username requried";
        }
        elseif (!empty($new)) {
            $sql = "SELECT * FROM owners WHERE email = '$new'";
            $res = $con->prepare($sql);
            $res->execute();
      

            if($res->rowCount() > 0) {
                $status = "Email Already Exists";
                echo $status;
            } else{
                $sql = "UPDATE owners set email = '$new' WHERE bID = '$bID'";
                $res = $con->prepare($sql);
                $res->execute();
                $status = "Email Updated";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="./css/editEmail.css"><!--add ./css/-->
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
            <button onclick="document.location='home.php'" type="button" id="idBtnHome"
                class="btn btn-link">Home</button>
            <button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
    </header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description">Change Email Address</h1>
		</div>
        <form  method="POST" id="formLogin" class="form">
	    	<input type="email" id="idnewEmail" name="newEmail" placeholder="New Email">
	  		<input type="submit" value="Save Changes">
		</form>
        <div id="divPhpMessage">
            <h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
		</div>
	</div>
		
</body>
</html>
