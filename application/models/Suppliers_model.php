<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }

	function add_supplier($input){
		$supplier_id = $input['supplier_id'] ?? 0; 
		$company_name = $input['company_name'] ?? NULL;
		$email = $input['email'] ?? NULL;
		$mobile_no = $input['mobile_no'] ?? 0;
		$address = $input['address'] ?? NULL;		
		$additional_info = $input['additional_info'] ?? NULL;
		$gst_no = $input['gst_no'] ?? NULL;
		$pan_no = $input['pan_no'] ?? NULL;
		$created_by = $input['created_by'] ?? 0;
		$is_oem = $input['is_oem'] ?? 0;

		$supplier_list = $this->Common_model->callSP("SAVE_SUPPLIER(".$supplier_id.",'".$company_name."','".$email."','".$mobile_no."','".$address."','".$pan_no."','".$gst_no."','".$is_oem."','".$additional_info."',".$created_by.")","row");
		return $supplier_list;
	}

	function list_supplier($input = NULL){
		$supplier_id = $input['supplier_id'] ?? 0;
		$company_name = $input['company_name'] ?? 'NULL';
		$is_oem = $input['is_oem'] ?? 0;
		$supplier_list = $this->Common_model->callSP("GET_SUPPLIER_LIST(".$supplier_id.",".$company_name.",'".$is_oem."')","");
		return $supplier_list;
	}
}

