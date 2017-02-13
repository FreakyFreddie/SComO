$(document).ready(function()
{
	$(".order-by").click(function()
	{
		var sequence = $(this).data("sequence");

		//prepare request
		$request = $.ajax({
			method:"POST",
			url:"AJAX/processSortOrdersRequest.php?r=" + new Date().getTime(),
			data: {sort: $(this).val(), sequence: sequence}
		});

		//print result
		$request.done(function(msg)
		{
			$(".workspace").find(".panel").remove();
			$(".workspace").html(msg);
		});

		//set data-sequence to the opposite
		if(sequence == "ASC")
		{
			$(this).data("sequence", "DESC");
		}
		else
		{
			$(this).data("sequence", "ASC");
		}
	});
});