<?php

require_once("../../info.php");

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
//
$type = "";
//if(array_key_exists('type', $_POST)) $type = $_POST['type'];

$firstCol = "Serialno";
if($type === 3)
    $firstCol = "InvoiceNo";

$conn = new mysqli($host, $user, $password, $defaultTbl);

if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap-reboot.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-grid.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/mainStyle.css">
    <title>Search Results</title>
</head>
<body>

    <div class="container">
        <h2>Search Results</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><?php echo $firstCol; ?></th>
                <th scope="col">Date</th>
                <th scope="col">Jn</th>
                <th scope="col">Code</th>
                <th scope="col">Company</th>
                <th scope="col">Project</th>
            </tr>
            </thead>

        </table>
    </div>


    <script src="../js/jquery-3.3.1.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.js"></script>
</body>
</html>