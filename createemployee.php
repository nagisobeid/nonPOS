<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();

        #IF NOT LOGGED IN OR NO ACCOUNT CREATED
    if (!isset($_SESSION['auth']))# and ($_SESSION['firstLogin'] == false))
	{
		header("location: login.php");
        exit;
    } elseif (isset($_SESSION['auth']) and (!isset($_SESSION['currentEmployeePermissions']))
                and !isset($_SESSION['firstLogin']))
    {
        #IF LOGGID IN AND NO EMPLOYEE PIN DETECTED
        header("location: pin.php");
        exit;
    } elseif (isset($_SESSION['auth']) and isset($_SESSION['currentEmployeePermissions']))
	{
        #IF LOGGID IN AND EMPLOYEE IS DETECTED
        #IF NOT MANAGER
        if ($_SESSION['currentEmployeePermissions'] == 2) {
		    header("location: home.php");
            exit;
        }
    } 
    
    #ONLY REACH THIS POINT IF FIRST LOGIN OR IS MANAGER

    include_once 'db.php';
	$obj = new DBH;
	$con = $obj->connect();
    
    if (isset($_SESSION['firstLogin'])) {
        $desc = "Create Manager";
        #unset ($_SESSION["firstLogin"]);
    } else {
        $desc = "Create Employee";
    }
    
	$status = "";
    
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$fname = $_POST['nameEFname'];
		$lname = $_POST['nameELname'];
		$password = $_POST['nameEPass'];
        $address = $_POST['nameAddress'];
        $city = $_POST['nameCity'];
        $state = $_POST['nameState'];
        $zip = $_POST['nameZip'];
        $phone = $_POST['namePhone'];
        $permissions = $_POST['namePermissions'];
        $payrate = $_POST['namePayRate'];
        $dob = $_POST['nameDOB'];     

        $username = $_SESSION['username'];
        #$sql = "SELECT * FROM employees WHERE ePass='$password'";
        $sql_b = "SELECT * FROM owners WHERE username='$username'";
        #$res = $con->prepare($sql);
        $res_b = $con->prepare($sql_b);
        #$res->execute();
        $res_b->execute();
        #$employee = $res->fetch();
        $owner = $res_b->fetch();
        $bID = $owner['bID'];

        $sql_u = "SELECT * FROM employees WHERE ePass='$password' AND bID='$bID'";
        $res = $con->prepare($sql_u);
        $res->execute();
        $employee = $res->fetch();

		$stateAbbr = Array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA",
		                   "HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA",
						   "MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY",
						   "NC","ND","OH","OK","OR","PA","PR","RI","SC","SD","TN",
						   "TX","UT","VT","VA","WA","WV","WI","WY");
											   
        if(empty($fname) || empty($lname) ||empty($password) || 
            empty($address) || empty($city) || empty($state) || 
            empty($zip) || empty($phone) || empty($permissions) || empty($payrate)) {
			$status = "All fields are requried";
		}
		else if (strlen($fname) > 25) {
			$status = "First Name Must Be 25 Characters Or Shorter";
		} else if (strlen($lname) > 25) {
			$status = "Last Name Must Be 25 Characters Or Shorter";
		} else if ($res->rowCount() > 0) {
			$status = "Passcode Already Exists";
		} else if (strlen($password) > 25) {
			$status = "Passcode Must Be 25 Characters Or Shorter";
		} else if (strlen($address) > 50) {
			$status = "Address Must Be 50 Characters Or Shorter";
		} else if (strlen($city) > 25) {
			$status = "City Must Be 25 Characters Or Shorter";
		} else if (!in_array($state,$stateAbbr)) {
			$status = "State Must Be A Valid, Capitalized, State Abbreviation";
		} else if (strlen($zip) > 5) {
			$status = "Zip Must Be 5 Characters Or Shorter";
		} else if (!is_numeric($zip)) {
			$status = "Zip Must Be A Series of Numbers";
		} else if (strlen($phone) > 10) {
			$status = "Phone Number Must Be 10 Characters Or Shorter";
		} else if (!is_numeric($phone)) {
			$status = "Phone Number Can Only Consist Of Numbers";
		} else if ($permissions != '1' && $permissions != '2') {
			$status = "Employee Must Have Manger(1) Or Employee(2) Permissions";
		} else if($permissions != '1' and isset($_SESSION['firstLogin'])) {
            $status = "First User Must Have Manager Permissions(1)";
        } else if (substr_count($payrate,".") > 1) {
			$status = "PayRate Must Have 1 Or Fewer Decimal Points";
		} else if (!is_numeric(str_replace(".","",$payrate))) {
			$status = "PayRate Must Be A Valid Float";
		} else {  
                    $sql = "INSERT INTO employees (fName, lName, dob, ePass, address, city, state, zip, phone, permisions, payRate, bID)
                    VALUES (:fName, :lName, :dob, :ePass, :address, :city, :state, :zip, :phone, :permisions, :payRate, :bID)";
                    #VALUES (:fname,:lname,:dob,:password,:address, :city, :state,:zip,:phone,:permissions,:payrate,bID)";
				
					$stmt = $con->prepare($sql);
					$stmt->execute(['fName' => $fname, 'lName' => $lname, 'dob' => $dob, 
                                    'ePass' => $password, 'address' => $address, 'city'=> $city,
                                    'state' => $state, 'zip' => $zip, 'phone'=> $phone,
                                    'permisions' => $permissions, 'payRate' => $payrate, 'bID'=> $bID]);
                    
                    if(isset($_SESSION['firstLogin'])) {
                        unset ($_SESSION["firstLogin"]);
                    }
						
                    #$_SESSION['auth']=true;
                    
                    #header("location: home.php");
                    
                    if($permissions == '1') {
                        #header("location: home.php");
                        $status = "Manager Created";
                    } elseif($permissions == '2') {
                        $status = "Employee Created";
                    }
                }
        }	  	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="createemployee.css">
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
		<button onclick="document.location='home.php'" type="button" id="idBtnHome" class="btn btn-link">Home</button>
		<button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
	</div>
</header>
	
	<div id="divCreateAccount" class="container">
		<div class="formDescription">
			<h1 class="description"><?php echo $desc ?></h1>
		</div>
		<form action="" method="POST" id="formCreateAccount" class="form">
			<input class="inputForm" type="text" id="idFname" name="nameEFname" placeholder="First Name" required>    
	    	<input class="inputForm" type="text" id="idLname" name="nameELname" placeholder="Last Name" required>  
            <label> DOB <input type="date" id="idDOB" name="nameDOB"></label> 
	    	<input class="inputForm" type="text" id="idEpass" name="nameEPass" placeholder="Password" required>
            <input class="inputForm" type="text" id="idAddress" name="nameAddress" placeholder="Address" required>
            <input class="inputForm" type="text" id="idCity" name="nameCity" placeholder="City" required>
            <input class="inputForm" type="text" id="idState" name="nameState" placeholder="State" required>
            <input class="inputForm" type="text" id="idZip" name="nameZip" placeholder="Zip" required>
            <input class="inputForm" type="text" id="idPhone" name="namePhone" placeholder="Phone" required>
            <input class="inputForm" type="text" id="idPermissions" name="namePermissions" placeholder="Permissions (1 or 2)" required>
            <input class="inputForm" type="text" id="idPayRate" name="namePayRate" placeholder="PayRate" required>
			<input name="btnCreateAccount" id="btnCreateAccount" class="btnForm" type="submit" value="Submit">  
		</form>

		<div id="divPhpMessage">
			<h4 name="h1PHPMessage" id="h1PHPMessage" class="phpDescription"><?php echo $status ?></h4>
		</div>
	</div>
	
</body>
</html>