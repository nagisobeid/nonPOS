<?php
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/JavaScript">

        var currCountOfModifierFields = 3;
    	$(document).ready(function() {
			$(document).on('click', '.nav-link', function() {
                $(".nav-link").removeClass("selected");
                $(".tab-pane").removeClass("show");
                $(this).addClass("selected");
            });
            $(document).on('click', '#btnAddModifier', function() {
                var mID = '#divModifier-3';
                var x = $(mID).clone();
                currCountOfModifierFields+=1;
                x.removeAttr('id'); 
                var newID = '#divModifier-'+currCountOfModifierFields.toString();
                x.attr( "id", newID);
                x.insertBefore("#divAddModifier");
            });
    	}); //end
	</script>
</head>
<body>
    <header>
        <div id="main-bar">
            <img id="logo" src="./images/logo2.png"></img>
            <button onclick="document.location='home.php'" type="button" id="idBtnHome"
                class="btn btn-link">Home</button>
            <button type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
    </header>
    <!-- ROW -->
    <div class="row h-100 w-100" style="margin-right: 0px !important; margin-left: 0px !important;">
        <div class="col-lg-2 col-md-3 col-sm-3 bg-dark"></div>
        <div class="col-lg-10 col-md-9 col-sm-9 bg-secondary mine" style="height: 35px;">
        <button type="submit" class=" btn btn-light t">1</button>
        <button type="submit" class=" btn btn-light t">2</button>
        <button type="submit" class=" btn btn-light t">3</button>
        <button type="submit" class=" btn btn-light t">4</button>
        <button type="submit" class=" btn btn-light t">5</button>
        <button type="submit" class=" btn btn-light t">6</button>
        <button type="submit" class=" btn btn-light t">7</button>
        <button type="submit" class=" btn btn-light t">8</button>
        <button type="submit" class=" btn btn-light t">9</button>
        <button type="submit" class=" btn btn-light t">10</button>
    
        </div>
       
        


    </div

    </body>
</html>