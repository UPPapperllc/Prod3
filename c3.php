<!doctype html>
<html>

<head>
	<title>3 Year Sales/Sales Estimate  </title>
		<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
	<script src="../JQB/Charts/dist/Chart.bundle.js"></script>
	
 <link href="../jqb/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" /> 
	<link href="../jqb/jtable/lib/themes/metro/UPPInc/jtable.css" rel="stylesheet" type="text/css" />
	<link href="../jqb/jquery-ui.css" rel="stylesheet" type="text/css" />
	

    
	
    
	
	
	
	
	<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>
    
     <link rel="stylesheet"
          type="text/css"
          href="../jqb/jqconfirm/css/jquery-confirm.css"/>
           <link rel="stylesheet" href="prod3.css">
	
	
	
<script>

$(document).ready(function(){
    $(window).resize(function(){
       
       $w  =  $(window).width();
       $h =  $(window).height();
    
      
       localStorage.setItem("c3w", $w);
       localStorage.setItem("c3h", $h);
    });

    $(window).on('mouseout', function () {
      
       $t  =  window.screenTop;
       $l =   window.screenLeft;
      
      	
       localStorage.setItem("c3t", $t);
       localStorage.setItem("c3l", $l);
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
	<div class="portlet-header"></div>
		<div class="portlet-content"  id="InvYTDList" style="width: 60%;" ></div>

	

<script>
var today = new Date();
var year = today.getFullYear();
year = year-2;
	$.getJSON("getRollCode5g.php?OCC=" + year, function (result) {
		var labels = [], pdata = [], cdata = [];
          labels = result.Labels;
          y1data = result.Data1;
          y2data = result.Data2;
          y3data = result.Data3;
          y4data = result.Data4;
          y5data = result.Data5;
          y6data = result.Data6;

          $y1 = result.Y1;
          $y2 = result.Y2;
          $y3 = result.Y3;
          $y4 = result.Y4;
          $y5 = result.Y5;
          $y6 = result.Y6;
	         //   console.log("result", labels, pdata, cdata);
	    


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
	        						text: 'Sales by Month/Year'
	        					},
	        					scales: {
	        						xAxes: [{
	        							display: true,
	        							scaleLabel: {
	        								display: true,
	        								labelString: 'Month'
	        							}
	        						}],
	        			        					
	        						yAxes: [{
	        							scaleLabel: {
	        								display: true,
	        								labelString: 'Sales (/M)'
	        							},
	        							
	        						ticks: {
	        			                  //  suggestedMin: 50,
	        			            //        suggestedMax: 20,
	        			            //        beginAtZero: true,
	        						//		stepSize: 1,
	        			               },
	        							
	        							type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
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


//////////////////////////////////////////////////////////////////////
	        		    //Prepare jTable
	        			$('#InvYTDList').jtable({
	        				
	        				title: 'Daily Invoicing',
	        	           // paging: true,
	        	          // pageSize: 6,
	        	            
	        	           
	        	          //  selecting: true,
	        	            columnResizable: true,
	        	          //  multiselect: true, //Allow multiple selecting
	        	           // selectingCheckboxes: true, //Show checkboxes on first column
	        	           // selectOnRowClick: false, //Enable this to only select using checkboxes
	        				actions: {
	        					listAction: 'getRollCode5.php?action=list'
	        					//createAction: 'getProj.php?action=create',
	        					//updateAction: 'getProj.php?action=update',
	        					//deleteAction: 'getProj.php?action=delete'
	        						
	        				},
	        				toolbar:{               
	        		            items: [{
	        		            	icon: '../images/reload.jpg',
	        		                text: 'Refresh Table',
	        		                click: function () {
	        		                	$('#InvYTDList').jtable('load');
	        		                }
	        		            },
	        		            {
	        		                Tooltip: 'Click here to export this table to excel',
	        		                icon: '../images/excel2.jpg',
	        		                text: 'Export to Excel',
	        		                click: function () {
	        		                	window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=InvYTDList]').html()));
	        		                    e.preventDefault();
	        		                }
	        		            }
	        		            ]},
	        				
	        				
	        				fields: {
	        					RECID: {
	        						key: true,
	        						create: false,
	        						edit: false,
	        						list: false,
	        						delte: false,
	        						list:false
	        					},
	        					MD: {	
	        						title: 'Year',	
	        					},
	        		
	        					JanInv: {																																																																																				
	        						title: 'Jan Invoiced',
	        					},	
	        					JanCom: {																																																																																				
	        						title: 'Jan Commision',
	        					},								
	        					FebInv: {																																																																																				
	        						title: 'Feb Invoiced',
	        					},	
	        					FebCom: {																																																																																				
	        						title: 'Feb Commision',
	        					},
	        					MrcInv: {																																																																																				
	        						title: 'March Invoiced',
	        					},	
	        					MrcCom: {																																																																																				
	        						title: 'March Commision',
	        					},
	        					AprInv: {																																																																																				
	        						title: 'April Invoiced',
	        					},	
	        					AprCom: {																																																																																				
	        						title: 'April Commision',
	        					},
	        					MayInv: {																																																																																				
	        						title: 'May Invoiced',
	        					},	
	        					MayCom: {																																																																																				
	        						title: 'May Commision',
	        					},
	        					JunInv: {																																																																																				
	        						title: 'June Invoiced',
	        					},	
	        					JunCom: {																																																																																				
	        						title: 'June Commision',
	        					},
	        					JulInv: {																																																																																				
	        						title: 'July Invoiced',
	        					},	
	        					JulCom: {																																																																																				
	        						title: 'July Commision',
	        					},
	        					AugInv: {																																																																																				
	        						title: 'Aug Invoiced',
	        					},	
	        					AugCom: {																																																																																				
	        						title: 'Aug Commision',
	        					},
	        					SepInv: {																																																																																				
	        						title: 'Sept Invoiced',
	        					},	
	        					SepCom: {																																																																																				
	        						title: 'Sept Commision',
	        					},
	        					OctInv: {																																																																																				
	        						title: 'Oct Invoiced',
	        					},	
	        					OctCom: {																																																																																				
	        						title: 'Oct Commision',
	        					},
	        					NovInv: {																																																																																				
	        						title: 'Nov Invoiced',
	        					},	
	        					NovCom: {																																																																																				
	        						title: 'Nov Commision',
	        					},
	        					DecInv: {																																																																																				
	        						title: 'Dec Invoiced',
	        					},	
	        					DecCom: {																																																																																				
	        						title: 'Dec Commision',
	        					},
	        				}		
	        			});

	        			//Load person list from server
	        			$('#InvYTDList').jtable('load');
	        	//////////////////////////////////////////////////////////////////////	


	        			
	});
	</script>
</body>

</html>

