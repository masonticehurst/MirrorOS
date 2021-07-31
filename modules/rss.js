/**
 * Shuffles array in place.
 * @param {Array} a items An array containing the items.
 * @author Craftingexpert1 @ Stack Overflow
 */
function shuffle (arr) {
	var j, x, index;
	for (index = arr.length - 1; index > 0; index--) {
		j = Math.floor(Math.random() * (index + 1));
		x = arr[index];
		arr[index] = arr[j];
		arr[j] = x;
	}
	return arr;
}

function cycleNewsItem( itemStack ){
	if( itemStack.length < 1 ){
		return updateRSS();
	}

	$(".rssText").fadeOut(400, function() {
		var item = itemStack.pop();
		$(".rssText").hide();
		$(".rssText").html(item["Title"][0]);
		$(".rssText").fadeIn(400);

	    if( $(".rssSource").text() != item["Source"][0] ){
			$(".rssSource").hide();
			$(".rssSource").text(item["Source"][0]);
			$(".rssSource").fadeIn(400); 
	    }
	});

	setTimeout( function(){
		cycleNewsItem(itemStack);
	}, 10000);
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
			console.log("POSTED RSS");
			console.log(data);
			try{
				// JSON response echoed from stock.php api
				let jsonData = JSON.parse(data);

				// Error happened on API end, non-success response
				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}

				var itemsStack = [];

				$.each(jsonData["Data"], function(k ,v){
					$.each(v["Items"], function(k2, v2){
						itemsStack.push(v2);
					});
				});

				itemsStack = shuffle(itemsStack);
				cycleNewsItem(itemsStack);

				// Run callback
				setTimeout(func, 500);

				}catch(err){
					// Exception handling, stop loading, display error msg
					console.log("catch exception rss");
					triggerException( "cn" );
				}
				
				// No exceptions, continue running every 10 minutes (600s)
				setTimeout(updateRSS, 600000);
		},
		error: function(data){
			setTimeout(function(){
				console.log("err rss");
				triggerException( "cn" );
			}, 500);
		}
	});
}

// Initial
$(document).ready(function() {
	updateRSS(function(){
		$(".cnLoading").remove();
		$(".rss").removeClass("d-none").hide().fadeIn(400);
	});
});