<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	
	function dashboard_data(){
		$sqlQuery = "SELECT * FROM shipment_list "; 
		$where = '';
		if(($_POST['status'])!=''){
			$where .= " status LIKE '%".($_POST['status'])."%' "; 
		}
		
		// Individual column filtering
		$columnSearch = array();
		if ( isset( $_POST['columns'] ) ) {
			for ( $i=0, $ien=count($_POST['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $_POST['columns'][$i];
				
				$str = $requestColumn['search']['value'];

				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' ) {
					switch($i){
						/* 2:
							$columnSearch[] = "vessel_name LIKE '%".$str."%'";
							break;*/
						case 3:
							$str_arr = explode (",", $str);
							$new_str = "ref_code LIKE '%".$str_arr[0]."%'";

							for ($key = 1, $size = count($str_arr); $key < $size; $key++) {
								if($str_arr[$key]!='')
								$new_str .= " OR ref_code LIKE '%".$str_arr[$key]."%'";
							}
							$columnSearch[] = $new_str;
							//$columnSearch[] = "ref_code LIKE '%".$str."%'";
							break;
						case 4:
							$columnSearch[] = "Wens_PO_no LIKE '%".$str."%'";
							break;
						case 5:
							$columnSearch[] = "PO_no LIKE '%".$str."%'";
							break;
						case 6:
							$columnSearch[] = "supplier_info LIKE '%".$str."%'";
							break;
						case 9:
							$columnSearch[] = "origin LIKE '%".$str."%'";
							break;
						case 10:
							$columnSearch[] = "destination LIKE '%".$str."%'";
							break;
						case 13:
							$str_arr = explode (",", $str);
							$new_str = "AWB_no LIKE '%".$str_arr[0]."%'";

							for ($key = 1, $size = count($str_arr); $key < $size; $key++) {
								if($str_arr[$key]!='')
								$new_str .= " OR AWB_no LIKE '%".$str_arr[$key]."%'";
							}
							$columnSearch[] = $new_str;
							//$columnSearch[] = "AWB_no LIKE '%".$str."%'";
							break;
						case 14:
							$columnSearch[] = "status LIKE '%".$str."%'";
							break;
						case 15:
							$columnSearch[] = "remarks LIKE '%".$str."%'";
							break;
						case 16:
							$columnSearch[] = "login_user LIKE '%".$str."%'";
							break;
					}
					//$_POST["search"]["value"] = $str;
				}
			}
		}
		
		if(!empty($_POST["search"]["value"])){
			$where .= $where !== '' ? ' AND ' : '';
			$where .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
			$where .= ' OR ref_code LIKE "%'.$_POST["search"]["value"].'%" ';			
			$where .= ' OR vessel_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$where .= ' OR Wens_PO_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$where .= ' OR PO_no LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR supplier_info LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR origin LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR destination LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR remarks LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR login_user LIKE "%'.$_POST["search"]["value"].'%" '; 
			$where .= ' OR AWB_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$where .= ' OR `status` LIKE "%'.$_POST["search"]["value"].'%" ';          
			$where .= ' OR crtd_dt LIKE "%'.$_POST["search"]["value"].'%") ';
		   
	   }	
		// Combine the filters into a single string		
		
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		if ( $where !== '') {
			$where = 'WHERE '.$where;
		}
		
		$sqlQuery .= $where;

		$stmtTotal = $this->conn->prepare($sqlQuery);
        $stmtTotal->execute();
        $allResult = $stmtTotal->get_result();
        $allRecords = $allResult->num_rows;
        
        if(!empty($_POST['order']['0']['column'])){
			$col = '';
			switch($_POST['order']['0']['column']){
				case 1:
					$col = 'unix_timestamp(crtd_dt)';					
					break;
				/*case 2:
					$col = 'vessel_name';					
					break;*/
				case 3:
					$col = 'ref_code';					
					break;
				case 4:
					$col = 'Wens_PO_no';					
					break;
				case 5:
					$col = 'PO_no';					
					break;
				case 6:
					$col = 'supplier_info';					
					break;
				case 9:
					$col = 'origin';					
					break;
				case 10:
					$col = 'destination';					
					break;
				case 11:
					$col = 'vessel_ETA';					
					break;
				case 12:
					$col = 'vessel_ETD';					
					break;
				case 13:
					$col = 'AWB_no';					
					break;
				case 14:
					$col = 'status';					
					break;
				case 15:
					$col = 'remarks';					
					break;
				case 16:
					$col = 'login_user';					
					break;
			}
             $sqlQuery .= 'ORDER BY '.$col.' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY unix_timestamp(crtd_dt) DESC ';
        }
        
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $result = $stmt->get_result();	

		$total_qty = [];
		$total_weight = [];
		$items = $this->conn->query("SELECT cargo_id,sum(quantity) q, sum(weight) w FROM `cargo_items` group by cargo_id");
		if($items->num_rows > 0){
			while($row = $items->fetch_assoc()){
				$total_qty[$row['cargo_id']] = $row['q'];
				$total_weight[$row['cargo_id']] = $row['w'];
			}
		}
        
        // $qry = "SELECT * FROM shipment_list ";
		// if(($_POST['status'])!=''){
		// 	$qry .= "where status LIKE '%".($_POST['status'])."%' "; 
		// }
                
        // $stmtTotal = $this->conn->prepare($qry);
        // $stmtTotal->execute();
        // $allResult = $stmtTotal->get_result();
        // $allRecords = $allResult->num_rows;
        
        $displayRecords = $result->num_rows;
        $records = array();	
		$i = $_POST['start'];;	
        while ($record = $result->fetch_assoc()) { 		
            $rows = array();			
            $rows[] = '<input type="checkbox" name="shipments[]" value="'.$record['id'].'">';
            $rows[] = date("Y-m-d", strtotime($record['crtd_dt']));			
            //$rows[] = $record['vessel_name'];		
            //$rows[] = $record['ref_code'];
			$rows[] = '<a href="?page=transactions/view_transaction&id='.$record["id"].'">'.$record['ref_code'].'</a>
			<div style="white-space: nowrap;">						
						<a class="btn btn-default bg-gradient-light btn-flat btn-sm view-attachments" href="javascript:void(0)" data-id="'.$record["id"].'"><span class="fa fa-solid fa-paperclip"></span></a>
						<div>';
			//$rows[] = '<span id="'.$record['id'].'" name="PO_no" class="editable">'.$record['PO_no'].'</span>';
			$rows[] = $record['supplier_info'];
			$rows[] = isset($total_qty[$record['id']]) ? $total_qty[$record['id']] : '';
			$rows[] = isset($total_weight[$record['id']]) ? $total_weight[$record['id']] : '';
			$rows[] = $record['origin'];
			$rows[] = $record['destination'];
			$rows[] = '<input id="'.$record['id'].'" name="vessel_ETA" class="editable" type="date" value="'.$record['vessel_ETA'].'" onchange="changeIn(this)">';
			$rows[] = '<input id="'.$record['id'].'" name="vessel_ETD" class="editable" type="date" value="'.$record['vessel_ETD'].'" onchange="changeIn(this)">';

			//new block added to get awb of redirect shipments
			// if($record['status'] == 7){
			// 	$qry_awb = $this->conn->query("SELECT GROUP_CONCAT(AWB_no) a FROM redirect_shipment_list where cargo_id='{$record['id']}' GROUP BY cargo_id");
			// 	if($qry_awb->num_rows > 0){
			// 		while($row_awb = $qry_awb->fetch_assoc()){           
			// 			$list = $row_awb['a'];
			// 		}
			// 	}
			// 	$rows[] = $record['AWB_no']!=null ? $record['AWB_no']. "," . $list : $list;
			// }else
            	$rows[] = $record['AWB_no'];	
           // $rows[] = $record['status'];	  
			if($record['status'] == 1)
				$rows[] = '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Cargo Picked-Up</span>';
			 elseif($record['status'] == 2)
			 	$rows[] = '<span class="badge badge-warning bg-gradient-warning px-3 rounded-pill">Arrived at Station</span>';
			 elseif($record['status'] == 3)
			 	$rows[] = '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">Stored at Warehouse</span>';
			 elseif($record['status'] == 4)
			 	$rows[] = '<span class="badge badge-danger bg-gradient-blue px-3 rounded-pill">Ready for Delivery</span>';
			 elseif($record['status'] == 5)
			 	$rows[] = '<span class="badge badge-info bg-gradient-success px-3 rounded-pill">Delivered</span>';
			 else
			 	$rows[] = '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';

			$rows[] = '<span id="'.$record['id'].'" name="remarks" class="editable">'.$record['remarks'].'</span>';
			$rows[] = $record['login_user'];
            // $rows[] = '<div style="white-space: nowrap;">						
			// 			<a class="btn btn-default bg-gradient-light btn-flat btn-sm view-attachments" href="javascript:void(0)" data-id="'.$record["id"].'"><span class="fa fa-solid fa-paperclip"></span></a>
			// 			<div>';
            
            $records[] = $rows;
        }
        
        $output = array(
            "draw"	=>	intval($_POST["draw"]),			
            "iTotalRecords"	=> 	$displayRecords,
            "iTotalDisplayRecords"	=>  $allRecords,
            "data"	=> 	$records
        );
        
        return json_encode($output);
	}
	
	
	function update_data(){
		$shipmentId= $_REQUEST['shipmentId'];
		$newValue= $_REQUEST['newValue'];
		$colName= $_REQUEST['colName'];
		$login_user= $_REQUEST['login_user'];

		if($shipmentId != '' && $newValue != '' && $colName != '')
		{
			$update_qry = "update shipment_list set ".$colName." = '".$newValue."', login_user = '" .$login_user. "' where id = ".$shipmentId;
			$update = $this->conn->query($update_qry);
			if($update)
			{
				echo 'Updated successfully';
			}
			else
			{
				echo 'Error in Updation';
			}
		}
	}

	function save_cargo(){
		if(empty($_POST['id'])){
			$pref = "". date("ym") . "/";
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `shipment_list` where ref_code = '{$pref}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			$_POST['ref_code'] = $pref.$code;
		}

		if(!empty($_POST['upload_files']) && $_FILES['shipmentFiles']['name'][0]!=''){
			$_POST['upload_files'] .= ', ' . implode(', ', ($_FILES['shipmentFiles']['name']));
		}else{
			$_POST['upload_files'] .= implode(', ', ($_FILES['shipmentFiles']['name']));  
		}		
		
		extract($_POST);
		$cargo_allowed_statuss = ["ref_code","company_name","vessel_name","person_in_contact","PO_no","vessel_ETA","vessel_ETD","mode_of_transport","AWB_no","flight_ETA","flight_ETD","origin","destination","onboard_at","agency_info","status","remarks","login_user","upload_files", "supplier_info"];
		$data = "";
		foreach($_POST as $k =>$v){
			if(in_array($k,$cargo_allowed_statuss)){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}		 

		if(empty($id)){
			$sql = "INSERT INTO `shipment_list` set {$data} ";
		}else{
			$sql = "UPDATE `shipment_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$cid = empty($id) ? $this->conn->insert_id : $id;
			$resp['cid'] = $cid;

			$fileName = $_FILES['shipmentFiles']['name'];
			if (isset($fileName))
			{
				$count = count($fileName);
				$new_dir = base_app."uploads/shipment_attachments/" . $cid ."/";
				if( !is_dir($new_dir) )
					mkdir( $new_dir );
				for($i = 0; $i < $count; $i++){
					$target_file = $new_dir . basename($_FILES['shipmentFiles']['name'][$i]);
					move_uploaded_file($_FILES["shipmentFiles"]["tmp_name"][$i], $target_file);       
				}                 
			} 

			if(empty($id))
				$resp['msg'] = " New Shipment successfully added.";
			else
				$resp['msg'] = " Shipment Details has been updated successfully.";
			$resp['status'] = 'success';
			
			$data="";
			
			$total=0;
			for($i=0;$i<$total_item_count;$i++){
				if(!empty($data)) $data .=",";
				// if($price[$i]>0 && $quantity[$i]>0)
				// 	$total = ($slab[$i]>0 && $slab[$i]>$weight[$i]) ? ($price[$i] * $slab[$i] * $quantity[$i]) : ($price[$i] * $weight[$i] * $quantity[$i]);
				// $data .= "('{$cid}', '{$description[$i]}', '{$quantity[$i]}', '{$price[$i]}', '{$weight[$i]}', '{$slab[$i]}', '{$total}')";
				$data .= "('{$cid}', '{$description[$i]}', '{$quantity[$i]}', '{$price[$i]}', '{$weight[$i]}')";
			}		

			if(!empty($data)){
				$this->conn->query("DELETE FROM `cargo_items` where `cargo_id` = '{$cid}'");
				//$sql3 = "INSERT INTO `cargo_items` (`cargo_id`, `description`, `quantity`, `price`, `weight`, `slab`, `total`) VALUES {$data}";
				$sql3 = "INSERT INTO `cargo_items` (`cargo_id`, `description`, `quantity`, `price`, `weight`) VALUES {$data}";
				$save3 = $this->conn->query($sql3);
				if(!$save3){
					$resp['status'] = 'failed';
					$resp['msg'] = " Saving Transaction failed.";
					$resp['err'] = $this->conn->error;
					$resp['sql'] = $sql3;
					if(empty($id))
					$this->conn->query("DELETE FROM `shipment_list` where id = '{$cid}'");
				}
			}
			if(empty($id)){
				$save_track = $this->add_track($cid,"Pending","Shipment created.", $login_user);
			}
			//make entry for redirect shipment
			$data="";
			for($i=0;$i<$total_redirect;$i++){
				$redirect_ref_code = $_POST['ref_code'] . "-" . $i;
				if($isNewRedirect[$i] == "true"){
					if(!empty($data)) $data .=",";					
					$data .= "('{$cid}', '{$redirect_ref_code}', '{$r_mode_of_transport[$i]}', '{$r_AWB_no[$i]}', '{$r_vessel_ETA[$i]}', '{$r_vessel_ETD[$i]}', '{$r_agency_info[$i]}', '{$r_flight_ETA[$i]}', '{$r_flight_ETD[$i]}', '{$r_origin[$i]}', '{$r_destination[$i]}', '{$r_onboard_at[$i]}', '{$login_user}')";
				}else{
					$data="";
					extract($_POST);
					$update = $this->conn->query("UPDATE `redirect_shipment_list` set `mode_of_transport` = '{$r_mode_of_transport[$i]}', `AWB_no` = '{$r_AWB_no[$i]}',  `vessel_ETA` = '{$r_vessel_ETA[$i]}',  `vessel_ETD` = '{$r_vessel_ETD[$i]}',  `agency_info` = '{$r_agency_info[$i]}', `flight_ETA` = '{$r_flight_ETA[$i]}', `flight_ETD` = '{$r_flight_ETD[$i]}', `origin` = '{$r_origin[$i]}', `destination` = '{$r_destination[$i]}', `onboard_at` = '{$r_onboard_at[$i]}', `login_user` = '{$login_user}' where redirect_ref_code ='{$redirect_ref_code}'");
					if(!$update){
						$resp['status'] = 'failed';
						$resp['msg'] = " Saving Transaction failed.";
						$resp['err'] = $this->conn->error;
					}
				}
			}				
			if(!empty($data)){
				//$this->conn->query("DELETE FROM `redirect_shipment_list` where `cargo_id` = '{$cid}'");
				$sql4 = "INSERT INTO `redirect_shipment_list` (`cargo_id`, `redirect_ref_code`, `mode_of_transport`, `AWB_no`, `vessel_ETA`, `vessel_ETD`, `agency_info`, `flight_ETA`, `flight_ETD`, `origin`, `destination`, `onboard_at`, `login_user`) VALUES {$data}";
				$save4 = $this->conn->query($sql4);
				if(!$save4){
					$resp['status'] = 'failed';
					$resp['msg'] = " Saving Transaction failed.";
					$resp['err'] = $this->conn->error;
					$resp['sql'] = $sql4;					
				}
			}

		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_cargo(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `shipment_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," shipment successfully deleted.");
			if(is_dir(base_app."uploads/cargo_".$id)){
				$fopen = scandir(base_app."uploads/cargo_".$id);
				foreach($fopen as $file){
					if(!in_array($file,[".",".."])){
						unlink(base_app."uploads/cargo_".$id."/".$file);
					}
				}
				rmdir(base_app."uploads/cargo_".$id);
			}
			
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_attachment(){
		extract($_POST);
		$input = $lnk;
		$qry = $this->conn->query("SELECT upload_files from `shipment_list` where id = '{$id}' ");
		if($qry->num_rows > 0){
			while($row = $qry->fetch_assoc()){           
            	$list = $row['upload_files'];
			}
			$array1 = Array($input);
			$array2 = explode(', ', $list);
			$array3 = array_diff($array2, $array1);

			$output = implode(', ', $array3);
			
			$update = $this->conn->query("UPDATE `shipment_list` set `upload_files` = '{$output}' where id ='{$id}'");
			if($update){
				$resp['status'] = 'success';
				if(is_dir(base_app."uploads/shipment_attachments/" . $id)){
					$fopen = scandir(base_app."uploads/shipment_attachments/" . $id);
					unlink(base_app."uploads/shipment_attachments/".$id."/".$lnk);
				}
				//$resp['msg'] = "Attachment has been deleted.";
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $this->conn->error;
				//$resp['msg'] = "Delete action of attachment has failed.";
			}
		}
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		
		return json_encode($resp);
	}

	function update_shipment_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `shipment_list` set `status` = '{$status}', `login_user` = '{$login_user}', `remarks` = '{$remarks}' where id ='{$id}'");
		$status_lbl = ['Pending','Cargo Picked-Up','Arrive at Station', 'Stored at Warehouse', 'Ready for OBD', 'Delivered - Waiting POD', 'Delivered - POD Received', 'Redirected'];
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = " Shipment Status has been updated.";
			$save_track = $this->add_track($id,$status_lbl[$status],$remarks,$login_user);
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = " Shipment Status has failed update.";
		}

		if($resp['status'] == 'success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function add_track($cargo_id = '', $title= '', $description='', $login_user=''){
		if(!empty($cargo_id) && !empty($title)){
			$insert = $this->conn->query("INSERT INTO `tracking_list` (`cargo_id`, `title`, `description`, `login_user`) VALUES ('{$cargo_id}', '{$title}', '{$description}', '{$login_user}') ");
			if($insert)
			return true;
			else
			return false;
		}
		return false;
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'save_vessel':
		echo $Master->save_vessel();
	break;	
	case 'save_org_dest':
		echo $Master->save_org_dest();
	break;	
	case 'save_contact_person':
		echo $Master->save_contact_person();
	break;	
	case 'save_onboard':
		echo $Master->save_onboard();
	break;	
	case 'save_agency':
		echo $Master->save_agency();
	break;
	case 'save_supplier':
		echo $Master->save_supplier();
	break;
	case 'dashboard_data':
		echo $Master->dashboard_data();
	break;	
	case 'save_po':
		echo $Master->save_po();
	break;
	// case 'save_cargo_type_order':
	// 	echo $Master->save_cargo_type_order();
	// break;
	case 'save_status':
		echo $Master->save_status();
	break;
	case 'delete_status':
		echo $Master->delete_status();
	break;
	case 'save_status_order':
		echo $Master->save_status_order();
	break;
	case 'save_cargo':
		echo $Master->save_cargo();
	break;
	case 'delete_cargo':
		echo $Master->delete_cargo();
	break;
	case 'update_shipment_status':
		echo $Master->update_shipment_status();
	break;
	case 'get_vessels':
		echo $Master->get_vessels();
	break;
	case 'delete_attachment':
		echo $Master->delete_attachment();
	break;	
	case 'update_data':
		echo $Master->update_data();
	break;	
	default:
		// echo $sysset->index();
		break;
}
