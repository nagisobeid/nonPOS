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
		
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$fname = $_POST['nameFname'];
		$lname = $_POST['nameLname'];
		$bname = $_POST['nameBusiness'];
		$email = $_POST['nameEmail'];
		$username = $_POST['nameUsername'];
		$password = $_POST['namePassword'];
		$passwordRepeat = $_POST['namePasswordRepeat'];

		$sql_u = "SELECT * FROM owners WHERE username='$username'";
		$sql_e = "SELECT * FROM owners WHERE email='$email'";
		$res_u = $con->prepare($sql_u);
		$res_e = $con->prepare($sql_e); 
		$res_u->execute();
		$res_e->execute();
	  
		if(empty($fname) || empty($lname) ||empty($bname) || empty($email) || empty($username) || empty($password) || empty($passwordRepeat)) {
			$status = "All fields are requried";
		}
		else if ($res_u->rowCount() > 0) {
			$status = "Username Already Exists";
		} 
		else if ($res_e->rowCount() > 0) {
			$status = "Email Already Exists";
		} else { 
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$status = "Please enter a valid email";
			} else {
				if ($passwordRepeat != $password) {
					$status = "Passwords must match";
				} else {
					$sql = "INSERT INTO owners (username, email, fName, lName, bName, password) 
					VALUES (:username, :email, :fName, :lName, :bName, :password)";

					#$hashedPW = password_hash($password, PASSWORD_BCRYPT);
					$hashedPW = crypt($password, 'CRYPT_BLOWFISH');
				
					$stmt = $con->prepare($sql);
					$stmt->execute(['username' => $username, 'email' => $email, 'fName' => $fname, 
									'lName' => $lname, 'bName' => $bname, 'password'=> $hashedPW]);
					
					$sql = "SELECT * FROM owners WHERE username='$username'";
					$res = $con->prepare($sql); 
					$res->execute();
					$owner = $res->fetch();
					$bID = $owner['bID'];
						
					$_SESSION['bname'] = $bname;
					$_SESSION['bid'] = $bID;
					$_SESSION['username'] = $username;
					$_SESSION['auth']=true;
					$_SESSION['firstLogin']=true;
					header("location: createemployee.php");

					#$name = "";
					#$email = "";
					#$username = "";
					#$password = "";
					#$passwordRepeat = "";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
            <button onclick="document.location='about.php'" type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
    </header>

    <div id="divCreateAccount" class="container">
        <div class="formDescription">
            <h1 class="description">Create Account</h1>
        </div>
        <form action="" method="POST" id="formCreateAccount" class="form">
            <input class="inputForm" type="text" id="idFname" name="nameFname" placeholder="First Name" required>
            <input class="inputForm" type="text" id="idLname" name="nameLname" placeholder="Last Name" required>
            <input class="inputForm" type="text" id="idBusiness" name="nameBusiness" placeholder="Business Name"
                required>
            <input class="inputForm" type="text" id="idUsername" name="nameUsername" placeholder="Username" required>
            <input class="inputForm" type="Email" id="idEmail" name="nameEmail" placeholder="Email" required>
            <input class="inputForm" type="Password" id="idPassword" name="namePassword" placeholder="Password"
                required>
            <input class="inputForm" type="Password" id="idPasswordRepeat" name="namePasswordRepeat"
                placeholder="Password" required>
            <input name="btnCreateAccount" id="btnCreateAccount" class="btnForm" type="submit" value="Submit">
        </form>

        <div id="divPhpMessage">
            <h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
        </div>
    </div>

</body>

</html>
