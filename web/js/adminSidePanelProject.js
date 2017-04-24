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

	//if removebutton does not exist, add & add eventhandler
	if(!$("#removebutton2").length)
	{
		$(".removebutton2").find(".fixed-table-toolbar").append('<div class="columns btn-group pull-left"><button id="removebutton2" class="btn btn-default" type="button" name="removeuserfromproject" title="Verwijderen"><span class="glyphicon glyphicon-minus"></span> Verwijderen</button><button id="addbutton1" class="btn btn-default" type="button" name="addusertoproject" title="Toevoegen"><span class="glyphicon glyphicon-plus"></span> Toevoegen</button></div>');

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

		//script to add livesearch for users

		$("#addbutton1").click(function()
		{
			if(!$("#livesearchform").length)
			{
				$("#addbutton1").parent().parent().before(
				'<form id="livesearchform">' +
				'<input class="form-control" id="searchusers" placeholder="Search users" type="text" onkeyup="showResult($(this).val())" />' +
				'<div id="livesearch"></div>' +
				'</form>');
			}
			else
			{
				var rnummer = $("#searchusers").val();
				var id = $("#id").html();

				//prepare request
				$request = $.ajax({
					method:"POST",
					url:"AJAX/adminAddUserToProjectRequest.php?r=" + new Date().getTime(),
					data: {rnummer: rnummer, id: id}
				});

				$request.done(function()
				{
					$("#searchusers").val("");
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
				var rnummer=obj[i].rnummer;
				var voornaam=obj[i].voornaam;
				var achternaam=obj[i].achternaam;

				if(i==0)
				{
					document.getElementById("livesearch").innerHTML= '<a class="searchhint" onclick="addUserToInput(\''+ rnummer +'\')">' + achternaam + ", " + voornaam + ", " + rnummer + '</a>';
				}
				else
				{
					document.getElementById("livesearch").innerHTML = document.getElementById("livesearch").innerHTML + '<br /><a class="searchhint" onclick="addUserToInput(\''+ rnummer +'\')">' + achternaam + ", " + voornaam + ", " + rnummer + '</a>';
				}
			}

			document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		}
	}
	xmlhttp.open("GET","AJAX/adminLiveSearchUsersRequest.php?q="+str,true);
	xmlhttp.send();
}

//click a livesearch hint
function addUserToInput(rnummer)
{
	$("#searchusers").val(rnummer);
}
