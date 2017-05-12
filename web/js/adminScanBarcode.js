/* Open the sidenav */
$(document).ready(function()
{
	//recreate table with new data
	$('#displayorderproducts').bootstrapTable({
		url: 'AJAX/adminDisplayOrderProductsRequest.php?bestelnummer=' + Number($("#bestelnummer").html()) +'&r=' + new Date().getTime()
	});
});