
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="./css/settings.css">
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
            <button onclick="document.location='index.php'" type="button" id="idBtnHome"
                class="btn btn-link">Home</button>
            <button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
</header>
	
	<div class="container">
		<div class="formDescription">
			<h1 class="description">Settings</h1>
		</div>
		<div class="form">
	    	<input type="submit" id="idEmail" name="currentemail" placeholder="current email" value="Email" onclick="window.location.href='editEmail.php';">
	    	<input type="submit" id="idUsername" name="Username" placeholder="Username" value="User Name" onclick="window.location.href='editUsername.php';">
            <input type="submit" id="idPassword" name="Password" placeholder="Username" value="Password" onclick="window.location.href='editPassword.php';">
            <input type="submit" id="idSalesTax" name="salestax" placeholder="Username" value="Sales Tax" onclick="window.location.href='editSalesTax.php';">
	  	
		</div>

	</div>
		
</body>
</html>
