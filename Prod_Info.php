<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
$user = strtoupper($_SERVER['PHP_AUTH_USER']);

require '../uphead.php';
require "UPClass2.php";
$reel = $_GET['REEL'];
//$menu = UPClass2::getMenuOptions();
//UPClass2::checkMenuOptions($con);

//$s = "select * from dailyprodopt f1
//join dailyprod f2 on f1.pgmid = f2.pgmid
//where USRID = '$user' ";
//$r = db2_exec($con, $s);
//while ($row = db2_fetch_assoc($r)){
//    $pgm = $row['PGM'];
//    $$pgm = $row['ACTIVE'];
//}

?>

<html>
	<head>
		<title>Reel Data for <?php  echo $reel;?></title>
			<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="//code.jquery.com/jquery-3.1.0.slim.min.js"></script>		
<link rel="stylesheet" href="../jqb/jquery-ui.css">			

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
	font-size: 8
}
th {
font-size=7;
	align: center;
	height: 55px;	
}
.class="jtable-column-header{
	font-size=8;
}
div.jtable-main-container > table.jtable > tbody > tr.jtable-data-row > td {
  padding: 1px;
    font-size: 12px;
}
div.jtable-main-container > table.jtable > tbody > tr {
//  background-color: #fff;
    background: transparent;
    color:azure;
    font-size: 12px;
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

		   //Prepare uinfo
		$('#Pinfo').jtable({
			title: 'Reel Information',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'Prod_Info_ByReel.php?REEL=<?php echo $reel;?>',
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			
	        toolbar:{               
	            items: [{
	                icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#Pinfo').jtable('load');
	                }
	            }]},
			
			fields: {

VARIABLE: {		
	title: 'Variable'
					
				},
				
				VALUE: {																																																																																				
					title: 'Value'
				}
,
				
				ENTRYON: {																																																																																				
					title: 'Date/Time'
				},
				
				VARIABLEID: {																																																																																				
					title: 'Variable ID'
				}														
			}		
		});

		//Load person list from server
		$('#Pinfo').jtable('load');


//////////////////////////////////////////////////////////////////////   

		   //Prepare uinfo
		$('#LPinfo').jtable({
			title: 'Roll Information',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'Prod_Info_LPMAST.php?REEL=<?php echo $reel;?>',
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			
	        toolbar:{               
	            items: [{
	                icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#LPinfo').jtable('load');
	                }
	            }]},
			
			fields: {

MILORD: {		
	title: 'Order'
					
				},
				
				SHIPNUM: {																																																																																				
					title: 'Shipment'
				}
,
				
				MILRUN: {																																																																																				
					title: 'Run'
				},
				
				MILLINE: {																																																																																				
					title: 'Item'
				},
				
				GRD8: {																																																																																				
					title: 'Grade'
				},
				
				ROLWGTA: {																																																																																				
					title: 'Weight'
				},
				
				ROLLENA: {																																																																																				
					title: 'Length'
				},
				
				ROLKSQFTA: {																																																																																				
					title: 'K Sq Ft'
				},
				
				ROLWTH: {																																																																																				
					title: 'Width'
				},
				
				ROLFRC: {																																																																																				
					title: 'Width Frac'
				},
				
				LPSTAT: {																																																																																				
					title: 'Status'
				}
,
				
				ROLLID: {																																																																																				
					title: 'Roll ID'
				},
				
				CREATED: {																																																																																				
					title: 'Created Date/Time'
				}														
			}		
		});

		//Load person list from server
		$('#LPinfo').jtable('load');


////////////////////////////////////////////////////////////////////// 





		$( ".column" ).sortable({
			connectWith: ".column",
			handle: ".portlet-header",
			cancel: ".portlet-toggle",
			placeholder: 'ui-state-highlight'
			//revert: true,
	//	    receive: function(event, ui) {
	//	     console.log(ui);
		     
	//	     console.log(ui.item.data("order"));
	//	     console.log(ui.item.data("shipment"));
	//	     console.log(event);
	//	     console.log(event.target.id);
		    
		     
	//	    }
		});

		$( ".portlet" )
			.addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".portlet-header" )
				.addClass( "ui-widget-header ui-corner-all" )
				.prepend( "<span class='ui-icon ui-icon-minusthick portlet-toggle'></span>");

		$( ".portlet-toggle" ).on( "click", function() {
			var icon = $( this );
			icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
			icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
		});



	      
		
});

	 $( function() {
		 var sPositions = localStorage.positions || "{}",
		    positions = JSON.parse(sPositions);
		 var sSizes = localStorage.sizes || "{}",
		 sizes = JSON.parse(sSizes);
		 $.each(positions, function (id, pos) {
			    $("#" + id).css(pos)
			})
			$.each(sizes, function (id, siz) {
			    $("#" + id).css(siz)
			})
						///////////////////////////////////////////////////////
		    $( "#draggable1" ).draggable({
		    
		    stop: function( event, ui ) {
			   
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////
		    $( "#draggable2" ).draggable({
		    
		    stop: function( event, ui ) {
			
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable3" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
		    /*
//////////////////////////////////////////////////////////////////
		    $( "#draggable4" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable5" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable6" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable7" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
		    */

		    

		  

	



		    		    		    		    			    		    		    
		    		    		   		    
		    
		  } );



	

	
	</script>






  
	
	
</head>
<body>


<div class="col-sm-4" >
<!-- 	<div class="portlet" id="draggable1" > -->  		
	<!-- <div class="portlet-header"></div> -->
		<div class="portlet-content"  id="Pinfo"  style="width: 90%;"></div>
		

</div>

<div class="col-sm-4" >
<!-- 	<div class="portlet" id="draggable1" > -->  		
	<!-- <div class="portlet-header"></div> -->
		<div class="portlet-content"  id="LPinfo"  style="width: 90%;"></div>
		

</div>


                                                                                                        







</body>
</html>
		