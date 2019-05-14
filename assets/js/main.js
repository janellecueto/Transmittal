function addrFill(value, flag){
    $.ajax({
        type: "GET",
        url: "../assets/php/fillAddress.php",
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

function copyToFill(input, value){
    $.ajax({
        type: "GET",
        url: "../assets/php/fillAddress.php",
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