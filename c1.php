<!doctype html>
<html>

<head>
	<title>Production Tons per Day</title>
		<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
	<script src="../JQB/Charts/dist/Chart.bundle.js"></script>
<script>

x = 0;  

$(document).ready(function(){
    $(window).resize(function(){
       
       $w  =  $(window).width();
       $h =  $(window).height();
    
      
       localStorage.setItem("c1w", $w);
       localStorage.setItem("c1h", $h);
    });

    $(window).on('mouseout', function () {
      
       $t  =  window.screenTop;
       $l =   window.screenLeft;
      
      
       localStorage.setItem("c1t", $t);
       localStorage.setItem("c1l", $l);
    });
    
});





function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
            if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
                }

</script>



	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
</head>

<body>																																																																																																																																																																																																																																																																																																																																																									
<button onclick="myFunction()">Reload page</button>

<script>
function myFunction() {
	window.location.href = window.location.href;
    location.reload();
}
</script>

	<div style="width:100%;">
		<canvas id="canvas"></canvas>
	</div>
	
	<script>

	
	$.getJSON("getRollCode1.php?OCC=" + getParameterByName('OCC'), function (result) {
		var labels = [], pdata = [], cdata = [];
          labels = result.Labels;
          y1data = result.Data1;
          y2data = result.Data2;
          y3data = result.Data3;
          $y1 = "Production";
          $y2 = 'Average';
          $y3 = "Production - Minus Culls";
	        //    console.log("result", labels);
	    

          var lineChartData = {
      			labels: labels,
      			datasets: [{
      				label: $y1,
      				borderColor: 'red',
      				backgroundColor:'red',
      				fill: false,
      				data:y1data,
      				yAxisID: 'y-axis-1',
      			}, {
      				label: $y2,
      				borderColor: 'blue',
      				backgroundColor: 'blue',
      				fill: false,
      				data: y2data,
      				yAxisID: 'y-axis-1'
      			}, {
      				label: $y3,
      				borderColor: 'green',
      				backgroundColor: 'green',
      				fill: false,
      				data: y3data,
      				yAxisID: 'y-axis-1'
      			}
      			/*	, {
      				label: $y4,
      				borderColor: 'DarkOrange ',
      				backgroundColor: 'DarkOrange ',
      				fill: false,
      				data: y4data,
      				yAxisID: 'y-axis-1'
      			}
      			, {
      				label: $y5,
      				borderColor: 'yellow',
      				backgroundColor: 'yellow',
      				fill: false,
      				data: y4data,
      				yAxisID: 'y-axis-1'
      			},
      			 {
      				label: $y6,
      				display: false,
      				borderColor: 'DarkMagenta ',
      				backgroundColor: 'DarkMagenta ',
      				fill: false,
      				data: y6data,
      				yAxisID: 'y-axis-1'
	        				
      			} */
      			]
      		};

      	

      		
      	//	window.onload = function() {
	        		console.log('window on load', lineChartData);
      			var ctx = document.getElementById('canvas').getContext('2d');
      			window.myLine = Chart.Line(ctx, {
      				data: lineChartData,
      				options: {
      					responsive: true,
      					tooltips: {
      						mode: 'index',
      						intersect: false,
      					},
      					hover: {
      						mode: 'nearest',
      						intersect: true
      					},

      				//	stacked: false,
      					title: {
      						display: true,
      						text: 'Tons Produced'
      					},
      					scales: {
      						xAxes: [{
      							display: true,
      							scaleLabel: {
      								display: true,
      								labelString: 'Month/Day'
      							}
      						}],
      			        					
      						yAxes: [{
      							scaleLabel: {
      								display: true,
      								labelString: 'Tons'
      							},
      							
      							ticks: {
      			                  //  suggestedMin: 50,
      			                    suggestedMax: 20,
      			                    beginAtZero: true,
      								stepSize: 1,
      			                },
      							
      							type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
      							display: true,
      							labelString: 'Tons',
      							position: 'left',
      							id: 'y-axis-1',
      							
      					
      						}, {
      				//			type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
      				//			display: true,
      				//			position: 'right',
      				//			id: 'y-axis-2',
      							

      							

      							// grid line settings
      							gridLines: {
      								drawOnChartArea: true, // only want the grid lines for one axis to show up
      							},
      						}],
      					}
      				}
      			});
      	//	};

});
</script>
</body>

</html>
