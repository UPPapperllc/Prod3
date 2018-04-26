<?php 
if (isset($_GET['OCC'])) $occ = $_GET['OCC']; else $occ = 10;
?>


<html>
	<head>
		<title>Invoiced by Day</title>
			<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>		
<link rel="stylesheet" href="../jqb/jquery-ui.css">			

 <link href="../jqb/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" /> 
	<link href="../jqb/jtable/lib/themes/metro/red/jtable.css" rel="stylesheet" type="text/css" />
	<link href="../jqb/jquery-ui.css" rel="stylesheet" type="text/css" />
	

    
	
    
	
	
	
	
	<script src="../jqb/External/jquery/jquery.js" type="text/javascript"></script>
    <script src="../jqb/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../jqb/jtable/jquery.jtable.js" type="text/javascript"></script>
    
     <link rel="stylesheet"
          type="text/css"
          href="../jqb/jqconfirm/css/jquery-confirm.css"/>
          
          
    	<style>


body {
		min-width: 520px;
	}
	.column {
		width: 300px;
		float: left;
		padding-bottom: 100px;
		border: 1px solid black;
	}

	.portlet {
		margin: 0 1em 1em 0;
		padding: 0.3em;
	}
	.portlet-header {
		padding: 0.2em 0.3em;
		margin-bottom: 0.5em;
		position: relative;
	}
	.portlet-toggle {
		position: absolute;
		top: 50%;
		right: 0;
		margin-top: -8px;
	}
	.portlet-content {
		padding: 0.4em;
	}
	.portlet-placeholder {
		border: 1px dotted black;
		margin: 0 1em 1em 0;
		height: 50px;
	}
	
tr {
	font-size: 18;
	text-align: right;
}
th {
font-size=18;
	align: center;
	background-color: #9a1414;
	color: white
}
.class="jtable-column-header{
	font-size=8;
}
	
	</style>    

    <script type="text/javascript"
            src="../jqb/jqconfirm/js/jquery-confirm.js"></script>
    	
    
		<script type="text/javascript">

		


		
		var fullDate = new Date()
		//console.log(fullDate);
		//Thu Otc 15 2014 17:25:38 GMT+1000 {}
		  
		//convert month to 2 digits
		var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
		  
		var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" +fullDate.getDate()  ;

		

	
	$(window).on('load', function() {
	
		

//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#InvList').jtable({
			
			title: 'Daily Invoicing',
			id: 'IList',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getRollCode4.php?action=list&OCC=<?php echo $occ;?>' 
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#InvList').jtable('load');
	                }
	            },
	            {
	                Tooltip: 'Click here to export this table to excel',
	                icon: '../images/excel.jpg',
	                text: 'Export to Excel',
	                click: function () {
	                	window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=InvList]').html()));
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
					title: 'Date',	
				},
	
		
				Tons: {																																																																																				
					title: 'Tons',
					display : function(data){
						return "<div style='text-align: Center;'>" + data.record.Tons + '</div>';
					}
				},
				Invoiced: {																																																																																				
					title: 'Invoiced',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.Invoiced + '</div>';
					}
				},	
				FrtAlw: {																																																																																				
					title: 'Freight Allowed',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.FrtAlw + '</div>';
					}
				},								
				Discount: {																																																																																				
					title: 'Discount',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.Discount + '</div>';
					}
				},		
				Commish: {																																																																																				
					title: 'Commision',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.Commish + '</div>';
					}
				},
				FrtPpd: {																																																																																				
					title: 'Pre-Paid Freight',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.FrtPpd + '</div>';
					}
				},
			}		
		});
	
		  
		//Load person list from server
		$('#InvList').jtable('load');


	
		
//////////////////////////////////////////////////////////////////////	
	    		    		   		    
		    
	 } );

	$.getJSON("getRollCode4.php?OCC=<?php echo $occ;?>", function (result) {
		var labels = [], pdata = [], cdata = []; 
          y4data = result.Data3;
	
console.log(y4data);	

var div = document.getElementById('InvTtl');

div.innerHTML += '<table class="table.jtable" border = "1"><tr><th>Invoice Total</th><td>' + y4data.Invoiced + '</td></tr>' +
                                      '<tr><th>Commision Total</th><td>' + y4data.Commish + '</td></tr>' + 
                                      '<tr><th>Freight Total</th><td>' + y4data.Freight + '</td></tr></table>' ;


	 } );

	function myFunction() {
	    var x = document.getElementById("occ");
	    window.open('http://10.6.1.11:10090/prod3/tInvList.php?OCC=' + x.value, '_self')
	   
	}
function setup(){
	document.getElementById("occ").value = <?php echo $occ?>;
	
}

$(document).ready(function(){
    $(window).resize(function(){
       
       $w  =  $(window).width();
       $h =  $(window).height();
    
      
       localStorage.setItem("c4w", $w);
       localStorage.setItem("c4h", $h);
    });

    $(window).on('mouseout', function () {
      
       $t  =  window.screenTop;
       $l =   window.screenLeft;
      
      
       localStorage.setItem("c4t", $t);
       localStorage.setItem("c4l", $l);
    });
    
});


	
	</script>






  
	
	
</head>
<body onload='setup();'>

<div>
 Days to Display from 1 to 31 (field exit to change screen):
  <input type="number" id="occ" name="occ" min="1" max="31" onblur="myFunction()" >
  </div>
		<div   id="InvList"  style="width: 90%;"></div>
		<div   id="InvTtl"  style="width: 90%;"></div>
		

  
 
 
		








</body>
</html>

