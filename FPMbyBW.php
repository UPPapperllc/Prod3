<!doctype html>
<html>

<head>
<title>Feet/Min by Weight</title>
<link href="../jqb/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" /> 
	<link href="../jqb/jtable/lib/themes/metro/UPPInc/jtable.css" rel="stylesheet" type="text/css" />
	<link href="../jqb/jquery-ui.css" rel="stylesheet" type="text/css" />
    
	<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>
	
	
	
	<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>
       <link rel="stylesheet" href="prod3.css">
     <link rel="stylesheet"
          type="text/css"
          href="../jqb/jqconfirm/css/jquery-confirm.css"/>
    <script type="text/javascript"
            src="../jqb/jqconfirm/js/jquery-confirm.js"></script>





<script src="../JQB/Charts/dist/Chart.bundle.js"></script>







<script>

 $(document).ready(function(){

    $(window).resize(function(){
       
       $w  =  $(window).width();
       $h =  $(window).height();

     //  console.log($w, $h);
      
       localStorage.setItem("c4h", $h);
    });

    $(window).on('mouseout', function () {
      
       $t  =  window.screenTop;
       $l =   window.screenLeft;
      
      	
       localStorage.setItem("c4t", $t);
       localStorage.setItem("c4l", $l);
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
.canvas 
              {
                overflow-x: scroll;
              	font-size: 8px;
              }
.xAxis {
	transform: rotate(-90deg);
	-ms-transform: rotate(-90deg); /* IE 9 */
	-moz-transform: rotate(-90deg); /* Firefox */
	-webkit-transform: rotate(-90deg); /* Safari and Chrome */
	-o-transform: rotate(-90deg); /* Opera */
}

.chartjs-render-monitor {
	font-size: 6px;
	color: darkred;
}
</style>
</head>

<body>
	<button onclick="myFunction()">Reload page</button>
	<!-- 
<button  onclick = "saveImage();" id="save-btn">Save Chart Image</button>
-->
	<script>

function myFunction() {
	window.location.href = window.location.href;
    location.reload();
}


function saveImage(){ 
    $("#canvas").get(0).toBlob(function(blob) {
	saveAs(blob, "chart_1.png");
});
};
</script>
	

<div class="portlet" id="draggable2"  style="width: 100%;"  data-id='2'> Feet/Min - Graph
		<div class="portlet-header" style="width: 1900px;"></div>
		<div class="portlet-content"  >
		
		<canvas style='background-color: white;' id="canvas"></canvas>
		</div>
		
</div>
	

	<div class="portlet" id="draggable2"  style="width: 50%;" data-id='2'> Feet/Min - List
		<div class="portlet-header"></div>
		<div class="portlet-content" id="fpmbybw" style="width: 60%;"></div>
		
</div>

	<script>

$(document).ready(function(){




var today = new Date();
var year = today.getFullYear();
year = year-2;
	$.getJSON("getfpmbybwG.php", function (result) {
		console.log(result.data1);
		var labels = [], pdata = [], cdata = [];
          labels = result.Labels;
          y1data = result.Data1;    
          cdata = result.Data2;
          $y1 = result.Data1;
   
	    


	            var lineChartData = {
	        			labels: labels,
	        			datasets: [{
	        				label: 'Line-Feet/Min',
	        				type:'line',
	        				borderColor: 'red',
	        				backgroundColor:'red',
	        				fill: false,
	        				data:y1data,
	        				yAxisID: 'y-axis-1',
	        				labelAngle: 90,
  							fontSize: 6,
								fontColor: "#e60000",
	        				
	        			},
	        			{
	        				label: 'Bar-Feet/Min',
	        				type:'bar',
	        				borderColor: 'black',
	        				backgroundColor:cdata,
	        				barPercentage: 0.2,
	        				fill: false,
	        				data:y1data,
	        				yAxisID: 'y-axis-1',
	        				labelAngle: 90,
	        				fontSize: 6,
  							
	        				
	        			}  
	        			]
	        		};

	        	
	           //t Chart.defaults.global.defaultFontColor = 'darkgreen';
	            Chart.defaults.global.defaultFontSize = 8;
	        		
	        	//	window.onload = function() {
		        		console.log('window on load', lineChartData);
	        			var ctx = document.getElementById('canvas').getContext('2d');
	        			window.myMixedChart = new Chart(ctx, {
		        			type: 'bar',
	        				data: lineChartData,
	        				options: {
	        					responsive: true,
	        				//	tooltips: {
	        				//		mode: 'index',
	        				//		intersect: false,
	        				//	},
	        					
	        				//	hover: {
	        				//		mode: 'index',
	        				//		intersect: true
	        				//	},

	        					//stacked: true,
	        					title: {
	        						display: true,
	        						text: 'Feet/Min by  Weight'
	        					},
	        				
	        					scales: {
	          						xAxes: [{
	          							display: true,
	          							fontSize: '6px',
	          							scaleLabel: {
	          								display: true,
	          								
	          								labelString: 'Weight/month',
	          								
	          							},
	          							ticks: {
	          								maxRotation: 0 // angle in degrees
	          			                }
	          						}],
	          			        					
	          						yAxes: [{
	          							scaleLabel: {
	          								display: false,
	          								labelString: 'Feet/Min'
	          							},
	          							
	          							ticks: {},

	          							
	          							type: 'logarithmic', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
	          							display: true,
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



	        			$('#fpmbybw').jtable('load');

	        			
	});
	        			$(window).on('load', function () {

//////////////////////////////////////////////////////////////////////
//Prepare jTable
$('#fpmbybw').jtable({

title: 'FPM by BW',
//paging: true,
//pageSize: 6,


selecting: true,
columnResizable: true,
//multi: true, //Allow multiple selecting
//selectingCheckboxes: true, //Show checkboxes on first column
//selectOnRowClick: false, //Enable this to only select using checkboxes
actions: {
listAction: 'getFPMbyBW.php'
//createAction: 'getProj.php?action=create',
//updateAction: 'getProj.php?action=update',
//deleteAction: 'getProj.php?action=delete'

},
toolbar:{               
items: [{
icon: '../images/reload.jpg',
text: 'Load/Reload Detail Data',
click: function () {
$('#fpmbybw').jtable('load');
}
},
{
Tooltip: 'Click here to export this table to excel',
icon: '../images/excel2.jpg',
text: 'Export to Excel',
click: function () {
window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=fpmbybw]').html()));
e.preventDefault();
}
}
]},


fields: {

BW: {	
	key: true,	
title: 'Weight',	
},

MID: {																																																																																				
title: 'Month',
},	
FPM: {																																																																																				
title: 'Feet/Min',
}
},


recordsLoaded: function(event, data) {
    $('.jtable-data-row').click(function() {
   	 var record = $(this).data('record');
        var url = 'FPMbyBW_BW.php?BW=' + record.BW  +'&MID=' + record.MID;
        window.open(src=url, 'bwdetail', "width=1200,height=1000");
    });
}




});

//Load person list from server
$('#fpwbybw').jtable('load');
///////////////////////////////////////////////////				///////////////////

	});
	$('#fpwbybw').jtable('load');
});
		
	</script>
</body>

</html>

