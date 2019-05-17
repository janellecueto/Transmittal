<?php
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

echo "hello!<br>";
echo "jobNumber: $jobNumber<br>";
echo "clientCode: $clientCode<br>";
echo "date: ".$date->format("Y-m-d")."<br>";
echo "attention: $attention<br>";
echo "company: $company<br>";
echo "fax: $fax<br>";
echo "from: $from<br>";
echo "numPages: $numPages<br>";
echo "willFollow: $willFollow<br>";
echo "remarks: $remarks<br>";
echo "extraComp: ".implode(", ", $extraComp)."<br>";
echo "extraName: ".implode(", ", $extraName)."<br>";
echo "extraFax: ".implode(", ", $extraFax)."<br>";