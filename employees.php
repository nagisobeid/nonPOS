<?php
	session_start();

	//NOT LOGGED IN
	if(!isset($_SESSION['auth'])) {
		header("location: login.php");
		exit;
	}
	elseif (isset($_SESSION['auth']) and (!isset($_SESSION['currentEmployeePermissions']))
		and !isset($_SESSION['firstLogin'])) {
		//LOGGED IN BUT NO PIN
		header("location: pin.php");
		exit;
	}
	/*elseif (isset($_SESSION['auth']) and isset($_SESSION['currentEmployeePermissions'])) {
		//LOGGED IN AND PIN DETECTED, BUT NOT MANAGER
		if ($_SESSION['currentEmployeePermissions'] == 2) {
			header("location: home.php");
			exit;
		}
	}*/

	include_once 'db.php';

	$obj = new DBH;
	$con = $obj->connect();

	$status = "";

	$src = 'pin.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="employees.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery Library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/JavaScript">
	$(document).ready(function() {
		var currentEmployeePermissions = '<?php echo $_SESSION['currentEmployeePermissions']; ?>';
		if (currentEmployeePermissions == 2) {
			$("#idBtnViewEmployees").hide();
			$("#idBtnManageEmployees").hide();
			$(".form").css("height", "180px");
		}
	});
	</script>
</head>

<body>
<header>
	<div id="main-bar">
		<img id="logo" src="./images/logo2.png"></img>
		<button onclick="document.location='home.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button onclick="document.location='logout.php'" type="button" id="idBtnAboutus" class="btn btn-link">Logout</button>
	</div>
</header>

	<div class="container">
		<div class="formDescription">
			<h1 class="description"><?= "Employee Page"?></h1>
		</div>

		<div class="form">
			<input id="idBtnClock" type="submit" value="Clock-In">
			<input type="submit" value="Clock-Out"> 
			<input type="submit" value="Personal Employee Info">
			<input id="idBtnViewEmployees" type="submit" value="View Employees">
			<input id="idBtnManageEmployees" type="submit" value="Manage Employee Data">
		</div>
	</div>

</body>
</html>
