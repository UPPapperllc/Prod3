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
//$menu = UPClass2::getMenuOptions();
UPClass2::checkMenuOptions($con);
$x = UPClass2::getUserData($con);
$sl = $x['SL'];

$s = "select * from dailyprodopt f1                 
 join dailyprod f2 on f1.pgmid = f2.pgmid 
where USRID = '$user' ";
$r = db2_exec($con, $s);
while ($row = db2_fetch_assoc($r)){
    $pgm = $row['PGM'];
    $$pgm = $row['ACTIVE'];
}

?>

<html>
	<head>
		<title>Production Review</title>
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
		$('#Uinfo').jtable({
			title: 'My Info',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getUserInfo.php'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			
	        toolbar:{               
	            items: [{
	                icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#Uinfo').jtable('load');
	                }
	            }]},
			
			fields: {
				KEY: {
					Key: true,
					create: false,
					edit: false,
					list: true,
					delte: false,
					list:false
				},
LABEL: {																																																																																				
					
				},
				
				VALUE: {																																																																																				
					
				}														
			}		
		});

		//Load person list from server
		$('#Uinfo').jtable('load');


//////////////////////////////////////////////////////////////////////
		   //Prepare uinfo
		$('#DPopt').jtable({
			title: 'My Info',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'DailyProdOPt.php'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			
	        toolbar:{               
	            items: [{
	                icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#DPopt').jtable('load');
	                }
	            }]},
			
			fields: {
				PGMID: {
					
					create: false,
					edit: false,
					list: false,
					delte: false																																																																																			
					
				},
				
				USERID: {																																																																																				
					create: false,
					edit: false,
					list: false,
					delte: false
				},
				
				ACTIVE: {																																																																																				
					title: 'selected',
					width: '2%',
					display : function(data){
						return "<div style='text-align: right;'><input type='checkbox' name='optActive' ID - 'optActive'   onclick='handleClick(this);'  value='" + data.record.PGMID + "'"+ data.record.CHECKED +"> " + data.record.ACTIVE + '</div>';
					},
				},
				
				OPTURL: {																																																																																				
					title: 'Option'
				}	
																	
			}		
		});

		//Load person list from server
		$('#DPopt').jtable('load');


//////////////////////////////////////////////////////////////////////		
	    //Prepare jTable
		$('#ProdList').jtable({
			title: 'Daily Production',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getRollCode.php?action=list'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			
	        toolbar:{               
	            items: [{
	                icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#ProdList').jtable('load');
	                }
	            }]},
			
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
				Rolls: {																																																																																				
					title: 'Rolls',
				},
				Wgt: {
					title: 'Total Weight',
				},
				Tons: {																																																																																				
					title: 'Tons',
				},
				CRolls: {																																																																																				
					title: 'Culled Rolls',
				},
				CWgt: {																																																																																				
					title: 'Culled',
				},
				CTons: {																																																																																				
					title: 'Tons',
				}															
			}		
		});

		//Load person list from server
		$('#ProdList').jtable('load');


//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#ShipList').jtable({
			
			title: 'Daily Shipments',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getRollCode3.php?action=list'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#ShipList').jtable('load');
	                }
	            }]},
			
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
				Rolls: {																																																																																				
					title: 'Rolls',
				},
		
				Tons: {																																																																																				
					title: 'Tons',
				},
															
			}		
		});

		//Load person list from server
		$('#ShipList').jtable('load');
//////////////////////////////////////////////////////////////////////		

//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#InvList').jtable({
			
			title: 'Daily Invoicing',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getRollCode4.php?action=list'
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
	            }]},
			
			
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



//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#whsList').jtable({
			
			title: 'Rolls in Warehouse',
           // paging: true,
          // pageSize: 6,
            
           
          //  selecting: true,
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getRollCode6.php?action=list'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#whsList').jtable('load');
	                }
	            }]},
			
			fields: {
		//		RECID: {
		//			key: true,
		//			create: false,
		//			edit: false,
		//			list: false,
		//			delte: false,
		//			list:false
		//		},
				SCODE: {	
					title: 'Code',	
				},
				GRADE: {	
					title: 'Grade',	
				},
		
				WGT: {																																																																																				
					title: 'Weight',
				},
				CNT: {																																																																																				
					title: 'Qty',
				},	
				
			}		
		});

		//Load person list from server
		$('#whsList').jtable('load');
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
						///////////////////////////////////////////////////////
		    $( "#draggable3" ).draggable({
		    
		    stop: function( event, ui ) {
			   
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////
		    $( "#draggable4" ).draggable({
		    	
		    stop: function( event, ui ) {
			 
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////
		    $( "#draggable5" ).draggable({
		    	
		    stop: function( event, ui ) {
			
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////
		    $( "#draggable6" ).draggable({
		    
		    stop: function( event, ui ) {
			
			    positions[this.id] = ui.position
		        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////
		    $( "#draggable7" ).draggable({
		    	 
		    stop: function( event, ui ) {
	
				    positions[this.id] = ui.position
			        localStorage.positions = JSON.stringify(positions)

			    }
		    });

//////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////		    
		    $( "#draggable1" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable2" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
//////////////////////////////////////////////////////////////////
		    $( "#draggable3" ).resizable({
		    	resize: function(event,ui){
		    		  sizes[this.id] = ui.size
				        localStorage.sizes = JSON.stringify(sizes)
		    	   }
		    });
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

		    <?php 
		    		if ($PTONS == 'Y'){?> 
$c1w = localStorage.getItem("c1w");
$c1h = localStorage.getItem("c1h");
if ($c1w == null) $c1w = 400;
if ($c1h == null) $c1h = 400;
$c1t = localStorage.getItem("c1t");
$c1l = localStorage.getItem("c1l");
if ($c1w == null) $c1x = 500;
if ($c1h == null) $c1y = 500;

		   
		    window.open("C1.php?OCC=30", "DP_1", "toolbar=no,scrollbars=yes,resizable=yes,top=" + $c1t + ",left=" + $c1l + "500,width=" + $c1w + ",height=" + $c1h);
		    
		    <?php }?>
		    <?php 
		    		if ($CTONS == 'Y'){?> 

		    $c2w = localStorage.getItem("c2w");
		    $c2h = localStorage.getItem("c2h");
		    if ($c2w == null) $c2w = 400;
		    if ($c2h == null) $c2h = 400;
		    $c2t = localStorage.getItem("c2t");
		    $c2l = localStorage.getItem("c2l");
		    if ($c2w == null) $c2x = 500;
		    if ($c2h == null) $c2y = 500;

		    		   console.log('c2 Window', $c2w, $c2h);
		    		    window.open("c2.php?OCC=30", "DP_2", "toolbar=no,scrollbars=yes,resizable=yes,top=" + $c2t + ",left=" + $c2l + "500,width=" + $c2w + ",height=" + $c2h);
		    		    <?php }?>
		    		    <?php 
		    		    		if ($YTDINV == 'Y' and $sl >= 800){?> 
		    		    
		    		    $c3w = localStorage.getItem("c3w");
		    		    $c3h = localStorage.getItem("c3h");
		    		    if ($c3w == null) $c3w = 400;
		    		    if ($c3h == null) $c3h = 400;
		    		    $c3t = localStorage.getItem("c3t");
		    		    $c3l = localStorage.getItem("c3l");
		    		    if ($c3w == null) $c3x = 500;
		    		    if ($c3h == null) $c3y = 500;

		    		    		   console.log('c3 Window', $c3w, $c3h);
		    		    		    window.open("c3.php?OCC=30", "DP_3", "toolbar=no,scrollbars=yes,resizable=yes,top=" + $c3t + ",left=" + $c3l + "500,width=" + $c3w + ",height=" + $c3h);
		    		    		    <?php }?>
		    		    		    <?php 
		    		    		    if ($INVLIST == 'Y' and $sl >= 800){?> 
		    		    		    $c4w = localStorage.getItem("c4w");
		    		    		    $c4h = localStorage.getItem("c4h");
		    		    		    if ($c4w == null) $c4w = 400;
		    		    		    if ($c4h == null) $c4h = 400;
		    		    		    $c4t = localStorage.getItem("c4t");
		    		    		    $c4l = localStorage.getItem("c4l");
		    		    		    if ($c4w == null) $c4x = 500;
		    		    		    if ($c4h == null) $c4y = 500;

		    		    		    		//   console.log('Inv List Window', $c3w, $c3h);
		    		    		    		    window.open("tInvList.php?OCC=10", "DP_4", "toolbar=no,scrollbars=yes,resizable=yes,top=" + $c4t + ",left=" + $c4l + "500,width=" + $c4w + ",height=" + $c4h);
		    		    		    		    <?php }?>



		    		    		    		    			    		    		    
		    		    		   		    
		    
		  } );


	 function handleClick(cb) {
		 // console.log("Clicked, new value = " , cb.checked, cb.value);
theURL = 'DPOptchange.php?PGID='+cb.value+'&YN=' + cb.checked;
		  $.ajax({url: theURL, success: function(result){
			  for (var i=0; i<10; i++){
					var childWindowName = "DP_"+i;
					var handle = window.open("",childWindowName);
					if (handle && handle.open && !handle.closed){
						handle.close();
					}
				}


			  
			  window.location.reload(false);
		    }});
		  
		}

	

	
	</script>






  
	
	
</head>
<body>
	<div class="portlet" id="draggable1"  style="width: 50%;" data-id= "20" title = "My Information">My Info
		<div class="portlet-header"></div>
		<div class="portlet-content"  id="Uinfo"  style="width: 90%;"></div>
		
</div>
	<div class="portlet" id="draggable1"  style="width: 50%;" data-id= "20" title = "Report Options">Report Options
		<div class="portlet-header"></div>
		<div class="portlet-content"  id="DPopt"  style="width: 90%;"></div>
		
</div>
<?php  
if ($PRODLIST == 'Y'){?>
	<div class="portlet" id="draggable1"  style="width: 50%;" data-id= "1" title = "Prod_by_Day">Prod by Day
		<div class="portlet-header"></div>
		<div class="portlet-content"  id="ProdList"  style="width: 90%;"></div>
		
</div>
<?php }?>

<?php  
if ($SHIPLIST == 'Y'){?>
	<div class="portlet" id="draggable2"  style="width: 50%;" data-id='2'> Shipments by Day List
		<div class="portlet-header"></div>
		<div class="portlet-content"  id="ShipList" style="width: 90%;" ></div>
		
</div>

<?php }?>




                                                                                                        
 <?php 
if ($WHSLIST == 'Y'){?>                                                                                                       	
                                                                                                        	
 <div class="portlet" id="draggable7"   style="width: 50%;" data-id='7'> Rolls in Warehouse by code/grade
	
		<div class="portlet-header"></div>
		<div class="portlet-content"  id="whsList" style="width: 90%;" ></div>
		
</div>

 <?php }?>






</body>
</html>
