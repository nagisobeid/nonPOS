<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="index.css">
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
		<button type="button" id="btn-about-us" class="btn btn-link">About Us</button>
	</div>
</header>
<div class="mainContainer">
	
	<div id="mid-section" style="padding-bottom: 0px;">
		<!-- jumbotron -->

		<div class="jumbotron" style="box-shadow: 5px 10px; height: 80%; overflow:auto;">
		  <h1 class="display-4">Hello.</h1>
		  <p class="lead">Welcome to nonPOS</p>
		  <hr class="my-4">
		  <p id="message">The best Point of Sale system for small businesses in the quick-service industry
		  	<div>
				<button onclick="document.location='login.html'" id="btnLogin" type="button" class="btn btn-primary btnPos">Login</button>
		    	<button onclick="document.location='createaccount.php'" id="btnCreate_Account" type="button" class="btn btn-primary btnPos">Create Account</button>
		  	</div>
		  </p>
		</div>

		<!-- jumbotron -->
	</div>
	
</body>
	<div id="bottom-section">
		<img class="main-page-img" src="./images/graph.svg"></img>
		<img class="main-page-img" src="./images/log.svg"></img>
		<img class="main-page-img" src="./images/cash.svg"></img>
		<img class="main-page-img" src="./images/card.svg"></img>
	</div>
</div>
</html>
