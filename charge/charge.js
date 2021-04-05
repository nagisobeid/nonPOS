    
    function processPayment(price) {
      //$( ".charge" ).click(function() {
        //var price = document.getElementById('cost').value;
        //console.log("process payment called with price = " + totalPrice);
        console.log("process payment called with price = " + price);
        price = parseFloat(price)
        price = price * 100;
        price = price.toString();
        var dataParameter = {
          amount_money: {
          amount:        price,
          currency_code: "USD"
        },


        // Replace this value with your application's callback URL
        callback_url: "https://www.cs.csub.edu/~nonpos/nagiRemoteDev/nonPOS/transactionsuccess.php",

        // Replace this value with your application's ID
        //client_id: "sq0idp-tyw25h1yK9uzprice4BcSSrzPA",
        client_id: "sq0idp-tyw25h1yK9uzX4BcSSrzPA",       //DEVELOPMENT
        //client_id: "sandbox-sq0idb-nn7KDmNQ-ERWkqVDtGQobQ", //SANDBOX

        version: "1.3",
        notes: "Testing",
        options: {
          supported_tender_types: ["CREDIT_CARD","CASH","OTHER","SQUARE_GIFT_CARD","CARD_ON_FILE"]
        }
    };

    window.location =
      "square-commerce-v1://payment/create?data=" +
      encodeURIComponent(JSON.stringify(dataParameter));
    //});
    }



