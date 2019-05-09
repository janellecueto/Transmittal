<?php

//require_once("../../info.php");

//$startDate = "";
//if(array_key_exists('startDate', $_POST)) $startDate = $_POST['startDate'];
//$endDate = "";
//if(array_key_exists('endDate', $_POST)) $endDate = $_POST['endDate'];
//$jobNumber = "";
//if(array_key_exists('jobNumber', $_POST)) $jobNumber = $_POST['jobNumber'];
//$clientCode = "";
//if(array_key_exists('clientCode', $_POST)) $clientCode = $_POST['clientCode'];
//$signature = "";
//if(array_key_exists('signature', $_POST)) $signature = $_POST['signature'];
//$invoiceNo = "";
//if(array_key_exists('invoiceNo', $_POST)) $invoiceNo = $_POST['invoiceNo'];
//
$type = "";
//if(array_key_exists('type', $_POST)) $type = $_POST['type'];

$firstCol = "Serialno";
if($type === 3)
    $firstCol = "InvoiceNo";

//$conn = new mysqli($host, $user, $password, $defaultTbl);
//
//if($conn->errno){
//    echo "<br>Error: ".$conn->error;
//    exit();
//}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</body>
</html>