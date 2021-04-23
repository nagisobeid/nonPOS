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
    //LOADING ITEMS//
    function loadMenuItems($dbConn) {
        $bID = $_SESSION['bid'];
        $sqlLoadItems = "SELECT * FROM items INNER JOIN menus ON items.menuID = menus.menuID WHERE menus.bID = $bID";
        $res = $dbConn->prepare($sqlLoadItems);
        $res->execute();
        $menuItems = $res->fetchAll();
        return $menuItems;
    }
    //END LOADING ITEMS//

    function loadCategories($dbConn) {
        $bID = $_SESSION['bid'];
        $sql = "SELECT mName from menus where bID = $bID";
        $res = $dbConn->prepare($sql);
        $res->execute();
        $categories = $res->fetchAll();
        //print_r($categories);
        return $categories;
    }
    
    function addCategory($dbConn) {
        $categoryName = $_POST['nameCategoryName'];
        $categoryName = str_replace(' ', '-', $categoryName);
        $categoryDesc = $_POST['nameCateogryDesc'];
        $bID = $_SESSION['bid'];
        $sql = "INSERT INTO menus (mName, bID, mDescrip)
                    VALUES (:categoryName, :bID, :categoryDesc)";
        $stmt = $dbConn->prepare($sql);
        $stmt->bindParam(':categoryName', $categoryName,PDO::PARAM_STR, 32);
        $stmt->bindParam(':bID', $bID,PDO::PARAM_INT);
        $stmt->bindParam(':categoryDesc', $categoryDesc,PDO::PARAM_STR, 32);
        $stmt->execute();
    }

    function addModifiers($last_item_id,$dbConn,$names,$descriptions,$prices){
        $modifiersList = "";
        $sql = "INSERT INTO extras (name, exDescrip, exPrice)
                    VALUES (:modifierName, :modifierDesc, :modifierPrice)";
        $stmt = $dbConn->prepare($sql);
        for ($x = 1; $x < count($names)+1; $x++) {
            $stmt->bindParam(':modifierName', $names[$x],PDO::PARAM_STR, 32);
            $stmt->bindParam(':modifierDesc', $descriptions[$x],PDO::PARAM_STR, 32);
            $stmt->bindParam(':modifierPrice', $prices[$x],PDO::PARAM_STR, 32);
            $stmt->execute();
            $last_id = $dbConn->lastInsertId();
            $string = strval($last_id);
            $modifiersList = "{$modifiersList}{$string},";
        }
        //$sql = "INSERT INTO items (extraKey) WHERE itemID = $last_item_id
        //            VALUES (:keys)";
        $sql = "UPDATE items SET extraKey = :keys WHERE itemID = $last_item_id";
        $stmt = $dbConn->prepare($sql);
        $stmt->bindParam(':keys', $modifiersList,PDO::PARAM_STR, 120);
        $stmt->execute();
    }

    function addItem($dbConn) {
        $bID = $_SESSION['bid'];
        $itemName = $_POST['nameItemName'];
        $itemDesc = $_POST['nameItemDesc'];
        $itemPrice = $_POST['nameItemPrice'];
        $menuName = $_POST['nameMenuName'];
        $itemModifiers = $_POST['nameItemModifier'];
        $itemModifiersPrice = $_POST['nameItemModifierPrice'];
        $itemModifiersDesc = $_POST['nameItemModifierDesc'];
        
        $sql_menu_id = "SELECT menuID from menus where bID = $bID AND mName = '$menuName'";
        $res = $dbConn->prepare($sql_menu_id);
        $res->execute();
        $menuId = $res->fetch();
        
        $sql = "INSERT INTO items (name, iDescrip, price, menuID)
                    VALUES (:itemName, :itemDescription, :itemPrice, :menuID)";
        $stmt = $dbConn->prepare($sql);
        $stmt->bindParam(':itemName', $itemName,PDO::PARAM_STR, 32);
        $stmt->bindParam(':itemDescription', $itemDesc,PDO::PARAM_STR, 32);
        $stmt->bindParam(':itemPrice', $itemPrice,PDO::PARAM_STR, 10);
        $stmt->bindParam(':menuID', $menuId[0],PDO::PARAM_INT);
        $stmt->execute();
        $last_item_id = $dbConn->lastInsertId();
        
        if (!empty($itemModifiers)) {
            addModifiers($last_item_id,$dbConn,$itemModifiers,$itemModifiersDesc,$itemModifiersPrice);
        }
        
    }

    $menuItems = loadMenuItems($con);
    $categories = loadCategories($con);
 
    if(isset($_POST['submit'])) {
        if ($_POST['submit'] == 'Add Category') {
            addCategory($con);
            echo "<meta http-equiv='refresh' content='0'>";
      } elseif ($_POST['submit'] == 'Add Item') {
            addItem($con);
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }

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
        function deleteMenuItem(itemID) {
            $.ajax({
                url: 'deleteItem.php?q=' + itemID,
                data: itemID,
                type: 'POST',
                dataType: 'json',
                success: function(response)
                {
                    //modsIntoModals(response, itemID);
                }
            });
        }

        var currCountOfModifierFields = 0;
    	$(document).ready(function() {
            var categories = <?php echo json_encode($categories); ?>;
            var menuItems = <?php echo json_encode($menuItems); ?>;

            for (i = 0; i < categories.length; i++) {
                var categoryHtml = '<option>'+categories[i][0]+'</option>'
                $('#selectedCategory').append(categoryHtml);
            }
            for (i = 0; i < menuItems.length; i++) {
                var itemHtml = '<tr id="trIdRemoveItem'+menuItems[i]['itemID']+'"><td id="tdIdRemoveItem'+menuItems[i]['itemID']+'" class="noPadding"><button id="btnIdRemoveItem'+menuItems[i]['itemID']+'" style="width: 100% !important;" type="submit" class="btn btn-danger noRadius">'+menuItems[i]['name']+'</button></td></tr>';
                $('#trIdRemoveItemHead').append(itemHtml);
            }

			$(document).on('click', '.nav-link', function() {
                $(".nav-link").removeClass("selected");
                $(".tab-pane").removeClass("show");
                $(this).addClass("selected");
            });
            $(document).on('click', '#btnAddModifier', function() {
                currCountOfModifierFields+=1;
                //TEST
                var divMod = '<div id="divModifier-'+currCountOfModifierFields+'" class="form-group col-md-12 col-sm-12">\
                                <div id="divModifier-'+currCountOfModifierFields+'-Name" class="form-group col-md-4 col-sm-12 modifierPadding">\
                                <label for="inputPrice">Modifier Name</label>\
                                <input name="nameItemModifier['+currCountOfModifierFields+']" id="inputModifier-'+currCountOfModifierFields+'" type="text" class="form-control x" placeholder="Modifier" required>\
                                </div>\
                                <div id="divModifier-'+currCountOfModifierFields+'-Price" class="form-group col-md-4 col-sm-12 modifierPadding">\
                                <label for="inputPrice">Price</label>\
                                <input name="nameItemModifierPrice['+currCountOfModifierFields+']" id="inputModifierPrice-'+currCountOfModifierFields+'" type="text" class="form-control x" placeholder="$0.00" required>\
                                </div>\
                                <div id="divModifier-'+currCountOfModifierFields+'-Desc" class="form-group col-md-4 col-sm-12 modifierPadding">\
                                <label for="inputPrice">Description</label>\
                                <input name="nameItemModifierDesc['+currCountOfModifierFields+']" id="inputModifierDesc-'+currCountOfModifierFields+'" type="text" class="form-control x" placeholder="Description">\
                                </div>\
                            </div>';
                $(divMod).insertBefore("#divAddModifier")
                //END TEST
            });
            $(document).on('click', '#btnRemoveModifier', function() {
                if(currCountOfModifierFields > 0) {
                    $('#divModifier-'+currCountOfModifierFields+'').remove();
                    currCountOfModifierFields-=1;
                }
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
            <button onclick="document.location='about.php'" type="button" id="idBtnAboutus" class="btn btn-link">About Us</button>
        </div>
    </header>
    <div class="row flex-nowrap" style="width: 100% !important; height: 100% !important;">
         <!-- LEFT PANE -->
        <div class=" col-md-3 col-xs-4 bg-light" style="height: 100% !important;" id="sideCol">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link selected" id="v-pills-addCategory-tab" data-toggle="pill" href="#v-pills-addCategory"
                    role="tab" aria-controls="v-pills-addCategory" aria-selected="false">Add Category</a>
                <a class="nav-link" id="v-pills-addItem-tab" data-toggle="pill" href="#v-pills-addItem" role="tab"
                    aria-controls="v-pills-addItem" aria-selected="false">Add Item</a>
                <a class="nav-link" id="v-pills-removeItem-tab" data-toggle="pill" href="#v-pills-removeItem" role="tab"
                    aria-controls="v-pills-removeItem" aria-selected="false">Remove Item</a>
                <a id="last" class="nav-link" id="v-pills-editItem-tab" data-toggle="pill" href="#v-pills-editItem" role="tab"
                    aria-controls="v-pills-editItem" aria-selected="false">Edit Item</a>
            </div>
        </div>
        <!-- RIGHT PANE -->
        <div class="col-md-9 col-xs-8" id="sideCol2">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- ADD CATEGORY -->
                <div class="tab-pane fade show" id="v-pills-addCategory" role="tabpanel"
                    aria-labelledby="v-pills-addCategory-tab">
                    <form id="form" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCategoryName">Category Name</label>
                                <input name="nameCategoryName" type="text" class="form-control" id="inputCategoryname"
                                    placeholder="Category Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputCategoryDescription">Category Description</label>
                                <input name="nameCateogryDesc" type="text" class="form-control" id="inputCategory"
                                    placeholder="Category Description">
                            </div>
                        </div>
                        <input name="submit" type="submit" value="Add Category" class="btn btn-primary">
                    </form>
                </div>
                <!-- ADD ITEM -->
                <div class="tab-pane fade" id="v-pills-addItem" role="tabpanel" aria-labelledby="v-pills-addItem-tab">
                    <form id="form" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputItemName">Item Name</label>
                                <input name="nameItemName" type="text" class="form-control" id="inputItem" placeholder="Item Name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputItemDescription">Item Description</label>
                                <input name="nameItemDesc" type="text" class="form-control" id="inputItemDescription"
                                    placeholder="Description">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="selectCategory">Category</label>
                                <select name="nameMenuName" id="selectedCategory" class="form-control" style="height: 34px !important;">
                                    <!--<option selected>Choose...</option>-->
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPrice">Price</label>
                                <input name="nameItemPrice" type="text" class="form-control"
                                    id="inputPrice" placeholder="$0.00" required>
                            </div>
                        </div>
                        <hr>
                        <div id="divModifiers" class="form-row align-items-end">
                        <!-- MODS DIVS GO HERE -->
                            
                            <div id="divAddModifier" class="form-group">
                                <button id="btnAddModifier" type="button" class="btn btn-success btn-sm"
                                    style="height: 34px; width: 82.01px; margin-left: 5px;">+ Modifier</button>
                            </div>
                            <div id="divRemoveModifier" class="form-group">
                                <button id="btnRemoveModifier" type="button" class="btn btn-danger btn-sm"
                                    style="height: 34px; width: 82.01px; margin-left: 5px;">- Modifier</button>
                            </div>
                        </div>
                        <input style="margin-bottom: 10px;" name="submit" type="submit" value="Add Item" class="btn btn-primary">
                        <!--<button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Add Item</button>-->
                    </form>
                </div>
                <!-- REMOVE ITEM -->
                <div class="tab-pane fade" id="v-pills-removeItem" role="tabpanel"
                    aria-labelledby="v-pills-removeItem-tab">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputSearchItem">Search Menu</label>
                                <input type="text" class="form-control" id="idSearchItem" placeholder="Item">
                            </div>
                            <div class="form-group col-md-6">
                                <table id="trIdRemoveItemHead" class="table table-hover table-striped firstRow">
                                    
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- EDIT ITEM -->
                <div class="tab-pane fade" id="v-pills-editItem" role="tabpanel"
                    aria-labelledby="v-pills-editItem-tab">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputSearchEditItem">Search Menu</label>
                                <input type="text" class="form-control" id="idSearchEditItem" placeholder="Item">
                            </div>
                            <div class="form-group col-md-6">
                                <table class="table table-hover table-striped firstRow">
                                    <tr id="trIdEditItemHead" COLSPAN=2 BGCOLOR="#6D8FFF">
                                        <tr id="trIdEditItem"><td id="tdIdEditItem" class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-primary noRadius">Super Burger</button></td></tr>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- END EDIT ITEM -->
            </div>
        </div>
    </div>
    </div>

</body>
</html>
