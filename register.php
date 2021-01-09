<?php
     session_start();
     if (!isset($_SESSION['auth']))
     {
         header("location: login.php");
         exit;
     }
     include_once 'db.php';
     $obj = new DBH;
     $con = $obj->connect();
     $bID = $_SESSION['bid'];

     $sql = "SELECT * FROM menus WHERE bID = $bID";
     $res = $con->prepare($sql);
     $res->execute();
     $categories = $res->fetchAll();
     //print_r($categories[0]["mName"]);
     $sql = "SELECT * FROM items INNER JOIN menus ON items.menuID = menus.menuID WHERE menus.bID = $bID";
     $res = $con->prepare($sql);
     $res->execute();
     $menuItems = $res->fetchAll();
     //print_r($menuItems);
     //echo count($menuItems);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <script type="text/JavaScript">

      var categories = <?php echo json_encode($categories); ?>;
      var menuItems = <?php echo json_encode($menuItems); ?>;

      function modsIntoModals(mods, itemID){
        if($('.modal-body-'+itemID+'').val() != "true") {
            for (i = 0; i < mods.length; i++) {
                var modItem = '<div class="quantity col-xs-1 col-sm-1 col-md-1 col-lg-1">\
                                <input class="quantityMod text-primary" type="number" min="1" max="9" step="1" value="1">\
                                </div>  <div class="form-group col-xs-5 col-sm-5 col-md-5 col-lg-5">\
                                <button style="width:100%;" id="modGrid" type="button" class=" btn btn-light t">'+mods[i]+'</button>\
                                </div>';
                $('.modal-body-'+itemID+'').append(modItem);
                $('.modal-body-'+itemID+'').val("true");
            }
        }
    }
      
      function getModifiers(itemID) {
        $.ajax({
            url: 'getModifiers.php?q=' + itemID,
            data: itemID,
            type: 'get',
            dataType: 'json',
            success: function(response)
            {
                modsIntoModals(response, itemID);
            }
        });
        }

    	$(document).ready(function() {

			$(document).on('click', '.nav-link', function() {
                $("#divMultiplier").css("display","flex");
                $(".nav-link").removeClass("selected");
                $(".tab-pane").removeClass("show");
                $(this).addClass("selected");
            });

            $(document).on('click', '#v-pills-Order-tab', function() {
                $("#divMultiplier").css("display","none");
            });

            $('.mainMultiplierBtn').on('click', function(){
                if($(this).hasClass('selectedBtn')) {
                    $(this).removeClass('selectedBtn');
                    exit;
                }
                $('.mainMultiplierBtn').removeClass('selectedBtn');
                $(this).addClass('selectedBtn');
            });

            $('.orderType').on('click', function(){
                if($(this).hasClass('selectedBtn')) {
                    $(this).removeClass('selectedBtn');
                    exit;
                }
                $('.orderType').removeClass('selectedBtn');
                $(this).addClass('selectedBtn');
            });

            for (i = 0; i < categories.length; i++) {
                
                //console.log(categories[i]['mName']);
                if(i == 0) {                        //VERY FIRST MENU CATEGORY
                    var sideNavBtn = '<a class="nav-link selected" id="v-pills-'+categories[i]['mName']+'-tab" data-toggle="pill" href="#v-pills-'+categories[i]['mName']+'"\
                    role="tab" aria-controls="v-pills-'+categories[i]['mName']+'" aria-selected="false">'+categories[i]['mName']+'</a>'
                    var pillToggles = '<div class="tab-pane fade show" id="v-pills-'+categories[i]['mName']+'" role="tabpanel" aria-labelledby="v-pills-'+categories[i]['mName']+'-tab">\
                        <form id="form">\
                            <div id="id'+categories[i]['mName']+'Grid" class="form-row">\
                            </div>\
                        </form>\
                    </div>';

                } else if( i == categories.length-1) { // LAST MENU CATEGORY -> APPLY "last" ID
                    var sideNavBtn = '<a id="last" class="nav-link" data-toggle="pill" href="#v-pills-'+categories[i]['mName']+'"\
                    role="tab" aria-controls="v-pills-'+categories[i]['mName']+'" aria-selected="false">'+categories[i]['mName']+'</a>'

                    var pillToggles = '<div class="tab-pane fade" id="v-pills-'+categories[i]['mName']+'" role="tabpanel" aria-labelledby="v-pills-'+categories[i]['mName']+'-tab">\
                        <form id="form">\
                            <div id="id'+categories[i]['mName']+'Grid" class="form-row">\
                            </div>\
                        </form>\
                    </div>';
                } else {                                // EVERY OTHER CATEGORY
                    var sideNavBtn = '<a class="nav-link" id="v-pills-'+categories[i]['mName']+'-tab" data-toggle="pill" href="#v-pills-'+categories[i]['mName']+'"\
                    role="tab" aria-controls="v-pills-'+categories[i]['mName']+'" aria-selected="false">'+categories[i]['mName']+'</a>'

                    var pillToggles = '<div class="tab-pane fade" id="v-pills-'+categories[i]['mName']+'" role="tabpanel" aria-labelledby="v-pills-'+categories[i]['mName']+'-tab">\
                        <form id="form">\
                            <div id="id'+categories[i]['mName']+'Grid" class="form-row">\
                            </div>\
                        </form>\
                    </div>';
                }
               
                $("#v-pills-tab").append(sideNavBtn);
                $("#v-pills-tabContent").append(pillToggles);

            }
            ////////////// INSERTING ITEMS INTO GRIDS AND ADDING MODALS
            for(j = 0; j < menuItems.length; j++) {
                    console.log(menuItems[j]['name']);
                    var gridItem = '<div class="form-group col-xs-6 col-sm-3 col-md-3 col-lg-2">\
                            <button id="itemsGrid" type="button" onclick="getModifiers('+menuItems[j]['itemID']+')" class=" btn btn-light t" data-toggle="modal" data-target="#modal-'+menuItems[j]['itemID']+'">'+menuItems[j]['name']+'</button>\
                        </div>';
                    $("#id"+menuItems[j]["mName"]+"Grid").append(gridItem);
                    var modal = '<div class="modal fade" id="modal-'+menuItems[j]['itemID']+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">\
                                    <div class="modal-dialog modal-dialog-centered" role="document">\
                                        <div class="modal-content">\
                                        <div style="margin-bottom: 10px;" class="modal-header">\
                                            <h5 style = "width: 100% !important;text-align: center; width: 50%; font-weight:800;" class="modal-title text-primary" id="exampleModalLongTitle">'+menuItems[j]['name']+'</h5>\
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                            <span aria-hidden="true">&times;</span>\
                                            </button>\
                                        </div>\
                                        <div class="modal-body-'+menuItems[j]['itemID']+'">\
                                        </div>\
                                        <div class="modal-footer">\
                                            <button type="button" class="btn btn-secondary col-xs-6" data-dismiss="modal">Close</button>\
                                            <button type="button" class="btn btn-primary col-xs-6">Done</button>\
                                        </div>\
                                        </div>\
                                    </div></div>';
                    $("#itemModals").append(modal);
                }
            
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
    <div class="row flex-nowrap" style="width: 100% !important;">
         <!-- LEFT PANE <div class="row" style="width: 100% !important; height: 100% !important; overflow:auto !important;"> -->
        <div class="col-lg-3 col-md-3 col-xs-4 bg-light" id="sideCol">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    
            </div>
            <a class="nav-link bg-success" id="v-pills-Order-tab" data-toggle="pill" href="#v-pills-Order"
                    role="tab" aria-controls="v-pills-Order" aria-selected="false">Order Details</a>
        </div>
        <!-- MIDDLE PANE -->
        <div class="col-lg-6 col-md-9 col-xs-8" id="sideCol2">
            <div id="divMultiplier">
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">1</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">2</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">3</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">4</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">5</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">6</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">7</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">8</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">9</button>
                <button type="submit" class=" btn btn-primary t posBtns mainMultiplierBtn">10</button>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                <!-- PILL PANELS -->
                <div class="tab-pane fade" id="v-pills-Order" role="tabpanel" aria-labelledby="v-pills-Order-tab">
                        <form id="form">
                            <div id="idOrderGrid" class="form-row">
                            <!-- DETAILS GOES HERE-->
                            <div class="col-lg-12 bg-light" style="height: 100% !important;" id="orderSection2">
                                <div id="divOrderType">
                                    <button type="submit" class=" btn btn-primary t posBtns orderType">Dine-In</button>
                                    <button type="submit" class=" btn btn-primary t posBtns orderType">Take-Out</button>
                                    <button type="submit" class=" btn btn-primary t posBtns orderType">Call-In</button>
                                    <button type="submit" class=" btn btn-primary t posBtns orderType">Drive-Thru</button>
                                </div>
                                <div class="bg-white" id="orderDetails">
                                <!-- ORDER DETAILS HERE -->   
                                </div>
                                <div id="" class="checkoutOptions">
                                    <span style="text-align: left; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-primary">TOTAL</span>
                                    <span style="text-align: right; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-danger">$65.67</span>
                                </div>
                                <div id="" class="checkoutOptions">
                                    <button style="border-radius: 0px; padding-bottom: 10px;" type="submit" class=" btn btn-primary t posBtns">SAVE</button>
                                    <button style="border-radius: 0px; padding-bottom: 10px;" type="submit" class=" btn btn-success t posBtns">CHARGE</button>
                                </div>
                            </div>
                            <!-- DETAILS ENDS HERE-->
                            </div>
                        </form>
                </div>
            </div>
        </div>
        <!-- END MIDDLE PANE-->
        <div id="itemModals">

        </div>
        <!-- RIGHT PANE-->
        <div class="col-lg-3  bg-light" style="height: 100% !important;" id="orderSection">
            <div id="divOrderType">
                <button type="submit" class=" btn btn-primary t posBtns orderType">Dine-In</button>
                <button type="submit" class=" btn btn-primary t posBtns orderType">Take-Out</button>
                <button type="submit" class=" btn btn-primary t posBtns orderType">Call-In</button>
                <button type="submit" class=" btn btn-primary t posBtns orderType">Drive-Thru</button>
            </div>
            <div class="bg-white" id="orderDetails">
             <!-- ORDER DETAILS HERE -->   
            </div>
            <div id="" class="checkoutOptions">
                <span style="text-align: left; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-primary">TOTAL</span>
                <span style="text-align: right; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-danger">$65.67</span>
            </div>
            <div id="" class="checkoutOptions">
                <button style="border-radius: 0px;" type="submit" class=" btn btn-primary t posBtns">SAVE</button>
                <button style="border-radius: 0px;" type="submit" class=" btn btn-success t posBtns">CHARGE</button>
            </div>
        </div>
    </div>
    </div>

</body>

</html>
