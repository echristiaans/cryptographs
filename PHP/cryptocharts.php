<?php
/* Include the `fusioncharts.php` file that contains functions  to embed the charts.
replace COIN_NAME for the name of your coin, e.g. Ethereum
replace COIN_SHORT_NAME for the short name of your coin, e.g. ETH
*/

  include("includes/fusioncharts.php");

/* The following 4 code lines contain the database connection information. Alternatively, you can move these code lines to a separate file and include the file here. You can also modify this code based on your database connection. */

 $hostdb = "";  // MySQl host
 $userdb = "";  // MySQL username - user requires SELECT privileges
 $passdb = "";  // MySQL password
 $namedb = "";  // MySQL database name

 // Establish a connection to the database
 $dbhandle = new mysqli($hostdb, $userdb, $passdb, $namedb);

 // Render an error message, to avoid abrupt failure, if the database connection parameters are incorrect
 if ($dbhandle->connect_error) {
  exit("There was an error with your connection: ".$dbhandle->connect_error);
 }
?>
<html>

<head>
    <title>CryptoCharts | Line Chart using PHP and MySQL</title>
    <script src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
    <script src="http://static.fusioncharts.com/code/latest/fusioncharts.charts.js"></script>
    <script src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.zune.js"></script>
</head>

<body>
        <?php
  // Form the SQL query that returns the coin values from the database
  $COIN_SHORT_NAMEQuery = "SELECT CAST(timeStamp as DATE) ts, coinCurrentValue, coinPercentage FROM coins WHERE coinShortName='COIN_SHORT_NAME' ORDER by timeStamp ASC";
  // Form the SQL query that returns the latest coin value from the database
  $COIN_SHORT_NAMECurrentPriceQuery = "SELECT coinCurrentPrice, timeStamp FROM coins where coinShortName='COIN_SHORT_NAME' ORDER BY timeStamp DESC limit 1";
  // Repeat for more coins - example for Bitcoin
  $BTCQuery = "SELECT CAST(timeStamp as DATE) ts, coinCurrentValue, coinPercentage FROM coins WHERE coinShortName='BTC' ORDER by timeStamp ASC";
  $BTCCurrentPriceQuery = "SELECT coinCurrentPrice, timeStamp FROM coins where coinShortName='BTC' ORDER BY timeStamp DESC limit 1";
  // Form the SQL query that gets the totals from the database
  $trQuery = "SELECT CAST(timeStamp as DATE) ts, totalProfit, totalCurrentValue FROM totals ORDER by timeStamp ASC";
  $currentProfitQuery = "SELECT totalProfit, timeStamp FROM totals ORDER by timeStamp DESC limit 1";
  $currentValueQuery = "SELECT totalCurrentValue, timeStamp FROM totals ORDER by timeStamp DESC limit 1";

  // Execute the query, or else return the error message.
  $ETHresult = $dbhandle->query($ETHQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $ETHCurrentPrice = $dbhandle->query($ETHCurrentPriceQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  // Repeat for other coins
  $BTCresult = $dbhandle->query($BTCQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $BTCCurrentPrice = $dbhandle->query($BTCCurrentPriceQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  // Execute the query for the totals
  $trResult = $dbhandle->query($trQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $currentProfitResult = $dbhandle->query($currentProfitQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  $currentValueResult = $dbhandle->query($currentValueQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");

  // If the query returns a valid response, prepare the JSON string
  if ($COIN_SHORT_NAMEresult) {
    // The `COIN_SHORT_NAMEData` array holds the chart attributes and data
    // replace COIN_NAME for the name of your coin, e.g. Ethereum
    // replace COIN_SHORT_NAME for the short name of your coin, e.g. ETH
    $COIN_SHORT_NAMEData = array(
      "chart" => array(
         "caption"=> "COIN_NAME portfolio over time",
         "subCaption"=> "in Euro's",
         "xAxisName"=> "Date",
         "yAxisName"=> "Value",
          //Cosmetics
         "lineThickness"=> "2",
         "paletteColors"=> "#0075c2",
         "baseFontColor"=> "#333333",
         "baseFont"=> "Helvetica Neue,Arial",
         "captionFontSize"=> "14",
         "subcaptionFontSize"=> "14",
         "subcaptionFontBold"=> "0",
         "showBorder"=> "0",
         "bgColor"=> "#ffffff",
         "showShadow"=> "0",
         "canvasBgColor"=> "#ffffff",
         "canvasBorderAlpha"=> "0",
         "divlineAlpha"=> "100",
         "divlineColor"=> "#999999",
         "divlineThickness"=> "1",
         "divLineIsDashed"=> "1",
         "divLineDashLen"=> "1",
         "divLineGapLen"=> "1",
         "showXAxisLine"=> "1",
         "xAxisLineThickness"=> "1",
         "xAxisLineColor"=> "#999999",
         "showValues"=> "0",
         "drawAnchors"=> "0",
         "labelStep"=> "288",
         "showAlternateHGridColor"=> "0"
        )
    );

    $COIN_SHORT_NAMEData["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($result)) {
      array_push($COIN_SHORT_NAMEData["data"], array(
          "label" => $row["ts"],
          "value" => $row["coinCurrentValue"]
          )
      );
    }

    /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

    $jsonEncodedCOIN_SHORT_NAMEData = json_encode($COIN_SHORT_NAMEData);

    /*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

    $COIN_SHORT_NAMEChart = new FusionCharts("line", "COIN_SHORT_NAMEChart" , 600, 300, "chart-COIN_SHORT_NAME", "json", $jsonEncodedCOIN_SHORT_NAMEData);

    // Render the chart
    $COIN_SHORT_NAMEChart->render();
  }
  /* Repeat for other coins - example for Bitcoin
  if ($BTCresult) {
    // The `$arrData` array holds the chart attributes and data
    $BTCData = array(
      "chart" => array(
         "caption"=> "Value of Bitcoin portfolio over time",
         "subCaption"=> "in Euro's",
         "xAxisName"=> "Date",
         "yAxisName"=> "Value",
          //Cosmetics
         "lineThickness"=> "2",
         "paletteColors"=> "#0075c2",
         "baseFontColor"=> "#333333",
         "baseFont"=> "Helvetica Neue,Arial",
         "captionFontSize"=> "14",
         "subcaptionFontSize"=> "14",
         "subcaptionFontBold"=> "0",
         "showBorder"=> "0",
         "bgColor"=> "#ffffff",
         "showShadow"=> "0",
         "canvasBgColor"=> "#ffffff",
         "canvasBorderAlpha"=> "0",
         "divlineAlpha"=> "100",
         "divlineColor"=> "#999999",
         "divlineThickness"=> "1",
         "divLineIsDashed"=> "1",
         "divLineDashLen"=> "1",
         "divLineGapLen"=> "1",
         "showXAxisLine"=> "1",
         "xAxisLineThickness"=> "1",
         "xAxisLineColor"=> "#999999",
         "showValues"=> "0",
         "drawAnchors"=> "0",
         "labelStep"=> "288",
         "showAlternateHGridColor"=> "0"
        )
    );

    $BTCData["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($BTCresult)) {
      array_push($BTCData["data"], array(
          "label" => $row["ts"],
          "value" => $row["coinCurrentValue"]
          )
      );
    }

    $jsonEncodedBTCData = json_encode($BTCData);
    $BTCChart = new FusionCharts("line", "BTCChart" , 600, 300, "chart-BTC", "json", $jsonEncodedBTCData);
    $BTCChart->render();
  }
  */
  // Collect data for totals
  if ($trResult) {
    $totalData = array(
      "chart" => array(
         "caption"=> "Total profit over time",
         "subCaption"=> "in Euro's",
         "xAxisName"=> "Date",
         "yAxisName"=> "Value",
          //Cosmetics
         "lineThickness"=> "2",
         "paletteColors"=> "#0075c2",
         "baseFontColor"=> "#333333",
         "baseFont"=> "Helvetica Neue,Arial",
         "captionFontSize"=> "14",
         "subcaptionFontSize"=> "14",
         "subcaptionFontBold"=> "0",
         "showBorder"=> "0",
         "bgColor"=> "#ffffff",
         "showShadow"=> "0",
         "canvasBgColor"=> "#ffffff",
         "canvasBorderAlpha"=> "0",
         "divlineAlpha"=> "100",
         "divlineColor"=> "#999999",
         "divlineThickness"=> "1",
         "divLineIsDashed"=> "1",
         "divLineDashLen"=> "1",
         "divLineGapLen"=> "1",
         "showXAxisLine"=> "1",
         "xAxisLineThickness"=> "1",
         "xAxisLineColor"=> "#999999",
         "showValues"=> "0",
         "drawAnchors"=> "0",
         "labelStep"=> "288",
         "showAlternateHGridColor"=> "0"
        )
    );
    $totalValueData = array(
      "chart" => array(
         "caption"=> "Total value over time",
         "subCaption"=> "in Euro's",
         "xAxisName"=> "Date",
         "yAxisName"=> "Value",
          //Cosmetics
         "lineThickness"=> "2",
         "paletteColors"=> "#0075c2",
         "baseFontColor"=> "#333333",
         "baseFont"=> "Helvetica Neue,Arial",
         "captionFontSize"=> "14",
         "subcaptionFontSize"=> "14",
         "subcaptionFontBold"=> "0",
         "showBorder"=> "0",
         "bgColor"=> "#ffffff",
         "showShadow"=> "0",
         "canvasBgColor"=> "#ffffff",
         "canvasBorderAlpha"=> "0",
         "divlineAlpha"=> "100",
         "divlineColor"=> "#999999",
         "divlineThickness"=> "1",
         "divLineIsDashed"=> "1",
         "divLineDashLen"=> "1",
         "divLineGapLen"=> "1",
         "showXAxisLine"=> "1",
         "xAxisLineThickness"=> "1",
         "xAxisLineColor"=> "#999999",
         "showValues"=> "0",
         "drawAnchors"=> "0",
         "labelStep"=> "288",
         "showAlternateHGridColor"=> "0"
        )
    );

    $totalData["data"] = array();
    $totalValueData["data"] = array();

    // Push the data into the array
    while($row = mysqli_fetch_array($trResult)) {
      array_push($totalData["data"], array(
          "label" => $row["ts"],
          "value" => $row["totalProfit"]
          )
      );
      array_push($totalValueData["data"], array(
          "label" => $row["ts"],
          "value" => $row["totalCurrentValue"]
          )
      );
    }

    $jsonEncodedTotalData = json_encode($totalData);
    $jsonEncodedTotalValueData = json_encode($totalValueData);
    $totalChart = new FusionCharts("line", "TotalProfitChart" , 600, 300, "chart-Totals", "json", $jsonEncodedTotalData);
    $totalValueChart = new FusionCharts("line", "TotalValueChart" , 600, 300, "chart-TotalValue", "json", $jsonEncodedTotalValueData);
    $totalChart->render();
    $totalValueChart->render();
  }
    // Close the database connection
    $dbhandle->close();
  ?>
              <!-- Display the charts on the page -->
              <center>
                  <!-- Total Profit Chart -->
                  <div id="chart-Totals">Total Profit Chart will render here!</div>
                  <?php
                    if ($currentProfitResult) {
                      while($row = mysqli_fetch_array($currentProfitResult)) {
                        echo '<br>Current profit is <b>€'.$row["totalProfit"].'</b><br>';
                      }
                    }
                  ?>
                  <!-- Total Portfolio Value Chart -->
                  <div id="chart-TotalValue">Total Portfolio Value Chart will render here!</div>
                  <?php
                    if ($currentValueResult) {
                      while($row = mysqli_fetch_array($currentValueResult)) {
                        echo '<br>Current value is <b>€'.$row["totalCurrentValue"].'</b><br>';
                      }
                    }
                  ?>
                  <!-- COIN_SHORT_NAME Chart -->
                  <div id="chart-COIN_SHORT_NAME">COIN_SHORT_NAME Chart will render here!</div>
                  <!-- Display the latest value of COIN_SHORT_NAME -->
                  <?php
                    if ($COIN_SHORT_NAMECurrentPrice) {
                      while($row = mysqli_fetch_array($COIN_SHORT_NAMECurrentPrice)) {
                        echo '<br>Latest price for COIN_SHORT_NAME is <b>€'.$row["coinCurrentPrice"].'</b><br>';
                      }
                    }
                  ?>
                  <!-- repeat for other coins, example for XRP -->
                  <div id="chart-XRP">XRP Chart will render here!</div>
                  <?php
                    if ($XRPCurrentPrice) {
                      while($row = mysqli_fetch_array($XRPCurrentPrice)) {
                        echo '<br>Current price for XRP is <b>€'.$row["coinCurrentPrice"].'</b><br>';
                      }
                    }
                  ?>
              </center>
      </body>
      </html>
