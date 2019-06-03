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
    $query = "SELECT * FROM $defaultTbl.$pbillTbl WHERE InvoiceNo = $id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    foreach($row as $key => $value){
        if($value == null){
            $row[$key] = "";        //replace nulls with empty strings to pass to javascript
        }
        if($value == "undefined"){
            $row[$key] = "";
        }
    }
}
$conn->close();

$_GET['ret'] = 2;
$sheets = include("plottingPrices.php");
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
    <title>Bill Plot Form</title>
    <style>

    </style>
</head>
<body>
    <div class="container">
        <h2 class="top-sm">Plotting Set Biller Form</h2>
        <div class="form-wrapper">
            <form id="billForm">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="row">
                            <label for="date" class="col-sm-3">Date</label>
                            <div class="col-sm-9"><input type="date" id="date" name="date" class="form-control form-control-sm auto-date"></div>
                        </div>
                        <div class="row">
                            <label for="jobNumber" class="col-sm-3">Job Number</label>
                            <div class="col-sm-9"><input type="text" id="jobNumber" name="jobNumber" class="form-control form-control-sm" required></div>
                        </div>
                        <div class="row">
                            <label for="clientCode" class="col-sm-3">Client Code</label>
                            <div class="col-sm-9"><input type="text" id="clientCode" name="clientCode" class="form-control form-control-sm"></div>
                        </div>
                        <div class="row">
                            <label for="clientNumber" class="col-sm-3">Client Number</label>
                            <div class="col-sm-9"><input type="text" id="clientNumber" name="clientNumber" class="form-control form-control-sm"></div>
                        </div>
                        <div class="form-group row">
                            <label for="attention" class="col-sm-3">Attention</label>
                            <div class="col-sm-9">
                                <input type="text" id="attention" name="attention" class="form-control form-control-sm" list="clientNames">
                                <datalist id="clientNames"></datalist>
                            </div>
                        </div>
                        <div class="row">
                            <label for="authBy" class="col-sm-3">Authorized by</label>
                            <div class="col-sm-9"><input type="text" id="authBy" name="authBy" class="form-control form-control-sm"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="project" class="col-sm-3">Project</label>
                            <div class="col-sm-9"><input type="text" id="project" name="project" class="form-control form-control-sm"></div>
                        </div>
                        <div class="row">
                            <label for="company" class="col-sm-3">Company</label>
                            <div class="col-sm-9"><input type="text" id="company" name="company" class="form-control form-control-sm" required></div>
                        </div>
                        <div class="row">
                            <label for="addr1" class="col-sm-3">Address 1</label>
                            <div class="col-sm-9"><input type="text" id="addr1" name="addr1" class="form-control form-control-sm" required></div>
                        </div>
                        <div class="row">
                            <label for="addr2" class="col-sm-3">Address 2</label>
                            <div class="col-sm-9"><input type="text" id="addr2" name="addr2" class="form-control form-control-sm"></div>
                        </div>
                        <div class="row">
                            <label for="city" class="col-sm-3">City</label>
                            <div class="col-sm-9"><input type="text" id="city" name="city" class="form-control form-control-sm" required></div>
                        </div>
                        <div class="row">
                            <label for="state" class="col-sm-3">State</label>
                            <div class="col-3"><input type="text" id="state" name="state" class="form-control form-control-sm" required></div>
                            <label for="zip" class="col-3">Zip</label>
                            <div class="col-3"><input type="text" id="zip" name="zip" class="form-control form-control-sm" required></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea id="description" name="description" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <table class="table table-borderless table-sm">
                        <thead>
                        <tr>
                            <th scope="col">Number of Sets</th>
                            <th scope="col">Sheets per Set</th>
                            <th scope="col">Sheet size</th>
                            <th scope="col">Media Type</th>
                            <th scope="col">Colored</th>
                            <th scope="col">Cost/Sheet</th>
                            <th scope="col">Line Total</th>
                        </tr>
                        </thead>
                        <tbody id="rowWrapper">
                        <tr>
                            <td><input type="text" name="numSets[]" class="form-control form-control-sm num" id="sets1"></td>
                            <td><input type="text" name="numSheets[]" class="form-control form-control-sm num" id="sheets1"></td>
                            <td>
                                <select name="sheetSizes[]" class="form-control form-control-sm select-size">
                                    <option value="24 x 36">24 x 36</option>
                                    <option value="30 x 42">30 x 42</option>
                                    <option value="36 x 48">36 x 48</option>
                                    <option value="8.5 x 11">8.5 x 11</option>
                                    <option value="11 x 17">11 x 17</option>
                                    <option value="12 x 18">12 x 18</option>
                                    <option value="15 x 21">15 x 21</option>
                                    <option value="18 x 24">18 x 24</option>
                                    <option value="22 x 34">22 x 34</option>
                                </select>
                            </td>
                            <td>
                                <select name="mediaType[]" class="form-control form-control-sm">
                                    <option value="paper">Paper</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" id="color1" name="colored[]" value="0">
                            </td>
                            <td><input type="text" name="costs[]" class="form-control form-control-sm money cost-money" placeholder="$0.00" readonly></td>
                            <td><input type="text" name="lineTotals[]" class="form-control form-control-sm money total-money" placeholder="$0.00" readonly></td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td><button type="button" id="addRow" class="btn btn-primary btn-sm" style="margin: auto;">Add Row</button></td>
                            <th colspan="5" scope="row" class="text-right">Bill Total</th>
                            <td><input type="text" name="total" id="total" class="form-control form-control-sm money" placeholder="$0.00" readonly></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-group row">
                    <div class="col-md-8">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#plotPrices">View Plotting Prices</button>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <button type="button" class="btn btn-secondary" style="margin-left: auto;" onclick="location.href='../';">Cancel</button>
                            <input type="submit" value="Print" class="btn btn-primary" style="margin-left: 5px;">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="save" id="save" value="1">
            </form>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="plotPrices">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Current Plotting Prices</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe class="plotting-iframe" src="./plottingPrices.php"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
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
    $(".auto-date").val(fillDate());
    let sheetSizes = {
        "24 x 36": ["24 x 36"],
        "30 x 42": ["30 x 42"],
        "36 x 48": ["36 x 48"],
        "8.5 x 11": ["8.5 x 11"],
        "11 x 17": ["11 x 17"],
        "12 x 18": ["12 x 18"],
        "15 x 21": ["15 x 21"],
        "18 x 24": ["18 x 24"],
        "22 x 34": ["22 x 34"]
    };

    let row = [];
    let sheets = [];
    let id = 0;

    <?php
    if($id) {
        echo "id = $id;";
        echo "row = ".json_encode($row).";";
    }
    if($sheets){
        echo "sheets = ".json_encode($sheets).";";
    }
    ?>
    var index = 0;
    for(var key in sheetSizes){
        var colorCost = parseFloat(sheets[index][3].substring(1));
        var noColorCost = parseFloat(sheets[index+1][3].substring(1));
        sheetSizes[key].push(colorCost);
        sheetSizes[key].push(noColorCost);
        index += 2;
    }
    console.log(sheetSizes);


    //set variables for id and row, if there's an id, there's a row and we have to pre-fill the form with info in row


$(document).ready(function(){
    if(id){
        $("#jobNumber").val(row['Jn']);
        $("#clientCode").val(row['Code']);
        $("#clientNumber").val(row['Client_num']);
        $("#attention").val(row['Attn']);
        $("#company").val(row['Company']);
        $("#addr1").val(row['Addr1']);
        $("#addr2").val(row['Addr2']);
        $("#city").val(row['City']);
        $("#state").val(row['State']);
        $("#zip").val(row['Zip']);
        $("#authBy").val(row['RequestedBy']);
        $("#project").val(row['Project']);
        $("#description").val(row['Description']);

        for(var i = 1; i < 5; i++){
            if(!row["Sets"+i]) break;
            $("input[name='numSheets[]']:nth-child("+(i-1)+")").bind("rowLoaded", updateLineTotal); 
            $("input[name='numSheets[]']:nth-child("+(i)+")").bind("rowLoaded",updateLineTotal);  //need to bind updateLineTotal to an element
            document.getElementsByName("numSets[]")[i-1].value = parseInt(row["Sets"+i]);           //for $(this)
            document.getElementsByName("numSheets[]")[i-1].value = parseInt(row["Copies"+i]);
            document.getElementsByName("sheetSizes[]")[i-1].value = row["Size"+i];
            if(row["Color"+i] === "Y") document.getElementsByName("colored[]")[i-1].checked = true;
            document.getElementsByName("costs[]")[i-1].value = row["Cost"+i];
            
            if(row["Sets"+(i+1)]) addRow();
            $("input[name='numSheets[]']:nth-child("+(i)+")").trigger("rowLoaded");        
            $("input[name='numSheets[]']:nth-child("+(i-1)+")").trigger("rowLoaded");           //idk. for some reason i need to do both for when there are
        }                                                                                       //multiple rows being loaded in
        // $("input[name='numSheets[]']:nth-child("+(i-1)+")").trigger("rowLoaded");        
        $("#total").val(row['BillTotal']);
        $("#save").val("0");
    }
});


    $("input.num").mask("#");

    $("#sets1").change(updateLineTotal);
    $("#sheets1").change(updateLineTotal);
    $(".select-size").change(updateLineTotal);
    $("#color1").change(updateLineTotal);

    $("#jobNumber").change(function(e){
        e.preventDefault();
        $("#clientNames").empty();
        addrFill($(this).val(), "jobNumber");
    });
    $("#clientCode").change(function(e){
        e.preventDefault();
        $("#clientNames").empty();
        addrFill($(this).val(), "clientCode");
    });

    $("#billForm").submit(function(e){
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
    });

    $("#attention").change(function(e){
        
    })
    $("#attention").change(function(){
        if($(this).val().length < 5 && $("#clientCode").val()){
            signName($(this), $(this).val(), $("#clientCode").val());
            signName($("#authBy"), $(this).val(), $("#clientCode").val());
        } else {
            $("#authBy").val($(this).val());
        }
    })


    
</script>
</body>
</html>