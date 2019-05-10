<?php
include("info.php");

$id = 0;
if(array_key_exists('id', $_GET)) $id = intval($_GET['id']);

//$conn = new mysqli($host, $user, $password, $defaultTbl);
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
    <link rel="stylesheet" href="../assets/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/mainStyle.css">
    <title>Transmittal Form</title>
    <style>
        .mid{
            width: 90%;
            margin: auto;
        }
        .sml{
            width: 50%;
            margin: auto;
        }
    </style>
</head>

<body>
<div class="container">
    <h2 class="top-sm">Transmittal Form</h2>
<div class="form-wrapper">
    <form>
        <div class="form-group row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="jobNumber" placeholder="Job Number"></div>
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="clientCode" placeholder="Client Code"></div>
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="clientNumber" placeholder="Client Number"></div>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <div class="row">
                    <label for="date" class="col-6">Date:</label>
                    <div class="col-6"><input type="date" class="form-control form-control-sm" name="date" id="date"></div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" name="company" placeholder="Company">
                <input type="text" class="form-control form-control-sm" name="addr1" placeholder="Address Line 1">
                <input type="text" class="form-control form-control-sm" name="addr2" placeholder="Address Line 2">
                <input type="text" class="form-control form-control-sm" name="city" placeholder="City">
                <div class="row">
                    <div class="col-2"><input type="text" class="form-control form-control-sm" name="state" placeholder="State"></div>
                    <div class="col-3"><input type="text" class="form-control form-control-sm" name="zip" placeholder="Zip"></div>
                </div>
                <input type="text" class="form-control form-control-sm" name="attention" placeholder="Attention">
            </div>
            <div class="col-md-6">
                <label for="project">Project</label>
                <textarea class="form-control d-flex align-items-stretch" id="project" name="project"></textarea>
            </div>
        </div>
        <div class="form-group row mid">
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="rBtn" id="atch" checked >
                    <label for="atch" class="form-check-label">Attached</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="rBtn" id="sepr" checked >
                    <label for="sepr" class="form-check-label">Under Separate Cover</label>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <label for="via" class="col-1">Via</label>
                    <div class="col-5">
                        <select class="form-control form-control-sm">
                            <option value="FED EXPRESS">FedEx</option>
                            <option value="HAND DELIVERED">Hand Delivered</option>
                            <option value="MAIL">Mail</option>
                            <option value="MESSENGER">Messenger</option>
                            <option value="PICK UP">Pick Up</option>
                            <option value="UPS">UPS</option>
                        </select>
                    </div>
                    <label>For the following items:</label>
                </div>
            </div>
        </div>
        <div class="form-group row sml" style="margin-bottom: 1rem;">
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="sdcheck" value="Shop Drawings" >
                <label for="sdcheck" class="form-check-label">Shop Drawings</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="pcheck" value="Prints" checked>
                <label for="pcheck" class="form-check-label">Prints</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="clcheck" value="Copy of letter" >
                <label for="clcheck" class="form-check-label">Copy of letter</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="cocheck" value="Change order" >
                <label for="cocheck" class="form-check-label">Change order</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="plcheck" value="Plans" >
                <label for="plcheck" class="form-check-label">Plans</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="speccheck" value="Specifications" >
                <label for="speccheck" class="form-check-label">Specifications</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="scheck" value="Samples" >
                <label for="scheck" class="form-check-label">Samples</label>
            </div><br>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items" id="othercheck" >
                <label for="othercheck" class="form-check-label" style="margin-right: 5px;">Other:</label>
                <input type="text" class="form-control form-control-sm" id="othertext" placeholder="(Please specify)">
            </div>
        </div>
        <div class="form-group row mid" id="addWrapper" style="margin-bottom: 1rem;">
            <div class="col-sm-2"><input type="text" class="form-control form-control-sm" name="copies" placeholder="Copies"></div>
            <div class="col-sm-2"><input type="text" class="form-control form-control-sm" name="date" placeholder="Date"></div>
            <div class="col-sm-2"><input type="text" class="form-control form-control-sm" name="number" placeholder="Number"></div>
            <div class="col-sm-6"><input type="text" class="form-control form-control-sm" name="descript" placeholder="Description"></div>
        </div>
        <div class="form-group row text-center">
            <button id="addNew" class="btn btn-primary btn-sm">Add New</button>
        </div>
        <div class="form-group row sml">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" placeholder="Insert comments/remarks"></textarea>
        </div>

    </form>
</div>
</div>

<script src="../assets/js/jquery-3.3.1.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script src="../assets/js/bootstrap.bundle.js"></script>
<script src="../assets/js/bootstrap.js"></script>

</body>
</html>

