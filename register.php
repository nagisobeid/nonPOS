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
    <!-- Press and Hold -->
    <script src="./js/pressAndHold.js"></script>
    <!-- CHARGING FILES -->
    <script src="./charge/charge.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <!-- Jquery and Javascript starts here -->
    <!-- POS MENU LOGIC -->
    <!-- Jquery and Javascript starts here -->
    <script type="text/JavaScript">

    var categories = <?php echo json_encode($categories); ?>;
    var menuItems = <?php echo json_encode($menuItems); ?>;

    //GLOBAL
    var orderID = 0;
    var orderType = "";
    var toGoOrder = 1;
    var dineInOrder = 1;
    var driveThruOrder = 1;
    var callInOrder = "";

    var itemQuantity = 1;
    var itemModQuantity = 1;
    //var totalPrice = 0;
    var totalPrice = 0;
    var action = ""; 
    
    var itemsInOrder = [];
    var itemMods = {};

    //CURRENT ITEMS MODIFIERS TO BE USED IN itemMods
    var mods = [];
    itemMods.mods = mods;

    /////////////////// LOGIC FOR PROCESSING AND STORING ORDERS//////////////////////
    /////////////////////////////////////////////////////////////////////////////////
    /*
     Item will be added to the order. store in itemInOrder() Object
        *Store the new ItemInOrder Object in the itemsInOrder[] array
     Once order is complete
        *If SAVED,
            *Create an Order() object and store in DB
            *DELETE ALL ItemInOrder and Order Objects, and Clear the itemsInOrder Array
        *If CHARGE
            *Process payment then create Order() object and store in DB
            *DELETE ALL ItemInOrder and Order Objects, and Clear the itemsInOrder Array
     */

    //OBJECT to hold each item in order
    var ItemInOrder = function(itemID, itemQuantity, itemPrice, itemMods) {
        this.itemID = itemID;
        this.itemQuantity = itemQuantity;
        this.itemPrice = itemPrice;
        this.itemMods = itemMods;
    }

    //OBJECT to hold order data
    var Order = function(username, placed, orderType) {
        this.username = username;
        this.placed = placed;
        this.orderType = orderType;
    }

    //FUNCTION opens one of the open orders and displays the contents to the POS view
    function openOrder(orderID) {
        $.ajax({
            //url: 'loadOpenOrders.php?ordertype=',
            url: 'openOrder.php',
            //data: orderType,
            data:{orderID:orderID},
            type: 'get',
            dataType: 'json',
            success: function(response)
            {
                //INSERT 
            }
        });
    }

    //FUNCTION loads open orders depending on orderType into a modal
    function loadOpenOrders(orderType) {
        $.ajax({
            //url: 'loadOpenOrders.php?ordertype=',
            url: 'loadOpenOrders.php',
            //data: orderType,
            data:{orderType:orderType},
            type: 'get',
            dataType: 'json',
            success: function(response)
            {
                var count = Object.keys(response).length;
                $("#divOpenOrders").empty()
                for (i=0; i<count; i++) {
                    $( "#divOpenOrders" ).append('<button type="button" value="'+response[i]['oID']+'" class="btn btn-secondary openOrderName" data-dismiss="modal">'+response[i]['username']+'</button>');
                }
            }
        });
    }

    //FUNCTION clear all data on page
    function clearAll() {
        orderType = "";
        itemQuantity = 1;
        itemModQuantity = 1;
        totalPrice = 0;
        itemsInOrder = [];
        itemMods = {};
        mods = [];
        itemMods.mods = mods;
        action = "";

        $(".orderType").removeClass('selectedBtn');
        $("#orderDetails").empty();
        $("#orderDetailsSmall").empty();
        $(".text-totalPrice").text("0.00");
        $(".save").removeAttr("data-toggle");
        $(".save").removeAttr("data-target");
        $(".charge").removeAttr("data-toggle");
        $(".charge").removeAttr("data-target");
    }

    //FUNCTION adds and item to the order
    function addItemToOrder(itemName, itemQuantity, itemPrice, itemID, itemMods) {
        var random = Math.floor(Math.random() * Math.floor(500));
        //let id = "#"+random.toString()+itemName;
        var price = (parseFloat(itemQuantity) * parseFloat(itemPrice)).toFixed(2);
        var itemHtml = '<div class="divListItem"><p class="sectionQuantity">'+itemQuantity+' x</p><p class="sectionItemName '+random+'">'+itemName+'</p><p class="sectionPriceTimesQuantity">'+price+'</p></div>';
        //totalPrice = (totalPrice + price).toFixed(2);
        totalPrice = (parseFloat(price) + parseFloat(totalPrice)).toFixed(2);
        $(".text-totalPrice").empty();
        $(".text-totalPrice").text(totalPrice);
        $("#orderDetails").append(itemHtml);
        $("#orderDetailsSmall").append(itemHtml);
        $.each(itemMods.mods, function(index, element) {
            //alert(element.timeStamp); 
            var modHTML = '<p class="listMod">'+element.Quantity + " x " + element.Name+'</p>';
            $('p.'+random).append(modHTML);
        });

        var item = new ItemInOrder(parseInt(itemID), parseInt(itemQuantity), parseFloat(itemPrice), itemMods);
        itemsInOrder.push(item);
        console.log(itemsInOrder);
    }

    //FUNCTION inserts the modifiers into the modals
    function modsIntoModals(mods, itemID){
        if($('.modal-body-'+itemID+'').val() != "true") {
            for (i = 0; i < mods.length; i++) {
                var modItem = '<div class="form-group col-xs-6 col-sm-6 col-md-6 col-lg-6">\
                                <div class="divModQuantity"></div>\
                                <button style="width:100%;" id="modGrid" type="button" class=" btn btn-light t text-info modBtn"><div class="divModName">'+mods[i]+'</div></button>\
                                </div>';
                $('.modal-body-'+itemID+'').append(modItem);
                $('.modal-body-'+itemID+'').val("true");
            }
        }
    }
    
    //FUNCTION retrieves the modifiers of an item from php AJAX call
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

    //FUNCTION saves order
    function save(username, placed, ordertype, action) {
        username = username + "-" + String(Math.random().toString(36).substring(7));
        var order = new Order(username, placed, ordertype);
        //SEND TO PHP
        var orderStr = JSON.stringify(order);
        $.ajax({
            url: 'saveOrder.php',
            //dataType:'json',
            type: 'POST',
            data: {order: orderStr},
            success: function(currOrderID){
                orderID = currOrderID;
                console.log("returned from 1st ajax");
                saveItemsToOrder();
            }
        }); 
        //END SEND ORDER TO PHP
    }

    //FUNCTION 
    function checkSave() {
        if (orderType == "Drive-Thru") {
            console.log("drive-thru called");
            //data-toggle="modal" data-target="#saveModal"
            console.log("Drive Order");
            var placed = getDate();
            save("Drive-Thru-"+driveThruOrder, placed, orderType, action);
            if (driveThruOrder < 50) {
                driveThruOrder++;
            } else if (driveThruOrder >= 50) {
                driveThruOrder = 1;
            }
            //clearAll();
            return;
        } else {
            $(".save").attr("data-toggle","modal");
            $(".save").attr("data-target","#saveModal");
        }
    }

    //FUNCTION 
    function checkCharge() {
        if (orderType == "Drive-Thru") {
            console.log("drive-thru called");
            //data-toggle="modal" data-target="#saveModal"
            console.log("Drive Order");
            var placed = getDate();
            save("Drive-Thru-"+driveThruOrder, placed, orderType, action);
            if (driveThruOrder < 50) {
                driveThruOrder++;
            } else if (driveThruOrder >= 50) {
                driveThruOrder = 1;
            }
            //clearAll();
            return;
        } else {
            $('.charge').attr("data-toggle","modal");
            $(".charge").attr("data-target","#chargeModal");
        }
    }

    //function Save items to order
    function saveItemsToOrder() {
        var itemsStr = JSON.stringify(itemsInOrder);
        $.ajax({
            url: 'saveOrder.php',
            //dataType:'json',
            type: 'POST',
            data: {items: itemsStr, currOID: orderID },
            success: function(response){
                //do whatever.
                console.log("returned from 2nd ajax");
                if (action == "charge") {
                    let price = totalPrice;
                    clearAll();
                    processPayment(price);
                } 
                clearAll();
            }
        });
    }

    function getDate() {
        var dateObj = new Date();
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        var day = dateObj.getUTCDate() -1 ;
        var year = dateObj.getUTCFullYear();
        var date = year + "-" + month + "-" + day;
        return String(date);
    }
    
    //JQUERY document button calls
    $(document).ready(function() {
        //BUTTON CLICK - MODAL - DONE - calls the addItemToOrder function
        $(document).on('click', '.addItemToOrderBtn', function() {
            var btnID = $(this).attr('id');
            //itemName, itemQuantity, itemPrice, itemMods, itemID
            var itemName = $(this).parents(".modal-content").find(".modal-title").html();
            var itemID = $(this).parents(".modal-content").find(".modal-title").attr("value");
            var itemPrice =  $(this).parents(".modal-content").find("#modal-price").html();
            // call addItemToOrder() function
            /////////////////////////////////
            //$("#"+btnID).attr("data-dismiss","modal");
            //var ItemInOrder = function(orderID, itemID, itemQuantity, itemPrice) {
            addItemToOrder(itemName, itemQuantity, itemPrice, itemID, itemMods);
            //$("#"+btnID).attr("data-dismiss","modal");
            //close modal and clear btns selected and clear mods and ItemMods
            $(".addItemToOrderBtn").attr("data-dismiss","modal");
            $('.mainMultiplierBtn').removeClass('selectedBtn');
            $('.btnModQuantity').remove();
            itemQuantity = 1;
            itemModQuantity = 1;
            //mods.splice(0, mods.length);
            mods = [];
            itemMods = {};
            itemMods.mods = mods;
        });

        //BUTTON CLICK - MODAL - CLOSE - clears the modal and resets data
        $(document).on('click', '.closeItemBtn', function() {
            $('.mainMultiplierBtn').removeClass('selectedBtn');
            $('.btnModQuantity').remove();
            itemQuantity = 1;
            itemModQuantity = 1;
            mods = [];
            itemMods = {};
            itemMods.mods = mods;
        });

        //BUTTON CLICK - CALLS FUNCTION openOrder
        $(document).on('click', '.openOrderName', function() {
            openOrder($(this).attr('value'));
        });
        
        //Toggling the selected and deselected category
        $(document).on('click', '.nav-link', function() {
            $("#divMultiplier").css("display","flex");
            $(".nav-link").removeClass("selected");
            $(".tab-pane").removeClass("show");
            $(this).addClass("selected");
        });
        
        //Toggling display of the Multiplier field
        $(document).on('click', '#v-pills-Order-tab', function() {
            $("#divMultiplier").css("display","none");
        });

        //FOR modals because modal will not be ready when document loads
        $(document).on('click', '.subMultiplierBtn', function() {
            console.log("clicked");
            if($(this).hasClass('selectedBtn')) {
                $(this).removeClass('selectedBtn');
                itemModQuantity = 1;
                exit;
            }
            $('.subMultiplierBtn').removeClass('selectedBtn');
            $(this).addClass('selectedBtn');
            itemModQuantity = $(this).html();
        });
        
        //When a modifier is selected, and adding mod to mods.
        $(document).on('click', '.modBtn', function() {
            //var itemMods = {};
            //modMulBtn = '<div></div>';
            $(this).parent().find(".divModQuantity").append('<button class="btnModQuantity bg-success">'+itemModQuantity+'</button>');
            $(".subMultiplierBtn").removeClass('selectedBtn');
            var modName = $(this).children( ".divModName" ).html();
            var mod = {
                "Name": modName,
                "Quantity": parseInt(itemModQuantity)
            }
            itemMods.mods.push(mod);
            console.log(itemMods);
            itemModQuantity = 1;
        });

        // SELECT AND DE-SELECT A mainMultiplier BTN and RESETS itemQuantity
        $('.mainMultiplierBtn').on('click', function(){
            if($(this).hasClass('selectedBtn')) {
                $(this).removeClass('selectedBtn');
                itemQuantity = 1;
                exit;
            }
            $('.mainMultiplierBtn').removeClass('selectedBtn');
            $(this).addClass('selectedBtn');
            itemQuantity = $(this).html();
        });

        // SELECT AND DE-SELECT THE orderType
        $('.orderType').on('click', function(){
            if($(this).hasClass('selectedBtn')) {
                $(this).removeClass('selectedBtn');
                orderType = "";
                exit;
            }
            $('.orderType').removeClass('selectedBtn');
            $(this).addClass('selectedBtn');
            orderType = $(this).html();
            //console.log(orderType);
        });

        // DISMESS SAVE MODAL
        $("#saveOrderDismess").on("click", function(){
            $(".save").removeAttr("data-toggle");
            $(".save").removeAttr("data-target");
        });

        //DISMESS CHARGE MODAL
        $("#chargeOrderDismess").on("click", function(){
            $('.charge').removeAttr("data-toggle","modal");
            $(".charge").removeAttr("data-target","#chargeModal");
        });

        // SAVE ACTION
        $(".save").on("click", function(){
            action = "save";
            console.log(action);
        });

        // CHARGE ACTION
        $(".charge").on("click", function(){
            action = "charge";
            console.log(action);
        });

        // SAVING THE ORDER
        $('#saveOrder').on('click', function(){
            username = $("#customerName").val();
            console.log(username);
            if (orderType =="") {
                alert("PLEASE SELECT ORDER TYPE BEFORE SAVING");
                return;
            } 
            if (username == "") {
                $('#customerName').css("border-color","red");
            } else {
                if (username != "") {
                    $('#customerName').css("border-color","black");
                    $('#customerName').val("");
                    var placed = getDate();
                    save(username, placed, orderType, action);           
                    $('#saveOrderDismess').click();
                }
            }
            //console.log("Enter name");
        });

        // CHARGING THE ORDER
        $('#chargeOrder').on('click', function(){
            username = $("#customerNameCharge").val();
            console.log(username);
            if (orderType =="") {
                alert("PLEASE SELECT ORDER TYPE BEFORE SAVING");
                return;
            } 
            if (username == "") {
                $('#customerNameCharge').css("border-color","red");
            } else {
                if (username != "") {
                    $('#customerNameCharge').css("border-color","black");
                    $('#customerNameCharge').val("");
                    var placed = getDate();
                    save(username, placed, orderType, action);           
                    $('#chargeOrderDismess').click();
                }
            }
            //console.log("Enter name");
        });

        // CALL loadOpenOrders function on click ordertyp within modal
        $('.openOrdersType').on('click', function(){
            orderType = $(this).html();
            loadOpenOrders(orderType);
            //console.log(orderType);
        });

        //PREVENTING SUBMIT ON ENTER FOR SAVE ORDER
        document.getElementById('customerName').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            }
        });
        document.getElementById('customerNameCharge').addEventListener('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            }
        });

        // ADDING MENU CATEGORIES
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
        ////////////// ADDING MODALS, ITEMS, AND MODIFIERS TO THE GRID
        for(j = 0; j < menuItems.length; j++) {
                console.log(menuItems[j]['name']);
                var gridItem = '<div class="form-group col-xs-6 col-sm-3 col-md-3 col-lg-3">\
                        <button id="'+menuItems[j]['itemID']+'" type="button" onclick="getModifiers('+menuItems[j]['itemID']+')" class=" btn btn-light itemsGrid" data-toggle="modal" data-target="#modal-'+menuItems[j]['itemID']+'">'+menuItems[j]['name']+'</button>\
                    </div>';
                $("#id"+menuItems[j]["mName"]+"Grid").append(gridItem);
                var modal = '<div class="modal fade" id="modal-'+menuItems[j]['itemID']+'" tabindex="-1" role="dialog" aria-labelledby="saveModalCenterTitle" aria-hidden="true">\
                                <div class="modal-dialog modal-dialog-centered" role="document">\
                                    <div class="modal-content">\
                                    <div style="margin-bottom: 10px;" class="modal-header">\
                                        <div class="titleprice">\
                                        <h4 style = "width: 50% !important;text-align: center; width: 25%; font-weight:800;" value="'+menuItems[j]['itemID']+'" class="modal-title text-primary" id="saveModalLongTitle">'+menuItems[j]['name']+'</h4>\
                                        <h4 style = "width: 50% !important;text-align: center; width: 25%; font-weight:800;" class="modal-title text-primary" id="modal-price">'+menuItems[j]['price']+'</h4>\
                                        </div>\
                                        <div id="divMultiplier">\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">1</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">2</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">3</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">4</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">5</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">6</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">7</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">8</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">9</button>\
                                            <button type="submit" class=" btn btn-primary t posBtns subMultiplierBtn">10</button>\
                                        </div>\
                                    </div>\
                                    <div class="modal-body-'+menuItems[j]['itemID']+'">\
                                    </div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-secondary col-xs-6 closeItemBtn" data-dismiss="modal">Close</button>\
                                        <button id="addItemToOrderBtn-'+menuItems[j]['itemID']+'" type="button" class="btn btn-primary col-xs-6 addItemToOrderBtn">Done</button>\
                                    </div>\
                                    </div>\
                                </div></div>';
                $("#itemModals").append(modal);
            } ///////////// end inserting modifiers and setting up modals  
    }); 
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
            <!-- SAVE ORDER HIDDEN -->
            <!-- Modal -->
            <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="form">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveModalLabel">Save Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input class="form-control" id="customerName" placeholder="Customer Name" required>
                    </div>
                
                    <div class="modal-footer">
                    <button id="saveOrderDismess" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="saveOrder" type="button" class="btn btn-primary">Save Order</button>
                    </div>
                </form>
                    </div>
                
            </div>
            </div>

            <!-- END SAVE ORDER HIDDEN -->
             <!-- CHARGE ORDER HIDDEN -->
            <!-- Modal -->
            <div class="modal fade" id="chargeModal" tabindex="-1" role="dialog" aria-labelledby="chargeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="form">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveModalLabel">Charge Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <input class="form-control" id="customerNameCharge" placeholder="Customer Name" required>
                    </div>
                
                    <div class="modal-footer">
                    <button id="chargeOrderDismess" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="chargeOrder" type="button" class="btn btn-primary">Charge</button>
                    </div>
                </form>
                    </div>
                
            </div>
            </div>

            <!-- END CHARGE ORDER HIDDEN -->
            <!-- VIEW OPEN ORDERS HIDDEN -->
            <!-- Modal -->
            <div class="modal fade" id="openOrdersModal" tabindex="-1" role="dialog" aria-labelledby="openOrdersLabel" aria-hidden="true">
            <div class="modal-dialog" role="form">
                <div class="modal-content">
                <div class="modal-header">
                    <div id="divOrderType">
                        <button type="button" class=" btn btn-primary t posBtns orderType squared openOrdersType">Dine-In</button>
                        <button type="button" class=" btn btn-primary t posBtns orderType squared openOrdersType">Take-Out</button>
                        <button type="button" class=" btn btn-primary t posBtns orderType squared openOrdersType">Call-In</button>
                        <button type="button" class=" btn btn-primary t posBtns orderType squared openOrdersType">Drive-Thru</button>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body" id="divOpenOrders">
                        
                    </div>
                
                    <div class="modal-footer">
                    <button id="openOrdersDismess" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
                    </div>
                
            </div>
            </div>

            <!-- END VIEW ORDERS HIDDEN -->
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
                        <form id="orderInfoForm">
                            <div id="idOrderGrid" class="form-row">
                            <!-- DETAILS GOES HERE-->
                            <div class="col-lg-12 bg-light" id="orderSection2">
                                <div id="divOrderType">
                                    <button type="button" class=" btn btn-primary t posBtns orderType squared">Dine-In</button>
                                    <button type="button" class=" btn btn-primary t posBtns orderType squared">Take-Out</button>
                                    <button type="button" class=" btn btn-primary t posBtns orderType squared">Call-In</button>
                                    <button type="button" class=" btn btn-primary t posBtns orderType squared">Drive-Thru</button>
                                </div>
                                <div class="bg-white scroll" id="orderDetailsSmall">
                                <!-- ORDER DETAILS HERE -->   
                                </div>
                                <div id="" class="checkoutOptions">
                                    <span style="text-align: left; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-primary">TOTAL</span>
                                    <span style="text-align: right; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-danger text-totalPrice">0.00</span>
                                </div>
                                <div id="" class="checkoutOptions">
                                    <button style="border-radius: 0px; padding-bottom: 10px;" type="button" onclick="checkSave()" class=" btn btn-primary t posBtns save">SAVE</button>
                                    <button style="border-radius: 0px; padding-bottom: 10px;" type="button" onclick="checkCharge()" class=" btn btn-success t posBtns charge">CHARGE</button>
                                    <button style="border-radius: 0px;" type="button" data-toggle="modal" data-target="#openOrdersModal" class=" btn btn-success t posBtns">ORDERS</button>
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
                <button type="button" class=" btn btn-primary t posBtns orderType squared">Dine-In</button>
                <button type="button" class=" btn btn-primary t posBtns orderType squared">Take-Out</button>
                <button type="button" class=" btn btn-primary t posBtns orderType squared">Call-In</button>
                <button type="button" class=" btn btn-primary t posBtns orderType squared">Drive-Thru</button>
            </div>
            <div class="bg-white scroll" id="orderDetails">
             <!-- ORDER DETAILS HERE -->   
            </div>
            <div class="checkoutOptions">
                <span style="text-align: left; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-primary">TOTAL</span>
                <span style="text-align: right; width: 50%; font-weight:800;" type="text" class=" btn btn-white t posBtns text-danger text-totalPrice">0.00</span>
            </div>
            <div class="checkoutOptions">
                <button onclick="checkSave()" style="border-radius: 0px;" type="button" class=" btn btn-primary t posBtns save">SAVE</button>
                <button onclick="checkCharge()" style="border-radius: 0px;" type="button" class=" btn btn-success t posBtns charge">CHARGE</button>
                <button style="border-radius: 0px;" type="button" data-toggle="modal" data-target="#openOrdersModal" class=" btn btn-success t posBtns">ORDERS</button>
            </div>
        </div>
    </div>
    </div>

</body>

</html>
