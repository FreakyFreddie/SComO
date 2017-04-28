/* Open the sidenav */
function openNav(rnummer, voornaam, naam, email, niveau, aanmaakdatum) {
	//fill in table data target urls
	//$("#displayprojectparticipants").attr("data-url", "AJAX/adminDisplayProjectParticipantsRequest.php?id=" + id);
	//$("#displayuserorders").attr("data-url", "AJAX/adminDisplayProjectOrdersRequest.php?id=" + id);

	document.getElementById("sidepanel").style.width = "83.3333%";
	document.getElementById("sidepanel").style.padding= "0px 10px 0px 10px";

	//clear old user
	$("#rnummer").empty();
	$("#voornaam").empty();
	$("#naam").empty();
	$("#email").empty();
	$("#niveau").empty();
	$("#aanmaakdatum").empty();

	//fill in new info
	$("#rnummer").append(rnummer);
	$("#voornaam").append(voornaam);
	$("#naam").append(naam);
	$("#email").append(email);
	$("#niveau").append(niveau);
	$("#aanmaakdatum").append(aanmaakdatum);

	//remove old data from table
	$('#displayprojectparticipants').bootstrapTable('removeAll');
	$('#displayuserorders').bootstrapTable('removeAll');

	//destroy table
	$('#displayprojectparticipants').bootstrapTable('destroy');
	$('#displayuserorders').bootstrapTable('destroy');

	//recreate table with new data
	$('#displayprojectsparticipating').bootstrapTable({
		url: 'AJAX/adminDisplayProjectsParticipatingRequest.php?rnummer=' + rnummer +'&r=' + new Date().getTime()
	});

	//add data to table
	$('#displayuserorders').bootstrapTable({
		url: 'AJAX/adminDisplayUserOrdersRequest.php?rnummer=' + rnummer +'&r=' + new Date().getTime()
	});

	//update pie chart data
	var rnummer = $("#rnummer").html();

	//prepare request
	$request = $.ajax({
		method:"POST",
		url:"AJAX/adminUserOrderDataRequest.php?r=" + new Date().getTime(),
		data: {rnummer: rnummer}
	});

	$request.done(function(data)
	{
		$('.easypiechart').data('easyPieChart').update(data);
		$('.percent').html(data);
	});

	//if removebutton does not exist, add & add eventhandler
	if(!$("#removebutton2").length)
	{
		$(".removebutton2").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removebutton2" class="btn btn-default" type="button" name="removeuserfromuser" title="Verwijderen"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button><button id="addbutton1" class="btn btn-default" type="button" name="addusertouser" title="Toevoegen"><span class="glyphicon glyphicon-plus"></span> Toevoegen</button></div>');

		//script to get remove participants from a user
		$("#removebutton2").click(function()
		{
			var cont = confirm("Deze actie is onomkeerbaar! Doorgaan?");

			if(cont == true)
			{
				//set users array
				var projects=[];

				$(this).parent().parent().next().find("table").find(".selected").each(function () {
					var project = {id: $(this).find("td").eq(1).text()};

					//push user to array
					projects.push(project);
				});

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/adminRemoveProjectsParticipatingRequest.php?r=" + new Date().getTime(),
					data: {array: projects, rnummer: $("#rnummer").text()}
				});

				$request.done(function()
				{
					//refresh all tables
					$("button[name='refresh']").trigger("click");
				});
			}
		});

		//script to add livesearch for users

		$("#addbutton1").click(function()
		{
			if(!$("#livesearchform").length)
			{
				$("#addbutton1").parent().parent().before(
				'<form id="livesearchform">' +
				'<input class="form-control" id="searchprojects" placeholder="Search projects" type="text" onkeyup="showResult($(this).val())" />' +
				'<div id="livesearch"></div>' +
				'</form>');
			}
			else
			{
				var id = $("#searchprojects").val();
				var rnummer = $("#rnummer").html();

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/adminAddUserToProjectRequest.php?r=" + new Date().getTime(),
					data: {id: id, rnummer: rnummer}
				});

				$request.done(function()
				{
					$("#searchprojects").val("");
					//refresh all tables
					$("button[name='refresh']").trigger("click");
				});
			}
		});
	}
}

/* Close/hide the sidenav */
function closeNav()
{
	document.getElementById("sidepanel").style.width = "0";
	document.getElementById("sidepanel").style.padding= "0px 0px 0px 0px";
	$('.easypiechart').data('easyPieChart').update(0);
	$('.percent').html(0 + "%");
}

function showResult(str)
{
	if (str.length==0)
	{
		document.getElementById("livesearch").innerHTML="";
		document.getElementById("livesearch").style.border="0px";
		return;
	}
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (this.readyState==4 && this.status==200)
		{
			var obj = JSON.parse(this.responseText);

			for(var i = 0; i < obj.length; i++)
			{
				var idproject =obj[i].idproject;
				var titel = obj[i].titel;

				if(i==0)
				{
					document.getElementById("livesearch").innerHTML= '<a class="searchhint" onclick="addProjectToInput('+ idproject +')">\'' + titel + "', " + idproject + '</a>';
				}
				else
				{
					document.getElementById("livesearch").innerHTML = document.getElementById("livesearch").innerHTML + '<br /><a class="searchhint" onclick="addProjectToInput('+ idproject +')">\'' + titel + "', " + idproject + '</a>';
				}
			}

			document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		}
	}
	xmlhttp.open("GET","AJAX/adminLiveSearchProjectsRequest.php?q="+str,true);
	xmlhttp.send();
}

//click a livesearch hint
function addProjectToInput(idproject)
{
	$("#searchprojects").val(idproject);
}
