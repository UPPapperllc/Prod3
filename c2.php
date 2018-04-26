<!doctype html>
<html>

<head>
	<title>Cull Tons per Day</title>
		<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
	<script src="../JQB/Charts/dist/Chart.bundle.js"></script>
	
<script>

$(document).ready(function(){
    $(window).resize(function(){
       
       $w  =  $(window).width();
       $h =  $(window).height();
    
      
       localStorage.setItem("c2w", $w);
       localStorage.setItem("c2h", $h);
    });

    $(window).on('mouseout', function () {
      
       $t  =  window.screenTop;
       $l =   window.screenLeft;
      
      	
       localStorage.setItem("c2t", $t);
       localStorage.setItem("c2l", $l);
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
	
	$.getJSON("getRollCode2.php?OCC=" + getParameterByName('OCC'), function (result) {
		var labels = [], pdata = [], cdata = [];
          labels = result.Labels;
          y1data = result.Data1;
          y2data = result.Data2;
          mid = result.MID;
          $y1 = "Culls";
          $y2 = 'Average';
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
      			}
      			]
      		};

      	

      		
      	//	window.onload = function() {
	        	//	console.log('window on load', lineChartData);
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
      					onClick: oc,

      				//	stacked: false,
      					title: {
      						display: true,
      						text: 'Culled Wgt(Tons) by Day'
      					},
      					scales: {
      						xAxes: [{
      							display: true,
      							scaleLabel: {
      								display: true,
      								labelString: 'Date'
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
      							
      						//	type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
      						//	display: true,
      						//	position: 'left',
      						//	id: 'y-axis-1',
      					
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

      			//chart = new Chart(canvas, chart_config);
	function oc(e){
		console.log("on Click: ", e);
		 var activeElement = window.myLine.getElementAtEvent(e);
		 console.log('activeElement:', activeElement);
		 var ce = activeElement[0]._index;
		 console.log(ce);
		 d2  = labels[ce];
		 mx = mid[ce];
		 m = d2.substr(0,2);
		 d = d2.substr(3,2);
		 console.log(d2, m, d, mx);
		 window.open(mx, "Prod", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");

		
	}
	});
</script>
</body>

</html>
