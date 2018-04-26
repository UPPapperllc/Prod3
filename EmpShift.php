<?php 
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));




?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Toursheet Shift</title>
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

#jtable-create-form {
  display: inline-grid-col;
  width: 1400px;
  height: 450px;	
  -moz-column-gap:40px;
  -webkit-column-gap:40px;
  column-gap:40px;
  -moz-column-count:2;
  -webkit-column-count:2;
  column-count:2;
}
#jtable-edit-form {
  display: inline-grid-col;
  width: 1200px;
  height: 360px;	
  -moz-column-gap:40px;
  -webkit-column-gap:40px;
  column-gap:40px;
  -moz-column-count:2;
  -webkit-column-count:2;
  column-count:2;
}
form.jtable-dialog-form div.jtable-input-field-container {
	display: block;
	  column-count:3;
    padding: 2px 2px 3px 0px;
    border-bottom: 1px solid #ddd;
}

	</style>  
		<script type="text/javascript">

		var fullDate = new Date()
		//console.log(fullDate);
		//Thu Otc 15 2014 17:25:38 GMT+1000 {}
		  
		//convert month to 2 digits
		var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
		  
		var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" +fullDate.getDate()  ;
		var currentTime = ('0' + fullDate.getHours()).slice(-2)  + ':' + ('0' + fullDate.getMinutes()).slice(-2) +':'+ ('0' + fullDate.getSeconds()).slice(-2);
		
		$.ajaxSetup({
		    async: false
		});
		
	//	$.getJSON("getProdSchedsummary.php", function (result) {
	//		var labels = [], pdata = [], cdata = [];
	 //         sdate = result.SDate;
	  //        stime = result.STime;
		
		$(window).on('load', function() {


		    //Prepare jTable
			$('#ShiftList').jtable({
				title: 'Toursheet',
	          //  paging: true,
	          //  pageSize: 25,
	          //  sorting: true,
	          //  defaultSorting: 'USRPROJ ASC',
	            selecting: true,
	            columnResizable: true,
	          //  multiselect: true, //Allow multiple selecting
	           // selectingCheckboxes: true, //Show checkboxes on first column
	           // selectOnRowClick: false, //Enable this to only select using checkboxes
	        	actions: {
					listAction: "getEmpShift.php?action=list",
				//	createAction:  "getEmpShift.php?action=create",
					updateAction: "getEmpShift.php?action=update"
				//	deleteAction: 'getProdSched.php?action=delete'
						
				},
				toolbar:{             
		            items: [{
		            	icon: '../images/reload.jpg',
		                text: 'Refresh Table',
		                click: function () {
		                	$('#ShiftList').jtable('load');
		                }
		            }]
				},
fields: {
					
					SHIFTID: {
						title: 'Shift ID',
						width: '1%',
						key: true,
						create: false,
						edit: true,
						list: true,
						delte: true,
						input : function(data){
						return "<input type = 'TEXT' readonly NAME='SHIFTID' id='SHIFTID' VALUE=" + data.record.SHIFTID + '>';
					}
						
					},
					SHIFTNAME: {	
						title: 'Shift Name',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: false,
							list: false,
							delte: false
						
						
					},
					SHIFTIMG: {	
						title: 'Shift Image',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: false,
							list: false,
							delte: false
						
						
					},
					REPORTEMAIL: {	
						title: 'Report Send To',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					SUPERVISOR: {	
						title: 'Shift Super',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					MACHTENDER: {	
						title: 'Mch Tender',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					BACKTENDER: {	
						title: 'Back Tender',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					THIRDHAND: {	
						title: '3rd Hand',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					FOURTHHAND: {	
						title: '4th Hand',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					FIFTHHAND: {	
						title: '5th Hand',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					LEFTHAND: {	
						title: 'Alternate 1',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					RIGHTHAND: {	
						title: 'Alternate 2',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					LAB: {	
						title: 'Lab',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					PULPOPERATOR: {	
						title: 'Pulp',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					MATERIALHANDLER1: {	
						title: 'Mat 1',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					MATERIALHANDLER2: {	
						title: 'Mat 2',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					LOADER1: {	
						title: 'Loader  1',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					LOADER2: {	
						title: 'Loader 2',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					ROLLFINISHER: {	
						title: 'Finisher',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					WTPOPERATOR: {	
						title: 'WTP',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					BOILEROPERATOR: {	
						title: 'Boiler',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					OPERATIONSRELIEF: {	
						title: 'Ops Relieve',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					}
}
			});
			
			//Load person list from server
			$('#ShiftList').jtable('load');
			
		});

		</script>


		</head>
		<body>
			
		
				
								


		<div id="ShiftList" style="width: 1600px;"></div>



				

				
							
										
						

								


										
						
										
					

				<!-- Scripts -->
				
			
				
				
				
				
					
					
					<script src="assets/js/jquery.scrollex.min.js"></script>
				





			</body>
			

			
			
		</html>

