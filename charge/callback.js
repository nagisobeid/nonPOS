//If successful, Square Point of Sale returns the following parameters.
const clientTransactionId = "client_transaction_id";
const transactionId = "transaction_id";

//If there's an error, Square Point of Sale returns the following parameters.
const errorField = "error_code";

function getTransactionInfo(URL) {
    var data = decodeURI(URL.searchParams.get("data"));

    console.log("data: " + data);
    var transactionInfo = JSON.parse(data);
    return transactionInfo;
  } 

// Makes a result string for success situation
function handleSuccess(transactionInfo){
  var resultString ="";

 if (clientTransactionId in transactionInfo) {
    resultString += "Client Transaction ID: " + transactionInfo[clientTransactionId] + "<br>";
  }
  if (transactionId in transactionInfo) {
    resultString += "Transaction ID: " + transactionInfo[transactionId] + "      <br>";
  }
   else {
    resultString += "Transaction ID: NO CARD USED<br>";
  }
  return resultString;
}

// Makes an error string for error situation
function handleError(transactionInfo){
  var resultString ="";

  if (errorField in transactionInfo) {
    resultString += "Client Transaction ID: " + transactionInfo[clientTransactionId] + "<br>";
  }
  if (transactionId in transactionInfo) {
    resultString += "Transaction ID: " + transactionInfo[transactionId] + "     <br>";
  }
   else {
    resultString += "Transaction ID: PROCESSED OFFLINE OR NO CARD USED<br>";
  }
  return resultString;
}

// Determines whether error or success based on urlParams, then prints the string
function printResponse() {
  var responseUrl = window.location.href;
  var transactionInfo = getTransactionInfo(responseUrl);
  var resultString = "";

  if (errorField in transactionInfo) {
    resultString = handleError(transactionInfo);
  } else {
    resultString = handleSuccess(transactionInfo);
  }

  //document.getElementById('close').innerHTML = resultString+" Back To POS";
}
