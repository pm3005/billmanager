<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `shipment_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        // $res = $qry->fetch_array();
        // foreach($res as $k => $v){
        //     if(!is_numeric($k)){
        //         $$k = $v;
        //     }
        // }
        $upload_files_arr= [];
        while($row = $qry->fetch_array()){
            $ref_code = $row['ref_code'];
            $origin = $row['origin'];
            $destination = $row['destination']; 
            $status = $row['status']; 
            $id = $row['id']; 
            $company_name=$row['company_name'];
            $vessel_name=$row['vessel_name'];
            $person_in_contact=$row['person_in_contact'];
            $PO_no=$row['PO_no'];
            $AWB_no=$row['AWB_no'];
            $onboard_at=$row['onboard_at'];
            $agency_info=$row['agency_info'];
            $agency_info=$row['agency_info'];
            $vessel_ETA=$row['vessel_ETA'];
            $vessel_ETD=$row['vessel_ETD'];
            $flight_ETA=$row['flight_ETA'];
            $flight_ETD=$row['flight_ETD'];
            $remarks=$row['remarks'];
            $upload_files = $row['upload_files'];
            $supplier_info = $row['supplier_info'];
            $target_dir = "../uploads/shipment_attachments/" . $_GET['id'] ."/";                                   
            if($upload_files!=''){                    
                $upload_files_arr = explode (", ", $upload_files); 
            }
            $mode_of_transport = $row['mode_of_transport'];
        }
        //     if(isset($id)){
        //     $meta_qry = $conn->query("SELECT * FROM `cargo_meta` where cargo_id = '{$id}'");
        //     while($row = $meta_qry->fetch_assoc()){
        //         ${$row['meta_field']} = $row['meta_value'];
                    
        //     }
            
        // }
        
    }else{
        echo '<script> alert("Unknown Shipment\'s ID."); location.replace("./?page=transactions"); </script>';
    }
}else{
    echo '<script> alert("Shipment\'s ID is required to access the page."); location.replace("./?page=transactions"); </script>';
}
$status = isset($status) ? $status : '';
?>






<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title">Transaction Details</h4>
            <div class="card-tools">
                <a href="./?page=transactions/manage_transaction&id=<?= isset($id) ? $id : '' ?>" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-edit"></i> Edit Details</a>
                <button id="delete_cargo" class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash"></i> Delete Data</button>               
            </div>
        </div>
        <div class="card-body">
            <div class="text-right">
                <?php if($status == 1): ?>
                    <span class="badge badge-primary badge-lg bg-gradient-primary px-3 rounded-pill">Cargo Picked-Up</span>
                <?php elseif($status == 2): ?>
                    <span class="badge badge-warning badge-lg bg-gradient-warning px-3 rounded-pill">Arrived at Station</span>
                <?php elseif($status == 3): ?>
                    <span class="badge badge-light badge-lg bg-gradient-light border px-3 rounded-pill">Stored at Warehouse</span>
                <?php elseif($status == 4): ?>
                    <span class="badge badge-danger badge-lg bg-gradient-blue px-3 rounded-pill">Ready for Delivery</span> 
                <?php elseif($status == 5): ?>
                    <span class="badge badge-info badge-lg bg-gradient-success px-3 rounded-pill">Delivered</span> 
                    <span class="badge badge-secondary badge-lg bg-gradient-secondary px-3 rounded-pill">Pending</span>
                <?php endif; ?>
                <button class="btn btn-default border btn-flat" id="update_status" >Update Status</button>
                <button class="btn btn-default border btn-flat" id="print" title="Print"><i class="fa fa-print"></i></button>
            </div>
            <div id="outprint">
                <h4 class="text-muted">Reference Code:</h4>
                <h2><?= isset($ref_code) ? $ref_code : "" ?></h2>
                <!-- <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <large class="font-weight-bolder">Sender Information</large>
                            <div class="pl-3">
                                <span><?= isset($sender_name) ? ucwords($sender_name) : "" ?></span><br>
                                <span><?= isset($sender_contact) ? $sender_contact : "" ?></span><br>
                                <span><?= isset($sender_address) ? $sender_address : "" ?></span><br>
                                <span><?= isset($sender_provided_id_type) ? $sender_provided_id_type : "" ?></span><br>
                                <span><?= isset($sender_provided_id) ? $sender_provided_id : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <large class="font-weight-bolder">Receiver Information</large>
                            <div class="pl-3">
                                <span><?= isset($receiver_name) ? ucwords($receiver_name) : "" ?></span><br>
                                <span><?= isset($receiver_contact) ? $receiver_contact : "" ?></span><br>
                                <span><?= isset($receiver_address) ? $receiver_address : "" ?></span><br>
                                <span><?= isset($receiver_provided_id_type) ? $receiver_provided_id_type : "" ?></span><br>
                                <span><?= isset($receiver_provided_id) ? $receiver_provided_id : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                </div> -->
                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Company Name:</large>
                            <div class="pl-3">
                                <span><?= isset($company_name) ? $company_name : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Latest Remarks:</large>
                            <div class="pl-3">
                                <span><?= isset($remarks) ? $remarks : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                </div>
                
                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">PO No:</large>
                            <div class="pl-3">
                                <span><?= isset($PO_no) ? $PO_no : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Person In Contact:</large>
                            <div class="pl-3">
                                <span><?= isset($person_in_contact) ? $person_in_contact : "" ?></span>
                            </div>
                        </fieldset>
                    </div> 
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Mode Of Transport:</large>
                            <div class="pl-3">
                                <span><?= isset($mode_of_transport) ? $mode_of_transport : "" ?></span>
                            </div>
                        </fieldset>
                    </div>                     
                </div> 
                
                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Agency Information:</large>
                            <div class="pl-3">
                                <span><?= isset($agency_info) ? $agency_info : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Supplier Information:</large>
                            <div class="pl-3">
                                <span><?= isset($supplier_info) ? $supplier_info : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder"><?= $mode_of_transport == 'Truck' ? 'Trucking No:' : ($mode_of_transport == 'Sea' ? 'BL No:' : 'AWB No:') ?></large>
                            <div class="pl-3">
                                <span><?= isset($AWB_no) ? $AWB_no : "" ?></span>
                            </div>
                        </fieldset>
                    </div> 
                </div>

                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">ETA:</large>
                            <div class="pl-3">
                                <span><?= isset($vessel_ETA) ? $vessel_ETA : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">ETD:</large>
                            <div class="pl-3">
                                <span><?= isset($vessel_ETD) ? $vessel_ETD : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Flight ETA:</large>
                            <div class="pl-3">
                                <span><?= isset($flight_ETA) ? $flight_ETA : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Flight ETD:</large>
                            <div class="pl-3">
                                <span><?= isset($flight_ETD) ? $flight_ETD : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                </div>
                               
                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Origin:</large>
                            <div class="pl-3">
                                <span><?= isset($origin) ? $origin : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Destination:</large>
                            <div class="pl-3">
                                <span><?= isset($destination) ? $destination : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">On-board At:</large>
                            <div class="pl-3">
                                <span><?= isset($onboard_at) ? $onboard_at : "" ?></span>
                            </div>
                        </fieldset>
                    </div>
                    <!-- <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Shipment Type:</large>
                            <div class="pl-3">
                                <span>
                                    <?php
                                    $status = isset($status) ? $status : '';
                                    switch($status){
                                        case '1':
                                            echo "City to City";
                                            break;
                                        case '2':
                                            echo "State to State";
                                            break;
                                        case '1':
                                            echo "Country to Country";
                                            break;
                                        default:
                                            echo "N/A";
                                            break;
                                    }
                                    ?>
                                </span>
                            </div>
                        </fieldset>
                    </div> -->
                </div>

                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <fieldset>
                            <large class="font-weight-bolder">Attachment(s):</large>
                            <div class="pl-3">
                                <span>
                                <?php foreach($upload_files_arr as $str){
                                        $uploadedFile = $target_dir . basename($str); ?>
                                        <!-- <a href="<?php echo $uploadedFile; ?>" target="_blank"><?php echo $str; ?></a> <br> -->
                                        
                                        <a href="#" onclick="window.open('<?php echo $uploadedFile; ?>','Popup','width=700,height=600,left=500,top=200')"><?php echo $str; ?></a> <br>
                                    <?php } ?>
                                </span>
                            </div>
                        </fieldset>
                    </div>
                </div>
                
                <!-- Redirects -->
                <?php 
                    if(isset($id)):
                        $existing_redirect=0;
                        $items = $conn->query("SELECT * FROM `redirect_shipment_list` where cargo_id = '{$_GET['id']}'");
                        while($r_row = $items->fetch_array()):
                            $existing_redirect++;
                ?>

                <div class="clear-fix my-3"></div>                
                <fieldset>
                    <legend class="font-weight-bolder"><?= ($r_row['redirect_ref_code']) ?></legend>
                        
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder">Redirect Date:</large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['crtd_dt']) ? date("Y-m-d", strtotime($r_row['crtd_dt'])) : "" ?></span>
                                    </div>                         
                                </div>
                        </div> 
                        <div class="clear-fix my-3"></div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder">ETA:</large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['vessel_ETA']) ? $r_row['vessel_ETA'] : "" ?></span>
                                    </div>                           
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder">ETD</large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['vessel_ETD']) ? $r_row['vessel_ETD'] : "" ?></span>
                                    </div>                         
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder">Mode Of Transport:</large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['mode_of_transport']) ? $r_row['mode_of_transport'] : "" ?></span>
                                    </div>                           
                            </div>
                        </div>                       
                        <div class="clear-fix my-3"></div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <large class="font-weight-bolder">Flight ETA:</large>
                                <div class="pl-3">
                                    <span><?= isset($r_row['flight_ETA']) ? $r_row['flight_ETA'] : "" ?></span>
                                </div>                         
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <large class="font-weight-bolder">Flight ETD:</large>
                                <div class="pl-3">
                                    <span><?= isset($r_row['flight_ETD']) ? $r_row['flight_ETD'] : "" ?></span>
                                </div>                          
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder"><?= $r_row['mode_of_transport'] == 'Truck' ? 'Trucking No:' : ($r_row['mode_of_transport'] == 'Sea' ? 'BL No:' : 'AWB No:') ?></large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['AWB_no']) ? $r_row['AWB_no'] : "" ?></span>
                                    </div>                         
                            </div>
                        </div>
                        <div class="clear-fix my-3"></div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <large class="font-weight-bolder">Agency Information:</large>
                                    <div class="pl-3">
                                        <span><?= isset($r_row['agency_info']) ? $r_row['agency_info'] : "" ?></span>
                                    </div>                         
                                </div>
                        </div>                        
                        <div class="clear-fix my-3"></div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <large class="font-weight-bolder">Shipment From:</large>
                                <div class="pl-3">
                                    <span><?= isset($r_row['origin']) ? $r_row['origin'] : "" ?></span>
                                </div>                         
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <large class="font-weight-bolder">Shipment To:</large>
                                <div class="pl-3">
                                    <span><?= isset($r_row['destination']) ? $r_row['destination'] : "" ?></span>
                                </div>                        
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <large class="font-weight-bolder">On-board At:</large>
                                <div class="pl-3">
                                    <span><?= isset($r_row['onboard_at']) ? $r_row['onboard_at'] : "" ?></span>
                                </div>                         
                            </div>
                        </div>
                </fieldset>
                <?php endwhile; ?>
                <?php endif; ?>               

                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="40%">
                                <col width="20%">
                                <col width="20%">
                                <col width="20%">
                                <!-- <col width="10%">
                                <col width="20%"> -->
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 text-center">Description</th>
                                    <th class="px-2 py-1 text-center">No Of PCs.</th>                                    
                                    <th class="px-2 py-1 text-center">Weight (kg.)</th>
                                    <th class="px-2 py-1 text-center">Price</th>
                                    <!-- <th class="px-2 py-1 text-center">Slab</th>
                                    <th class="px-2 py-1 text-center">Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(isset($id)):
                                // $items = $conn->query("SELECT i.*,t.name as cargo_type FROM `cargo_items` i inner join cargo_type_list t on i.cargo_type_id = t.id where i.cargo_id = '{$id}'");
                                $items = $conn->query("SELECT * FROM `cargo_items` where cargo_id = '{$id}'");
                                $total_amount = 0;
                                while($row = $items->fetch_array()):
                                ?>
                                <tr>
                                    <td class="px-2 py-1 align-middle"><?= $row['description'] ?></td>
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['quantity']) ?></td>                                    
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['weight']) ?></td>
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['price']) ?></td>
                                    <!-- <td class="px-2 py-1 text-right align-middle"><?= isset($row['slab']) ? format_num($row['slab']) : "" ?></td>
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['total']) ?></td> -->
                                </tr>
                                <?php $total_amount += $row['price']; endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="px-1 py-1 text-center" colspan="3"><b>Total Amount</b></th>
                                    <th class="px-1 py-1 text-right"><b><?= isset($total_amount) ? format_num($total_amount) : "" ?></b></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
        </div>
        <div class="col-10 text-center">
            <h5 class="text-center m=0"><b>Bill Details</b></h5>
        </div>
    </div>
</div>
<hr>
</noscript>
<script>

$(function(){
    $('#print').click(function(){
        start_loader();
        var h = $('head').clone()
        var p = $('#outprint').clone()
        var ph = $($('noscript#print-head').html()).clone()
        var el = $('<div>')
        h.find("title").html("Bill Details - Print View")
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
    $('#update_status').click(function(){
        uni_modal("Update Shipment Status - <b><?= isset($ref_code) ? $ref_code : "" ?></b>","transactions/update_status.php?id=<?= isset($id) ? $id : '' ?>")
    })
    $('#delete_cargo').click(function(){
        _conf("Are you sure to delete this Shipment permanently?","delete_cargo",[])
    })
})
function delete_cargo($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_cargo",
			method:"POST",
			data:{id: '<?= isset($id) ? $id : "" ?>'},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.replace('./?page=transactions');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>