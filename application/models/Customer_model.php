<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }

	function add_customer($input){
		$customer_id = $input['customer_id'] ?? 0; 
		$first_name = $input['first_name'] ?? NULL;
		$last_name = $input['last_name'] ?? NULL;
		$company_name = $input['company_name'] ?? NULL;
		$display_name = $input['display_name'] ?? NULL;
		$email = $input['email'] ?? NULL;
		$mobile_no = $input['mobile_no'] ?? NULL;
		$pan_no = $input['pan_no'] ?? NULL;
		$gst_no = $input['gst_no'] ?? NULL;
		$shipping_add = $input['shipping_address'] ?? NULL;
		$billing_add = $input['billing_address'] ?? NULL;
		$additional_info = $input['additional_info'] ?? NULL;
		$created_by = $input['created_by'] ?? 1;
		
		$customer_list = $this->Common_model->callSP("SAVE_CUSTOMER(".$customer_id.",'".$first_name."','".$last_name."','".$company_name."','".$display_name."','".$email."','".$billing_add."','".$shipping_add."','".$mobile_no."','".$pan_no."','".$gst_no."','".$additional_info."',".$created_by.")","row");
		return $customer_list;
	}

	function list_customer($input){
		$customer_id = $input['customer_id'] ?? 0;
		$company_name = $input['company_name'] ?? 'NULL';
		$customer_list = $this->Common_model->callSP("GET_CUSTOMER_LIST(".$customer_id.",".$company_name.")","");
		return $customer_list;
	}

}

