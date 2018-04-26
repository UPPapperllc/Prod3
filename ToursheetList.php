<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require '../uphead.php';
require "UPClass2.php";
// $menu = UPClass2::getMenuOptions();
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));
if (isset ($_GET['Project'])) $project = $_GET['Project']; else $project = 'Default';
//var_dump($customer, $project, $con);

$currentDate = date('Y-m-d');
if (isset($_GET['StartDate']))$indate = substr($_GET['StartDate'],0,10); else $indate = date('Y-m-d');
if (isset($_GET['Shift'])){
    $inshift = $_GET['Shift'];
    if (trim($inshift) == 'Day'){
        $stime  = '06.00.00';
        $etime = '17.59.59';
        $fdate = $indate;
        $tdate = $indate;
    } else {
        $inshift = 'Nite';
        $stime  = '18.00.00';
        $etime = '05.59.59';
        $thrudate1 = strtotime($indate);
        $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
        $fdate = $indate;
    }
    
    
}else{
    $ctime = date('H:i:s');
    if ($ctime >= '06:00:00' and $ctime < '18:00:00'){
        $inshift = 'Day';
        $stime  = '06.00.00';
        $etime = '17.59.59';
        $fdate = date('Y-m-d',strtotime($indate));
        $tdate = date('Y-m-d',strtotime($indate));
    } else {
        $inshift = 'Nite';
        $stime  = '18.00.00';
        $etime = '05.59.59';
        $thrudate1 = strtotime($indate);
        $tdate = date('Y-m-d', strtotime("+1 day", $thrudate1));
        $fdate = date('Y-m-d',strtotime($indate));
    }
}

$thrudate = strtotime($indate);
$td = date('Y-m-d',$thrudate);

if (trim($inshift) == 'Day') {
    $inshiftday = 'checked';
    $inshiftnite = '';
} else {
    $inshiftday = '';
    $inshiftnite = 'checked';
}
$s = "Select * from shiftschedule f1
 where shiftdate = '$indate'";
$r = db2_exec($con, $s);
$shiftrow = db2_fetch_assoc($r);
if (trim($inshift) == 'Day')$shiftid = trim($shiftrow['SHIFTDAY']); else $shiftid = trim($shiftrow['SHIFTNIGHT']);
$s = "Select * from EmpSchedule where Shiftid = '$shiftid'";
$r = db2_exec($con, $s);
$schrow = db2_fetch_assoc($r);
$email = $schrow['REPORTEMAIL'];



$actual_link =  "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";





?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Toursheet Shift</title>
		<meta charset="utf-8" />
		 <meta http-equiv="refresh" content="600; URL=<?php echo $actual_link;?>">
		


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

.tdhead{
	display: table-cell;
    padding: 5px 20px 5px 10px;
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
  width: 1200px;
  height: 350px;	
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
  height: 350px;	
  -moz-column-gap:40px;
  -webkit-column-gap:40px;
  column-gap:40px;
  -moz-column-count:2;
  -webkit-column-count:2;
  column-count:2;
}
form.jtable-dialog-form div.jtable-input-field-container {
	display: block;
	  column-count:2;
    padding: 2px 0px 3px 0px;
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
					listAction: "Toursheet_shift.php?StartDate=<?php echo $indate;?>&Shift=<?php echo $inshift;?>&action=list",
					createAction:  "Toursheet_shift.php?StartDate=<?php echo $indate;?>&Shift=<?php echo $inshift;?>&action=create",
					updateAction: "Toursheet_shift.php?StartDate=<?php echo $indate;?>&Shift=<?php echo $inshift;?>&action=update",
				//	deleteAction: 'getProdSched.php?action=delete'
						
				},
				toolbar:{             
		            items: [{
		            	icon: '../images/reload.jpg',
		                text: 'Refresh Table',
		                click: function () {
		                	$('#ShiftList').jtable('load');
		                }
		            },
		            {
		            	icon: '../images/Excel2.jpg',
		                text: 'Toursheet Report in Excel',
		                click: function () {
			                email = document.getElementById('em');
			                sd =  document.getElementById('sd');
			                shift = document.getElementById('shift');
			                shiftid = document.getElementById('sid');
			                
			               url = 'http://10.6.1.11/intranet/toursheet/getV1xls.php?StartDate=' + sd.value + '&Shift=' + shift.value + '&DTS=1&SHIFTID='+sid.value+'&EMAIL=' + email.value;
			               window.open(url, 'toursheetxls');
		                }
		            }
		            ,
		            {
		            	//icon: '../images/Excel2.jpg',
		                text: 'Info',
		                click: function () {
			                
			                
			               window.open('Quick_Reference.htm', 'info');
		                }
		            }
		            ,
		            {
		            	icon: '../images/sched.png',
		                text: 'Shift Schedule',
		                click: function () {
			           ;
			               url = 'http://10.6.1.11/intranet/toursheet/simple-cal/Example/basic.php';
			               window.open(url, 'shiftsched');
		                }
		            }
		            ,
		            {
		            	icon: '../images/emp.png',
		                text: 'Employee/Shift Control',
		                click: function () {
			           ;
			               url = 'EmpShift.php';
			               window.open(url, 'empshift');
		                }
		            }
		            ]},
				
			         
				fields: {
					
					SHEETID: {
						key: true,
						create: false,
						edit: false,
						list: false,
						delte: true
						
					},
					SHIFT: {	
						title: 'Shift',
						width: '1%',
					//	defaultValue:'1',
							create: false,
							edit: false,
							list: true,
							delte: false
						
						
					},
					COMMENT: {
						title: 'Comment',
							width: '5%',
							create: true,
							edit: true,
							list: true,
							delte: false,
							sorting: false,	
							type: 'textarea'
				//		width: '100px%',	
					},
					GRADE: {
						title: 'Grade ',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						defaultValue: ' '
						
					},
					BW: {																																																																																				
						title: 'Basis Wgt',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
							console.log('Data: ', data);
								if (data.value == 0) {
								BW = 0;
						} else {BW=data.record.BW}
							return "<input type = 'number' NAME= 'BW' id='BW' VALUE=" + BW + '>';
						},
					defaultValue: 0
					},
					
		            
					
					MILRUN: {																																																																																				
						title: 'Run',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
								if (data.value == 0) {
								MILRUN = 0;
						} else {MILRUN=data.record.MILRUN}
							return "<input type = 'number' NAME='MILRUN' id='MILRUN' VALUE=" + MILRUN + '>';
						},
					defaultValue: 0
					},

					ITEM: {																																																																																				
						title: 'Item',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
								if (data.value == 0) {
								ITEM = 0;
						} else {ITEM=data.record.ITEM}
							return "<input type = 'number' NAME='ITEM' id='ITEM' VALUE=" + ITEM + '>';
						},
					defaultValue: 0
					},
					REELCODE: {
						title: 'Reel',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						defaultValue: ' '
					}
					,
					
					REEL: {
						title: 'Reel <br> Number',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
					
						input : function(data){
								if (data.value == 0) {
								REEL = 0;
						} else {REEL=data.record.REEL}
							return "<input type = 'number' NAME='REEL' id='REEL' VALUE=" + REEL + '>';
						},	
						defaultValue: 0				
			//			width: '100px%',	
					},
					
					EVENTDATE: {
						title: 'Event Date',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						type: 'date',
		                displayFormat: 'mm/dd/yy',
						defaultValue: currentDate
					},
					EVENTTIME: {
						title: 'Event<br>Time',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						height: '100px',
						input : function(data){
							EVENTTIME = currentTime;
							console.log('data: ', data);
								if (data.value == currentTime) {
									EVENTTIME = currentTime;
						} else {EVENTTIME=data.record.EVENTTIME}
							return "<input type = 'time' NAME='EVENTTIME' id='EVENTTIME' VALUE=" + EVENTTIME + '>';
						},
						defaultValue: currentTime
				//		width: '100px%',	
					},
					PTIME: {
						title: 'Prod <br>Time(min)',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
								if (data.value == 0) {
								PTIME = 0;
						} else {PTIME=data.record.PTIME}
							return "<input type = 'number' NAME='PTIME' id='PTIME' VALUE=" + PTIME + '>';
						},
						defaultValue: 0
			
		                    
					},
					DTIME: {
						title: 'Down<br>Time (min)',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
								if (data.value == 0) {
								DTIME = 0;
						} else {DTIME=data.record.DTIME}
							return "<input type = 'number' NAME='DTIME' id='DTIME' VALUE=" + DTIME + '>';
						},
						defaultValue: 0
					},
					
					
					//  PWGT, ECODE, ECAT, COMMENT, SET, EID
					PWGT: {
						title: 'Prod<br>Weight',
						width: '1%',
						create: true,
						edit: true,
						list: true,
						delte: false,
						input : function(data){
								if (data.value == 0) {
								PWGT = 0;
						} else {PWGT=data.record.PWGT}
							return "<input type = 'number' NAME='PWGT' id='PWGT' VALUE=" + PWGT + '>';
						},
						defaultValue: 0
		                   
		                    
					}
					,
					TID: {
						title: 'Code',
						options: 'getCode.php',
							width: '1%',
							create: true,
							edit: true,
							list: true,
							delte: false,
							sorting: false	
					
					}
					,
					ECODE: {
						title: 'Code',
							width: '1%',
							create: false,
							edit: false,
							list: false,
							delte: false,
							
						//	type: 'date',
		                //    displayFormat: 'mm/dd'
					
					}
					,
					ECAT: {
						title: 'Category',
						options: 'getCat.php',
							width: '1%',
							create: false,
							edit: false,
							list: false,
							delte: false,
							sorting: false	
					
					}
					,
		
				
					SET: {
						title: 'Set <br> of Change',
						create: true,
						edit: true,
						list: true,
						delte: false,
						width: '1%',
						input : function(data){
								if (data.value == 0) {
								SET = 0;
						} else {SET=data.record.SET}
							return "<input type = 'number' NAME='SET'id='SET' VALUE=" + SET + ' title="Use Set to inform the system what set you changed the roll grade on">';
						},
						 defaultValue: 0
		                    
					},
					EID: {
						title: 'Event<br>ID',
							width: '1%',
								create: false,
								edit: false,
								list: true,
								delte: false,
				//		width: '100px%',	
					}
					
					
				
				
					
				},
				 //Register to selectionChanged event to hanlde events
				   selectionChanged: function () {
			            var $selectedRows = $('#ProjectList').jtable('selectedRows');
			            $selectedRows.each(function () {

			               var record = $(this).data('record');
			        //        var keyid = record.PROD_ID;
			        //        var name = record.TASKTITLE;
			        //        var url = 'SchedDetail.php?ID=' + record.PROD_ID ;
			        //    window.open(src=url, 'detail', "width=1200,height=1000");




			                
			            });
			        }
			
					
			});
				
			//Load person list from server
			$('#ShiftList').jtable('load');

	
	
		//	});
  
		});

		function newQC(){
			var x = document.getElementById("QC");
		  var QC =   x.value
		  if ($.trim(x.value) !== ''){
		  url = "Toursheet_Shift.php?action=create&Shift=<?php echo $inshift;?>&QC=" + QC;
		  
          window.open(src=url, 'NewQC;', "width=400,height=400");
          
		  }
		}
	function newSheet(){
		var xdate = document.getElementById("newDate");
		var xday = document.getElementById('shiftday');
		var xnite = document.getElementById('shiftnite');

		console.log(xdate.value, xday.checked, xnite.checked);
		  if (xday.checked) shift='Day'; else shift='Nite';

	url = 'ToursheetList.php?StartDate=' + xdate.value + 'T00%3A00%3A00&Shift=' + shift;
	window.open(url,"_self")
	}
	</script>	
	
		

		
	</head>
	<body>
<script>
var windowName = 'userMenu'; 
var viewportwidth = window.innerWidth;
var viewportheight = document.documentElement.clientHeight;
window.resizeBy(-200,0);
//window.moveTo(0,0);








</script>
<div><table align="right"><tr>
<?php 
	echo" <td>  Send Report to: <input type ='text' id='em' value='$email'></td></tr></table></div>";
	?>

	<div>
	<table border = '1'><tr><th>Quick comment</th><td><textarea  id="QC" onblur="newQC()" title = "Type a comment and press Tab comment will be created with current date and time"></textarea></td></tr></table>
	</div>	
	
	<div >
	<table><tr><td class='tdhead'>
	
	Change Date <input type='date' id='newDate' name='newDate' value = '<?php echo $indate; ?>'> </td><td class='tdhead'> 

  <input type="radio" id='shiftday' name="newShift" value="Day" <?php echo $inshiftday;?> > Day
  <input type="radio" id='shiftnite' name="newShift" value="Nite" <?php echo $inshiftnite; ?>> Night<br> </td>
	
<td class='tdhead'>	 <input type="button" onClick= "newSheet()" value="Change Toursheet"> </td></tr></table></div>

			

				
	<div id="divCheckbox" style="display: none;">
	
	<?php 
	echo"<input type='text' id='sid' value='$shiftid'>";
	echo"<input type ='text' id='sd' value='$indate'>";
 echo"<input type ='text' id='shift' value='$inshift'>";
	?>
	</div>							
						


<div id="ShiftList" style="width: 1600px;"></div>



		

		
					
								
				

						


								
				
								
			

		<!-- Scripts -->
		
	
		
		
		
		
			
			
			<script src="assets/js/jquery.scrollex.min.js"></script>
		





	</body>
	

	
	
</html>