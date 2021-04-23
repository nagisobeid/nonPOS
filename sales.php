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
    

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="sales.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/JavaScript">
    // DONUT CHART
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChartDonut(sales) {
            var donutChartData = new google.visualization.DataTable();
            donutChartData.addColumn('string', "Category");
            donutChartData.addColumn('number', '# Of Sales');
            var allCategories = [];
            var row = [2];
            for (var i = 0; i < sales.length; i++) {
                if(allCategories.includes(sales[i]["mName"])) {
                    //console.log("OK");
                } else {
                    allCategories.push(sales[i]["mName"]);
                }
            }
            
            for (var i = 0; i < allCategories.length; i++) {
                var curCatCount = 0;
                row[0] = allCategories[i];
                for(var j = 0; j < sales.length; j++) {
                    if(sales[j]["mName"] == allCategories[i]) {
                        curCatCount += (1 * sales[j]["quantity"]);
                    }
                }
                row[1] = curCatCount;
                donutChartData.addRow(row);
            }

            var options = {
            legend: { position: 'none' },
            title: 'Category Sales',
            titlePosition: 'none',
            pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(donutChartData, options);
        }
    // END DONUT CHART

        var range = "";
        //var sales = [5.99,6.99,7.99];
        //FOR WEEK PROCESSING -> GETTING THE FIRST DAY OF THE WEEK -> MONDAY
        function getMonday(d) {
            d = new Date(d);
            var day = d.getDay(),
                diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
            return new Date(d.setDate(diff));
        }

        function getDaysInMonth() {
            var today = new Date();
            var month = today.getMonth();
            var days = daysInMonth(month + 1, today.getFullYear());
            return days;
            //console.log(daysInMonth(month + 1, today.getFullYear()))
        }

        function daysInMonth(month,year) {
            return new Date(year, month, 0).getDate();
        }

        function calculateSalesReport(processedSales) {
            let totalSales = 0;
            let tax = .75;
            for( var i = 0; i< processedSales.length; i++) {
                totalSales+=processedSales[i];
            }
            let taxValue = totalSales * .075;
            $("#grossSalesValue").text("$" + totalSales.toLocaleString());
            $("#netSalesValue").text("$" + totalSales.toLocaleString());
            taxValue = taxValue.toFixed(2);
            taxValue = parseFloat(taxValue);
            //console.log(taxValue.toLocaleString());
            $("#taxValue").text("$" + taxValue.toLocaleString());
            totalSales -= taxValue;
            totalSales = totalSales.toFixed(2);
            totalSales = parseFloat(totalSales);
            $("#totalValue").text("$" + totalSales.toLocaleString());
        }

        //FUNCTION -> PROCESS THE SALES DATA
        function processSales(sales, range) {
            drawChartDonut(sales);
            switch(range) {
                case "Today":
                    var indexedSales = Array(24).fill(0);
                    //LOOP THROUGH EACH SALE AND DETERMINE HOUR
                    for (var i = 0; i < sales.length; i++) {
                        //0 = 12am, 1 = 1am, 2 = 2am, ..., 11 = 11am, 12 = 12pm, 13 = 1pm, 14 = 2pm, ..., 22 = 10pm, 23 = 11pm  
                        //EVALUATE TIME AND PRICE @ i
                        var timeonly = sales[i]["placed"].slice(-8);
                        var hour = timeonly[0] + timeonly[1];
                        hour = parseInt(hour);
                        indexedSales[hour]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                        //console.log(indexedSales);
                    }
                    //drawChartDonut(sales);
                    calculateSalesReport(indexedSales);
                    //console.log(indexedSales);
                    return indexedSales;
                    break; 
                case "This Week":
                    var indexedSales = Array(7).fill(0);
                    //GET MONDAY
                    var mon = getMonday(new Date());
                    mon = mon.getDate();
                    for (var i = 0; i < sales.length; i++) {
                        var day = sales[i]["placed"].slice(-19,-9);
                        var dayOnly = day.slice(-2);
                        dayOnly = parseInt(dayOnly);

                        switch(dayOnly) {
                            case mon:
                                indexedSales[0]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                                break;
                            case mon+1:
                                indexedSales[1]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                                break;
                            case mon+2:
                                indexedSales[2]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                                break;
                            case mon+3:
                                indexedSales[3]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                                break;
                            case mon+4:
                                indexedSales[4]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));  
                                break;
                            case mon+5:
                                indexedSales[5]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));     
                                break;
                            case mon+6:
                                indexedSales[6]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                                break;
                        }
                        
                    }
                    calculateSalesReport(indexedSales);
                    return indexedSales;
                    break;
                case "This Month":
                    // process sales and add
                    var days = getDaysInMonth();
                    var indexedSales = Array(days).fill(0);
                    //LOOP THROUGH EACH SALE AND DETERMINE HOUR
                    for (var i = 0; i < sales.length; i++) {
                        var day = sales[i]["placed"].slice(-19,-9);
                        var dayOnly = day.slice(-2);
                        dayOnly = parseInt(dayOnly);
                        indexedSales[dayOnly-1]+=(parseFloat(sales[i]["price"]) * parseInt(sales[i]["quantity"]));
                        //console.log(indexedSales);
                    }
                    //console.log(indexedSales);
                    calculateSalesReport(indexedSales);
                    return indexedSales;
                    break;
                case "This Year":
                    break;
            }
        }
        // END FUNCTION

        $(document).ready(function() {
            $("#today").addClass("selected");
            range = $("#today").text();
            setTimeout(function(){
                drawChart(range);
            }, 1000);

            $(document).on('click', '.optionBtn', function() {
                $(".optionBtn").removeClass("selected");
                $(this).addClass("selected");
                range = $(this).text();
                console.log(range);
                drawChart(range);
            });
        });

        //PIE CHART
        google.charts.load('current', {'packages':['bar']});
        //google.charts.setOnLoadCallback(drawChart);

        function drawChart(range) {
            $.ajax({
                url: 'getSales.php',
                dataType:'json',
                type: 'POST',
                data: {range: range},
                success: function(response){
                    var processedSales = processSales(response, range);
                    switch(range) {
                        case "Today":
                        //console.log(range[0]["price"]);
                            // code block
                            console.log(processedSales);
                            var today = google.visualization.arrayToDataTable([
                                ['Hour', 'Sales'],
                                ['6:00 AM', processedSales[6]],
                                ['7:00 AM', processedSales[7]],
                                ['8:00 AM', processedSales[8]],
                                ['9:00 AM', processedSales[9]],
                                ['10:00 AM', processedSales[10]],
                                ['11:00 AM', processedSales[11]],
                                ['12:00 PM', processedSales[12]],
                                ['1:00 PM', processedSales[13]],
                                ['2:00 PM', processedSales[14]],
                                ['3:00 PM', processedSales[15]],
                                ['4:00 PM', processedSales[16]],
                                ['5:00 PM', processedSales[17]],
                                ['6:00 PM', processedSales[18]],
                                ['7:00 PM', processedSales[19]],
                                ['8:00 PM', processedSales[20]],
                                ['9:00 PM', processedSales[21]],
                                ['10:00 PM', processedSales[22]],
                                ['11:00 PM', processedSales[23]],
                                ['12:00 AM', processedSales[0]],
                                ['1:00 AM', processedSales[1]],
                                ['2:00 AM', processedSales[2]],
                                ['3:00 AM', processedSales[3]],
                                ['4:00 AM', processedSales[4]],
                                ['5:00 AM', processedSales[5]],
                                ]);

                                var options = {
                                    legend: { position: 'none' },
                                    chart: {
                                        title: 'Sales Data',
                                        subtitle: 'Sales: '+ range,
                                    },
                                    vAxis: {format: 'currency'}
                                };
                                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                                chart.draw(today, google.charts.Bar.convertOptions(options));
                                //NOW PREPARE DONUT CHART
        
                            break;
                        case "This Week":
                            console.log(processedSales);
                            var thisWeek = google.visualization.arrayToDataTable([
                                ['Day', 'Sales'],
                                ['Monday', processedSales[0]],
                                ['Tuesday', processedSales[1]],
                                ['Wednesday', processedSales[2]],
                                ['Thursday', processedSales[3]],
                                ['Friday', processedSales[4]],
                                ['Saturday', processedSales[5]],
                                ['Sunday', processedSales[6]],
                                ]);

                            var options = {
                                legend: { position: 'none' },
                                chart: {
                                    title: 'Sales Data',
                                    subtitle: 'Sales: '+ range,
                                },
                                vAxis: {format: 'currency'}
                            };
                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(thisWeek, google.charts.Bar.convertOptions(options));
                            
                            break;
                        case "This Month":
                            const monthNames = ["January", "February", "March", "April", "May", "June",
                                                "July", "August", "September", "October", "November", "December"
                                                ];

                            const d = new Date();
                            //console.log(getDaysInMonth());
                            var monthData = new google.visualization.DataTable();
                            monthData.addColumn('string', monthNames[d.getMonth()]);
                            monthData.addColumn('number', 'Sales');
                            //var newData = [['Month', 'Sales']];
                            for (var i = 0; i < getDaysInMonth(); i++) {
                                var row = [2];
                                row[0] = String(i+1);
                                row[1] = processedSales[i];
                                //row[1] = 
                                monthData.addRow(row);
                            }
                            var options = {
                                    legend: { position: 'none' },
                                    chart: {
                                        title: 'Sales Data',
                                        subtitle: 'Sales: '+ range,
                                    },
                                    vAxis: {format: 'currency'}
                                };
                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
                            chart.draw(monthData, google.charts.Bar.convertOptions(options));
                            
                            break;
                        case "This Year":
                            break;
                    }
                }
            });
      }
        //END

        
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
    <div class="btn-group" role="group" aria-label="Basic example" id="salesOptions">
        <button id="today" type="button" class="btn btn-success optionBtn noRadius">Today</button>
        <button type="button" class="btn btn-success optionBtn noRadius">This Week</button>
        <button type="button" class="btn btn-success optionBtn noRadius">This Month</button>
        <button id="last" type="button" class="btn btn-success optionBtn noRadius">This Year</button>
    </div>
    <div style="width: 100%;">
        <div id="columnchart_material" style="width: 99%; height: 200px;"></div>
        
        <div id="donutchart" style="width: 99%; height: 500px;"></div>
    </div> 
    <div class="bg-light" style="width: 100%;">
        <div id="report" style="display: block; margin-left: auto; margin-right: auto; width: 80%; padding-bottom: 15px;">
            <h3 style= " text-align: center; padding-top: 15px;" class="text-secondary">Sales Report</h3>
            <div class="bg-light" id="grossSales" style="width: 100%">
                <h4 class="text-secondary" style="display: inline-block; color: white; padding-left: 5px;">Gross Sales</h4>
                <h4 class="text-secondary" id="grossSalesValue" style="display: inline-block; float: right; color: white;  padding-right: 5px;">$0.00</h4>
            </div>
            <hr>
            <div style="border-radius: 5px;" class="bg-danger" id="returns">
                <h4 style="display: inline-block; color: white; padding-left: 5px;">Returns</h4>
                <h4 id="returnsValue" style="display: inline-block; float: right; color: white;  padding-right: 5px;">$0.00</h4>
            </div>
            <hr>
            <div class="bg-light" id="netSales">
                <h4 class="text-secondary" style="display: inline-block; color: white; padding-left: 5px;">Net Sales</h4>
                <h4 class="text-secondary" id="netSalesValue" style="display: inline-block; float: right; color: white;  padding-right: 5px;">$0.00</h4>
            </div>
            <hr>
            <div id="tax">
                <h4 class="text-secondary" style="display: inline-block; padding-left: 5px;">Tax</h4>
                <h4 class="text-secondary" id="taxValue" style="display: inline-block; float: right;  padding-right: 5px;">$0.00</h4>
            </div>
            <hr>
            <div style="border-radius: 5px;" class="bg-success" id="total">
                <h4 style="display: inline-block; color: white; padding-left: 5px;">Total</h4>
                <h4 id="totalValue" style="display: inline-block; float: right; color: white; padding-right: 5px;">$0.00</h4>
            </div>
        </div>
    </div>
    <!--
    <div class="row flex-nowrap" style="width: 100% !important; height: 100% !important;">
        <div class=" col-md-12 col-xs-12 col-lg-12 bg-light" style="height: 50px !important;" id="sideCol">
            <div class="nav" id="idOptions">
                <a class="nav-lin selected" id="idToday">Today</a>
                <a class="nav-lin" id="idThisWeek">This Week</a>
                <a class="nav-lin" id="idThisMonth">This Month</a>
                <a class="nav-lin" id="idThisYear">This Year</a>
            </div>
        </div>
    </div>
    -->

</body>
</html> 
