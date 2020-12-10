<?php
	include_once 'db.php';

	$obj = new DBH;
	$con = $obj->connect();

	$status = "";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewpoet" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="employees.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<!-- jQuery Library -->
	<script src="https://ajax.goofleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

</body>
</html>
