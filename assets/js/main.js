/***********************************************************************************************************************
 *  Functions below this line are called on document ready for filling in and initializing values
 */

/**
 * insets the sheet size color and no color costs in the sheetSizes object for billplot/index.html
 * on document ready
 */
function setPrices(){
    $.ajax({
        type: "GET",
        url: "plottingPrices.php",
        data: {
            ret: 1
        },
        success: function(result){
            let data = JSON.parse(result);
            let index = 0;
            for(var key in sheetSizes){
                var colorCost = parseFloat(data[index][3].substring(1));
                var noColorCost = parseFloat(data[index+1][3].substring(1));
                sheetSizes[key].push(colorCost);
                sheetSizes[key].push(noColorCost);
                index += 2;
            }
            console.log(sheetSizes);
        },
        error: function(result){
            alert("error: "+result);
        }
    });
}

/***********************************************************************************************************************
 * Functions below are used on click for adding new rows
 */


/**
 * This onClick function adds a new item description row in transmittal/index
 */
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

/**
 * This onClick function adds a new bill plot row in billplot/index
 */
let bpRowCount = 0;     //this counter will indicate the index in each array that the "Colored" checkboxes
                        //align with
$("#addRow").click(function(){
    let wrapper = $("#rowWrapper");
    let newRow = $("<tr>");
    bpRowCount++;

    let setsTd = $("<td>");
    let setsIn = $("<input>");
    setsIn.addClass("form-control form-control-sm num");
    setsIn.attr({
        type: "text",
        name: "numSets[]"
    });
    setsIn.mask("#");
    setsIn.change(updateLineTotal);
    setsTd.append(setsIn);
    newRow.append(setsTd);

    let sheetsTd = $("<td>");
    let sheetsIn = $("<input>");
    sheetsIn.addClass("form-control form-control-sm num");
    sheetsIn.attr({
        type: "text",
        name: "numSheets[]"
    });
    sheetsIn.mask("#");
    sheetsIn.change(updateLineTotal);
    sheetsTd.append(sheetsIn);
    newRow.append(sheetsTd);

    let sizeTd = $("<td>");
    let sizeIn = $("<select>");
    sizeIn.addClass("form-control form-control-sm");
    sizeIn.attr("name", "sheetSizes[]");
    for (var key in sheetSizes){
        sizeIn.append("<option value=\""+key+"\">"+sheetSizes[key][0]+"</option>");
    }
    sizeIn.change(updateLineTotal);
    sizeTd.append(sizeIn);
    newRow.append(sizeTd);

    let mediaTd = $("<td>");
    let mediaIn = $("<select>");
    mediaIn.addClass("form-control form-control-sm");
    mediaIn.attr("name", "mediaType[]");
    mediaIn.append("<option value=\"paper\">Paper</option>");
    mediaTd.append(mediaIn);
    newRow.append(mediaTd);

    let colorTd = $("<td>");
    colorTd.addClass("text-center");
    let colorIn = $("<input>");
    colorIn.addClass("form-check-input");
    colorIn.attr({
        type: "checkbox",
        name: "colored[]",
        value: bpRowCount
    });
    colorIn.change(updateLineTotal);
    colorTd.append(colorIn);
    newRow.append(colorTd);

    let costTd = $("<td>");
    let costIn = $("<input>");
    costIn.addClass("form-control form-control-sm money cost-money");
    costIn.attr({
        type: "text",
        name: "costs[]",
        placeholder: "$0.00",
        readonly: true
    });
    costTd.append(costIn);
    newRow.append(costTd);

    let lineTd = $("<td>");
    let lineIn = $("<input>");
    lineIn.addClass("form-control form-control-sm money total-money");
    lineIn.attr({
        type: "text",
        name: "lineTotals[]",
        placeholder: "$0.00",
        readonly: true
    });
    lineTd.append(lineIn);
    newRow.append(lineTd);

    wrapper.append(newRow);

});


/***********************************************************************************************************************
 * Functions below are called on change for auto filling client addresses
 */

/**
 *
 * @param {*} value - the job number or client code provided
 * @param {*} flag - flag denoting "jobNumber" or "clientCode"
 * @param {*} url - relative path to fillAddresses.php based on the calling location
 */
function addrFill(value, flag, url="../assets/php/fillAddress.php"){
    $.ajax({
        type: "GET",
        url: url,
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
                $("#project").val(projStr);
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
 * @param {*} url - relative path to fillAddresses.php based on the calling location
 */
function copyToFill(input, value, url="../assets/php/fillAddress.php"){
    $.ajax({
        type: "GET",
        url: url,
        data: {
            value: value,
            flag: "clientCode"
        },
        success: function(result){
            let data = JSON.parse(result);
            input.val(data["company"]);
            let clnames = $("#"+input.attr("data-list"));
            // alert(input.attr("data-list"));
            if(input.attr("data-fax")){
                $("#"+input.attr("data-fax")).val(data["fax"]);
            }
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
 * @param {*} url - relative path to fillAddresses.php based on the calling location
 */
function faxFill(value, flag, url="../assets/php/fillAddress.php"){
    $.ajax({
        type: "GET",
        url: url,
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




/***********************************************************************************************************************
 * Functions below this line are specific to certain pages, as specified in function descriptions
 */
/**
 *  In billplot/index: every time a line item changes, update the line total, cost, and bill total
 */
function updateLineTotal(){
    let parent = $(this).closest("tr");     //find parent row

    let cost = parent.find(".cost-money");      //cost / sheet element
    let lineTotal = parent.find(".total-money");    //line total element

    let sets = parent.find("td:first-child").find("input").val();       //value of Number of Sets input element
    let sheets = parent.find("td:nth-child(2)").find("input").val();    //value of Sheets per Set input element

    let setsBySheets = (sets && sheets) ? parseInt(sets) * parseInt(sheets) : 0;        //multiply both vals if given else 0

    let sheetSize = parent.find("td:nth-child(3)").find("select").val();
    // console.log(parent.find("td:nth-child(3)").find("select").val());

    //NOTE: media type is always paper :/ we may remove this column in the future which means the following index
    //      would need to be changed
    let price = 0;
    // console.log(parent.find("td:nth-child(5)").find("input").is(":checked"));
    if(parent.find("td:nth-child(5)").find("input").is(":checked")) {
        price = sheetSizes[sheetSize][1];
    } else {
        price = sheetSizes[sheetSize][2];
    }
    cost.val("$" + price.toFixed(2));       //always use 2 decimals for dollar values
    // console.log(setsBySheets);
    lineTotal.val("$" + (setsBySheets * price).toFixed(2));
    updateBillTotal();
}

/**
 * Helper function for updateLineTotal for updating the Bill Total when items get changed
 */
function updateBillTotal(){
    let billTotal = $("#total");
    let running = 0;
    $("#rowWrapper > tr").each(function(){
        var lineTotal = parseFloat($(this).find(".total-money").val().substring(1));
        running += lineTotal;
        // console.log(lineTotal);
    });
    // console.log(running);
    billTotal.val("$"+running.toFixed(2));
}

