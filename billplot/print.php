<?php
require_once("../assets/php/info.php");
include("../assets/php/current.php");
include_once("../assets/php/createPDF.php");

$debug = false;

$date = new DateTime($_POST['date']);
$jobNumber = $_POST['jobNumber'];
$clientCode = $_POST['clientCode'];
$clientNumber = $_POST['clientNumber'];
$attention = $_POST['attention'];
$authBy = $_POST['authBy'];

$project = $_POST['project'];
$company = $_POST['company'];
$addr1 = $_POST['addr1'];
$addr2 = $_POST['addr2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];

$description = $_POST['description'];

$numSets = $_POST['numSets'];
$numSheets = $_POST['numSheets'];
$sheetSizes = $_POST['sheetSizes'];

$colored = $_POST['colored'];
$costs = $_POST['costs'];
$lineTotals = $_POST['lineTotals'];

$total = $_POST['total'];
$save = intval($_POST['save']);

/***********************************************************************************************************************
 * Write to DB
 */

 if($save){
    $conn = new mysqli($host, $user, $password, $defaultTbl);
    if($conn->errno){
        echo "<br>Error: ".$conn->error;
        exit();
    }

    $mainQuery = "INSERT INTO $defaultTbl.$pbillTbl (Jn, `Code`, `Date`, Attn, Company, Addr1, ";
    $values = " VALUES('$jobNumber', '$clientCode', '".$date->format("Y-m-d")."', '$attention', '$company', '$addr1', ";
    if ($addr2) {
        $mainQuery .= "Addr2, ";
        $values .= "'$addr2', ";
    }
    $mainQuery .= "City, State, Zip, RequestedBy, Project, ";
    $values .= "'$city', '$state', '$zip', '$authBy', '".str_replace("'", '"', $project)."', ";
    if ($description) {
        $mainQuery .= "Description, ";
        $values .= "'".str_replace("'", '"', $description)."', ";
    }
    for ($i = 1; $i <= sizeof($numSets); $i++) {
        if ($numSets[$i - 1]) {
            $mainQuery .= "Sets$i, Copies$i, Size$i, Media$i, Color$i, Cost$i, ";
            $values .= $numSets[$i-1].", ".$numSheets[$i-1].", '".$sheetSizes[$i-1]."', 'Paper', ";
            if(in_array(($i-1), $colored)) $values .= "'Y', ";
            else $values .= "'N', ";
            $values .= "'".$costs[$i-1]."', ";
        }
    }
    $mainQuery .= "BillTotal"; //Client_num";
    $values .= "'".$total."'";
    if ($clientnum) {
        $mainQuery .= ", Client_num";
        $values .= ", '$clientNumber'";
    }
    $mainQuery .= ") ".$values.")";

    if(!$debug){
        if ($conn->query($mainQuery)) {
            echo "New pbill record has been saved<br>";
        } else {
            echo "ERROR: " . $mainQuery . "<br>" . $conn->error;
        }
    } else {
        echo $mainQuery."<br>";
    }

    $conn->close();
}

billPlotPDF();
