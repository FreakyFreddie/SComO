/* Open the sidenav */
function openNav(id, titel, budget, rekening, startdatum, einddatum) {
	//fill in table data target urls
	//$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
	//$("#displayprojectorders").attr("data-url", "AJAX/adminDisplayProjectOrdersRequest.php?id=" + id);

	document.getElementById("sidepanel").style.width = "83.3333%";
	document.getElementById("sidepanel").style.padding= "0px 10px 0px 10px";

	//clear old project
	$("#id").empty();
	$("#titel").empty();
	$("#budget").empty();
	$("#rekening").empty();
	$("#startdatum").empty();
	$("#einddatum").empty();

	//fill in new info
	$("#id").append(id);
	$("#titel").append(titel);
	$("#budget").append(budget);
	$("#rekening").append(rekening);
	$("#startdatum").append(startdatum);
	$("#einddatum").append(einddatum);

	//remove old data from table
	$('#displayprojectparticipants').bootstrapTable('removeAll');
	$('#displayprojectorders').bootstrapTable('removeAll');

	//destroy table
	$('#displayprojectparticipants').bootstrapTable('destroy');
	$('#displayprojectorders').bootstrapTable('destroy');

	//recreate table with new data
	$('#displayprojectparticipants').bootstrapTable({
		url: 'AJAX/adminDisplayProjectParticipantsRequest.php?id=' + id +'&r=' + new Date().getTime()
	});

	//add data to table
	$('#displayprojectorders').bootstrapTable({
		url: 'AJAX/adminDisplayProjectOrdersRequest.php?id=' + id +'&r=' + new Date().getTime()
	});

	if(!$("#removebutton2").length)
	{
		$(".removebutton2").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removebutton2" class="btn btn-default" type="button" name="removeprojects" title="Verwijderen"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button></div>');

		//script to get remove participants from a project
		$("#removebutton2").click(function()
		{
			var cont = confirm("Deze actie is onomkeerbaar! Doorgaan?");

			if(cont == true)
			{
				//set users array
				var users=[];

				$(this).parent().parent().next().find("table").find(".selected").each(function () {
					var user = {rnummer: $(this).find("td").eq(1).text()};

					//push project to array
					users.push(user);
				});

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/adminRemoveProjectParticipantsRequest.php?r=" + new Date().getTime(),
					data: {array: users, id: $("#id").text()}
				});

				$request.done(function()
				{
					$("#userslist").find(".selected").remove();
					//refresh all tables
					$("button[name='refresh']").trigger("click");
				});
			}
		});
	}

}

/* Close/hide the sidenav */
function closeNav() {
	document.getElementById("sidepanel").style.width = "0";
	document.getElementById("sidepanel").style.padding= "0px 0px 0px 0px";
}