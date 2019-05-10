<?php

include("../../info.php");
//$transTbl = "trans";
//$transTblOld = "trans91_18";
//$faxTbl = "faxtr";
//$faxTblOld = "faxtr94_17";
//$pbillTbl = "pbill";
//$pbillTblOld = "pbill01_18";

/**  Get and Set initial values */

//these are flags indicating whether or not we pass in search constraints and for determining if we're looking for the last item
$q = false;
$last = false;

$startDate = "";
if(array_key_exists('startDate', $_POST)) {$startDate = $_POST['startDate']; $q = true; }
$endDate = "";
if(array_key_exists('endDate', $_POST)) {$endDate = $_POST['endDate']; $q = true; }
$jobNumber = "";
if(array_key_exists('jobNumber', $_POST)) {$jobNumber = $_POST['jobNumber']; $q = true; }
$clientCode = "";
if(array_key_exists('clientCode', $_POST)) {$clientCode = $_POST['clientCode']; $q = true; }
$signature = "";
if(array_key_exists('signature', $_POST)) {$signature = $_POST['signature']; $q = true; }
$invoiceNo = "";
if(array_key_exists('invoiceNo', $_POST)) {$invoiceNo = $_POST['invoiceNo']; $q = true; }

$type = 0;
if(array_key_exists('type', $_POST)) $type = intval($_POST['type']);

$tableName = $tableNameOld = "";
$firstCol = "Serialno";

switch($type){
    case 1:
        $tableName = $transTbl;
        $tableNameOld = $transTblOld;
        break;
    case 2:
        $tableName = $faxTbl;
        $tableNameOld = $faxTblOld;
        break;
    case 3:
        $tableName = $pbillTbl;
        $tableNameOld = $pbillTblOld;
        $firstCol = "InvoiceNo";
        break;
    default:
        $tableName = $transTbl;
        $tableNameOld = $transTblOld;
        $firstCol = "Serialno";
}

$conn = new mysqli($host, $user, $password, $defaultTbl);

if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

/** @var  $mainQuery | build string for SQL query */
$mainQuery = "SELECT * FROM $tableName";

if ($q){
    $mainQuery .= " WHERE ";

    /** Add WHERE clause */
    if($startDate){
        $mainQuery .= "DATE(Date) >= DATE('";
        $d1 = new DateTime($startDate);
        $mainQuery .= $d1->format('Y-m-d');
        $mainQuery .= "') AND ";
    }
    if($endDate){
        $mainQuery .= "DATE(Date) <= DATE('";
        $d1 = new DateTime($endDate);
        $mainQuery .= $d1->format('Y-m-d');
        $mainQuery .= "') AND ";
    }
    if($jobNumber){
        $mainQuery .= "Jn LIKE '$jobNumber%' AND ";
    }
    if($clientCode){
        $mainQuery .= "Code = '$clientCode' AND ";
    }
    if($signature){
        $mainQuery .= "Signed = '$signature' AND ";
    }
    if($invoiceNo){
        $mainQuery .= "InvoiceNo = '$invoiceNo' AND";
    }

    /** remove trailing " AND " */
    $mainQuery = preg_replace('/\W\w+\s*(\W*)$/', '$1', $mainQuery);

    /** include records from the past by union with search from *TblOld */
    $oldQuery = str_replace("$tableName", "$tableNameOld", $mainQuery);
    $mainQuery .= " UNION $oldQuery";

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
        <?php
        if($q){
            echo "$mainQuery<br>$tableName<br>type = $type<br>$transTbl";
        }
        ?>
    </div>


    <script src="../js/jquery-3.3.1.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.js"></script>
</body>
</html>