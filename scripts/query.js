$(document).ready(function(){
	$("#header_textbox").keyup(function(){
		var query = $("#header_textbox").val().toLowerCase();
		if(query.length > 3){
			var query = $("#header_textbox").val().toLowerCase();
			$.post('libs/query.php',{query:query},function(data){
				$('#results').html(data);
				$('#suggestions').show();
				$('#suggestions').width($("#header_textbox").width() + 18);
				$("#suggestions").offset({top: ($("#header_textbox").position().top + 31), left: $("#header_textbox").position().left})
			},"html");
			return false;
		}
	});
});