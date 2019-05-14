/**
 * These functions are responsible for filling address fields using fillAddress.php
 * 
 */

 /**
  * 
  * @param {*} value - the job number or client code provided
  * @param {*} flag - flag denoting "jobNumber" or "clientCode"
  */
function addrFill(value, flag){
    $.ajax({
        type: "GET",
        url: "../php/fillAddress.php",
        data: {
            value: value,
            flag: flag
        },
        success: function(result){
            let data = JSON.parse(result);
            if(flag === "jobNumber"){
                $("#clientCode").val(data["clientCode"]);
                $("#clientNumber").val(data["clientNumber"]);

                let projStr = data["jn1"];
                if(data["jn2"]) projStr += data["jn2"];
                $("#project").text(projStr);
            }
            $("#company").val(data["company"]);
            $("#addr1").val(data["addr1"]);
            $("#addr2").val(data["addr2"]);
            $("#city").val(data["city"]);
            $("#state").val(data["state"]);
            $("#zip").val(data["zip"]);

            let clnames = $("#clientNames");
            data["names"].forEach(function(item){
                var opt = $("<option>");
                opt.val(item).text(item);
                clnames.append(opt);
             });

        },
        error: function(result){
            alert("error: "+result);
        }
    })
}

/**
 * 
 * @param {*} input jQuery <input> element
 * @param {*} value client code provided
 */
function copyToFill(input, value){
    $.ajax({
        type: "GET",
        url: "../php/fillAddress.php",
        data: {
            value: value,
            flag: "clientCode"
        },
        success: function(result){
            let data = JSON.parse(result);
            input.val(data["company"]);
            let clnames = $("#"+input.attr("data-list"));
            alert(input.attr("data-list"));
            data["names"].forEach(function(item){
                var opt = $("<option>");
                opt.val(item).text(item);
                clnames.append(opt);
            });
        },
        error: function(result){
            alert("error: "+result);
        }
    })
}

/**
 * 
 * @param {*} value - job number or client code provided
 * @param {*} flag - flag denoting "jobNumber" or "clientCode" (same usage as in fillAddr)
 */
function faxFill(value, flag){
    $.ajax({
        type: "GET",
        url: "../php/fillAddress.php",
        data: {
            value: value,
            flag: flag
        },
        success: function(result){
            let data = JSON.parse(result);
            if(flag === "jobNumber"){
                $("#clientCode").val(data["clientCode"]);

                let projStr = data["jn1"];
                if(data["jn2"]) projStr += data["jn2"];
                $("#project").text(projStr);
            }
            $("#company").val(data["company"]);
            $("#fax").val(data["fax"]);

            let clnames = $("#clientNames");
            data["names"].forEach(function(item){
                var opt = $("<option>");
                opt.val(item).text(item);
                clnames.append(opt);
             });
        }
    });
}