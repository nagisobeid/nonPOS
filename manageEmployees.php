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
	
	function permissionDoubleCheck($eID, $pin, $bID) { //Stops sneaky people if a manager
		global $con;                             //leaves a device logged in
		$stmt = $con->prepare("SELECT * FROM employees WHERE
			eID = :eID AND ePass = :pin AND bID = :bID");
			if($stmt->execute(array(":eID" => $eID, ":pin" => $pin, ":bID" => $bID))) {
				if($stmt->rowCount() > 0)
					return true;
			}
			else
				return false;
	}
	
	function updateEmployee($fields) {
		global $con;
		$query = "UPDATE employees SET ";
		$fieldKey = array(":eID" => $fields[1]);
		$onePlusFields = 0; // Once tripped, assures insertion of commas into query
		if(!empty($fields[2])) {
			$query = $query. "fname = :fname";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":fname" => $fields[2]));
		}
		if(!empty($fields[3])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "lname = :lname";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":lname" => $fields[3]));
		}
		if(!empty($fields[4])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "dob = :dob";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":dob" => $fields[4]));
		}
		if(!empty($fields[5])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "ePass = :empPIN";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":empPIN" => $fields[5]));
		}
		if(!empty($fields[6])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "address = :address";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":address" => $fields[6]));
		}
		if(!empty($fields[7])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "city = :city";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":city" => $fields[7]));
		}
		if(!empty($fields[8])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "state = :state";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":state" => $fields[8]));
		}
		if(!empty($fields[9])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "zip = :zipCode";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":zipCode" => $fields[9]));
		}
		if(!empty($fields[10])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "phone = :phoneNumber";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":phoneNumber" => $fields[10]));
		}
		if(!empty($fields[11])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "permisions = :permissions"; //Looks like a mistake,
			$onePlusFields = 1;                           //but too many files use
			                                              //'permisions' already
			$fieldKey = array_merge($fieldKey, array(":permissions" => $fields[11]));
		}
		if(!empty($fields[12])) {
			if($onePlusFields == 1)
				$query = $query. ", ";
			$query = $query. "payRate = :payRate";
			$onePlusFields = 1;
			$fieldKey = array_merge($fieldKey, array(":payRate" => $fields[12]));
		}
		$query = $query. " WHERE eID = :eID";
		if($onePlusFields == 1) {
			$stmt = $con->prepare($query);
			if($stmt->execute($fieldKey)) {
				echo "Update Successful";
			}
			else {
				echo "Database Error: Query couldn't be executed.";
			}
		}
		else {
			echo "You must fill at least one field alongside Employee ID & Manager PIN";
		}
	}
	
	function deleteEmployee($eID) { //Ideally this would remove to a different table
		global $con;                       //for record keeping purposes
		$stmt = $con->prepare("DELETE FROM employees WHERE eID = :eID");
		if ($stmt->execute(array(":eID" => $eID))) {
			echo "Successful Deletion";
		}
	}
?>

<!--DOCTYPE html-->
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="manageEmployees.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery Library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script type="text/JavaScript">
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
			<h1 class="description"><?= "Manage Employees"?></h1>
		</div>
		<div class="form">
			<form method="post" action="manageEmployees.php">
				Update Employee
				<br>[Employee ID] - Must Be Filled: <input id="idEtyBx" name="update-ID" type="text" >
				<br>   [First Name]: <input id="idEtyBx" name="update-fname" type="text" >
				<br>    [Last Name]: <input id="idEtyBx" name="update-lname" type="text" >
				<br>[Date of Birth]: <input id="idEtyBx" name="update-dob" type="date" >
				<br> [Employee PIN]: <input id="idEtyBx" name="update-empPIN" type="password" >
				<br>      [Address]: <input id="idEtyBx" name="update-addr" type="text" >
				<br>         [City]: <input id="idEtyBx" name="update-city" type="text" >
				<br>[State (2-Letter Abbreviation)]: <input id="idEtyBx" name="update-state" type="text" >
				<br>     [Zip Code]: <input id="idEtyBx" name="update-zip" type="text" >
				<br> [Phone Number]: <input id="idEtyBx" name="update-phone" type="text" >
				<br>[Permissions (1 - Manager or 2 - Employee)]: <input id="idEtyBx" name="update-perm" type="text" >
				<br>     [Pay Rate]: <input id="idEtyBx" name="update-pay" type="text" >
				<br>[Manager PIN] - Must Be Filled: <input id="idEtyBx" name="update-manPIN" type="password" >
				<br><input id="idBtnUpd" type="submit" name="action" value="UPDATE">
			</form>
			<form method="post" action="manageEmployees.php">
				Remove Employee
				<br>[Employee ID]: <input id="idEtyBx" name="delete-ID" type="text" >
				<br>[Manager PIN]: <input id="idEtyBx" name="delete-PIN" type="password" >
				<br><input type="submit" name="action" value="DELETE">
			</form>
		</div>
		
		<?php
		if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='UPDATE') {
			if(!empty($_POST["update-ID"]) || !empty($_POST["update-manPIN"])) {
				$fields = array(1 => $_POST["update-ID"], $_POST["update-fname"], $_POST["update-lname"],
					$_POST["update-dob"], $_POST["update-empPIN"], $_POST["update-addr"],
					$_POST["update-city"],$_POST["update-state"], $_POST["update-zip"],
					$_POST["update-phone"], $_POST["update-perm"], $_POST["update-pay"]);
				if(permissionDoubleCheck($_SESSION["currentEmployee"], $_POST["update-manPIN"], $_SESSION["bid"])) {
					updateEmployee($fields);
				}
				else {
					echo "Update Failed: Incorrect manager PIN";
				}
			}
			else {
				echo "Error: You must enter an Employee ID and your manager PIN to update an employee record";
			}
		}
		elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='DELETE') {
			if(!empty($_POST["delete-ID"]) || !empty($_POST["delete-PIN"])) {
				if(permissionDoubleCheck($_SESSION["currentEmployee"], $_POST["delete-PIN"], $_SESSION["bid"])) {
					deleteEmployee($_POST["delete-ID"]);
				}
				else {
					echo "Delete Failed: Incorrect manager PIN";
				}
			}
			else {
				echo "Error: You must enter an Employee ID and your manager PIN to delete an employee record";
			}
		}
		?>
	</div>
</header>
</body>
</head>
</html>