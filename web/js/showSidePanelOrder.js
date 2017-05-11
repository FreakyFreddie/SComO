/* Open the sidenav */
function openNav(bestelnummer, status, besteldatum, project) {
	//fill in table data target urls
	//$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
	//$("#displayprojectorders").attr("data-url", "AJAX/adminDisplayProjectOrdersRequest.php?id=" + id);

	document.getElementById("sidepanel").style.width = "100%";
	document.getElementById("sidepanel").style.padding= "100px 10px 10px 10px";

	//clear old order
	$("#bestelnummer").empty();
	$("#status").empty();
	$("#besteldatum").empty();
	$("#project").empty();
	$("#message").empty();

	//fill in new info
	$("#bestelnummer").append(bestelnummer);
	$("#status").append(status);
	$("#besteldatum").append(besteldatum);
	$("#project").append(project);


	//if status is denied, display message
	if(status == "Geweigerd")
	{
		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processOrderDeniedMessageRequest.php?r=" + new Date().getTime(),
			data: {bestelnummer: bestelnummer}
		});

		$request.done(function(msg)
		{
			$("#message").append(msg);
		});
	}

	//remove old data from table
	$('#displayuserorderproducts').bootstrapTable('removeAll');

	//destroy table
	$('#displayuserorderproducts').bootstrapTable('destroy');

	//recreate table with new data
	$('#displayuserorderproducts').bootstrapTable({
		url: 'AJAX/processDisplayUserOrderProductsRequest.php?bestelnummer=' + bestelnummer + '&r=' + new Date().getTime()
	});

	//prepare request
	$request = $.ajax({
		method:"POST",
		url:"AJAX/processUserOrderDataRequest.php?r=" + new Date().getTime(),
		data: {bestelnummer: bestelnummer}
	});

	$request.done(function(data)
	{
		$('.easypiechart').data('easyPieChart').update(data);
		$('.percent').html("€" + data);
	});
}

/* Close/hide the sidenav */
function closeNav()
{
	document.getElementById("sidepanel").style.width = "0";
	document.getElementById("sidepanel").style.padding= "0px 0px 0px 0px";
	$('.easypiechart').data('easyPieChart').update(0);
	$('.percent').html("€" + 0);
}
