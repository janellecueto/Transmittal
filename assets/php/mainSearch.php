<?php

$startDate = "";
if(array_key_exists('startDate', $_POST)) $startDate = $_POST['startDate'];
$endDate = "";
if(array_key_exists('endDate', $_POST)) $endDate = $_POST['endDate'];
$jobNumber = "";
if(array_key_exists('jobNumber', $_POST)) $jobNumber = $_POST['jobNumber'];
$clientCode = "";
if(array_key_exists('clientCode', $_POST)) $clientCode = $_POST['clientCode'];
$signature = "";
if(array_key_exists('signature', $_POST)) $signature = $_POST['signature'];
$invoiceNo = "";
if(array_key_exists('invoiceNo', $_POST)) $invoiceNo = $_POST['invoiceNo'];

$type = $_POST['type'];