function triggerException( location ){
	$( "." + location + "Spinner" ).remove();
	$( "." + location + "Loading" ).empty();
	$( "." + location + "Loading" ).append(window.ModuleError);
}

function updateSpotify( func ){
	$.ajax({
		type: 'POST',
		url: 'modules/spotify.php',
		timeout: 600000,
		success: function(data){
			try{
				// JSON response echoed from stock.php api
				let jsonData = JSON.parse(data);

				// Error happened on API end, non-success response
				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}

				// Run callback
				setTimeout(func, 500);

				}catch(err){
					// Exception handling, stop loading, display error msg
					triggerException( "br" );
				}
				
				// No exceptions, continue running every 10 minutes (600s)
				setTimeout(updateSpotify, 600000);
		},
		error: function(data){
			setTimeout(function(){
				triggerException( "br" );
			}, 500);
		}
	});
}

// Initial
$(document).ready(function() {
	updateSpotify(function(){
		$(".brLoading").remove();
	});
});