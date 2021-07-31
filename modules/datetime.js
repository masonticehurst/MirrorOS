function updateRTC(func) {
	$.ajax({
		type: 'POST',
		url: 'modules/datetime.php',
		timeout: 1000,
		success: function(data){
			console.log("POSTED DATETIME");
			let jsonData = JSON.parse(data);
			$(".time").html(jsonData.time);
			$(".date").html(jsonData.textual + ", " + jsonData.month + " " + jsonData.day);
			// parse json
			setTimeout(func, 1000);
			setTimeout(updateRTC, 1000);
		},
		error: function(data){
			setTimeout(function(){
				$(".trSpinner").remove();
				$(".trLoading").empty();
				$(".trLoading").append("<div><h3>Module Failed</h3><h3>Try refreshing or checking console log!</h3></div>");
			}, 500);
		}
	})
}

$(document).ready(function() {
	updateRTC(function(){
		$(".trLoading").remove();
		$(".display-1").removeClass("d-none").hide().fadeIn(400);
	});
});