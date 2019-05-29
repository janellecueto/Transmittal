<?php
require_once("../assets/php/info.php");
include("../assets/php/current.php");
include_once("../label-envelope/printLabel.php");
include_once("../label-envelope/printEnvelope.php");

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

echo "hello!<br>";
echo "date: ".$date->format("Y-m-d")."<br>";
echo "jobNumber: $jobNumber<br>";
echo "clientCOde: $clientCode<br>";
echo "clientNumber: $clientNumber<br>";
echo "attention: $attention<br>";
echo "authBy: $authBy<br>";
echo "project: $project<br>";
echo "company: $company<br>";
echo "addr1: $addr1<br>";
echo "addr2: $addr2<br>";
echo "city: $city<br>";
echo "state: $state<br>";
echo "zip: $zip<br>";
echo "description: $description<br>";

echo "numSets: ".implode(", ", $numSets)."<br>";
echo "numSheets: ".implode(", ", $numSheets)."<br>";
echo "colored: ".implode(", ", $colored)."<br>";
echo "costs: ".implode(", ", $costs)."<br>";
echo "lineTotals: ".implode(", ", $lineTotals)."<br>";
echo "total: $total<br>";
