// Globals
window.ModuleError = "<div><h6>Module Failed</h6><h6>Try refreshing or checking console log!</h6></div>";

function changeStockPrice( index, newAmount, digs ){
	// Execute outside jQuery selector for Odometer obj cast
	var od = document.querySelector('.flipPrice' + index);
	
	odom = new Odometer({
		el: od,
		value: newAmount
	});

	// Trigger class functions for anim update
	odom.update(newAmount);
	
	var oldDollarAmount = $('.flipPrice' + index).data("value") || 0;
	$('.flipPrice' + index).data("value", newAmount);
	$('.flipPrice' + index).html(newAmount);

	// In the green!
	if( newAmount > oldDollarAmount ){
		$(".flipPrice" + index).stop().dequeue().animate({color: '#00B900'}, 100, function() {
			$(".flipPrice" + index).stop().dequeue().animate({color: '#FFFFFF'}, 600);
		});
	}
	
	// In the red :(
	if(  newAmount < oldDollarAmount ){
		$(".flipPrice" + index).stop().dequeue().animate({color: '#B90000'}, 100, function(){
			$(".flipPrice" + index).stop().dequeue().animate({color: '#FFFFFF'}, 600);
		});
	}
}

function triggerException( location ){
	$( "." + location + "Spinner" ).remove();
	$( "." + location + "Loading" ).empty();
	$( "." + location + "Loading" ).append(window.ModuleError);
}

function updateStocks( func ){
	$.ajax({
		type: 'POST',
		url: 'modules/stocks.php',
		timeout: 180000,
		success: function(data){
			console.log("POSTED STOCKS");
			
			try{
				// JSON response echoed from stock.php api
				let jsonData = JSON.parse(data);

				// Error happened on API end, non-success response
				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}

				// Iterate over each stock current value
				$.each(jsonData["Stocks"], function(k ,v){
					$.each(v, function(ticker, value){
						$(".stockName" + k).html(ticker);

						// Render chart from intraday series
						changeStockPrice(k, value);
						renderChart( "#chart" + k, genChartOptions(jsonData["Intraday"][ticker]) );
					});
				});

				// Run callback
				setTimeout(func, 500);
				}catch(err){
					// Exception handling, stop loading, display error msg
					triggerException( "bl" );
				}
				
				// No exceptions, continue running every 3 minutes (180s)
				setTimeout(updateStocks, 180000);
		},
		error: function(data){
			setTimeout(function(){
				triggerException( "bl" );
			}, 500);
		}
	});
}

// Initial
$(document).ready(function() {
	updateStocks(function(){
		$(".blLoading").remove();
		$(".stocks").removeClass("d-none").hide().fadeIn(400);
	});
});