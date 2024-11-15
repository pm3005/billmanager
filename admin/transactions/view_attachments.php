<?php

require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `shipment_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        $upload_files_arr= [];
        while($row = $qry->fetch_array()){
            $upload_files = $row['upload_files'];
            $target_dir = "../uploads/shipment_attachments/" . $_GET['id'] ."/";                                   
            if($upload_files!=''){                    
                $upload_files_arr = explode (", ", $upload_files); 
            }
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
	<dl>
        <dt class="text-muted">Attachment(s)</dt>
        <dd class="pl-4">
        <span>
                                <?php foreach($upload_files_arr as $str){
                                        $uploadedFile = $target_dir . basename($str); ?>
                                        <!-- <a href="<?php echo $uploadedFile; ?>" target="_blank"><?php echo $str; ?></a> <br> -->
                                        
                                        <a href="#" onclick="window.open('<?php echo $uploadedFile; ?>','Popup','width=700,height=600,left=500,top=200')"><?php echo $str; ?></a> <br>
                                    <?php } ?>
                                </span>
        </dd>
        
    </dl>
    <div class="clear-fix my-3"></div>
    <div class="text-right">
        <button class="btn btn-sm btn-dark bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>