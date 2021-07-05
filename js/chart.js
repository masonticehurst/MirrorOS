// Prepare chart options
function genChartOptions( seriesData ){
	var options = {
		series: [
			{
				name: 'stock',
				data: seriesData
			}
		],
		chart: {
			height: '100px',
			type: 'line',
			animations: {
				enabled: false
			},
			zoom: {
				enabled: false
			},
			toolbar: {
				show: false
			}
		},
		labels: [],
		tooltip: {
			enabled: false
		},
		markers: {
			size: 0,
			hover: {
				size: 0
			}
		},
		legend: {
			show: false,
			enabled: false
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			curve: 'smooth'
		},
		grid: {
			show: false
		},
		colors: ['#FFFFFF'],
		xaxis: {
			show: false,
			labels: {
				show: false
			},
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
			crosshairs: {
				show: false
			},
			tooltip: {
				enabled: false
			}
		},
		yaxis: {
			show: false,
			labels: {
				show: false
			},
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			},
			crosshairs: {
				show: false
			},
			tooltip: {
				enabled: false
			}
		}
	};

	return options;
}

function renderChart( querySelect, options ){
	return (new ApexCharts(document.querySelector(querySelect), options)).render();
}