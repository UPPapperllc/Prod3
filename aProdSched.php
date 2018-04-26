<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require '../uphead.php';
require "UPClass2.php";
//$menu = UPClass2::getMenuOptions();
$customer = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
if (isset ($_GET['Project'])) $project = $_GET['Project']; else $project = 'Default';
//var_dump($customer, $project, $con);




    
 


?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Prod Schedule List</title>
		<meta charset="utf-8" />
		


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
    
              
    	<style>


body {
		min-width: 520px;
	 background: linear-gradient(lightgray,midnightblue); /* Standard syntax (must be last) */
	}

	
tr {

	text-align: center;
	background: linear-gradient(midnightblue,lightgray); /* Standard syntax (must be last) */
}
th {
font-size=18;
	align: center;
	background-color: darkblue;
	color: white
}
.class="jtable-column-header{
	font-size=8;
}
div.jtable-main-container > table.jtable > tbody > tr.jtable-data-row > td {
  padding: 1px;
    font-size: 6px;
}
div.jtable-main-container > table.jtable > tbody > tr {
//  background-color: #fff;
    background: transparent;
    color:azure;
    font-size: 12px;
}
	</style>  
		<script type="text/javascript">

		var fullDate = new Date()
		//console.log(fullDate);
		//Thu Otc 15 2014 17:25:38 GMT+1000 {}
		  
		//convert month to 2 digits
		var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
		  
		var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" +fullDate.getDate()  ;
		$.ajaxSetup({
		    async: false
		});
		
		$.getJSON("getProdSchedsummary.php", function (result) {
			var labels = [], pdata = [], cdata = [];
	          sdate = result.SDate;
	          stime = result.STime;
		
		$(window).on('load', function() {
		
		
		    //Prepare jTable
			$('#ProjectList').jtable({
				title: 'Production Schedule',
	            paging: true,
	            pageSize: 25,
	            sorting: true,
	            defaultSorting: 'USRPROJ ASC',
	            selecting: true,
	            columnResizable: true,
	          //  multiselect: true, //Allow multiple selecting
	           // selectingCheckboxes: true, //Show checkboxes on first column
	           // selectOnRowClick: false, //Enable this to only select using checkboxes
				actions: {
					listAction: 'getProdSched.php?action=list',
					createAction: 'getProdSched.php?action=create',
					updateAction: 'getProdSched.php?action=update',
					deleteAction: 'getProdSched.php?action=delete'
						
				},
				toolbar:{             
		            items: [{
		            	icon: '../images/reload.jpg',
		                text: 'Refresh Table',
		                click: function () {
		                	$('#ProjectList').jtable('load');
		                }
		            },
		            {
		                Tooltip: 'Click here to export this table to excel',
		                icon: '../images/excel2.jpg',
		                text: 'Export to Excel',
		                click: function () {
			                url = "ProdSchedDownload.php";
			                window.open(src=url, 'ProdExce;', "width=100,height=100");
		                    e.preventDefault();
		                }
		            },
		            {
		                Tooltip: 'Show Production Calendar',
		                icon: '../images/cal.jpg',
		                text: 'Prod Calendar',
		                click: function () {
		                	 var url = 'Prod_Cal.php' ;
					            window.open(src=url, 'ProdCal', "width=1200,height=1000");
		                    e.preventDefault();
		                }
		            },
		            {
		                Tooltip: 'Reset Schedule',
		                icon: '../images/reset.jpg',
		                text: 'Reset Schedule',
		                click: function () {
		                	 var url = 'setSchedule.php' ;
					            window.open(src=url, 'ResetSched', "width=400,height=400");
		                    e.preventDefault();
		                }
		            }
		            ]},
				
				
				fields: {
					
					PROD_ID: {
						key: true,
						create: false,
						edit: true,
						list: false,
						delte: true
						
					},
					REV: {	
						title: 'Rev',
						width: '1%',
						defaultValue:'1',
							create: false,
							edit: false,
							list: true,
							delte: false
						
						
					},
				
					MILRUN: {
						title: 'Run ',
						width: '1%',
						
					},
					RUNITEM: {
						title: 'Item/Line ',
						width: '1%',
						
					},
					GRD8: {																																																																																				
						title: 'Grade',
						width: '3%',
						defaultValue: 'New'
						
				//		width: '100px'
					},
					
					ADLINFO: {																																																																																				
						title: 'Info',
						width: '1%',
						type: 'textarea'
						
				//		width: '100px'
					},
					ADLNOTES: {
						title: 'Notes',
						width: '5%',
						type: 'textarea'
						
			//			width: '100px%',	
					}
					,
					
					RUNWGT: {
						title: 'Run Weight',
						create: false,
						edit: false,
						list: false,
						delte: false
						
			//			width: '100px%',	
					},
					RUNTON: {
						title: 'Tons',
						create: false,
						edit: false,
						list: false,
						delte: false
			//			width: '100px%',	
					},
					HRSPERTON: {
						title: 'Hours/Ton',
						create: false,
						edit: false,
						list: false,
						delte: false
				//		width: '100px%',	
					},
					ESTHOURS: {
						title: 'Est Hours',
						width: '1%',
							defaultValue: 1
			
		                    
					},
					SCHDATE: {
						title: 'Sch Date',
						width: '1%',
						 type: 'date',
		                    displayFormat: 'mm/dd/yy',
		                    defaultValue: sdate
					}
					,
					SCHTIME: {
						title: 'Sch Time',
						width: '1%',
						 type: 'time',
		                    displayFormat: 'hh:mm',
		                    defaultValue: stime
		                   
		                    
					}
					,
					RUNSTAT: {
						title: 'Req Date',
							width: '1%',
							create: false,
							edit: false,
							list: true,
							delte: false,
							type: 'date',
		                    displayFormat: 'mm/dd'
					
					}
					,
					TRAND: {
						title: 'Tran Days/<br>Prod By',
							width: '1%',
							create: false,
							edit: false,
							list: true,
							delte: false,
							sorting: false	
					
					}
					,
				//	PDATE: {
				//		title: 'Produce By',
				//			width: '1%',
				//			create: false,
				//			edit: false,
				//			list: true,
				//			delte: false,
				//			sorting: false,
						//	type: 'date',
		                 //   displayFormat: 'mm/dd'
				//	
				//	}
				//	,
				
					SHIFT: {
						title: 'Pri',
						create: true,
						edit: true,
						list: true,
						delte: false,
						width: '1%',
						 defaultValue: 'N'
		                    
					},
					SCHEDSTAT: {
						title: 'Status',
							width: '1%'
				//		width: '100px%',	
					},
					PRODUCED: {
						title: 'Prod <br> Wgt/Rolls',
							width: '1%',
							create: false,
							edit: false,
							list: true,
							delte: false,
							sorting: false	
				//		width: '100px%',	
					}
					,
				//	PCNT: {
				//		title: 'Rolls ',
				//			width: '1%',
				//			create: false,
				//			edit: false,
				//			list: true,
				//			delte: false, 
				//			sorting: false	
				//		width: '100px%',	
				//	}
				//	,
					REQ: {
						title: 'Requested <br> wgt/rolls',
							width: '1%',
							create: false,
							edit: false,
							list: true,
							delte: false,
							sorting: false	
				//		width: '100px%',	
					}
					,
				//	RCNT: {
			//			title: 'Rolls',
			//				width: '1%',
			//					create: false,
			//					edit: false,
			//					list: true,
			//					delte: false,
			//					sorting: false		
			//	 	 
			//		},
				
					SCHTS: {
						title: 'Scheduled Start Date/time',
						create: false,
						edit: false,
						list: false,
						delte: false,
						type: 'timestamp',
						displayFormat: 'mm/dd - hh:mm',
		                  	
					},
					SCHETS: {
						title: 'Scheduled End Date/time',
						create: false,
						edit: false,
						list: false,
						delte: false
		                    
					},
					LCDT: {
						title: 'Last Changed Date/Time',
						create: false,
						edit: false,
						list: false,
						delte: false
	                   
					}
				},
				 //Register to selectionChanged event to hanlde events
				   selectionChanged: function () {
			            var $selectedRows = $('#ProjectList').jtable('selectedRows');
			            $selectedRows.each(function () {

			               var record = $(this).data('record');
			                var keyid = record.PROD_ID;
			                var name = record.TASKTITLE;
			                var url = 'SchedDetail.php?ID=' + record.PROD_ID ;
			            window.open(src=url, 'detail', "width=1200,height=1000");




			                
			            });
			        }
			
					
			});
				
			//Load person list from server
			$('#ProjectList').jtable('load');

	
	
			});
  
		});

		function newRun(){
			var x = document.getElementById("newrun");
		  var mr =   x.value
		  if ($.trim(x.value) !== ''){
		  url = "AddtoSchedule.php?MR=" + mr;
          window.open(src=url, 'NewRun;', "width=400,height=400");
		  }

	


		  
		}
		
	</script>	
	
		

		
	</head>
	<body>



	<div>
	<table border = '1'><tr><th>Quick Add</th><td><input type="number" id="newrun" onblur="newRun()"></td></tr></table>
	</div>					

				
								
						


<div id="ProjectList" style="width: 1600px;"></div>



		

		
					
								
				

						


								
				
								
			

		<!-- Scripts -->
		
	
		
		
		
		
			
			
			<script src="assets/js/jquery.scrollex.min.js"></script>
		

 <upmenu class="floating-menu">
   <div class="dropdown">
  <button class="dropbtn">UP Functions</button>
  <div class="dropdown-content">
    <?php  echo $menu;?>
  </div>
</div>
  </upmenu>



	</body>
	

	
	
</html>