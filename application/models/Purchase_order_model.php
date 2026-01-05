<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_order_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
    

	function add_purchase_order($input){
		$store_id = $input['store_id'] ?? 0;
		$purchase_order_no = $input['purchase_order_no'] ?? NULL;
		$purchase_order_id = $input['purchase_order_id'] ?? 0;
		$po_date = !empty($input['po_date']) ? $input['po_date']: 'NULL';
		$customer_id = $input['customer_id'] ?? 0;
		$billing_address = $input['billing_address'] ?? 'NULL';
		$shipping_address = $input['shipping_address'] ?? 'NULL';
		$shipping_description = $input['shipping_description'] ?? 'NULL';
		$shipping_cost = $input['shipping_cost'] ?? 0;
		$tax_id = $input['tax_id'] ?? 0;		
		$additional_info = $input['additional_info'] ?? 'NULL'; 
		$is_deleted = $input['is_deleted'] ?? 0;
		$created_by = $input['created_by'] ?? 0;		
		$result_type = $input['result_type'] ?? '';

		$purchase_order = $this->Common_model->callSP("SAVE_PURCHASE_ORDER(".$purchase_order_id.",".$store_id.",'".$purchase_order_no."','".$po_date."',".$customer_id.",".$billing_address.",".$shipping_address.",".$shipping_description.",".$shipping_cost.",".$tax_id.",'".$additional_info."','".$is_deleted."',".$created_by.")", $result_type);
		return $purchase_order;
	}

	function add_purchase_order_detail($input){
		$purchase_order_id = $input['purchase_order_id'] ?? NULL;
		$category_id = $input['category_id'] ?? 0;		
		$product_id = $input['product_id'] ?? 0;
		$product_alias = $input['product_alias'] ?? NULL;
		$quantity = $input['quantity'] ?? 0;
		$price = $input['price'] ?? 0;
		$discount_percentage = $input['discount_percentage'] ?? 0;
		$tax_id = $input['tax_id'] ?? 0;		
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? '';

		$purchase_order_detail = $this->Common_model->callSP("SAVE_PURCHASE_ORDER_DETAIL(".$purchase_order_id.",".$product_id.",'".$product_alias."',".$quantity.",".$price.",".$discount_percentage.",".$tax_id.",".$created_by.")", $result_type);
		return $purchase_order_detail;
	}
	
	function list_purchase_order($input){
		$store_id = $input['store_id'] ?? 0;
		$purchase_order_id = $input['purchase_order_id'] ?? 0;
		$purchase_order_no = $input['purchase_order_no'] ?? 'NULL';		
		$customer_id = $input['customer_id'] ?? 0;
		$from_date = $input['from_date'] ?? 'NULL';		
		$to_date = $input['to_date'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';

		$purchase_order_list = $this->Common_model->callSP("GET_PURCHASE_ORDER_LIST(".$store_id.",".$purchase_order_id.",".$purchase_order_no.",".$customer_id.",".$from_date.",".$to_date.")", $result_type);
		return $purchase_order_list;
	}

	function purchase_order_detail($input){
		$store_id = $input['store_id'] ?? 0;
		$purchase_order_id = $input['purchase_order_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';

		$purchase_order_list = $this->Common_model->callSP("GET_PO_PRODUCT_LIST(".$store_id.",".$purchase_order_id.")", $result_type);
		return $purchase_order_list;
	}

}

