g<?php 
require '../uphead.php';
require "UPClass2.php";
$x = UPClass2::getUserData($con);
$sl = $x['SL'];
$cust = $_GET['CUST'];
?>
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
		width: 800px;
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
		$('#YTDSales').jtable({
			
			title: 'Customer YTD Sales 3 year',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'custSalesYTD.php?action=list&CUST='+<?php echo $cust;?>
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#YTDSales').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				INVOICED: {	
					title: 'Invoiced',	
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.INVOICED + '</div>';
					}
				},
				COMMIS: {	
					title: 'Commisions',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.COMMIS + '</div>';
					}	
				},
				TONS: {	
					title: 'Tons Shipped',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.TONS + '</div>';
					}	
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#YTDSales').jtable('load');
//////////////////////////////////////////////////////////////////////	


//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#openOrders').jtable({
			
			title: 'Open Orders',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'custOpenOrders.php?action=list&CUST='+<?php echo $cust;?>
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#openOrders').jtable('load');
	                }
	            }]},
			
			fields: {
				SCODE: {	
					title: "Status Code",	
				},
				MILORD: {	
					title: 'Order',	
			//		display : function(data){
			//			return "<div style='text-align: right;'>" + data.record.INVOICED + '</div>';
			//		}
				},
				SHIP: {	
					title: 'Ship#/line',
			//		display : function(data){
			//			return "<div style='text-align: right;'>" + data.record.COMMIS + '</div>';
			//		}	
				},
				REQDATE: {	
					title: 'Requested by Date',
			//		display : function(data){
			//			return "<div style='text-align: right;'>" + data.record.TONS + '</div>';
			//		},
					ROLLWIDTH: {	
						title: "Roll Width",	
					},	
					CARVIA: {	
						title: "Ship Via",	
					}
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#openOrders').jtable('load');
//////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#MTDSales').jtable({
			title: 'Customer MTD Sales Current Year',
            columnResizable: true,
			actions: {
				listAction: 'custSalesMTD.php?action=list&CUST='+<?php echo $cust;?>				
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#MTDSales').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				INVOICED: {	
					title: 'Invoiced',	
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.INVOICED + '</div>';
					}
				},
				COMMIS: {	
					title: 'Commisions',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.COMMIS + '</div>';
					}	
				},
				TONS: {	
					title: 'Tons Shipped',
					display : function(data){
						return "<div style='text-align: right;'>" + data.record.TONS + '</div>';
					}	
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#MTDSales').jtable('load');
//////////////////////////////////////////////////////////////////////	

//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#custInfo').jtable({
			
			title: 'Customer Information',
            columnResizable: true,
          //  multiselect: true, //Allow multiple selecting
           // selectingCheckboxes: true, //Show checkboxes on first column
           // selectOnRowClick: false, //Enable this to only select using checkboxes
			actions: {
				listAction: 'getCusRec.php?action=list&CUST='+<?php echo $cust;?>
				//createAction: 'getProj.php?action=create',
				//updateAction: 'getProj.php?action=update',
				//deleteAction: 'getProj.php?action=delete'
					
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#custInfo').jtable('load');
	                }
	            }]},
			
			fields: {
				LABEL: {	
						
				},
				VALUE: {	
					
				}
		
					
				
			}		
		});

		//Load person list from server
		$('#custInfo').jtable('load');
//////////////////////////////////////////////////////////////////////	

//////////////////////////////////////////////////////////////////////
	    //Prepare jTable
		$('#TranTime').jtable({
			title: 'Customer MTD Sales Current Year',
            columnResizable: true,
			actions: {
				listAction: 'custTranTime.php?action=list&CUST='+<?php echo $cust;?>,	
				updateAction: 'custTranTime.php?action=update&CUST='+<?php echo $cust;?>,	
				 rowInserted: function(event, data){
		                if (data.record.READONLY == '1'){
		                  data.row.find('.jtable-edit-command-button').hide();
		                }
		              }	
			},
			
			toolbar:{               
	            items: [{
	            	icon: '../images/reload.jpg',
	                text: 'Refresh Table',
	                click: function () {
	                	$('#TranTime').jtable('load');
	                }
	            },
	            {
	            	icon: '../images/reload.jpg',
	                text: 'Set Tran Time from Google Maps',
	                click: function () {
	                url = 'DistanceAPI.php?CUST=' + <?php  echo $cust;?>,
	                		window.open(src=url, 'DAPI', "width=100,height=100");        
	                }
	            },

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

<div class="column">
<div  class="portlet">
		<div class="portlet-header">Customer Information</div>
		<div class="portlet-content"  id="custInfo"  style="width: 90%;"></div>
		

</div>
<div class="column">
<div  class="portlet">
		<div class="portlet-header">Transportation Time</div>
   <div class="portlet-content"  id="TranTime" style="width: 90%;" ></div>
   </div>
   <div  class="portlet">
		<div class="portlet-header">Open Ordersr</div>
   <div class="portlet-content"  id="openOrders" style="width: 90%; height: 400px" ></div>
   </div>
</div>
<?php  if($sl >= 800){ ?>

<div class="column">
<!--	<div class="portlet" id="draggable2"  >  -->
<!--		<div class="portlet-header"></div> -->
	<div  class="portlet">
		<div class="portlet-header">YTD Sales</div>
		<div class="portlet-content"  id="YTDSales" style="width: 90%;" ></div>
		</div>
		<div  class="portlet">
		<div class="portlet-header">MTD Sales</div>
		<div class="portlet-content"  id="MTDSales" style="width: 90%;" ></div>
		</div>
</div>
 
 <?php } ?>





                                                                                                        
                                                                                                        	


 







</body>
</html>
