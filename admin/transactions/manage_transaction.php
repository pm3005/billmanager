<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['userdata'])){
    $login_user = $_SESSION['userdata']['username']; 
}

$upload_files_arr= []; 
$mode_of_transport = '';
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `shipment_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        while($row = $qry->fetch_assoc()){
            $id = $row['id'];
            $ref_code = $row['ref_code'];
            $company_name = $row['company_name'];
            $vessel_name = $row['vessel_name'];
            $person_in_contact = $row['person_in_contact'];
            $PO_no = $row['PO_no'];
            $vessel_ETA = $row['vessel_ETA'];
            $vessel_ETD = $row['vessel_ETD'];
            $AWB_no = $row['AWB_no'];
            $flight_ETA = $row['flight_ETA'];
            $flight_ETD = $row['flight_ETD'];
            $origin = $row['origin'];
            $destination = $row['destination'];
            $onboard_at = $row['onboard_at'];
            $agency_info = $row['agency_info'];
            $upload_files = $row['upload_files'];
            $target_dir = "../uploads/shipment_attachments/" . $id ."/";                                   
            if($upload_files!=''){                    
                $upload_files_arr = explode (", ", $upload_files); 
            }    
            $mode_of_transport = $row['mode_of_transport'];
            $status = $row['status'];
            $supplier_info = $row['supplier_info'];
        }
        // if(isset($id)){
        //     $meta_qry = $conn->query("SELECT * FROM `cargo_meta` where cargo_id = '{$id}'");
        //     while($row = $meta_qry->fetch_assoc()){
        //         ${$row['meta_field']} = $row['meta_value'];
        //     }
        // }
    }
}
// $cargo_type = [];
// $cargo_type_qry = $conn->query("SELECT id,`name`, city_price, state_price, country_price FROM `cargo_type_list` where delete_flag = 0 order by `name` asc");
// while($row = $cargo_type_qry->fetch_assoc()){
//     $cargo_type[$row['id']] = $row;
// }
$origin_list = [];
$origin_qry = $conn->query("SELECT `name` FROM `origin_list` where `status` = 1 order by `name` asc");
while($row = $origin_qry->fetch_assoc()){
    $origin_list[$row['name']] = $row;
}
$onboard_list = [];
$dest_qry = $conn->query("SELECT `name` FROM `onboard_list` where `status` = 1 order by `name` asc");
while($row = $dest_qry->fetch_assoc()){
    $onboard_list[$row['name']] = $row;
}
$company_list = [];
$comp_qry = $conn->query("SELECT distinct `company_name` FROM `company_vessel_mapping` where `status` = 1 order by `company_name` asc");
while($row = $comp_qry->fetch_assoc()){
    $company_list[$row['company_name']] = $row;
}
$PIC_list = [];
$PIC_qry = $conn->query("SELECT `name` FROM `contact_person_list` where `status` = 1 order by `name` asc");
while($row = $PIC_qry->fetch_assoc()){
    $PIC_list[$row['name']] = $row;
}
$agency_list = [];
$agency_qry = $conn->query("SELECT `name` FROM `agency_list` where `status` = 1 order by `name` asc");
while($row = $agency_qry->fetch_assoc()){
    $agency_list[$row['name']] = $row;
}
$supplier_list = [];
$supplier_qry = $conn->query("SELECT `name` FROM `supplier_list` where `status` = 1 order by `name` asc");
while($row = $supplier_qry->fetch_assoc()){
    $supplier_list[$row['name']] = $row;
}
?>
<style>
    img#cimg{
		max-height: 15vh;
		width: 100%;
		object-fit: scale-down;
		object-position: center center;
	}
    
</style>
<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h4 class="card-title"><b>Record Shipment</b></h4>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="cargo-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <input type="hidden" name ="ref_code" value="<?php echo isset($ref_code) ? $ref_code : '' ?>">                    
                    <input type="hidden" name ="login_user" value="<?php echo isset($login_user) ? $login_user : '' ?>">
                    <input type="hidden" name ="vessel" id="vessel" value="<?php echo isset($vessel_name) ? $vessel_name : '' ?>">
                    <input type="hidden" name ="upload_files" id="upload_files" value="<?php echo isset($upload_files) ? $upload_files : '' ?>">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                
                            </div>
                            </div>
                             
                            <fieldset class="box">
                            <legend style="width:auto;">Client Details: &nbsp;</legend>
                            <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="PO_no">PO No</label>
                                    <input type="text" name="PO_no" id="PO_no" class="form-control form-control-sm form-control-border" value='<?= isset($PO_no) ? $PO_no : "" ?>'>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="company_name" class="control-label">Company Name</label>
                                    <select name="company_name" id="company_name" class="form-control form-control-sm  form-control-border"  required>
                                    <option value="" disabled='' selected></option>
                                    <?php 
                                        foreach($company_list as $row):
                                    ?>
                                    <option value="<?= $row['company_name'] ?>" <?= isset($company_name) && $company_name == $row['company_name'] ? 'selected' : "" ?>><?= $row['company_name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="person_in_contact" class="control-label">Person In Contact</label>
                                    <select name="person_in_contact" id="person_in_contact" class="form-control form-control-sm  form-control-border"  required>
                                    <option value="" disabled='' selected></option>
                                    <?php 
                                        foreach($PIC_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>" <?= isset($person_in_contact) && $person_in_contact == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                              
                            </div>
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="supplier_info"  class="control-label">Supplier Information</label>
                                    <select name="supplier_info" id="supplier_info" class="form-control form-control-sm  form-control-border" required>
                                        <option value="" selected></option>
                                        <?php 
                                            foreach($supplier_list as $row):
                                        ?>
                                        <option value="<?= $row['name'] ?>" <?= isset($supplier_info) && $supplier_info == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            </div>
                            </fieldset>   
                            </br>                                               
                       
                            <fieldset class="box">
                            <legend style="width:auto;">Transportation Details: &nbsp;</legend>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="mode_of_transport">Mode Of Transport</label>
                                        <select name="mode_of_transport" id="mode_of_transport" class="form-control form-control-sm  form-control-border">     
                                       <option value="Air" <?= isset($mode_of_transport) && $mode_of_transport == 'Air' ? 'selected' : "" ?>>Air</option>
                                        <option value="Sea" <?= isset($mode_of_transport) && $mode_of_transport == 'Sea' ? 'selected' : "" ?>>Sea</option>
                                        <option value="Truck" <?= isset($mode_of_transport) && $mode_of_transport == 'Truck' ? 'selected' : "" ?>>Truck</option>                                                                 
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="vessel_ETA">ETA</label>
                                    <input type="date" name="vessel_ETA" id="vessel_ETA" class="form-control form-control-sm form-control-border" value='<?= isset($vessel_ETA) ? $vessel_ETA : "" ?>'>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="vessel_ETD">ETD</label>
                                    <input type="date" name="vessel_ETD" id="vessel_ETD" class="form-control form-control-sm form-control-border" value='<?= isset($vessel_ETD) ? $vessel_ETD : "" ?>'>
                                </div>
                            </div> 
                             </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="track_no" id="track_no"><?= $mode_of_transport == 'Truck' ? 'Trucking No' : ($mode_of_transport == 'Sea' ? 'BL No' : 'AWB No') ?> </label>
                                        <input type="text" name="AWB_no" id="AWB_no" class="form-control form-control-sm form-control-border" value='<?= isset($AWB_no) ? $AWB_no : "" ?>'>
                                    </div>
                                </div>
                            </div>
                           
                            </fieldset> 
                            </br>      

                            <fieldset class="box">
                            <legend style="width:auto;">Delivery Details: &nbsp;</legend>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="agency_info">Agency Information</label>
                                        <!-- <textarea rows="7" name="agency_info" id="agency_info" class="form-control form-control-sm rounded-0"><?= isset($agency_info) ? $agency_info : '' ?></textarea> -->
                                        <select name="agency_info" id="agency_info" class="form-control form-control-sm  form-control-border">
                                        <option value="" selected></option>
                                        <?php 
                                            foreach($agency_list as $row):
                                        ?>
                                        <option value="<?= $row['name'] ?>" <?= isset($agency_info) && $agency_info == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>   
                                <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="origin">Origin</label>
                                    <select name="origin" id="origin" class="form-control form-control-sm  form-control-border">
                                    <!-- <option value="ICN" <?= isset($origin) && $origin == 'ICN' ? 'selected' : "" ?>>ICN</option>
                                    <option value="Norway" <?= isset($origin) && $origin == 'Norway' ? 'selected' : "" ?>>Norway</option>
                                    <option value="China" <?= isset($origin) && $origin == 'China' ? 'selected' : "" ?>>China</option> -->
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($origin_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>" <?= isset($origin) && $origin == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                                </div>                             
                            
                        

                        <!-- <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="card rounded-0 shadow">
                                    <div class="card-header">
                                        <h5 class="font-weight-bolder">Sender Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-2">
                                            <label for="sender_name" class="control-label">Full Name</label>
                                            <input type="text" name="sender_name" id="sender_name" class="form-control form-control-sm form-control-border" value="<?= isset($sender_name) ? $sender_name : '' ?>" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="sender_contact" class="control-label">Contact #</label>
                                            <input type="text" name="sender_contact" id="sender_contact" class="form-control form-control-sm form-control-border" value="<?= isset($sender_contact) ? $sender_contact : '' ?>" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="sender_address" class="control-label">Address</label>
                                            <textarea rows="3" name="sender_address" id="sender_address" class="form-control form-control-sm rounded-0" required><?= isset($sender_address) ? $sender_address : '' ?></textarea>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="sender_provided_id_type" class="control-label">Provided ID Type</label>
                                            <input type="text" name="sender_provided_id_type" id="sender_provided_id_type" class="form-control form-control-sm form-control-border" value="<?= isset($sender_provided_id_type) ? $sender_provided_id_type : '' ?>" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="sender_provided_id" class="control-label">Provided ID #/Code</label>
                                            <input type="text" name="sender_provided_id" id="sender_provided_id" class="form-control form-control-sm form-control-border" value="<?= isset($sender_provided_id) ? $sender_provided_id : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="card rounded-0 shadow">
                                    <div class="card-header">
                                        <h5 class="font-weight-bolder">Receiver Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-2">
                                            <label for="receiver_name" class="control-label">Full Name</label>
                                            <input type="text" name="receiver_name" id="receiver_name" class="form-control form-control-sm form-control-border" value="<?= isset($receiver_name) ? $receiver_name : '' ?>" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="receiver_contact" class="control-label">Contact #</label>
                                            <input type="text" name="receiver_contact" id="receiver_contact" class="form-control form-control-sm form-control-border" value="<?= isset($receiver_contact) ? $receiver_contact : '' ?>" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="receiver_address" class="control-label">Address</label>
                                            <textarea rows="3" name="receiver_address" id="receiver_address" class="form-control form-control-sm rounded-0" required><?= isset($receiver_address) ? $receiver_address : '' ?></textarea>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                        </div> -->
                                       
                            <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control form-control-sm  form-control-border">                                    
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($origin_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>" <?= isset($destination) && $destination == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="onboard_at">On Board At</label>
                                    <select name="onboard_at" id="onboard_at" class="form-control form-control-sm  form-control-border">
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($onboard_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>" <?= isset($onboard_at) && $onboard_at == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>    
                        </div>
                        </fieldset> 
                        </br>      
                            
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="attachment">Attachment(s)</label>
                                    <input type="file" name="shipmentFiles[]" id="shipmentFiles" class="form-control form-control-sm form-control-border" multiple>   
                                    <?php foreach($upload_files_arr as $str){
                                        $uploadedFile = $target_dir . basename($str); ?>
                                        <a href="<?php echo $uploadedFile; ?>" target="_blank"><?php echo $str; ?></a>
                                         <!-- <input type="submit" name="<?php echo $str; ?>" value="delete"/>  -->
                                         <a class="delete_data" href="javascript:void(0)" data-id="<?php echo $str ?>"><span class="fa fa-trash text-danger"></span></a><br>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        </br>      
                        <div class="row">
                        </div>
                        </br>

                                <?php 
                                    if(isset($id)):
                                    $existing_redirect=0;
                                    $items = $conn->query("SELECT * FROM `redirect_shipment_list` where cargo_id = '{$id}'");
                                    while($r_row = $items->fetch_array()):
                                    $existing_redirect++;
                                ?>
                                        <div class="accordion">    
                                                <div class="section">
                                                <dl class="row">
                                                    <dt class="col-11"><a class="section-title" href='#<?= $existing_redirect ?>'><?= ($r_row['redirect_ref_code']) ?></a></dt>
                                                </dl>                                                
                                                                            
                                                <div id='<?= $existing_redirect ?>' class="section-content">  
                                                <input type="hidden" name="isNewRedirect[]" value="false">      
                                                               
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="redirect_date">Redirect Date</label>
                                                                    <input type="date" name="r_redirect_date[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['crtd_dt']) ? date("Y-m-d", strtotime($r_row['crtd_dt'])) : "" ?>' readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="vessel_ETA" class="control-label">ETA</label>
                                                                    <input type="date" name="r_vessel_ETA[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['vessel_ETA']) ? $r_row['vessel_ETA'] : "" ?>' required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="vessel_ETD" class="control-label">ETD</label>
                                                                    <input type="date" name="r_vessel_ETD[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['vessel_ETD']) ? $r_row['vessel_ETD'] : "" ?>' required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="mode_of_transport">Mode Of Transport</label>
                                                                    <select name="r_mode_of_transport[]" class="form-control form-control-sm  form-control-border">     
                                                                <option value="Air" <?= isset($r_row['mode_of_transport']) && $r_row['mode_of_transport'] == 'Air' ? 'selected' : "" ?>>Air</option>
                                                                    <option value="Sea" <?= isset($r_row['mode_of_transport']) && $r_row['mode_of_transport'] == 'Sea' ? 'selected' : "" ?>>Sea</option>
                                                                    <option value="Truck" <?= isset($r_row['mode_of_transport']) && $r_row['mode_of_transport'] == 'Truck' ? 'selected' : "" ?>>Truck</option>                                                                 
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="track_no" id="track_no"><?= $r_row['mode_of_transport'] == 'Truck' ? 'Trucking No' : ($r_row['mode_of_transport'] == 'Sea' ? 'BL No' : 'AWB No') ?> </label>
                                                                    <input type="text" name="r_AWB_no[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['AWB_no']) ? $r_row['AWB_no'] : "" ?>'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="flight_ETA">Flight ETA</label>
                                                                    <input type="date" name="r_flight_ETA[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['flight_ETA']) ? $r_row['flight_ETA'] : "" ?>'>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="flight_ETD">Flight ETD</label>
                                                                    <input type="date" name="r_flight_ETD[]" class="form-control form-control-sm form-control-border" value='<?= isset($r_row['flight_ETD']) ? $r_row['flight_ETD'] : "" ?>'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                                <div class="form-group mb-2">
                                                                    <label for="agency_info">Agency Information</label>
                                                                    <!-- <textarea rows="7" name="r_agency_info[]" id="r_agency_info" class="form-control form-control-sm rounded-0"><?= isset($r_row['agency_info']) ? $r_row['agency_info'] : '' ?></textarea> -->
                                                                    <select name="r_agency_info[]" id="r_agency_info" class="form-control form-control-sm  form-control-border">
                                                                    <option value="" selected></option>
                                                                    <?php 
                                                                        foreach($agency_list as $row):
                                                                    ?>
                                                                    <option value="<?= $row['name'] ?>" <?= isset($r_row['agency_info']) && $r_row['agency_info'] == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                                    <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    
                                                        <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                            <div class="form-group mb-2">
                                                                <label for="origin" class="control-label">Origin</label>
                                                                <select name="r_origin[]" class="form-control form-control-sm  form-control-border" required>                                   
                                                                <option value="" selected></option>
                                                                <?php 
                                                                    foreach($origin_list as $row):
                                                                ?>
                                                                <option value="<?= $row['name'] ?>" <?= isset($r_row['origin']) && $r_row['origin'] == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                            <div class="form-group mb-2">
                                                                <label for="destination" class="control-label">Destination</label>
                                                                <select name="r_destination[]" class="form-control form-control-sm  form-control-border" required>
                                                                <option value="" selected></option>
                                                                <?php 
                                                                    foreach($origin_list as $row):
                                                                ?>
                                                                <option value="<?= $row['name'] ?>" <?= isset($r_row['destination']) && $r_row['destination'] == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                                            <div class="form-group mb-2">
                                                                <label for="onboard_at">On Board At</label>
                                                                <select name="r_onboard_at[]" class="form-control form-control-sm  form-control-border">
                                                                <option value="" selected></option>
                                                                <?php 
                                                                    foreach($onboard_list as $row):
                                                                ?>
                                                                <option value="<?= $row['name'] ?>" <?= isset($r_row['onboard_at']) && $r_row['onboard_at'] == $row['name'] ? 'selected' : "" ?>><?= $row['name'] ?></option>
                                                                <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                </div><!-- section-content end -->                                
                                            </div><!-- section end -->                            
                                        </div><!-- accordion end -->
                                <?php endwhile; ?>
                                <?php endif; ?>

                        <div id="mainDiv"></div>
                        <input type="hidden" name="total_redirect" value="<?= isset($existing_redirect) ? $existing_redirect : 0 ?>">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card card-outline card-info shadow rounded0">
                                    <div class="card-header">
                                        <h5 class="card-title"><b>Package Information</b></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-0 pb-0">
                                            <div class="col-4 border text-center"><b>Description</b></div>
                                            <div class="col-2 border text-center"><b>No Of PCs.</b></div>                                            
                                            <div class="col-3 border text-center"><b>Weight/kg.</b></div>
                                            <div class="col-3 border text-center"><b>Price</b></div>
                                            <!-- <div class="col-1 border text-center"><b>Slab</b></div>
                                            <div class="col-1 border text-center"><b>Total</b></div> -->
                                        </div>
                                        <div id="cargo-item-list" class="d-table w-100">
                                        <?php 
                                        if(isset($id)):
                                       // $items = $conn->query("SELECT i.*,t.name as cargo_type FROM `cargo_items` i inner join cargo_type_list t on i.cargo_type_id = t.id where i.cargo_id = '{$id}'");
                                        $items = $conn->query("SELECT * FROM `cargo_items` where cargo_id = '{$id}'");
                                        while($row = $items->fetch_array()):
                                        ?>
                                        <div class="d-table-row align-items-center justify-content-center my-0 py-0 cargo-item">
                                            
                                            <div class="d-table-cell col-4 px-2 py-1 border text-center"><input type="text" step="any" name="description[]" class="form-control form-control-sm form-control-border text-right" value="<?= ($row['description']) ?>"></div>
                                            <div class="d-table-cell col-2 px-2 py-1 border text-center"><input type="number" step="any" name="quantity[]" class="form-control form-control-sm form-control-border text-right" value="<?= ($row['quantity']) ?>"></div>
                                            <div class="d-table-cell col-3 px-2 py-1 border text-center"><input type="number" step="any" name="weight[]" class="form-control form-control-sm form-control-border text-right" value="<?= ($row['weight']) ?>"></div>
                                            <div class="d-table-cell col-3 px-2 py-1 border text-center"><input type="number" step="any" name="price[]" class="form-control form-control-sm form-control-border text-right" value="<?= ($row['price']) ?>"></div>                                            
                                            <!-- <div class="d-table-cell col-1 px-2 py-1 border text-center"><input type="number" step="any" name="slab[]" class="form-control form-control-sm form-control-border text-right" value="<?= ($row['slab']) ?>"></div>
                                            <div class="d-table-cell col-1 px-2 py-1 border text-right"><span class="font-weight-bold total"><?= format_num($row['total']) ?></span></div> -->
                                        </div>
                                        <?php endwhile; ?>
                                        <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center mb-0 pb-0">
                                            <div class="col-9 border text-center"><b>Total</b></div>
                                            <div class="col-3 border text-center"><b id="gtotal"><?= isset($total_amount) ? format_num($total_amount) : '0.00' ?></b><input type="hidden" name="total_amount" value="<?= isset($total_amount) ? $total_amount : "" ?>"></div>
                                        </div>
                                        <div class="clear-fix my-2"></div>
                                        <div class="text-right">
                                            <button class="btn btn-default border btn-sm btn-flat" id="add_item" type="button"><i class="fa fa-plus"></i> Add Item</button>
                                            <input type="hidden" name="total_item_count" value="<?= isset($total_item_count) ? $total_item_count : "" ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer text-right">
                <button class="btn btn-sm btn-flat btn-primary" type="submit" form="cargo-form">Save</button>
                <a class="btn btn-sm btn-flat btn-default border" href="./?page=transactions">Cancel</a>
        </div>
    </div>
</div>
<noscript id="cargo-item-clone">
<div class="d-table-row align-items-center justify-content-center my-0 py-0 cargo-item">
    <!-- <div class="d-table-cell col-3 px-2 py-1 border text-center">
        <input type="hidden" name="price[]">
        <input type="hidden" name="total[]">
        <select name="cargo_type_id[]" class="form-control form-control-sm form-control-border select2">
            <option value="" disabled='' selected></option>
            <?php 
                foreach($cargo_type as $row):
            ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div> -->
    <div class="d-table-cell col-4 px-2 py-1 border text-center"><input type="text" step="any" name="description[]" class="form-control form-control-sm form-control-border text-right"></div>
    <div class="d-table-cell col-2 px-2 py-1 border text-center"><input type="number" step="any" name="quantity[]" class="form-control form-control-sm form-control-border text-right"></div>    
    <div class="d-table-cell col-2 px-2 py-1 border text-center"><input type="number" step="any" name="weight[]" class="form-control form-control-sm form-control-border text-right"></div>
    <div class="d-table-cell col-2 px-2 py-1 border text-center"><input type="number" step="any" name="price[]" class="form-control form-control-sm form-control-border text-right"></div>
    <!-- <div class="d-table-cell col-1 px-2 py-1 border text-center"><input type="number" step="any" name="slab[]" class="form-control form-control-sm form-control-border text-right"></div>
    <div class="d-table-cell col-1 px-2 py-1 border text-right"><span class="font-weight-bold total">0.00</span></div> -->

    <!-- <div class="d-table-cell col-3 px-2 py-1 border text-right"><span class="font-weight-bold price">0.00</span></div>
    <div class="d-table-cell col-3 px-2 py-1 border text-center"><input type="number" step="any" name="weight[]" class="form-control form-control-sm form-control-border text-right"></div>
    <div class="d-table-cell col-3 px-2 py-1 border text-right"><span class="font-weight-bold total">0.00</span></div> -->
</div>
</noscript>
<style>
    /* Accordion */
        .accordion, .accordion * {
        box-sizing:border-box;
        -webkit-box-sizing:border-box;
        -moz-box-sizing:border-box;
        }

        .accordion {
        overflow:hidden;
        box-shadow:0px 1px 3px rgba(0,0,0,0.25);
        border-radius:3px;
        }

        /* Section Title */
        .section-title {
        background:#1e2055;
        display:inline-block;
        border-bottom:1px solid #1a1a1a;
        width:100%;
        padding:15px;
        transition:all linear 0.15s;
        color:#fff;
        font-size:12px;
        text-shadow:0px 1px 0px #1b1b1b;
        }

        .section-title.active,
        .section-title:hover {
        background:#505050;
        }

        .section:last-child .section-title {
        border-bottom:none;
        }

        /* Section Content */
        .section-content {
        display:none;
        padding:20px;
        }
        .control-label:after{
        content:"*";
        color:red;
        }
        .box {
        width: 100%;
        height: auto;
        border: 1px solid lightgrey;
        padding: 10px;
        margin: 10px;
        border-top: 3px solid #17a2b8;
        display: block;
        }
</style>
    
<script>
       
    function calc(){
        var gtotal = 0;
        var count=0;
        $('#cargo-item-list .cargo-item').each(function(){
            var price = $(this).find('[name="price[]"]').val();
        //    var weight = $(this).find('[name="weight[]"]').val();
        //    var slab = $(this).find('[name="slab[]"]').val();
        //    var qty = $(this).find('[name="quantity[]"]').val();
            price = price > 0 ? price : 0;
        //    weight = weight > 0 ? weight : 0;
        //    qty = qty > 0 ? qty : 0;
        //    slab = slab > 0 ? slab : 0;            
        //    var total = (slab>0 && slab>weight) ? (parseFloat(price) * parseFloat(slab) * parseFloat(qty)) : (parseFloat(price) * parseFloat(weight) * parseFloat(qty));
            var total = parseFloat(price);
            $(this).find('[name="total[]"]').val(total)
            $(this).find('.total').text(parseFloat(total).toLocaleString('en-US'))
            gtotal += parseFloat(total)
            count++;

        })
        $('[name="total_item_count"]').val(count);
        $('[name="total_amount"]').val(gtotal)
        $('#gtotal').text(parseFloat(gtotal).toLocaleString('en-US'))
    }
	$(document).ready(function(){
        calc();
        $('.select2').select2({
            width:"100%",
            placeholder:"Please Select Here"
        })
        $('#add_item').click(function(){
            var item = $($('noscript#cargo-item-clone').html()).clone()
            $('#cargo-item-list').append(item)
            item.find('.select2').select2({
                width:"100%",
                placeholder:"Please Select Here"
            })
           
            // item.find('[name="weight[]"]').on('input',function(){
            //     console.log('test')
            //     calc()
            // })
            // item.find('[name="slab[]"]').on('input',function(){
            //     console.log('test')
            //     calc()
            // })
            item.find('[name="price[]"]').on('input',function(){
                 calc()
            })
        })
       
        // $('[name="weight[]"]').on('input',function(){
        //     console.log('test')
        //     calc()
        // })
        // $('[name="slab[]"]').on('input',function(){
        //     console.log('test')
        //     calc()
        // })
        $('[name="price[]"]').on('input',function(){
            calc()
        })

        $('#company_name').change(function(){
            var company = $(this).val();
            if(company){
                $.ajax({
                    type:'POST',
                    url:_base_url_+"classes/Master.php?f=get_vessels",
                    data:{'company':company},
                    success:function(result){
                        $('#vessel_name').html(result);
                        $('#vessel_name').val($('#vessel').val());     
                    }
                }); 
            }else{
                $('#vessel_name').html('<option value=""></option>');                
            }           
        }).trigger("change");
        $('#mode_of_transport').change(function() {
            if ($(this).val() == 'Truck') {
            $('#track_no').text('Trucking No');
            } else if ($(this).val() == 'Sea') {
            $('#track_no').text('BL No');
            } else {
            $('#track_no').text('AWB No');
            }
        }).trigger("change");

        $("select[name='r_mode_of_transport[]']").change(function(){
            if ($(this).val() == 'Truck') {
            $('#track_no').text('Trucking No');
            } else if ($(this).val() == 'Sea') {
            $('#track_no').text('BL No');
            } else {
            $('#track_no').text('AWB No');
            }
        }).trigger("change");
        // $('#shipping_type').change(function(){
        //     $('#cargo-item-list .cargo-item').each(function(){
        //         var id = $(this).find('[name="cargo_type_id[]"]').val()
        //         change_price($(this), id)
        //     })
        // })

        $('.delete_data').click(function(){
            if(confirm("Are you sure to delete this attachment?")) {
                delete_attachment($(this).attr('data-id'));
            }
            else{
                return false;
            }
		});

		$('#cargo-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_cargo",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.replace("./?page=transactions/view_transaction&id="+resp.cid);
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                    }else{
						alert_toast("An error occured",'error');
                        console.log(resp)
					}
					end_loader();
				}
			})
		})

        let entryIndex = <?= isset($existing_redirect) ? $existing_redirect : 0 ?>;
        $('#add_redirect').click(function() {
            this.disabled=true;
            entryIndex++;
            $('[name="total_redirect"]').val(entryIndex);
            $('#mainDiv').append(`<div class="accordion">    
                                <div class="section">
                                <dl class="row">
                                    <dt class="col-11"><a class="section-title" href='#${entryIndex}' onClick="openAccordion(this,'#${entryIndex}')">Redirect-${entryIndex}</a></dt>
                                    <dd class="col-1"><button type="button" class="btn btn-sm btn-primary btn-flat" style="width:auto;" onclick="cancel_button(this)">Remove</button></dd>
                                </dl>
                                
                                                               
                                <div id='${entryIndex}' class="section-content">  
                                <input type="hidden" name="isNewRedirect[]" value="true">                 
                                 
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="vessel_ETA" class="control-label">Vessel ETA</label>
                                        <input type="date" name="r_vessel_ETA[]" class="form-control form-control-sm form-control-border" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="vessel_ETD" class="control-label">Vessel ETD</label>
                                        <input type="date" name="r_vessel_ETD[]" class="form-control form-control-sm form-control-border" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="mode_of_transport">Mode Of Transport</label>
                                        <select name="r_mode_of_transport[]" class="form-control form-control-sm  form-control-border">     
                                       <option value="Air">Air</option>
                                        <option value="Sea">Sea</option>
                                        <option value="Truck">Truck</option>                                                                 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="track_no" id="track_no"><?= $mode_of_transport == 'Truck' ? 'Trucking No' : ($mode_of_transport == 'Sea' ? 'BL No' : 'AWB No') ?> </label>
                                        <input type="text" name="r_AWB_no[]" class="form-control form-control-sm form-control-border">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="flight_ETA">Flight ETA</label>
                                        <input type="date" name="r_flight_ETA[]" class="form-control form-control-sm form-control-border">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="flight_ETD">Flight ETD</label>
                                        <input type="date" name="r_flight_ETD[]" class="form-control form-control-sm form-control-border">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                    <div class="form-group mb-2">
                                        <label for="agency_info">Agency Information</label>
                                        <select name="r_agency_info[]" class="form-control form-control-sm  form-control-border">
                                        <option value="" selected></option>
                                        <?php 
                                            foreach($agency_list as $row):
                                        ?>
                                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>                                                            
                            </div>
                           
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="origin" class="control-label">Origin</label>
                                    <select name="r_origin[]" class="form-control form-control-sm  form-control-border" required>                                   
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($origin_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="destination" class="control-label">Destination</label>
                                    <select name="r_destination[]" class="form-control form-control-sm  form-control-border" required>
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($origin_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-sm-12">
                                <div class="form-group mb-2">
                                    <label for="onboard_at">On Board At</label>
                                    <select name="r_onboard_at[]" class="form-control form-control-sm  form-control-border">
                                    <option value="" selected></option>
                                    <?php 
                                        foreach($onboard_list as $row):
                                    ?>
                                    <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                                
                                </div><!-- section-content end -->                                
                            </div><!-- section end -->                            
                        </div><!-- accordion end -->`);
        });
        window.cancel_button = function(e) { 
                    $(e).closest('.accordion').remove();
                    entryIndex--;                                       
        }

	})
    //accordian JS
    $('.section-title').click(function(e) {
            // Get current link value
            var currentLink = $(this).attr('href');
            if($(e.target).is('.active')) {
                close_section();
            }else {
                close_section();
            // Add active class to section title
            $(this).addClass('active');
            // Display the hidden content
            $('.accordion ' + currentLink).slideDown(350).addClass('open');             
             
            }
        e.preventDefault();
        });
            
        function close_section() {
            $('.accordion .section-title').removeClass('active');
            $('.accordion .section-content').removeClass('open').slideUp(350);
        }
        function openAccordion(e, currentLink){           
                    if(e.classList.contains("active")) {
                        close_section();
                    }else {
                        close_section();
                    // Add active class to section title
                    $(e).addClass('active');
                    // Display the hidden content
                    $('.accordion ' + currentLink).slideDown(350).addClass('open');            
                }
        }        
        function delete_attachment($lnk){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_attachment",
			method:"POST",
			data:{lnk: $lnk, id: '<?= isset($id) ? $id : "" ?>'},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
                    window.location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>