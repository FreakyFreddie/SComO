/* Open the sidenav */
function openNav(defbestelnummer, besteldatum, rnummer, projectid, projecttitel, status) {
    //fill in table data target urls
    //$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
    //$("#displayprojectorder").attr("data-url", "AJAX/adminDisplayProjectOrderRequest.php?id=" + id);

    document.getElementById("sidepanel").style.width = "83.3333%";
    document.getElementById("sidepanel").style.padding= "0px 10px 0px 10px";

    //clear old project
    $("#defbestelnummer").empty();
    $("#besteldatum").empty();
    $("#rnummer").empty();
    $("#projectid").empty();
    $("#projecttitel").empty();
    $("#status").empty();
    $("#message").empty();

    //fill in new info
    $("#defbestelnummer").append(defbestelnummer);
    $("#besteldatum").append(besteldatum);
    $("#rnummer").append(rnummer);
    $("#projectid").append(projectid);
    $("#projecttitel").append(projecttitel);
    $("#status").append(status);

    //remove old data from table
    $('#displayuserorderproducts').bootstrapTable('removeAll');

    //destroy table
    $('#displayuserorderproducts').bootstrapTable('destroy');

    //add data to table
    $('#displayuserorderproducts').bootstrapTable({
        url: 'AJAX/adminDisplayFinalOrderProductsRequest.php?bestelnummer=' + defbestelnummer + '&r=' + new Date().getTime()
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
