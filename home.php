<?php
	session_start();
	if (!isset($_SESSION['auth']))
	{
		header("location: login.php");
    	exit;
	}
	$src = 'pin.php';	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="home.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/JavaScript">
    	$(document).ready(function() {
			var currentEmployeePermissions = '<?php echo $_SESSION['currentEmployeePermissions']; ?>';
			//console.log(currentEmployeePermissions);
			if (currentEmployeePermissions == 2) {
				$("#idBtnSales").hide();
				$("#idBtnMenu").hide();
				$("#idBtnSettings").hide();
				$(".form").css("height", "190px");
			}
    	}); 
	</script>

</head>
<body>
<header>
	<div id="main-bar">
		<img id="logo" src="./images/logo2.png"></img>
		<button onclick="document.location='home.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button onclick="document.location='logout.php'" type="button" id="idBtnAboutus" class="btn btn-link">Log Out</button>
	</div>
</header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description"><?= $_SESSION['bname']?></h1>
		</div>
		<div class="form">  
			<input id="idBtnPosRegister" action="" method="POST" type="submit" value="POS Register">
			<input id="idBtnSales" type="submit" value="Sales">
			<input id="idBtnEmployees" onclick="document.location='employees.php'" type="submit" value="Employees">
			<input id="idBtnMenu" type="submit" value="Menu">
			<input id="idBtnSettings" type="submit" value="Settings">
			<input id="itBtnEmployeePin" onclick="document.location='pin.php'" type="submit" value="Employee Login">
		</div>
	</div>		
</body>
</html>
