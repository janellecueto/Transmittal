<?php
include("../assets/php/info.php");

//If an id is passed in, we are pre-filling out this form
$id = 0;
if(array_key_exists('id', $_GET)) $id = intval($_GET['id']);

$conn = new mysqli($host, $user, $password, $defaultTbl);
if($conn->errno){
    echo "<br>Error: ".$conn->error;
    exit();
}

$row = [];
//there should be a row corresponding to the id, we could check the old table but this is mostly for recent documents
//(disregard <type>TblOld)
if($id) {
    $query = "SELECT * FROM $defaultTbl.$transTbl WHERE Serialno = $id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    foreach($row as $key => $value){
        if($value == null || $value == "undefined"){
            $row[$key] = "";        //replace nulls with empty strings to pass to javascript
        }
    }
}
$conn->close();
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
        <!-- <form action="print.php" method="post"> -->
        <form id="transmittalForm">
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
                    <input type="text" class="form-control form-control-sm" id="attention" name="attention" placeholder="Attention" list="clientNames">
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
                    <input type="text" class="form-control form-control-sm" id="othertext" name="othertext" placeholder="(Please specify)">
                </div>
            </div>
            <div class="form-group mid" id="addWrapper" style="margin-bottom: 1rem;">
                <div class="row">
                    <div class="col-sm-2"><input type="text" class="form-control form-control-sm num" name="copies[]" placeholder="Copies"></div>
                    <div class="col-sm-2"><input type="date" class="form-control form-control-sm auto-date" name="dates[]"></div>
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
                        <td style="width: 25%;">
                            <input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" id="extraComp1" data-list="extraNames1">
                            <input type="hidden" name="extraCode[]" id="extraCode1">
                        </td>
                        <td style="width: 25%;">
                            <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames1" id="extraName1">
                            <datalist id="extraNames1"></datalist>
                        </td>
                        <td class="text-center"><input type="checkbox" class="form-check-input" name="trOnly[]" value="tr1"></td>
                        <td class="text-center"><input type="checkbox" class="form-check-input" name="copyLbl[]" value="lbl1"></td>
                        <td class="text-center"><input type="checkbox" class="form-check-input" name="copyEnv[]" value="env1"></td>
                    </tr>
                    <tr>
                        <th scope="row"></th>
                        <td style="width: 25%;">
                            <input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" id="extraComp2" data-list="extraNames2">
                            <input type="hidden" name="extraCode[]" id="extraCode1">
                        </td>
                        <td style="width: 25%;">
                            <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames2" id="extraName2">
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
                        <input type="checkbox" class="form-check-input" id="printLblMain" name="printLblMain">
                    </div>
                    <div class="form-inline row">
                        <label for="printEnvMain" class="col-3">Print Envelope</label>
                        <input type="checkbox" class="form-check-input" id="printEnvMain" name="printEnvMain">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group row">
                        <label for="signed" class="col-4 text-right">Signed</label>
                        <div class="col-8">
                            <input type="text" id="signed" name="signed" class="form-control form-control-sm" list="deiEmps" required>
                            <datalist id="deiList"></datalist>
                        </div>
                    </div>
                    <div class="row">
                        <a href="../" class="btn btn-secondary btn-sm" style="margin-left: auto;">Cancel</a>
                        <input type="submit" class="btn btn-primary btn-sm" style="margin-left: 5px;" value="Print">
                    </div>
                </div>
            </div>
            <input type="hidden" name="save" id="save" value="1"><!-- used for skipping save to db for copy tos -->
        </form>
    </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Sent to printer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="successBody">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="../" class="btn btn-primary">Exit to Transmittal Home</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="errorBody">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="../" class="btn btn-primary">Exit to Transmittal Home</a>
      </div>
    </div>
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
    $(".auto-date").val(fillDate());
    $(document).ready(fillDEI);

    //set variables for id and row, if there's an id, there's a row and we have to pre-fill the form with info in row
    let row = [];
    let id = 0;

    <?php
    if($id) {
        echo "id = $id;";
        echo "row = ".json_encode($row).";";
    }
    ?>

    if(id){
        $("#jobNumber").val(row["Jn"]);     //NOTE: DB table column names are slightly different than common naming
        $("#clientCode").val(row["Code"]);  //      conventions used throughout the program. Make sure to match the db table columns
        $("#clientNumber").val(row["Client num"]);
        $("#company").val(row["Company"]);
        $("#addr1").val(row["Addr1"]);
        $("#addr2").val(row["Addr2"]);
        $("#city").val(row["City"]);
        $("#state").val(row["State"]);
        $("#zip").val(row["Zip"]);
        $("#attention").val(row["Attention"]);
        $("#project").val(row["Project"]);
        if(row["Under separate cover"]) $("#sepr").attr("checked", true);
        $("#via").val(row["Via"]);
        if(row["Shop drawings"]) $("#sdcheck").attr("checked", true);
        if(row["Prints"]) $("#pcheck").attr("checked", true);
        if(row["Plans"]) $("#plcheck").attr("checked", true);
        if(row["Specifications"]) $("#speccheck").attr("checked", true);
        if(row["Samples"]) $("#scheck").attr("checked", true);
        if(row["Copy of letter"]) $("#clcheck").attr("checked", true);
        if(row["Change order"]) $("#cocheck").attr("checked", true);
        if(row["Other"]) {
            $("#othercheck").attr("checked", true);
            $("#othertext").val(row["What other"]);
        }
        document.getElementsByName("extraComp[]")[0].value = row["Copy to1"];
        document.getElementsByName("extraComp[]")[1].value = row["Copy to2"];
        //we need to add something for extraCode[], we don't store code but we need it for addrInfo
        document.getElementsByName("extraName[]")[0].value = row["Copy_1_who"];
        document.getElementsByName("extraName[]")[1].value = row["Copy_1_who"];
        $("#signed").val(row["Signed"]);

        for(var i = 1; i < 9; i++){
            if(!row["C"+i]) break;
            document.getElementsByName("copies[]")[i-1].value = row["C"+i];
            if(row["D"+i].includes("/")) document.getElementsByName("dates[]")[i-1].value = convertDate(row["D"+i]);
            else document.getElementsByName("dates[]")[i-1].value = row["D"+i];
            console.log(convertDate(row["D"+i]));
            document.getElementsByName("numbers[]")[i-1].value = (row["Nn"+i] ? parseInt(row["Nn"+i]) : "");
            document.getElementsByName("descriptions[]")[i-1].value = row["Des"+i];
            if(row["C"+(i+1)]){
                addNew();
            }
        }

        //NOTE: if we're just printing a copy of a previous transmittal, we don't need to re-enter data in the transmittal tbl
        $("#save").val("0");
    }

    $("#jobNumber").change(function(e){         //fill address inputs when job number changes
        e.preventDefault();
        $("#clientCode").val("");
        $("#clientNames").empty();              //empty out the datalist of client names before inserting new names
        addrFill($(this).val(), "jobNumber");
    });
    $("#clientCode").change(function(e){        //fill/change address inputs when client code changes
        e.preventDefault();
        $("#clientNames").empty();
        addrFill($(this).val(), "clientCode");
    });
    $(".extraComp").change(function(e){         //this fills out just the company name and the client list for the Copy To companies
        e.preventDefault();
        $("#"+$(this).attr("data-list")).empty();

        if($(this).attr("id") == "extraComp1"){
            $("#extraCode1").val($(this).val());
        } else {
            $("#extraCode2").val($(this).val());
        }

        copyToFill($(this), $(this).val());
    });

    $("#transmittalForm").submit(function(e){
        e.preventDefault();
        // sendTransmittal();
        var formData = $(this).serialize();
        formDate = formData.replace(/&?[^=]+=&|&[^=]+=$/g,'');
        console.log(formData);
        $.ajax({
            method: "POST",
            url: "print.php",
            data: formData,
        }).done(function(result){
                if(result.includes("Error:")){
                    $("#errorBody").html(result);
                    $("#errorModal").modal("show");
                }
                else{
                    console.log(result);
                    $("#successBody").html(result);
                    $("#successModal").modal("show");
                }
            });
    });

    $("form input, form textarea, form select").change(function(e){
        $("#save").val("1");
        console.log("save=1");
    })
    $("#attention").change(function(){
        if($(this).val().length < 5){
            signName($(this).val(), $("#clientCode").val());
        } 
    })
    $("#signed").change(function(){
        if($(this).val().length < 5){
            signName($(this).val(), "DEI");
        } 
    })


</script>
</body>
</html>

