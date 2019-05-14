<?php
include("../assets/php/info.php");

$id = 0;
if(array_key_exists('id', $_GET)) $id = intval($_GET['id']);

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
    <link rel="stylesheet" href="../assets/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-grid.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/mainStyle.css">
    <title>Transmittal Form</title>
    <style>

        #addNew{margin: auto;}
    </style>
</head>

<body>
<div class="container">
    <h2 class="top-sm">Transmittal Form</h2>
<div class="form-wrapper">
    <form action="print.php" method="post">
        <div class="form-group row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="jobNumber" id="jobNumber" placeholder="Job Number"></div>
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="clientCode" id="clientCode" placeholder="Client Code"></div>
                    <div class="col-4"><input type="text" class="form-control form-control-sm" name="clientNumber" id="clientNumber" placeholder="Client Number"></div>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <div class="row">
                    <label for="date" class="col-6">Date:</label>
                    <div class="col-6">
                        <input type="date" class="form-control form-control-sm" name="date" id="date" value="<?php echo date('Y-m-d');?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" name="company" id="company" placeholder="Company">
                <input type="text" class="form-control form-control-sm" name="addr1" id="addr1" placeholder="Address Line 1">
                <input type="text" class="form-control form-control-sm" name="addr2" id="addr2" placeholder="Address Line 2">
                <input type="text" class="form-control form-control-sm" name="city" id="city" placeholder="City">
                <div class="row">
                    <div class="col-2"><input type="text" class="form-control form-control-sm" name="state" id="state" placeholder="State"></div>
                    <div class="col-3"><input type="text" class="form-control form-control-sm" name="zip" id="zip" placeholder="Zip"></div>
                </div>
                <input type="text" class="form-control form-control-sm" name="attention" placeholder="Attention" list="clientNames">
                <datalist id="clientNames"></datalist>
            </div>
            <div class="col-md-6">
                <label for="project">Project</label>
                <textarea class="form-control d-flex align-items-stretch" id="project" name="project"></textarea>
            </div>
        </div>
        <div class="form-group row mid">
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="rBtn" id="atch" value="Attached" checked >
                    <label for="atch" class="form-check-label">Attached</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" name="rBtn" id="sepr" value="Under separate cover">
                    <label for="sepr" class="form-check-label">Under Separate Cover</label>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <label for="via" class="col-1">Via</label>
                    <div class="col-5">
                        <select class="form-control form-control-sm" id="via" name="via">
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
                <input type="checkbox" class="form-check-input" name="items[]" id="sdcheck" value="Shop Drawings" >
                <label for="sdcheck" class="form-check-label">Shop Drawings</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="pcheck" value="Prints" checked>
                <label for="pcheck" class="form-check-label">Prints</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="clcheck" value="Copy of letter" >
                <label for="clcheck" class="form-check-label">Copy of letter</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="cocheck" value="Change order" >
                <label for="cocheck" class="form-check-label">Change order</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="plcheck" value="Plans" >
                <label for="plcheck" class="form-check-label">Plans</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="speccheck" value="Specifications" >
                <label for="speccheck" class="form-check-label">Specifications</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="scheck" value="Samples" >
                <label for="scheck" class="form-check-label">Samples</label>
            </div><br>
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="items[]" id="othercheck" value="Other">
                <label for="othercheck" class="form-check-label" style="margin-right: 5px;">Other:</label>
                <input type="text" class="form-control form-control-sm" id="othertext" placeholder="(Please specify)">
            </div>
        </div>
        <div class="form-group mid" id="addWrapper" style="margin-bottom: 1rem;">
            <div class="row">
                <div class="col-sm-2"><input type="text" class="form-control form-control-sm num" name="copies[]" placeholder="Copies"></div>
                <div class="col-sm-2"><input type="date" class="form-control form-control-sm" name="dates[]"></div>
                <div class="col-sm-2"><input type="text" class="form-control form-control-sm num" name="numbers[]" placeholder="Number"></div>
                <div class="col-sm-6"><input type="text" class="form-control form-control-sm" name="descriptions[]" placeholder="Description"></div>
            </div>
        </div>
        <div class="row text-center">
            <button type="button" id="addNew" class="btn btn-primary btn-sm">Add New</button>
        </div>
        <div class="form-group row sml">
            <label for="remarks">Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" placeholder="Insert comments/remarks"></textarea>
        </div>
        <div class="form-group row">
            <table class="table table-borderless table-sm">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Company</th>
                    <th scope="col">Name</th>
                    <th scope="col">Trans only</th>
                    <th scope="col">Print Label</th>
                    <th scope="col">Print Envelope</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">COPY TO</th>
                    <td style="width: 25%;"><input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" data-list="extraNames1"></td>
                    <td style="width: 25%;">
                        <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames1">
                        <datalist id="extraNames1"></datalist>
                    </td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="trOnly[]" value="tr1"></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="copyLbl[]" value="lbl1"></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="copyEnv[]" value="env1"></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td style="width: 25%;"><input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" data-list="extraNames2"></td>
                    <td style="width: 25%;">
                        <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames2">
                        <datalist id="extraNames2"></datalist>
                    </td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="trOnly[]" value="tr2"></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="copyLbl[]" value="lbl2"></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="copyEnv[]" value="env2"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group row">
            <div class="col">
                <div class="form-inline row">
                    <label for="dupl" class="col-3"># of Duplicates</label>
                    <input type="text" id="dupl" name="dupl" class="form-control form-control-sm">
                </div>
                <div class="form-inline row">
                    <label for="printLblMain" class="col-3">Print Label</label>
                    <input type="checkbox" class="form-check-input" id="printLblMain">
                </div>
                <div class="form-inline row">
                    <label for="printEnvMain" class="col-3">Print Envelope</label>
                    <input type="checkbox" class="form-check-input" id="printEnvMain">
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label for="signed" class="col-4 text-right">Signed</label>
                    <div class="col-8">
                        <input type="text" id="signed" class="form-control form-control-sm" list="deiEmps" required>
                        <datalist id="deiEmps"></datalist>
                    </div>
                </div>
                <div class="row">
                    <a href="../" class="btn btn-secondary btn-sm" style="margin-left: auto;">Cancel</a>
                    <input type="submit" class="btn btn-primary btn-sm" style="margin-left: 5px;" value="Print">
                </div>
            </div>
        </div>
    </form>
</div>
</div>

<script src="../assets/js/jquery-3.3.1.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script src="../assets/js/bootstrap.bundle.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/main.js"></script>
<script>

    $("#dupl").mask("#0"); //limit to 99 duplicates :)
    $(".num").mask("#");




    $("#addNew").click(function(){
        let $addWrapper = $('#addWrapper');
        let newRow = $("<div>");
        newRow.addClass("row");

        let copiesDiv = $("<div>");
        copiesDiv.addClass("col-sm-2");
        let copiesInput = $("<input>");
        copiesInput.addClass("form-control form-control-sm num");
        copiesInput.attr({
            type: "text",
            name: "copies[]",
            placeholder: "Copies"
        });
        copiesDiv.append(copiesInput);
        newRow.append(copiesDiv);

        let datesDiv = $("<div>");
        datesDiv.addClass("col-sm-2");
        let datesInput = $("<input>");
        datesInput.addClass("form-control form-control-sm");
        datesInput.attr({
            type: "date",
            name: "dates[]",
        });
        datesDiv.append(datesInput);
        newRow.append(datesDiv);

        let numbersDiv = $("<div>");
        numbersDiv.addClass("col-sm-2");
        let numbersInput = $("<input>");
        numbersInput.addClass("form-control form-control-sm num");
        numbersInput.attr({
            type: "text",
            name: "numbers[]",
            placeholder: "Number"
        });
        numbersDiv.append(numbersInput);
        newRow.append(numbersDiv);

        let descriptDiv = $("<div>");
        descriptDiv.addClass("col-sm-6");
        let descInput = $("<input>");
        descInput.addClass("form-control form-control-sm");
        descInput.attr({
            type: "text",
            name: "descriptions[]",
            placeholder: "Description"
        });
        descriptDiv.append(descInput);
        newRow.append(descriptDiv);

        $addWrapper.append(newRow);
    });


    $("#jobNumber").change(function(e){
        e.preventDefault();
        $("#clientCode").val("");
        $("#clientNames").empty();
        addrFill($(this).val(), "jobNumber");
    });
    $("#clientCode").change(function(e){
        e.preventDefault();
        $("#clientNames").empty();
        addrFill($(this).val(), "clientCode");
    });
    $(".extraComp").change(function(e){
        e.preventDefault();
        $("#"+$(this).attr("data-list")).empty();
        copyToFill($(this), $(this).val());
    });




</script>
</body>
</html>

