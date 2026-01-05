<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_order_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
    

	function add_sales_order($input){
		//pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$sales_order_no = $input['sales_order_no'] ?? NULL;
		$sales_type_id = $input['sales_type_id'] ?? 0;
		$purchase_order_id = $input['purchase_order_id'] ?? 0;
		$po_date = $input['po_date'] ?? 0;
		$customer_id = $input['customer_id'] ?? 0;
		$order_date = $input['order_date'] ?? 'NULL';
		$delivery_date = $input['delivery_date'] ?? 'NULL';
		$customer_reference_no = $input['customer_reference_no'] ?? 'NULL';
		$billing_address = $input['billing_address'] ?? 'NULL';
		$shipping_address = $input['shipping_address'] ?? 'NULL';
		$shipping_description = $input['shipping_description'] ?? 'NULL';
		$shipping_cost = $input['shipping_cost'] ?? 0;
		$tax_id = $input['tax_id'] ?? 0;		
		$additional_info = $input['additional_info'] ?? 'NULL'; 
		$sales_order_status_id = $input['sales_order_status_id'] ?? 0; 
		$created_by = $input['created_by'] ?? 0;		
		$result_type = $input['result_type'] ?? '';

		$sales_order = $this->Common_model->callSP("SAVE_SALES_ORDER(".$store_id.",'".$sales_order_no."','".$purchase_order_id."','".$po_date."',".$customer_id.",".$customer_reference_no.",".$billing_address.",".$shipping_address.",".$shipping_description.",".$shipping_cost.",".$tax_id.",'".$order_date."','".$delivery_date."','".$additional_info."',".$sales_order_status_id.",".$created_by.",'".$sales_type_id."')", $result_type);
		//echo $this->db->last_query();
		return $sales_order;
	}

	function add_sales_order_detail($input){
		//pr($input);exit;
		$sales_order_id = $input['sales_order_id'] ?? NULL;
		$sales_order_detail_id = $input['sales_order_detail_id'] ?? 0;
		$category_id = $input['category_id'] ?? 0;		
		$product_id = $input['product_id'] ?? 0;
		$product_alias = $input['product_alias'] ?? NULL;
		$quantity = $input['quantity'] ?? 0;
		$price = $input['price'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? '';

		$sales_order_detail = $this->Common_model->callSP("SAVE_SALES_ORDER_DETAIL(".$sales_order_id.",".$product_id.",'".$product_alias."',".$quantity.",".$price.",".$created_by.",". $sales_order_detail_id.")", $result_type);
		//echo $this->db->last_query();exit;
		return $sales_order_detail;
	}	
	function delete_sales_order_detail($input){
		$sales_order_id = $input['sales_order_id'] ?? NULL;
		$this->Common_model->deleteData('tbl_sales_order_detail',array('sales_order_id'=>$sales_order_id));
	}
	
	function list_sales_order($input){
		$store_id = $input['store_id'] ?? 0;
		$sales_order_id = $input['sales_order_id'] ?? 0;
		$sales_order_no = $input['sales_order_no'] ?? 'NULL';		
		$customer_id = $input['customer_id'] ?? 0;
		$from_date = $input['from_date'] ?? 'NULL';		
		$to_date = $input['to_date'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';

		$sales_order_list = $this->Common_model->callSP("GET_SALES_ORDER_LIST(".$store_id.",".$sales_order_id.",".$sales_order_no.",".$customer_id.",".$from_date.",".$to_date.")", $result_type);
		return $sales_order_list;
	}

	function sales_order_detail($input){
		$store_id = $input['store_id'] ?? 0;
		$sales_order_id = $input['sales_order_id'] ?? 0;
		$sales_type_id = $input['sales_type_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';

		$sales_order_list = $this->Common_model->callSP("GET_SALES_ORDER_PRODUCT_LIST(".$store_id.",".$sales_order_id.",'".$sales_type_id."')", $result_type);
		return $sales_order_list;
	}

	function update_sales_order($input){
		$sales_order_id = $input['sales_order_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$sales_order_status_id = $input['sales_order_status_id'] ?? 0;
		if($sales_order_status_id > 0){
			return $pick_list_box = $this->Common_model->updateValue(array('sales_order_status_id'=>$sales_order_status_id),'tbl_sales_order',array('sales_order_id'=>$sales_order_id,'store_id'=>$store_id));
		}
	}

}

