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

	include_once "db.php";

	$obj = new DBH;
	$con = $obj->connect();

	$status = "";

	$src = "pin.php";
	
	function attemptClockIn($pin, $bID) {
		global $con;
		$stmt = $con->prepare("SELECT clockedIn, lastClockIn FROM employees WHERE
			ePass = :pin AND bID = :bID");
		if ($stmt->execute(array(":pin" => $pin, ":bID" => $bID))) {
			if($stmt->rowCount() > 0) {
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					if($row["clockedIn"] == 1) {
						echo "You are already clocked-in. Last Clock-In: "
							.$row["lastClockIn"]. "<br>";
					}
					elseif($row["clockedIn"] == 0) {
						$stmt2 = $con->prepare("UPDATE employees SET clockedIn=1,
							lastClockIn = :curTime WHERE ePass = :pin AND bID = :bID");
						$curDt = new DateTime("now");
						$curTime = $curDt->format("Y-m-d H:i:s");
						if($stmt2->execute(array(":curTime" => $curTime,
							":pin" => $pin, ":bID" => $bID))) {
							echo "Successful Clock-In. Clock-In Time is: " .$curTime; #Temp Mark
						}
					}
				}
			}
		}
		else {
			echo "Database Error: Could not execute query";
		}
	}
	
	function attemptClockOut($pin, $bID) {
		global $con;
		$stmt = $con->prepare("SELECT eID, payRate, clockedIn, lastClockIn FROM
			employees WHERE ePass = :pin AND bID = :bID");
		if ($stmt->execute(array(":pin" => $pin, ":bID" => $bID))) {
			if($stmt->rowCount() > 0) {
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					if($row["clockedIn"] == 1) {
						$lastClockIn = DateTime::createFromFormat("Y-m-d H:i:s",
							$row["lastClockIn"]);
						$curDt = new DateTime("now");
						$curTime = $curDt->format("Y-m-d H:i:s");
						$timeInterval = $curDt->diff($lastClockIn);
						$hours = $timeInterval->h;
						$hours = $hours + ($timeInterval->days*24);
						$minutes = $timeInterval->i;
						$shift = $hours. ":" .$minutes;
						// For quickly checking times used in function
						/*echo $lastClockIn->format("Y-m-d H:i:s");
						echo "<br>";
						echo $curDt->format("Y-m-d H:i:s");
						echo "<br>" .$shift. "<br>";*/
						$stmt2 = $con->prepare("INSERT INTO hours (eID, payStart,
							payEnd, payRate, hoursWorked) VALUES (:eID, :payStart,
							:payEnd, :payRate, :hoursWorked)");
						if($stmt2->execute(array(":eID" => $row["eID"],
							":payStart" => $row["lastClockIn"], ":payEnd" => $curTime,
							":payRate" => $row["payRate"], ":hoursWorked" => $shift))) {
							$stmt3 = $con->prepare("UPDATE employees SET
								clockedIn=0, lastClockIn=NULL WHERE ePass=:pin
								AND bID=:bID");
							if ($stmt3->execute(array(":pin" => $pin,
								":bID" => $bID))) {
								#Temp Mark
								echo "Successful Clock-Out. Shift was: <br>
									Start Time: " .$row["lastClockIn"]. "<br>
									  End Time: " .$curTime. "<br>Hours Worked: "
									  .$shift;
							}
						}
					}
					elseif($row["clockedIn"] == 0) {
						echo "Clock-Out Failed. You must be clocked in before you
							  can clock-out.";
					}
				}
			}
			else {
				echo "Error: No employee record found";
			}
		}
		else {
			echo "Database Error: Could not execute query";
		}
	}
	
	function employeeInfo($bID) {
		global $con;
		$stmt = $con->prepare("SELECT eID, fName, lName, dob, address, city,
							   state, zip, phone, permisions, payRate
							   FROM employees WHERE bID = :bID AND employed = 1");
		if($stmt->execute(array(":bID" => $bID))) {
			if($stmt->rowCount() > 0) {
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo "ID: " .$row["eID"]. " -- Name: " . $row["fName"]. " "
						.$row["lName"]. " -- DOB: " .$row["dob"]. " -- Address: " .$row["address"]. " "
						.$row["city"]. " " .$row["state"]. " " .$row["zip"]. " -- Phone #:  "
						.$row["phone"];
					if($row["permisions"] == 1) {
						echo " -- Manager";
					}
					else {
						echo " -- Employee";
					}
					echo " -- Pay Rate: " .$row["payRate"]. "<br><br>";
				}
			} else {
				echo "0 results";
			}
		}
	}
	
	function formerEmpInfo($bID) {
		global $con;
		$stmt = $con->prepare("SELECT eID, fName, lName, dob, address, city,
							   state, zip, phone FROM employees WHERE bID = :bID
							   AND employed = 0");
		if($stmt->execute(array(":bID" => $bID))) {
			if($stmt->rowCount() > 0) {
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo "ID: " .$row["eID"]. " -- Name: " . $row["fName"]. " "
						.$row["lName"]. " -- DOB: " .$row["dob"]. " -- Address: " .$row["address"]. " "
						.$row["city"]. " " .$row["state"]. " " .$row["zip"]. " -- Phone #:  "
						.$row["phone"]. "<br><br>";
				}
			} else {
				echo "0 results";
			}
		}
	}
	
	function personalInfo($pin, $bID) {
		global $con;
		$stmt = $con->prepare("SELECT eID, fName, lName, dob, address, city, state, zip, phone, permisions, payRate FROM employees WHERE
			ePass = :pin AND bID = :bID");
		if ($stmt->execute(array(":pin" => $pin, ":bID" => $bID))) {
			if($stmt->rowCount() > 0) {
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo "ID: " .$row["eID"]. " -- Name: " . $row["fName"]. " "
						.$row["lName"]. " -- DOB: " .$row["dob"]. " -- Address: " .$row["address"]. " "
						.$row["city"]. " " .$row["state"]. " " .$row["zip"]. " -- Phone #:  "
						.$row["phone"];
					if($row["permisions"] == 1) {
						echo " -- Manager";
					}
					else {
						echo " -- Employee";
					}
					echo " -- Pay Rate: " .$row["payRate"]. "<br><br>";
				}
			} else {
				echo "0 results";
			}
		}
	}
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
			$("#idBtnCreateEmployees").hide();
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
			<form method="post" action="employees.php">
				<input id="idBtnClock" name="action" type="submit" value="Clock-In">
			</form>
			<form method="post" action="employees.php">
				<input type="submit" name="action" value="Clock-Out"> 
			</form>
			<form method="post" action="employees.php">
				<input type="submit" name="action" value="Personal Employee Info">
			</form>
			<form method="post" action="employees.php">
				<input id="idBtnViewEmployees" type="submit" name="action" value="View Current Employees">
			</form>
			<form method="post" action="employees.php">
				<input type="submit" name="action" value="View Former Employees">
			</form>
			<input id="idBtnCreateEmployees" onclick="document.location='createemployee.php'" type="submit" value="Create New Employee">
			<input id="idBtnManageEmployees" onclick="document.location='manageEmployees.php'" type="submit" value="Manage Employee Data">
		</div>
		<?php
		if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='Clock-In') {
			attemptClockIn($_SESSION['currentEmployeePin'], $_SESSION['bid']);
		}
		elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='Clock-Out') {
			attemptClockOut($_SESSION['currentEmployeePin'], $_SESSION['bid']);
		}
		elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='Personal Employee Info') {
			personalInfo($_SESSION['currentEmployeePin'], $_SESSION['bid']);
		}
		elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='View Current Employees') {
			employeeInfo($_SESSION['bid']);
		}
		elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['action']=='View Former Employees') {
			formerEmpInfo($_SESSION['bid']);
		}
		?>
	</div>

</body>
</html>