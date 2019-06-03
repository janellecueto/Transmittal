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
    $query = "SELECT * FROM $defaultTbl.$faxTbl WHERE Serialno = $id";
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
    <title>Fax Transmittal Form</title>
    <style>

    </style>
</head>
<body>
    <div class="container">
        <h2 class="top-sm">Fax Transmittal Form</h2>
        <div class="form-wrapper">
            <form id="faxForm">
                <div class="form-group row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-6"><input type="text" class="form-control form-control-sm" name="jobNumber" id="jobNumber" placeholder="Job Number"></div>
                            <div class="col-6"><input type="text" class="form-control form-control-sm" name="clientCode" id="clientCode" placeholder="Client Code"></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <label for="date" class="col-3">Date</label>
                            <div class="col-9">
                                <input type="date" class="form-control form-control-sm auto-date" name="date" id="date">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" id="attention" name="attention" placeholder="Attention" list="clientNames">
                        <datalist id="clientNames"></datalist>
                        <input type="text" class="form-control form-control-sm" id="company" name="company" placeholder="Company">
                        <input type="text" class="form-control form-control-sm" id="fax" name="fax" placeholder="Fax Number" required>
                        <input type="text" class="form-control form-control-sm" id="from" name="from" placeholder="From" list="deiList">
                        <datalist id="deiList"></datalist>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group row">
                            <label for="project" class="col-3">Project</label>
                            <div class="col-9">
                                <textarea id="project" name="project" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <label for="numPages" class="col-3">No. of Pages</label>
                            <div class="col-4"><input type="text" class="form-control form-control-sm" id="numPages" name="numPages" required></div>
                            <div class="col-5 form-check">
                                <input type="checkbox" id="willFollow" name="willFollow" class="form-check-input" value="1">
                                <label for="willFollow" class="form-check-label">Will Follow</label>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-3"><span class="description">(Including cover page)</span></div>
                        </div> -->
                    </div>
                </div>
                <div class="form-group mid">
                    <label for="remarks">Remarks</label>
                    <textarea id="remarks" name="remarks" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <table class="table table-borderless table-sm">
                        <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Company</th>
                            <th scope="col">Name</th>
                            <th scope="col">Fax Number</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">COPY TO</th>
                            <td>
                                <input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" data-list="extraNames1" data-fax="extraFax1" data-code="extraCode1">
                                <input type="hidden" name="extraCode[]" id="extraCode1">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames1">
                                <datalist id="extraNames1"></datalist>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" id="extraFax1" name="extraFax[]"></td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <input type="text" class="form-control form-control-sm extraComp" name="extraComp[]" data-list="extraNames2" data-fax="extraFax2" data-code="extraCode2">
                                <input type="hidden" name="extraCode[]" id="extraCode2">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="extraName[]" list="extraNames2">
                                <datalist id="extraNames2"></datalist>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" id="extraFax2" name="extraFax[]"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group row">
                    <button type="button" class="btn btn-secondary" style="margin-left: auto;" onclick="location.href='../';">Cancel</button>
                    <input type="submit" class="btn btn-primary" style="margin-left: 5px;" value="Print">
                    <button type="button" class="btn btn-primary" style="margin-left: 5px;" disabled>Send Fax</button>
                    
                </div>
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
        $("#jobNumber").val(row['Jn']);
        $("#clientCode").val(row['Code']);
        $("#company").val(row['Company']);
        $("#project").val(row['Project']);
        $("#attention").val(row['Attention']);
        $("#fax").val(row['FaxNumber']);
        $("#remarks").val(row['Remarks']);
        $("#numPages").val(row['NumberPages']);
        $("#from").val(row['Signed']);
    }



    $("#jobNumber").change(function(e){
        e.preventDefault();
        $("#clientNames").empty();
        faxFill($(this).val(), "jobNumber");
    });
    $("#clientCode").change(function(e){
        e.preventDefault();
        $("#clientNames").empty();
        faxFill($(this).val(), "clientCode");
    })
    $(".extraComp").change(function(e){
        e.preventDefault();
        $("#"+$(this).attr("data-list")).empty();
        $("#"+$(this).attr("data-code")).val($(this).val());
        copyToFill($(this), $(this).val());
    });

    $("#faxForm").submit(function(e){
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

    $("#attention").change(function(){
        if($(this).val().length < 5 && $("#clientCode").val()){
            signName($(this), $(this).val(), $("#clientCode").val());
        } 
    });
    $("#from").change(function(){
        if($(this).val().length < 5){
            signName($(this), $(this).val(), "DEI");
        } 
    });

</script>
</body>
</html>