/*
	Grab RSS items
	Send array of items to cycle as queue
	Show each roughly 10 seconds and dequeue
	Update when no items present
*/

function cycleNewsItem(){

}

function triggerException( location ){
	$( "." + location + "Spinner" ).remove();
	$( "." + location + "Loading" ).empty();
	$( "." + location + "Loading" ).append(window.ModuleError);
}

function updateRSS( func ){
	$.ajax({
		type: 'POST',
		url: 'modules/rss.php',
		timeout: 600000,
		success: function(data){
			try{
				// JSON response echoed from stock.php api
				let jsonData = JSON.parse(data);

				// Error happened on API end, non-success response
				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}

				// Iterate over each stock current value
				$.each(jsonData["Data"], function(k ,v){
					$.each(v["Items"], function(k2, v2){
						console.log(v2["Title"][0])
					});
				});

				// Run callback
				setTimeout(func, 500);
				}catch(err){
					// Exception handling, stop loading, display error msg
					triggerException( "cn" );
				}
				
				// No exceptions, continue running every 3 minutes (180s)
				setTimeout(updateRSS, 600000);
		},
		error: function(data){
			setTimeout(function(){
				triggerException( "cn" );
			}, 500);
		}
	});
}

// Initial
$(document).ready(function() {
	updateRSS(function(){
		console.log("LOADED");
	});
});