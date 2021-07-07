function changeStockPrice( index, newAmount, digs ){
	/*
	var DollarAmnt = Math.floor(newAmount);
	var ChangeAmnt = Math.round((newAmount % 1) * 100);
	// Make sure we can interact with Flip class otherwise create/recreate
	if( Stocks[index] == null ){			
		// Set data- attribute to reflect value without having to parse html manually
		$(".flipPrice" + index).data("value", newAmount);
			// Instantiate flip object to interface with number-flip library
		Stocks[index] = {};
			// Dollars
		Stocks[index].Dollars = new Flip({
			node: document.querySelector(".flipPrice" + index),
			from: DollarAmnt,
			easeFn: function(pos) {
				// Smoothing anim
				if ((pos/=0.5) < 1) return 0.5*Math.pow(pos,3);
				return 0.5 * (Math.pow((pos-2),3) + 2);
			},
			maxLenNum: (''+DollarAmnt).length
		});
			// Change
		Stocks[index].Change = new Flip({
			node: document.querySelector(".flipChange" + index),
			from: ChangeAmnt,
			easeFn: function(pos) {
				// Smoothing anim
				if ((pos/=0.5) < 1) return 0.5*Math.pow(pos,3);
				return 0.5 * (Math.pow((pos-2),3) + 2);
			},
			maxLenNum: 2,
		});
			return;
	}
	var oldDollarAmount = $(".flipPrice" + index).data("value");
	$(".flipPrice" + index).data("value", newAmount);
		// Flip to new val
	Stocks[index].Dollars.flipTo({
		to: DollarAmnt,
		direct: false,
		duration: 0.4,
		maxLenNum: digs,
	});
	Stocks[index].Change.flipTo({
		to: ChangeAmnt,
		direct: false,
		duration: 0.4,
		maxLenNum: 2,
	});
	*/
	var od = document.querySelector('.flipPrice' + index);
	odom = new Odometer({
		el: od,
		value: newAmount
	});
	odom.update(newAmount);
	
	var oldDollarAmount = $('.flipPrice' + index).data("value") || 0;
	$('.flipPrice' + index).data("value", newAmount);
	$('.flipPrice' + index).html(newAmount);
		// In the green!
	if( newAmount > oldDollarAmount ){
		console.log("animated up!!");
		$(".flipPrice" + index).stop().dequeue().animate({color: '#00B900'}, 100, function() {
			$(".flipPrice" + index).stop().dequeue().animate({color: '#FFFFFF'}, 600);
		});
	}
		// In the red :(
	if(  newAmount < oldDollarAmount ){
		console.log("animated down!!");
		$(".flipPrice" + index).stop().dequeue().animate({color: '#B90000'}, 100, function(){
			$(".flipPrice" + index).stop().dequeue().animate({color: '#FFFFFF'}, 600);
		});
	}
}

function updateStocks( func ){
	$.ajax({
		type: 'POST',
		url: 'modules/stocks.php',
		timeout: 180000,
		success: function(data){
			try{
				let jsonData = JSON.parse(data);

				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}

				$.each(jsonData["Stocks"], function(k ,v){
					$.each(v, function(ticker, value){
						$(".stockName" + k).html(ticker);
						changeStockPrice(k, value);
						// $("#flipPrice" + k).html(value);
					});
				});

				setTimeout(func, 500);
				}catch(err){
					console.log("caught ex stocks");
					$(".blSpinner").remove();
					$(".blLoading").empty();
					$(".blLoading").append("<div><h6>Module Failed</h6><h6>Try refreshing or checking console log!</h6></div>");
				}
					setTimeout(updateStocks, 180000);
		},
		error: function(data){
			setTimeout(function(){
				console.log("caught err stocks");
				$(".blSpinner").remove();
				$(".blLoading").empty();
				$(".blLoading").append("<div><h6>Module Failed</h6><h6>Try refreshing or checking console log!</h6></div>");
			}, 500);
		}
	});
}

$(document).ready(function() {
	updateStocks(function(){
		$(".blLoading").remove();
		$("#Stocks").hide().fadeIn(400);
	});
});