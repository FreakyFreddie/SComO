/* Open the sidenav */
function openNav(bestelnr, besteldatum, rnummer, projectid, projecttitel, status) {
    //fill in table data target urls
    //$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
    //$("#displayprojectorder").attr("data-url", "AJAX/adminDisplayProjectOrderRequest.php?id=" + id);

    document.getElementById("sidepanel").style.width = "83.3333%";
    document.getElementById("sidepanel").style.padding= "0px 10px 0px 10px";

    //clear old project
    $("#bestelnr").empty();
    $("#besteldatum").empty();
    $("#rnummer").empty();
    $("#projectid").empty();
    $("#projecttitel").empty();
    $("#status").empty();

    //fill in new info
    $("#bestelnr").append(bestelnr);
    $("#besteldatum").append(besteldatum);
    $("#rnummer").append(rnummer);
    $("#projectid").append(projectid);
    $("#projecttitel").append(projecttitel);
    $("#status").append(status);

    //remove old data from table
    $('#displayprojectorders').bootstrapTable('removeAll');

    //destroy table
    $('#displayprojectorders').bootstrapTable('destroy');

    //add data to table
    $('#displayprojectorders').bootstrapTable({
        url: 'AJAX/adminDisplayProjectOrdersRequest.php?id=' + id +'&r=' + new Date().getTime()
    });

    //update pie chart data
    var budget = $("#budget").html();
    var id = $("#id").html();

    //prepare request
    $request = $.ajax({
        method:"POST",
        url:"AJAX/adminProjectOrderDataRequest.php?r=" + new Date().getTime(),
        data: {id: id, budget: budget}
    });

    $request.done(function(data)
    {
        $('.easypiechart').data('easyPieChart').update(data);
        $('.percent').html(data + "%");
    });

}

/* Close/hide the sidenav */
function closeNav()
{
    document.getElementById("sidepanel").style.width = "0";
    document.getElementById("sidepanel").style.padding= "0px 0px 0px 0px";
    $('.easypiechart').data('easyPieChart').update(0);
    $('.percent').html(0 + "%");
}
