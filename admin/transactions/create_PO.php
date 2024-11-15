<?php
if(isset($_SESSION['userdata'])){
    $login_user = $_SESSION['userdata']['username']; 
}
if(isset($_GET['wens_PO'])){
    $wens_PO = $_GET['wens_PO'];
    $qry = $conn->query("SELECT id FROM `shipment_list` where `Wens_PO_no` = '{$_GET['wens_PO']}'");
    while($row = $qry->fetch_assoc()){        
        $_POST["shipments"][] = $row['id'];
    }   
}
else if (empty($_POST["shipments"])) {   
    echo '<script> alert("Shipment\'s ID is required to access the page."); location.replace("./?page=transactions"); </script>'; 
}else{
    $ids = implode(", ", $_POST["shipments"]);
    $qry = $conn->query("SELECT Wens_PO_no from `shipment_list` where id in ({$ids}) ");
    if($qry->num_rows > 0){
        while($row = $qry->fetch_assoc()){
            $wens_PO = $row['Wens_PO_no'];
            if ($wens_PO != null){
                echo '<script> alert("WENS PO is already created."); location.replace("./?page=transactions"); </script>';
                break;
            }
        }
    }
    
}

if(isset($wens_PO)){
    $qry = $conn->query("SELECT w.ETD, w.ETA,w.origin,w.destination,w.agency_info, a.address,a.telephone from `wens_po_list` w LEFT JOIN agency_list a ON w.agency_info = a.name where w.wens_PO_no = '{$wens_PO}' ");
    if($qry->num_rows > 0){
        while($row = $qry->fetch_assoc()){
            $dep_date = $row['ETD'];
            $arr_date = $row['ETA'];
            $origin = $row['origin'];
            $destination = $row['destination'];
            $agency_info = $row['agency_info'];    
            $address =  $row['address'];      
            $telephone =  $row['telephone']; 
        }
    }
}

$origin_list = [];
$origin_qry = $conn->query("SELECT `name` FROM `origin_list` where `status` = 1 order by `name` asc");
while($row = $origin_qry->fetch_assoc()){
    $origin_list[$row['name']] = $row;
}
$agency_list = [];
$agency_qry = $conn->query("SELECT `name` FROM `agency_list` where `status` = 1 order by `name` asc");
while($row = $agency_qry->fetch_assoc()){
    $agency_list[$row['name']] = $row;
}

if (! empty($_POST["shipments"])){
    $rowCount = count($_POST["shipments"]);
    $total_price = 0;
    $total_pcs = 0;
    $total_weight = 0;   
    $vessel_name;
    for ($i = 0; $i < $rowCount; $i ++) {
        $items = $conn->query("SELECT s.supplier_info,s.PO_no,s.ref_code,c.quantity,c.weight,c.price,s.vessel_name FROM `cargo_items` c, shipment_list s where s.id=c.cargo_id and c.cargo_id = '{$_POST["shipments"][$i]}'");                                
        //$items = $conn->query("SELECT s.supplier_info,s.PO_no,s.ref_code,c.quantity,c.weight,c.price,s.vessel_name,s.agency_info, a.address,a.telephone FROM `cargo_items` c JOIN shipment_list s ON s.id=c.cargo_id  LEFT JOIN agency_list a ON s.agency_info = a.name  where  c.cargo_id = '{$_POST["shipments"][$i]}'");                                
        while($row = $items->fetch_array()){
                             
            $total_price += $row['price'];
            $total_pcs += $row['quantity'];
            $total_weight += $row['weight'];  
            $vessel_name =  $row['vessel_name'];                                        
        }
    } 
}
?>

<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title">PO Details</h4>            
        </div>
        <div class="card-body">
            <div class="text-right">                
                <a class="btn btn-default border btn-flat" href="./?page=transactions" >Back</a>   
                <button class="btn btn-default border btn-flat" id="generate_PO" <?= isset($wens_PO) ? 'disabled' : "" ?> >Generate PO</button>                
                <button class="btn btn-default border btn-flat" id="print" title="Print" <?= isset($wens_PO) ? "" : 'disabled' ?>><i class="fa fa-print"></i></button>
            </div>
            <div id="outprint">
                <h5 class="text-muted" style="text-align: center;">Vessel Name: <?= isset($vessel_name) ? $vessel_name : "" ?></h5>
                <h4 class="text-muted">WENS PO #</h4>
                <h2><?= isset($wens_PO) ? $wens_PO : "" ?></h2>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <large class="font-weight-bolder">Shipper:</large>                                                
                        </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <large class="font-weight-bolder">Consignee:</large>                                                
                        </fieldset>
                    </div>                    
                </div> 
                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <div class="pl-3">
                                <span>WENS Shipping</span> </br>
                                <span>Jumeirah Lakes Towers (JLT),</span> </br>
                                <span>Dubai, UAE</span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <fieldset>
                            <div class="pl-3">
                                <span>Master of <?= isset($vessel_name) ? $vessel_name : "" ?></span></br>
                                        <?php
                                            if (isset($agency_info)) { ?>
                                                <span><?= isset($agency_info) ? $agency_info : "" ?></span> </br>
                                                <span><?= isset($address) ? $address : "" ?></span> </br>
                                                <span>Tel: <?= isset($telephone) ? $telephone : "" ?></span>
                                        <?php }else { ?>
                                            <select name="agency_info" id="agency_info" class="form-control form-control-sm  form-control-border">
                                        <option value="" selected></option>
                                        <?php 
                                            foreach($agency_list as $row):
                                        ?>
                                        <option value="<?= $row['name'] ?>" <?= isset($agency_info) && $agency_info == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                        <?php } ?>                                  
                            </div>
                        </fieldset>
                    </div>                    
                </div>  
                <div class="clear-fix my-3"></div>

                <fieldset class="box">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="dep_date">Departure Date:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <?php
                                            if (isset($dep_date)) { ?>
                                                <span> <?= $dep_date?> </span>
                                            <?php }else { ?>
                                                <span><input type="date" name="dep_date" id="dep_date" value='<?= isset($dep_date) ? $dep_date : "" ?>'></span>   
                                            <?php } ?>                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="total_pckg" id="track_no">Total No Of Packages:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <span><?= isset($total_pcs) ? $total_pcs : "" ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="arr_date">Arrival Date:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <?php
                                            if (isset($arr_date)) { ?>
                                                <span> <?= $arr_date?> </span>
                                            <?php }else { ?>
                                                <span><input type="date" name="arr_date" id="arr_date" value='<?= isset($arr_date) ? $arr_date : "" ?>'></span>   
                                            <?php } ?>     
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="total_value" id="track_no">Cargo Value:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <span><?= isset($total_price) ? $total_price : "" ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="origin">Origin:</label>
                                        </div>
                                        <div class="ccol-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <?php
                                            if (isset($origin)) { ?>
                                                <span> <?= $origin?> </span>
                                            <?php }else { ?>
                                                <select name="origin" id="origin" class="form-control form-control-sm  form-control-border">                                  
                                                <option value="" selected></option>
                                                <?php 
                                                    foreach($origin_list as $row):
                                                ?>
                                                <option value="<?= $row['name'] ?>" <?= isset($origin) && $origin == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                <?php endforeach; ?>
                                                </select>   
                                            <?php } ?>                                             
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="total_weight" id="track_no">Actual Weight:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <span><?= isset($total_weight) ? $total_weight : "" ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
                                    <div class="row form-group mb-2">
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <label for="destination">Destination:</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-5 col-sm-5">
                                            <?php
                                            if (isset($destination)) { ?>
                                                <span> <?= $destination?> </span>
                                            <?php }else { ?>
                                                <select name="destination" id="destination" class="form-control form-control-sm  form-control-border">                                    
                                                <option value="" selected></option>
                                                <?php 
                                                    foreach($origin_list as $row):
                                                ?>
                                                <option value="<?= $row['name'] ?>" <?= isset($destination) && $destination == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                <?php endforeach; ?>
                                                </select>  
                                            <?php } ?>                                                
                                        </div>
                                    </div>
                                </div>
                                
                            </div>  
                    </fieldset> 

                <!-- <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <fieldset>
                                                <large class="font-weight-bolder">Shipment Ref Code:</large>                                                
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <fieldset>
                                                <large class="font-weight-bolder">PO No:</large>                                                
                                            </fieldset>
                                        </div>                    
                </div> 

                <?php
                    if (! empty($_POST["shipments"])) {
                        $rowCount = count($_POST["shipments"]);
                        for ($i = 0; $i < $rowCount; $i ++) {
                            $qry = $conn->query("SELECT * FROM `shipment_list` where id = '{$_POST["shipments"][$i]}' ");
                            if($qry->num_rows > 0){
                                while($row = $qry->fetch_array()){
                                    $ref_code = $row['ref_code'];
                                    $PO_no = $row['PO_no'];
                                    ?>
                                    <div class="clear-fix my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <fieldset>
                                                <div class="pl-3">
                                                    <span><?= isset($ref_code) ? $ref_code : "" ?></span>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <fieldset>
                                                <div class="pl-3">
                                                    <span><?= isset($PO_no) ? $PO_no : "" ?></span>
                                                </div>
                                            </fieldset>
                                        </div>                    
                                    </div>  
                                    <?php }
                            }    
                        }   
                    }
                ?> -->
                              
                         

                <div class="clear-fix my-3"></div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="40%">
                                <col width="30%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 text-center">Supplier Name</th>
                                    <th class="px-2 py-1 text-center">PO Number</th>
                                    <th class="px-2 py-1 text-center">No Of PKGS</th>
                                    <th class="px-2 py-1 text-center">Weight (kgs.)</th>
                                    <th class="px-2 py-1 text-center">Value</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (! empty($_POST["shipments"])):
                                $total_price = 0;
                                $total_pcs = 0;
                                $total_weight = 0;
                                for ($i = 0; $i < $rowCount; $i ++) {
                                // $items = $conn->query("SELECT i.*,t.name as cargo_type FROM `cargo_items` i inner join cargo_type_list t on i.cargo_type_id = t.id where i.cargo_id = '{$id}'");
                                $items = $conn->query("SELECT s.supplier_info,s.PO_no,s.ref_code,c.quantity,c.weight,c.price FROM `cargo_items` c, shipment_list s where s.id=c.cargo_id and c.cargo_id = '{$_POST["shipments"][$i]}'");                                
                                while($row = $items->fetch_array()):
                                ?>
                                <tr>
                                    <td class="px-2 py-1 align-middle"><?= $row['supplier_info'] ?></td>
                                    <td class="px-2 py-1 align-middle"><?= $row['PO_no'] ?></br>Ref Id: <?= $row['ref_code'] ?></td>
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['quantity']) ?></td>                                   
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['weight']) ?></td>
                                    <td class="px-2 py-1 text-right align-middle"><?= format_num($row['price']) ?></td>
                                </tr>
                                <?php $total_price += $row['price'];
                                        $total_pcs += $row['quantity'];
                                        $total_weight += $row['weight'];                                
                                endwhile; ?>
                                <?php } endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- <th class="px-1 py-1 text-center" colspan="5"><b>Total Amount</b></th> -->
                                    <th></th>
                                    <th></th>
                                    <th class="px-1 py-1 text-right"><b><?= isset($total_pcs) ? format_num($total_pcs) : "" ?></b></th>                             
                                    <th class="px-1 py-1 text-right"><b><?= isset($total_weight) ? format_num($total_weight) : "" ?></b></th>    
                                    <th class="px-1 py-1 text-right"><b><?= isset($total_price) ? format_num($total_price) : "" ?></b></th>                                   
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
        .box {
        width: 100%;
        height: auto;
        border: 1px solid lightgrey;
        padding: 20px;
        margin: 10px;
        border-top: 3px solid #f1e7e8;
        border-right: 3px solid #f1e7e8;
        border-left: 3px solid #f1e7e8;
        border-bottom: 3px solid #f1e7e8;
        background-color : #f1e7e8;
        }
    </style>
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-1">
            <img src="<?= validate_image($_settings->info('wens_logo')) ?>" alt="" class="img-fluid w-100" id="sys-logo">
        </div>
        <div class="col-10 text-center">
            <h5 class="text-center m-0"><b>WENS SHIPPING AND LOGISTICS LLC</b></h5>
            <div class="text-center"><b>Purchase Order</b></div>
        </div>
    </div>
</div>
<hr>
</noscript>
<style>
    .box {
        width: 100%;
        height: auto;
        border: 1px solid lightgrey;
        padding: 20px;
        margin: 10px;
        border-top: 3px solid #f1e7e8;
        border-right: 3px solid #f1e7e8;
        border-left: 3px solid #f1e7e8;
        border-bottom: 3px solid #f1e7e8;
        background-color : #f1e7e8;
        }
</style>
<script>

$(function(){
    $('#print').click(function(){
        start_loader();
        var h = $('head').clone()
        var p = $('#outprint').clone()
        var ph = $($('noscript#print-head').html()).clone()
        var el = $('<div>')
        h.find("title").html("Purchase Order")
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

    $('#generate_PO').click(function(){
        
       
    if($('#dep_date').val()!='' && $('#arr_date').val()!='' && $('#origin').val()!='' 
        && $('#destination').val()!='' && $('#agency_info').val()!=''){
        start_loader();        
        
		$.ajax({
			url:_base_url_+"classes/Master.php?f=save_po",
			method:"POST",
			data:{id: '<?= isset($_POST["shipments"]) ? implode(", ", $_POST["shipments"]) : "" ?>',
                shipment_count: '<?= isset($rowCount) ? $rowCount : "" ?>',
                etd: $('#dep_date').val(),
                eta: $('#arr_date').val(),
                origin: $('#origin').val(),
                destination: $('#destination').val(),
                vessel_name: '<?= isset($vessel_name) ? $vessel_name : "" ?>',
                agency_info: $('#agency_info').val(),
                login_user : '<?= isset($login_user) ? $login_user : "" ?>'},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
                    //window.location.reload();
                    location.replace("./?page=transactions/create_PO&wens_PO="+ resp.msg);
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
    }
    else{
        alert("Please fillup all the details.");
    }
    })
})

</script>