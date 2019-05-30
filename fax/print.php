<?php

require_once("../assets/php/info.php");
include("../assets/php/current.php");
include_once("../assets/createPDF.php");
include_once("../label-envelope/printLabel.php");
include_once("../label-envelope/printEnvelope.php");

$debug = true;

$jobNumber = $_POST['jobNumber'];
$clientCode = $_POST['clientCode'];
$date = new DateTime($_POST['date']);

$attention = $_POST['attention'];
$company = $_POST['company'];
$fax = $_POST['fax'];
$from = $_POST['from'];

$project = $_POST['project'];

$numPages = $_POST['numPages'];
$willFollow = $_POST['willFollow'];

$remarks = $_POST['remarks'];

$extraComp = $_POST['extraComp'];
$extraName = $_POST['extraName'];
$extraFax = $_POST['extraFax'];

$save = $_POST['save'];

// echo "hello!<br>";
// echo "jobNumber: $jobNumber<br>";
// echo "clientCode: $clientCode<br>";
// echo "date: ".$date->format("Y-m-d")."<br>";
// echo "attention: $attention<br>";
// echo "company: $company<br>";
// echo "fax: $fax<br>";
// echo "from: $from<br>";
// echo "numPages: $numPages<br>";
// echo "willFollow: $willFollow<br>";
// echo "remarks: $remarks<br>";
// echo "extraComp: ".implode(", ", $extraComp)."<br>";
// echo "extraName: ".implode(", ", $extraName)."<br>";
// echo "extraFax: ".implode(", ", $extraFax)."<br>";
$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "Error: ".$conn->error();
    exit();
}

if(intval($save)){
    $mainQuery = "INSERT INTO tc.$faxTbl (`Date`, Code, Company, Jn, Project, Attention, FaxNumber, NumberPages, ";
    $values = " VALUES('$date', '$clientCode', '$company', '$jobNumber', '$project', '$attention', '$fax', $numPages, ";
    if($remarks){
        $mainQuery .= "Remarks, ";
        $values .= "'$remarks',";
    }
    $mainQuery .= "Signed";
    $values .= "'$from'";
    $mainQuery .= ", WillFollow";
    if($willFollow){
        $values .= ','.true;
    }
    else{
        $values .= ', 0';
    }
    for($i=1; $i<= 2; $i++){
        if($extraComp[$i-1]){
            $mainQuery .= ", Copy_".$i."_Who";
            $values .= ', "'.$extraName[$i-1].'"';
            $mainQuery .= ", Copy_".$i."_Co";
            $values .= ', "'.$extraComp[$i-1].'"';
            $mainQuery .= ", Copy_".$i."_Fax";
            $values .= ', "'.$extraFax[$i-1].'"';
        }
    }
    $mainQuery .= ')';
    $values .= ')';
    $mainQuery .= $values;

    //echo $mainQuery;
    if(!$debug){
        if($conn->query($mainQuery) === true){
            echo "New record created successfully in fax_test";
        }
        else{
            echo "ERROR: ".$conn->error;
        }
    }
    else{
        echo $mainQuery."<br>";
    }
}

//$serialResult->free();
// $serial = findCurrentNo($conn, $sfQuery);
mysqli_close($conn);

$mainPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
faxPDF($mainPdf);

//print copy tos 

for($i = 0; $i < 2; $i++){
    if($extraComp[$i]){
        $company = $extraComp[$i];
        $attention = $extraname[$i];
        $fax = $extraFax[$i];

        $copyPdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        faxPDF($copyPdf);
    }
}