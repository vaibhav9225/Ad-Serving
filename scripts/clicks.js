$(document).ready(function(){
	$('.results_left').on('click', '.news_clicked' ,function(){
		var docId = $(this).attr('rel');
		var port = 1002;
		$.post('libs/update.php',{docId:docId, port:port});
	});
	$('.results_right').on('click', '.ad_clicked' ,function(){
		var docId = $(this).attr('rel');
		var port = 1001;
		$.post('libs/update.php',{docId:docId, port:port});
	});
});