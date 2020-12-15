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
    <div class="row" style="width: 100% !important; height: 100% !important; overflow:auto !important;">
        <div class=" col-md-3 col-xs-4 bg-light" style="height: 100% !important;" id="sideCol">
            <!-- LEFT PANE -->
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
        <div class="col-md-8 col-xs-7" id="sideCol2">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- ADD CATEGORY -->
                <div class="tab-pane fade show" id="v-pills-addCategory" role="tabpanel"
                    aria-labelledby="v-pills-addCategory-tab">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCategoryName">Category Name</label>
                                <input type="text" class="form-control" id="inputCategoryname"
                                    placeholder="Category Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputCategoryDescription">Category Description</label>
                                <input type="text" class="form-control" id="inputCategory"
                                    placeholder="Category Description">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </form>
                </div>
                <!-- ADD ITEM -->
                <div class="tab-pane fade" id="v-pills-addItem" role="tabpanel" aria-labelledby="v-pills-addItem-tab">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputItemName">Item Name</label>
                                <input type="text" class="form-control" id="inputItem" placeholder="Item Name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputItemDescription">Item Description</label>
                                <input type="text" class="form-control" id="inputItemDescription"
                                    placeholder="Description">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="selectCategory">Category</label>
                                <select id="selectedCategory" class="form-control" style="height: 34px !important;">
                                    <option selected>Choose...</option>
                                    <option>...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPrice">Price</label>
                                <input type="text" class="form-control"
                                    id="inputPrice">
                            </div>
                        </div>
                        <hr>
                        <div id="divModifiers" class="form-row align-items-end">
                            <div id="divModifier-1" class="form-group col-md-3 col-sm-3">
                                <label for="inputZip">Modifier</label>
                                <input id="inputModifier-1" type="text" class="form-control" id="inputZip">
                            </div>
                            <div id="divModifier-2" class="form-group col-md-3 col-sm-3">
                                <label for="inputZip">Modifier</label>
                                <input id="inputModifier-2" type="text" class="form-control" id="inputZip">
                            </div>
                            <div id="divModifier-3" class="form-group col-md-3 col-sm-3">
                                <label for="inputZip">Modifier</label>
                                <input id="inputModifier-3" type="text" class="form-control" id="inputZip">
                            </div>
                            <div id="divAddModifier" class="form-group">
                                <button id="btnAddModifier" type="button" class="btn btn-success btn-sm"
                                    style="height: 34px;">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Item</button>
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
                            <table class="table table-hover table-striped firstRow">
                                    <tr COLSPAN=2 BGCOLOR="#6D8FFF">
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-danger noRadius">Super Burger</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-danger noRadius">Chicken Sandwich</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-danger noRadius">Pastrami Burger</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-danger noRadius">Hot Dog</button></td></tr>
                                    </tr>
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
                                    <tr COLSPAN=2 BGCOLOR="#6D8FFF">
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-primary noRadius">Super Burger</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-primary noRadius">Chicken Sandwich</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-primary noRadius">Pastrami Burger</button></td></tr>
                                        <tr><td class="noPadding"><button style="width: 100%;" type="submit" class="btn btn-primary noRadius">Hot Dog</button></td></tr>
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