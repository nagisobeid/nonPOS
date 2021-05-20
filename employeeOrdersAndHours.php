<?php
	session_start();

	//NOT LOGGED IN
	if(!isset($_SESSION["auth"])) {
		header("location: login.php");
		exit;
	}
	elseif (isset($_SESSION["auth"]) and (!isset($_SESSION["currentEmployeePin"]))
		and !isset($_SESSION["firstLogin"])) {
		//LOGGED IN BUT NO PIN
		header("location: pin.php");
		exit;
	}
	elseif (isset($_SESSION["auth"]) and ($_SESSION["currentEmployeePermissions"] == 2)) {
		//DON'T HAVE PERMISSION
		header("location: pin.php");
		exit;
	}

	include_once "db.php";

	$obj = new DBH;
	$con = $obj->connect();

	$status = "";

	$src = "pin.php";
	
	function inputValidation($eID, $entPIN, $manPIN, $bID) {
		global $con;
		$stmt = $con->prepare("SELECT eID FROM employees WHERE eID = :eID AND
							   bID = :bID");
		if($stmt->execute(array(":eID" => $eID, ":bID" => $bID))) {
			if(!($stmt->rowCount() > 0)) {
				print("ERROR: Employee ID either doesn't exist or belongs to a
					   different business");
				return false;
			}
		}
		if(!($entPIN == $manPIN)) {
			print("ERROR: Wrong Manager PIN entered");
			return false;
		}
		return true;
	}
	
	function getOrders($eID) {
		global $con;
		$stmt = $con->prepare("SELECT * FROM orders WHERE eID = :eID");
		if($stmt->execute(array(":eID" => $eID))) {
			if($stmt->rowCount() > 0) {
				print("Orders Handles By Employee #" .$eID. "<br>-----------------------------<br>");
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					print("Order ID: " .$row["oID"]. " -- Customer: "
					       .$row["username"]. " -- Placed: " .$row["placed"].
						   " -- Completed: " .$row["completed"]. " -- Type: ");
					if($row["type"] == 1) {
						print("Dine-In<br><br>");
					}
					else {
						print("Drive-Thru<br><br>");
					}
				}
			}
			else {
				print("This employee has handled no orders");
			}
		}
	}
	
	function getHours($eID) {
		global $con;
		$stmt = $con->prepare("SELECT * FROM hours WHERE eID = :eID");
		if($stmt->execute(array(":eID" => $eID))) {
			if($stmt->rowCount() > 0) {
				print("Shifts Worked By Employee #" .$eID. "<br>------------------------------<br>");
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					print("-- Shift Start: " .$row["payStart"]. " -- Shift End: "
					       .$row["payEnd"]. " -- Hourly Wage: " .$row["payRate"].
						   " -- Hours Worked: " .$row["hoursWorked"]. "<br><br>");
				}
			}
			else {
				print("This employee has worked no shifts");
			}
		}
	}
	
	
?>

<!--DOCTYPE html-->
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="./css/employeeOrdersAndHours.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery Library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/JavaScript">
	$(document).ready(function() {
		$(".form").css("height", "360px");
	});
	</script>
	

<body>
<header>
	<div id="main-bar">
		<img id="logo" src="./images/logo2.png"></img>
		<button onclick="document.location='home.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button onclick="document.location='logout.php'" type="button" id="idBtnAboutus" class="btn btn-link">Logout</button>
	</div>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description"><?= "Employee Hours And Orders"?></h1>
		</div>
		<div class="form">
			<form method="post" action="employeeOrdersAndHours.php">
				View Employee Orders
				<br>[Employee ID]: <input id="idEtyBx" name="orders-ID" type="text" >
				<br>[Manager PIN]: <input id="idEtyBx" name="orders-PIN" type="password" >
				<br><input id="idBtnOrders" type="submit" name="action" value="GET ORDERS">
			</form>
			<form method="post" action="employeeOrdersAndHours.php">
				View Employee Hours
				<br>[Employee ID]: <input id="idEtyBx" name="hours-ID" type="text" >
				<br>[Manager PIN]: <input id="idEtyBx" name="hours-PIN" type="password" >
				<br><input type="submit" name="action" value="GET HOURS">
			</form>
			<form method="post" action="employeeOrdersAndHours.php">
				<input type="submit" name="action" value="View Own Shifts">
			</form>
		</div>
		<?php
		if($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=="GET ORDERS") {
			if (!empty($_POST["orders-ID"]) && !empty($_POST["orders-PIN"])) {
				if (inputValidation($_POST["orders-ID"],$_POST["orders-PIN"],
					$_SESSION["currentEmployeePin"], $_SESSION["bid"])) {
					getOrders($_POST["orders-ID"]);
				}
			}
			else {
				print("ERROR: Both fields for 'GET ORDERS' must be entered");
			}
		}
		elseif($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=="GET HOURS") {
			if (!empty($_POST["hours-ID"]) && !empty($_POST["hours-PIN"])) {
				if (inputValidation($_POST["hours-ID"],$_POST["hours-PIN"],
					$_SESSION["currentEmployeePin"], $_SESSION["bid"])) {
					getHours($_POST["hours-ID"]);
				}
			}
			else {
				print("ERROR: Both fields for 'GET HOURS' must be entered");
			}
		}
		elseif($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["action"]=="View Own Shifts") {
			getHours($_SESSION["currentEmployee"]);
		}
		?>
	</div>
</header>
</body>
</head>
</html>