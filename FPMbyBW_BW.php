<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));

$bw = $_GET['BW'];
$mid = $_GET['MID'];


?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>FPM list by a Weight</title>
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
			$('#BWList').jtable({
				title: 'F/M by Weight and Reel for Current Year',
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
					listAction: "getfpmbybw_bw.php?action=list&BW=<?php echo $bw;?>&MID=<?php echo $mid;?>",
				//	createAction:  "getEmpShift.php?action=create",
				//	updateAction: "getEmpShift.php?action=update"
				//	deleteAction: 'getProdSched.php?action=delete'
						
				},
				toolbar:{             
		            items: [{
		            	icon: '../images/reload.jpg',
		                text: 'Refresh Table',
		                click: function () {
		                	$('#BWList').jtable('load');
		                }
		            }]
				},
fields: {
					
					
					EVENTNAME: {	
						title: 'Reel',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: false,
							list: true,
							delte: false
						
						
					},
					BW: {	
						title: 'Weight',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: false,
							list: false,
							delte: false
						
						
					},
					ENTRYON: {	
						title: 'Date/Time',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					
					MID: {	
						title: 'Month',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					},
					VALUE: {	
						title: 'Feet/Min',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: true,
							list: true,
							delte: false
						
						
					} 
},
recordsLoaded: function(event, data) {
    $('.jtable-data-row').click(function() {
   	 var record = $(this).data('record');
        var url = 'Prod_Info.php?REEL=' + record.EVENTNAME ;
        window.open(src=url, 'pinfo', "width=1200,height=1000");
    });
}
			});
			
			//Load person list from server
			$('#BWList').jtable('load');
			
		});

		</script>


		</head>
		<body>
			
		
				
								


		<div id="BWList" ></div>



				

				
							
										
						

								


										
						
										
					

				<!-- Scripts -->
				
			
				
				
				
				
					
					
					<script src="assets/js/jquery.scrollex.min.js"></script>
				





			</body>
			

			
			
		</html>

