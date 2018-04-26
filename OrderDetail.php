<?php 
require '../uphead.php';
require "UPClass2.php";

$ord = $_GET['O'];
$shp = $_GET['S'];
?>

<html>
	<head>
		<title>Order/Shipment Information </title>
			            <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
		width: 400px;
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
/* unvisited link */
a:link {
    color: white;
}

/* visited link */
a:visited {
    color: yellow;
}

/* mouse over link */
a:hover {
    color: hotpink;
}

/* selected link */
a:active {
    color: brightblue;
}
	
	</style>

    <script type="text/javascript"
            src="../jqb/jqconfirm/js/jquery-confirm.js"></script>
    	
    	<script>
	$( function() {
		$( ".column" ).sortable({
			connectWith: ".column",
			handle: ".portlet-header",
			cancel: ".portlet-toggle",
			placeholder: 'ui-state-highlight',
			//revert: true,
		    receive: function(event, ui) {
		     console.log(ui);
		     
		     console.log(ui.item.data("order"));
		     console.log(ui.item.data("shipment"));
		     console.log(event);
		     console.log(event.target.id);
		    
		     
		    }
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
		 
	} );
	</script>
    
    
    
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
		$('#header').jtable({
			
			title: 'Header Information',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderHeader.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#header').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				VALUE: {
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.VALUE + '</div>';
					},	
					
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#header').jtable('load');
//////////////////////////////////////////////////////////////////////	
//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#detail').jtable({
			
			title: 'Detail Information',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderDetails.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#detail').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				VALUE: {
					display : function(data){
					return "<div style='text-align: right;'>" + data.record.VALUE + '</div>';
				},	
					
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#detail').jtable('load');
//////////////////////////////////////////////////////////////////////	
//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#TranTime').jtable({
			title: 'Transporation Time to Customer',
            columnResizable: true,
			actions: {
				listAction: 'custTranTime.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'	
		//		updateAction: 'custTranTime.php?action=update&CUST='	
		//		 rowInserted: function(event, data){
		 //               if (data.record.READONLY == '1'){
		 //                 data.row.find('.jtable-edit-command-button').hide();
		 //               }
		 //             }	
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#TranTime').jtable('load');
	                }
	            }
	           

	            ]},

	           
	            
			
			fields: {
				ID: {
					key: true,
					edit: true,
					list: false,
					 input: function (data) {
					        if (data.record.ID) {
					            return '<input type="text" readonly class="jtable-input-readonly" name="ID" value="' + data.record.ID + '"/>';
					        } else {
					            //nothing to worry about here for your situation, data.value is undefined so the else is for the create/add new record user interaction, create is false for your usage so this else is not needed but shown just so you know when it would be entered
					        }
					    }
					
					
						
				},
				LABEL: {
					edit: true,
					list: true,
					 input: function (data) {
					        if (data.record.LABEL) {
					            return '<input type="text" readonly class="jtable-input-readonly" name="LABEL" value="' + data.record.LABEL + '"/>';
					        } else {
					            //nothing to worry about here for your situation, data.value is undefined so the else is for the create/add new record user interaction, create is false for your usage so this else is not needed but shown just so you know when it would be entered
					        }
					    }
					
						
				},
				VALUE: {	
					edit: true,
					list: true,
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.VALUE + '</div>';
					},
					 input: function (data) {
					        if (data.record.VALUE) {
					            return '<input type="text" '+ data.record.READONLY + ' tooltip="' + data.record.TIP + '" name="VALUE" value="' + data.record.VALUE + '"/>';
					        } else {
					            //nothing to worry about here for your situation, data.value is undefined so the else is for the create/add new record user interaction, create is false for your usage so this else is not needed but shown just so you know when it would be entered
					        }
					    }
				},
				READONLY: {
					edit: false,
					list: false,
					  
				}
						
				
			}		
		});

		//Load person list from server
		$('#TranTime').jtable('load');
//////////////////////////////////////////////////////////////////////	
//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#mfgsum').jtable({
			
			title: 'Manufactued Summary',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderMfgSum.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#mfgsum').jtable('load');
	                }
	            }]},
		         // STATUS, ROLLS, WGT, LEN, SQFT, WP
			fields: {
				STATUS: {
					title: 'Status',	
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.STATUS + '</div>';
					},
				},
				ROLLS: {	
					title: 'Rolls',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.ROLLS + '</div>';
					},
				},
				WGT: {	
					title: 'Wgt',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.WGT + '</div>';
					},
				}
				,
				LEN: {	
					title: 'Length',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.LEN + '</div>';
					},
				}
				,
				SQFT: {	
					title: 'K/SqFt',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.SQFT + '</div>';
					},
				},
				WP: {	
					title: '%of Ord',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.WP + '</div>';
					},
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#mfgsum').jtable('load');
//////////////////////////////////////////////////////////////////////	
//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#ordshp').jtable({
			
			title: 'Detail Information',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderShp.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#ordshp').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				VALUE: {
					display : function(data){
					return "<div style='text-align: right;'>" + data.record.VALUE + '</div>';
				},	
					
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#ordshp').jtable('load');
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#ordrun').jtable({
			
			title: 'Manufacturing',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderRUN.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>'
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#ordrun').jtable('load');
	                }
	            }]},
	            // MILRUN, SCHDT, STATUS, HOURS
			fields: {
				MILRUN: {
					title: "Run",	
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.MILRUN + '</div>';
					},
						
				},
				SCHDT: {
					title: 'Scheduled',
					display : function(data){
					return "<div style='text-align: right;'>" + data.record.SCHDT + '</div>';
				},	
					
				},
				STATUS: {
					title: 'Status',
					display : function(data){
					return "<div style='text-align: right;'>" + data.record.STATUS + '</div>';
				},	
					
				},
				HOURS: {
					title: 'Est Hours',
					display : function(data){
					return "<div style='text-align: right;'>" + data.record.HOURS + '</div>';
				},	
					
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#ordrun').jtable('load');
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#coa').jtable({
			
			title: 'COA Report',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'OrderCOA.php?action=list&O=<?php echo $ord. '&S=' . $shp;?>',
				//createAction: 'getProj.php?action=create&O=<?php echo $ord. '&S=' . $shp;?>' 
				//updateAction: 'getProj.php?action=update&O=<?php echo $ord. '&S=' . $shp;?>'
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#coa').jtable('load');
	                }
	            }]},
	            // MILRUN, SCHDT, STATUS, HOURS
			fields: {
				filename: {
					title: "COA",	
					display : function(data){
						return "<div vlink = 'yellow' style='color: white;'><a href='http://10.6.1.11/HarrisData/EIP/" + data.record.PATH +"'>" + data.record.FILENAME + '</a></div>';
					//	http://10.6.1.11/HarrisData/EIP/Attachments/PR/Customer/0317458/K24368%20-%2014_%20American%20Twisting%20Company.pdf
					//  http://10.6.1.11/HarrisData/EIP/attachments/PR/Customer/0317458/K24368%20-%2014_American%20Twisting%20Company.pdf
					}
					
						
				}
				
				}
		
					
				
				
		});

		//Load person list from server
		$('#coa').jtable('load');
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
	 });
						///////////////////////////////////////////////////////
		    $( "#draggable1" ).draggable();
		    
		 //   stop: function( event, ui ) {
			   
		//	    positions[this.id] = ui.position
		 //       localStorage.positions = JSON.stringify(positions)///

		//	    }
		//    });

//////////////////////////////////////////////////////////////////
						///////////////////////////////////////////////////////
		    $( "#draggable2" ).draggable();
		    
		   // stop: function( event, ui ) {
			
			//    positions[this.id] = ui.position
		    //    localStorage.positions = JSON.stringify(positions)

			 //   }
		   // });

//////////////////////////////////////////////////////////////////





//////////////////////////////////////////////////////////////////		    
		    $( "#draggable1" ).resizable();
		    //	resize: function(event,ui){
		    //		  sizes[this.id] = ui.size
			//	        localStorage.sizes = JSON.stringify(sizes)
		    //	   }
		   // });
//////////////////////////////////////////////////////////////////
		    $( "#draggable2" ).resizable();
		    //	resize: function(event,ui){
		   // 		  sizes[this.id] = ui.size
		//		        localStorage.sizes = JSON.stringify(sizes)
		  //  	   }
		   // });
//////////////////////////////////////////////////////////////////

	

	
	</script>






  
	
	
</head>
<body>


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">UP Paper LLC.</a>
    </div>

    <button class="btn btn-Warning navbar-btn" onclick = "window.open('sendCOA.php?O=<?php echo $ord;?>&S=<?php  echo $shp;?>')">Create COA</button>
    <button class="btn btn-Danger navbar-btn" onclick = "window.open('MarkOrderClosedX.php?O=<?php echo $ord;?>&S=<?php  echo $shp;?>')">Mark Order Closed (X)</button>
    
  </div>
</nav>

<div class="column">
		
			<div  class="portlet">
		<div class="portlet-header">Header</div>
		<div class="portlet-content"  id="header"  style="width: 90%;"></div>
	</div>
		

</div>
<div class="column"> 
	<div  class="portlet">
		<div class="portlet-header">Detail</div>
<div class="portlet-content"  id="detail"  style="width: 90%;"></div>
</div>
	<div  class="portlet">
		<div class="portlet-header">Mfg Summary</div>
<div class="portlet-content"  id="mfgsum"  style="width: 90%;"></div>
   </div>
   	<div  class="portlet">
		<div class="portlet-header">Manufacturing/Schedule</div>
		<div class="portlet-content"  id="ordrun" style="width: 90%;" ></div>
		</div>
		<div  class="portlet">
		<div class="portlet-header">COA</div>
		<div class="portlet-content"  id="coa" style="width: 90%;" ></div>
		</div>	
</div>
<div class="column" >
	<div  class="portlet">
		<div class="portlet-header">Trans Time</div>
		<div class="portlet-content"  id="TranTime" style="width: 90%;" ></div>
		</div>
		<div  class="portlet">
		<div class="portlet-header">Shipping</div>
		<div class="portlet-content"  id="ordshp" style="width: 90%;" ></div>
		</div>
	
		
</div>
 



</body>
</html>
