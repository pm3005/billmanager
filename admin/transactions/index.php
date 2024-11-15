<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php
	$status = isset($_GET['status']) ? $_GET['status'] : '';
	if(isset($_SESSION['userdata'])){
		$login_user = $_SESSION['userdata']['username']; 
	}					
?>
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
        
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>

<div class="card card-outline rounded-0 card-primary">
	<div class="card-header">
		<h3 class="card-title">Billing List</h3>
		<div class="card-tools">
			<a class="btn btn-sm btn-flat btn-primary" id="create_new" href="./?page=transactions/manage_transaction"><i class="fa fa-plus"></i> Add New Bill</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
		<div class="text-left" id="button-panel">
			<!-- <button class="btn btn-default border btn-flat" id="print" title="Print"><i class="fa fa-print"></i></button> -->
		</div>
		<div class="text-right">
                
        </div>
        <div class="container-fluid" id="outprint">
		<form id="frmUser" name="frmUser" method="post" action="">
			<table id="recordListing" class="table table-hover table-striped table-bordered" style="width: 100%">
				<!-- <colgroup>
					<col width="5%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup> -->
				<thead>
					<tr>
						<th></th>
						<th>Date Added</th>
						<th>Ref Code</th>
						<th>Supplier</th>
						<th>Package</th>
						<th>Weight</th>
						<th>Origin</th>
						<th>Destination</th>
						<th>ETA</th>
						<th>ETD</th>
						<th>AWB/BL/Trucking No</th>
						<th>Status</th>
						<th>Remarks</th>
						<th>Last Update By</th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>				
				
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th>Ref Code</th>
						<th>Supplier</th>
						<th></th>
						<th></th>
						<th>Origin</th>
						<th>Destination</th>
						<th></th>
						<th></th>
						<th>AWB/BL/Trucking No</th>
						<th>Status</th>
						<th>Remarks</th>
						<th>Last Update By</th>
						<!-- <th></th> -->
					</tr>
				</tfoot>
			</table>
		</form>
		</div>
		</div>
	</div>
</div>
<noscript id="print-head">
<div>
    <style>
        #sys-logo{
            height:150px;
            width:150px;
            object-fit:scale-down;
            object-position: center center;
        }		
    </style>
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-1">
            <img src="<?= validate_image($_settings->info('logo')) ?>" alt="" class="img-fluid w-100" id="sys-logo">
        </div>
        <div class="col-10 text-center">
            <h5 class="text-center m-0"><b><?= $_settings->info('name') ?></b></h5>
            <div class="text-center"><b>Shipment List</b></div>
        </div>
    </div>
</div>
<hr>
</noscript>
<script>
	$(document).ready(function(){
		
		// $('.table').dataTable({
		// 	columnDefs: [
		// 			{ orderable: false, targets: [7] }
		// 	],
		// 	order:[0,'asc'],
		// 	"pageLength": 25,
		// 	//stateDuration: -1, //-1 means session storage
      	// 	stateSave: true,
		// 	initComplete: function () {
		// 		this.api()
		// 			.columns([2, 3, 4, 5, 6])
		// 			.every(function () {
		// 				let column = this;
		// 				let title = column.footer().textContent;
		
		// 				// Create input element
		// 				let input = document.createElement('input');
		// 				input.placeholder = title;
		// 				column.footer().replaceChildren(input);
		
		// 				// Event listener for user input
		// 				input.addEventListener('keyup', () => {
		// 					if (column.search() !== this.value) {
		// 						column.search(input.value).draw();
		// 					}
		// 				});
		// 			});
		// 			var state = this.api().state.loaded();
		// 			if (state) {
		// 				this.api().columns().eq(0).each(function (colIdx) {
		// 					var colSearch = state.columns[colIdx].search;
			
		// 					if (colSearch.search) {
		// 						$('input', this.column(colIdx).footer()).val(colSearch.search);
		// 					}
		// 				});
		// 			}
		// 	}
		// });
		// $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
		// $('.dataTable tfoot tr').appendTo('.dataTable thead');

		var dataRecords = $('#recordListing').DataTable({
			//"lengthChange": true,
            "processing":true,
            "serverSide":true,			
            'serverMethod': 'post',		
            "ajax":{
                url:_base_url_+"classes/Master.php?f=dashboard_data",
				"data": {
					"status": "<?php echo $status ?>"
				},
				type:"POST",
                dataType:"json"
            },
			"columnDefs":[
                {
					"width": 200,
                    "targets":[0,4,5],
                    "orderable":false					
                },
            ],
			select: {
               style: 'multi',
			   selector: 'td:first-child input:checkbox'
            },
			"order":[1,'desc'],			
			"pageLength": 25,
			"language": {                
            	"infoFiltered": ""
        	},
			"fixedColumns": true,
			"scrollX": true,
			dom: 'Bflrtip',
			buttons: [
						{extend: 'excelHtml5',
                        title: 'Excel',
                        text:'<i class="fas fa-file-excel"></i>'
						}
						//,
						// {extend: 'pdfHtml5',
                        // title: 'Print',
                        // text:'<i class="fas fa-print"></i>'
						// }
			],
			stateSave: <?php echo $status!='' ? 'false' : 'true' ?>,
			initComplete: function () {
				this.api()
					.columns([2, 3, 6, 7, 10, 11, 12, 13])
					.every(function () {
						let column = this;
						let title = column.footer().textContent;
		
						// Create input element
						let input = document.createElement('input');
						input.placeholder = title;
						input.style.width= "100px";
						column.footer().replaceChildren(input);
		
						// Event listener for user input
						input.addEventListener('keyup', () => {
							if (column.search() !== this.value) {
								column.search(input.value).draw();
							}
						});
					});
					var state = this.api().state.loaded();
					if (state) {
						this.api().columns().eq(0).each(function (colIdx) {
							var colSearch = state.columns[colIdx].search;
			
							if (colSearch.search) {
								$('input', this.column(colIdx).footer()).val(colSearch.search);
							}
						});
					}
			}			
        });	
		//$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
		$('.dataTable tfoot tr').appendTo('.dataTable thead');		
		dataRecords.buttons().container().appendTo('#button-panel');
		
		// Inline editing
		var oldValue = null;
			$(document).on('dblclick', '.editable', function(){
				oldValue = $(this).html();

				$(this).removeClass('editable');	// to stop from making repeated request
				
				$(this).html('<input type="text" style="width:150px;" class="update" value="'+ oldValue +'" />');
				$(this).find('.update').focus();
			});

			var newValue = null;
			$(document).on('blur', '.update', function(){
				var elem    = $(this);
				newValue 	= $(this).val();
				var shipmentId	= $(this).parent().attr('id');
				var colName	= $(this).parent().attr('name');

				if(newValue != oldValue)
				{
					$.ajax({
						url : _base_url_+"classes/Master.php?f=update_data",
						method : 'post',
						data : 
						{
							shipmentId    : shipmentId,
							colName  : colName,
							newValue : newValue,
							login_user : '<?= isset($login_user) ? $login_user : "" ?>',
						},
						success : function(respone)
						{
							$(elem).parent().addClass('editable');
							$(elem).parent().html(newValue);
						}
					});
				}
				else
				{
					$(elem).parent().addClass('editable');
					$(this).parent().html(newValue);
				}
			});
		
		$("#recordListing").on('click', '.view-attachments', function(){
			uni_modal("<i class='fa fa-eye'></i> Shipment Attachments List","transactions/view_attachments.php?id="+$(this).attr('data-id'))
		});
					
	})

	function setUpdateAction() {
		document.frmUser.action = "./?page=transactions/create_PO";
		document.frmUser.submit();
	}

	function changeIn(e){				
				newValue 	= e.value;
				var shipmentId	= e.id;
				var colName	= e.name;

				$.ajax({
						url : _base_url_+"classes/Master.php?f=update_data",
						method : 'post',
						data : 
						{
							shipmentId    : shipmentId,
							colName  : colName,
							newValue : newValue,
							login_user : '<?= isset($login_user) ? $login_user : "" ?>',
						},
						success : function(respone)
						{
							
						}
					});
		}

	$('#print').click(function(){
        start_loader();
        var h = $('head').clone()
        var p = $('#outprint').clone()
        var ph = $($('noscript#print-head').html()).clone()
        var el = $('<div>')
        h.find("title").html("Shipment List - Print View")
            el.append(h)
            el.append(ph)
            el.append(p)
        var nw = window.open("","_blank","height=800,width=1000,top=50, left=150")
            nw.document.write(el.html())
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    end_loader();
                    nw.close()
                }, 200);
            }, 500);
    })

	// $('#view-attachments').click(function(){
	// 	alert("hhhh");
	// 		//uni_modal("<i class='fa fa-edit'></i> Update Agency Details","agencies/manage_agency.php?id="+$(this).attr('data-id'))
	// 	})
	
</script>