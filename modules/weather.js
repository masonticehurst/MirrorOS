function updateWeather( func ){
	$.ajax({
		type: 'POST',
		url: 'modules/weather.php',
		timeout: 100000,
		success: function(data){
			try{
				let jsonData = JSON.parse(data);
				if( jsonData.status != 200 ){
					throw 'Invalid response';
				}
			
				let currentData = jsonData.current;

				let curTemp = currentData.temp;
				let curHumid = currentData.humidity;
				let curWind = currentData.wind_speed;
				let curDesc = currentData.weather[0].description;

				$(".sunnyTemp").html(Math.ceil(curTemp) + "˚");
				$(".humidity").html(Math.ceil(curHumid) + "%");
				$(".windSpeed").html(Math.ceil(curWind) + "mph");
				$("#currentWeatherIcon").attr("src", "img/" + currentData.weather[0].icon + ".svg");
				$(".weatherDesc").html(curDesc);

				let displayName = ( jsonData.state ? jsonData.city + ", " + jsonData.state : jsonData.city );
				$("#cityName").html(displayName);
				$("#sunRise").html(currentData.sunrise);
				$("#sunSet").html(currentData.sunset);

				$.each( jsonData['daily'], function( k, v ){
					$("#dayName" + k).html(v.week_day);
					$("#dayHigh" + k).html(Math.ceil(v.temp.max) + "˚");
					$("#dayLow" + k).html(Math.ceil(v.temp.min) + "˚");
					$("#dayIcon" + k).attr("src", "img/" + v.weather[0].icon + ".svg");
				});

				setTimeout(func, 500);
			} catch(err) {
				$(".weatherDisplay").hide();
				$("#weeatherForecast").hide();
				$(".tlSpinner").remove();
				$(".tlLoading").empty();
				$(".tlLoading").append("<div><h3>Module Failed</h3><h3>Try refreshing or checking console log!</h3></div>");
			}

			setTimeout(updateWeather, 100000);
		},
		error: function(data){
			setTimeout(function(){
				$(".weatherDisplay").hide();
				$("#weeatherForecast").hide();
				$(".tlSpinner").remove();
				$(".tlLoading").empty();
				$(".tlLoading").append("<div><h6>Module Failed</h3><h6>Try refreshing or checking console log!</h3></div>");
			}, 500);
		}
	})
}

$(document).ready(function() {
	updateWeather(function(){
		$(".tlLoading").remove();
		$(".weatherDisplay").hide().fadeIn(400);
		$("#weatherForecast").hide().fadeIn(400);
	});
});