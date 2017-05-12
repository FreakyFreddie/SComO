/* Open the sidenav */
function openNav(defbestelnummer, defbesteldatum, totaalkost) {
    //fill in table data target urls
    //$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
    //$("#displayprojectorder").attr("data-url", "AJAX/adminDisplayProjectOrderRequest.php?id=" + id);

    document.getElementById("sidepanel").style.width = "83.3333%";
    document.getElementById("sidepanel").style.padding= "0px 10px 0px 10px";

    //clear old project
    $("#defbestelnummer").empty();
    $("#defbesteldatum").empty();

    //fill in new info
    $("#defbestelnummer").append(defbestelnummer);
    $("#defbesteldatum").append(defbesteldatum);

    //remove old data from table
    $('#displayfinalorderproducts').bootstrapTable('removeAll');

    //destroy table
    $('#displayfinalorderproducts').bootstrapTable('destroy');

    //add data to table
    $('#displayfinalorderproducts').bootstrapTable({
        url: 'AJAX/adminDisplayFinalOrderProductsRequest.php?defbestelnummer=' + defbestelnummer + '&r=' + new Date().getTime()
    });

    $('.easypiechart').data('easyPieChart').update(totaalkost);
    $('.percent').html("€" + totaalkost);
}

/* Close/hide the sidenav */
function closeNav()
{
    document.getElementById("sidepanel").style.width = "0";
    document.getElementById("sidepanel").style.padding= "0px 0px 0px 0px";
    $('.easypiechart').data('easyPieChart').update(0);
    $('.percent').html("€" + 0);
}
