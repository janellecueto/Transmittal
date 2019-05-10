<?php

//include("../../info.php");
include("info.php");

/**  Get and Set initial values */

//this flag indicates whether or not we're coming from a form submission. if it is false, assume we are only looking for the last item
$q = false;

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
if(array_key_exists('type', $_GET)) $type = intval($_GET['type']);

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

    /** remove trailing " AND " or " WHERE " */
    $mainQuery = preg_replace('/\W\w+\s*(\W*)$/', '$1', $mainQuery);

    /** include records from the past by union with search from *TblOld */
    $oldQuery = str_replace("$tableName", "$tableNameOld", $mainQuery);
    $mainQuery .= " UNION $oldQuery";
}
/** if q is passed in (submit form from index.html) then we limit by 1000, otherwise assume last */
$mainQuery .= " ORDER BY $firstCol";
if($q){
    $mainQuery .= " DESC LIMIT 1000";
}
else{
    $mainQuery .= " DESC LIMIT 1";
}

$result = $conn->query($mainQuery);
$fields = $result->fetch_fields();

$conn->close();

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
    <style>

        a{color: white;}
        tr.clicked{
            background-color: rgba(153,192,249,0.75);
        }

    </style>
</head>
<body>

    <div class="container">
        <h2 class="top-sm">Search Results</h2>
        <div class="table-wrapper">
            <table class="table table-hover table-bordered">
                <thead class="sticky-top">
                <tr>
                    <th scope="col"><?php echo $firstCol; ?></th>
                    <th scope="col">Date</th>
                    <th scope="col">Jn</th>
                    <th scope="col">Code</th>
                    <th scope="col">Company</th>
                    <th scope="col">Project</th>
                </tr>
                </thead>
                <tbody>
                <?php

                while($r = $result->fetch_array()){
                    echo "<tr class='clickable' data-id='$r[0]'>\n";
                    echo "<td>$r[0]</td>\n";
                    echo "<td>".$r['Date']."</td>\n";
                    echo "<td>".$r['Jn']."</td>\n";
                    echo "<td>".$r['Code']."</td>\n";
                    echo "<td>".$r['Company']."</td>\n";
                    echo "<td>".$r['Project']."</td>\n";
                    echo "</tr>";
                }
                ?>
                </tbody>

            </table>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <a class="btn btn-secondary" href="../../">Back</a>
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#searchModal">New Search</a>
            </div>
            <div class="col text-right">
                <button class="btn btn-primary" id="okBtn" style="width: 110px;">Ok</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search Bill Plots</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="type" value="3">
                        <div class="form-group row">
                            <label for="pbStart" class="col-sm-3">Start Date:</label>
                            <div class="col-sm-9">
                                <input type="date" id="pbStart" name="startDate" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pbEnd" class="col-sm-3">End Date:</label>
                            <div class="col-sm-9">
                                <input type="date" id="pbEnd" name="endDate" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pbJn" class="col-sm-3">Job Number:</label>
                            <div class="col-sm-9">
                                <input type="text" id="pbJn" name="jobNumber" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pbCode" class="col-sm-3">Client Code:</label>
                            <div class="col-sm-9">
                                <input type="text" id="pbCode" name="clientCode" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <?php
                            if ($type == 3){
                                echo <<< END
                            <label for="pbInvoice" class="col-sm-3">InvoiceNo:</label>
                            <div class="col-sm-9">
                                <input type="text" id="pbInvoice" name="invoiceNo" class="form-control form-control-sm">
                            </div>
END;
                            }
                            else {
                                echo <<< END
                            <label for="pbInvoice" class="col-sm-3">Signature:</label>
                            <div class="col-sm-9">
                                <input type="text" id="pbInvoice" name="signature" class="form-control form-control-sm">
                            </div>
END;
                            }
                            ?>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Search">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>



    <script src="../js/jquery-3.3.1.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/bootstrap.js"></script>
<script>
    var selected;
    var type = '<?php echo $type; ?>';

    $(".clickable").click(function(){
        if(selected){
            selected.toggleClass("clicked");
        }
        selected = $(this);
        selected.toggleClass("clicked");
    });

    $("#okBtn").click(function(){
        if(type === '1'){
            location.href = "../../transmittal/index.php?id=" + $('.clicked').attr("data-id");
        } else if(type === '2'){

        } else{

        }
    });



</script>
</body>
</html>