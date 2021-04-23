<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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

    function saveItemsToDB($con, $bID, $decodedItems, $oID) {
        //OID, ITEMID, QUANTITY, PRICE, EXTRAS
         
        //print_r($decodedItems);
        foreach($decodedItems as $item) {
            //echo $item["itemID"] . " ";
            //echo $item["itemQuantity"] . " ";
            //echo $item["itemPrice"] . " ";
            $sql = "INSERT INTO contains (oID, itemID, quantity, price)
            VALUES (:oID, :itemID, :quantity, :price)";
            $stmt = $con->prepare($sql);
            $stmt->execute(['oID' => $oID, 'itemID' => $item["itemID"],'quantity' => $item["itemQuantity"], 
                            'price' => $item["itemPrice"]]);
                        
            
            $sqlMods = "UPDATE contains SET extras = ? WHERE oID = ? and itemID = ? and quantity = ?";
            $stmt= $con->prepare($sqlMods);
            
            foreach($item["itemMods"] as $mods) {
                $extrasString = "";
                foreach($mods as $mod) {
                    $extrasString .= $mod["Quantity"];
                    $extrasString .= "x"; 
                    $extrasString .= $mod["Name"]; 
                    $extrasString .= ",";
                }
                //echo($extrasString);
                //echo("NEW ITEM MODS");
                $stmt->execute([$extrasString, $oID, $item["itemID"], $item["itemQuantity"]]);
            }
          }
    }
    

    function saveOrderToDB($con, $bID, $decodedOrder) {
        $largestID = 0;
        $type = 0;
        $sql = "SELECT MAX(oID) FROM orders WHERE bID = '$bID'";
        $res = $con->prepare($sql); 
		$res->execute();
        #echo "executed";
        if (empty($res->rowCount())) {
			$largestID = 1;
		}
        else {
            $largestID = $res->fetch();
        }

        switch($decodedOrder['orderType']) {
            case "Dine-In":
                $type = 1;
                break;
            case "Take-Out":
                $type = 2;
                break;
            case "Call-In":
                $type = 3;
                break;
            case "Drive-Thru":
                $type = 4;
                break;
        }

        $sql = "INSERT INTO orders (username, placed, eID, type, bID)
        VALUES (:username, :placed, :eID, :type, :bID)";
        $stmt = $con->prepare($sql);
        //$decodedOrder['placed']
        $dt = date('Y-m-d H:i:s');
        $stmt->execute(['username' => $decodedOrder['username'], 'placed' => $dt,
                        'eID' => $_SESSION['currentEmployee'], 'type' => $type, 'bID'=> $bID ]);

        $largestID = $largestID[0] + 1;
        echo $largestID;
    }


    if(isset($_POST['order']))
    {
        $order = $_POST['order'];
        $decodedOrder = json_decode($order,true);

        //INSERTING USER IN DATABASE
        $sql = "INSERT INTO users (username, uEmail, uPass)
        VALUES (:username, :uEmail, :uPass)";
        $stmt = $con->prepare($sql);
        $stmt->execute(['username' => $decodedOrder['username'], 'uEmail' => "none", 'uPass' => "none"]);
        saveOrderToDB($con, $bID, $decodedOrder);
    }

    if(isset($_POST['items']))
    {
       
        $items = $_POST['items'];
        $oID = $_POST['currOID'];
        $decodedItems = json_decode($items,true);
        saveItemsToDB($con, $bID, $decodedItems, $oID);
        
        /*
        //print_r($decodedItems);
        foreach($decodedItems as $item) {
            //echo $item["itemID"] . " ";
            //echo $item["itemQuantity"] . " ";
            //echo $item["itemPrice"] . " ";
            foreach($item["itemMods"] as $mods) {
                foreach($mods as $mod) {
                    echo $mod["Name"] . " ";
                    echo $mod["Quantity"] . " ";
                }
            }
          }*/
    }
?> 