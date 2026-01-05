<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Semi_finish_good_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	function add_sfg($input)
	{
		// pr($input);exit;
		$sfg_id = $input['sfg_id'] ?? 0;
		$category_id = $input['category_id'] ?? 0;
		$subcategory_id = $input['subcategory_id'] ?? 0;
		$unit_id = $input['unit_id'] ?? 0;
		$hsn_code = $input['hsn_code'] ?? NULL;
		$sfg_code = $input['sfg_code'] ?? 'NULL';
		$sfg_name = $input['sfg_name'] ?? NULL;
		$additional_info = $input['additional_info'] ?? NULL;
		$store_id = $input['store_id'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$weight = !empty($input['weight']) ? (float)$input['weight'] : 0;
		$sustainability_score = !empty($input['sustainability_score']) ? (float)$input['sustainability_score'] : 0;
		$created_date = date('Y-m-d H:i:s');
		$is_deleted = $input['is_deleted'] ?? 0;
		$raw_material_id = $this->Common_model->callSP("SAVE_SFG(" . $sfg_id . ",'" . $sfg_name . "','" . $sfg_code . "','" . $category_id . "','" . $subcategory_id . "','" . $hsn_code . "','" . $additional_info . "','" . $is_deleted . "','" . $created_by . "','" . $created_date . "'," . $store_id . "," . $weight . "," . $sustainability_score . ",".$unit_id.")", "row");
		return $raw_material_id;
	}
	function sfg_list($input)
	{
		$sfg_id = $input['sfg_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$sfg_name = $input['sfg_name'] ?? 'NULL';
		$category_id = $input['category_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$sfg_list = $this->Common_model->callSP("GET_SFG_LIST(" . $sfg_id . "," . $sfg_name . "," . $category_id . "," . $store_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $sfg_list;
	}
	function add_sfg_grn($input)
	{
		// pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$grn_type = $input['grn_type'] ?? 0;
		$sfg_grn_id = $input['sfg_grn_id'] ?? 0;
		$sfg_grn_no = $input['sfg_grn_no'] ?? 'NULL';
		$po_date = $input['po_date'] ?? 'NULL';
		$po_no = $input['po_no'] ?? 'NULL';
		$additional_info = $input['additional_info'] ?? 'NULL';
		$created_by = $input['created_by'] ?? 0;
		$is_sfg_quality_checked = $input['is_sfg_quality_checked'] ?? '0';
		$result_type = $input['result_type'] ?? 'row';

		$sfg_grn = $this->Common_model->callSP("SAVE_SFG_GRN(" . $store_id . "," . $sfg_grn_id . ",'" . $sfg_grn_no . "','" . $additional_info . "'," . $created_by . "," . $po_no . "," . $po_date . ",'" . $is_sfg_quality_checked . "','" . $grn_type . "')", $result_type);
		// pr($sfg_grn);exit;
		// echo $this->db->last_query();exit;
		return $sfg_grn;
	}
	function add_sfg_grn_detail($input)
	{
		// pr($input);exit;
		$sfg_grn_id = $input['sfg_grn_id'] ?? 0;
		$item_id = $input['item_id'] ?? 0;
		$hsn_code = $input['hsn_code'] ?? 'NULL';
		$quantity = $input['quantity'] ?? 0;
		$grn_no_of_boxes = $input['grn_no_of_boxes'] ?? 0;
		$inward_unit_id = $input['inward_unit_id'] ?? NULL;
		$amount = $input['amount'] ?? NULL;
		$tax_id = $input['tax_id'] ?? 0;
		$after_tax = $input['after_tax'] ?? NULL;
		$rate = $input['rate'] ?? 0;
		$supplier_id = $input['supplier_id'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';
		$sfg_grn_detail = $this->Common_model->callSP("SAVE_SFG_GRN_DETAIL(" . $sfg_grn_id . ",'" . $hsn_code . "'," . $quantity . ",'" . $inward_unit_id . "'," . $rate . ",'" . $amount . "','" . $tax_id . "','" . $after_tax . "'," . $grn_no_of_boxes . "," . $created_by . ",'" . $item_id . "'," . $supplier_id . ")", $result_type);
		//pr($product_grn_detail);exit;
		// echo $this->db->last_query();exit;
		return $sfg_grn_detail;
	}
	function list_sfg_grn($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$sfg_grn_id = $input['sfg_grn_id'] ?? 0;
		$sfg_grn_detail_id = $input['sfg_grn_detail_id'] ?? 0;
		$sfg_grn_no = $input['sfg_grn_no'] ?? 'NULL';
		$sfg_name = $input['sfg_name'] ?? 'NULL';
		// $is_sfg_quality_checked = $input['is_sfg_quality_checked'] ?? 0;
		$grn_date = $input['grn_date'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';

		$product_grn_list = $this->Common_model->callSP("GET_SFG_GRN_LIST(" . $store_id . "," . $sfg_grn_id . "," . $sfg_grn_detail_id . "," . $sfg_grn_no . "," . $sfg_name . "," . $grn_date . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $product_grn_list;
	}
	function add_sfg_quality_check($input)
	{
		// pr($input);exit;
		$sfg_grn_detail_id = $input['sfg_grn_detail_id'] ?? 0;
		$sfg_quality_checked_boxes = $input['sfg_quality_checked_boxes'] ?? 0;
		$sfg_quality_checked_item = $input['sfg_quality_checked_item'] ?? 0;
		$purchase_price_per_item = $input['purchase_price_per_item'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';

		$sfg_grn_detail = $this->Common_model->callSP("SAVE_SFG_QUALITY_CHECK(" . $sfg_quality_checked_boxes . "," . $sfg_quality_checked_item . "," . $purchase_price_per_item . "," . $sfg_grn_detail_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $sfg_grn_detail;
	}
	function add_box_detail($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$sfg_grn_id = $input['sfg_grn_id'] ?? NULL;
		$sfg_grn_detail_id = $input['sfg_grn_detail_id'] ?? 0;
		$grn_type = $input['grn_type'] ?? 0;
		$item_id = $input['item_id'] ?? 0;
		$box_name = $input['box_name'] ?? NULL;  
		$no_of_items = $input['no_of_items'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';

		$sp_call = "SAVE_SFG_BOX_DETAIL(" . $store_id . "," . $item_id . "," . $sfg_grn_id . "," . $sfg_grn_detail_id . ",'" . $box_name . "'," . $no_of_items . "," . $created_by . ",'" . $grn_type . "')";

		$rm_grn_detail = $this->Common_model->callSP($sp_call, $result_type);
		return $rm_grn_detail;
	}

	function create_put_away($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$location_no = $input['location_no'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$grn_type = $input['grn_type'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$created_by = $input['created_by'] ?? 0;
		$box_detail = $this->Common_model->callSP("SAVE_SFG_BOX_LOCATION(" . $store_id . "," . $location_no . "," . $box_no . "," . $created_by . ",'".$grn_type."')", $result_type);
		return $box_detail;
	}
	function list_box_location($input)
	{
		$box_location_id = $input['box_location_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$location_no = $input['location_no'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_RM_BOX_LOCATION(" . $box_location_id . "," . $store_id . "," . $box_no . "," . $location_no . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $box_detail;
	}
	function list_box($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$box_id = $input['box_id'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_RM_BOX_DETAIL(" . $box_id . "," . $box_no . "," . $store_id . ")", $result_type);
		return $box_detail;
	}
	function get_item_barcode($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_ITEM_BARCODE(" . $box_no . "," . $store_id . ")", $result_type);
		return $box_detail;
	}
	function get_barcode($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$sfg_grn_detail_id = $input['sfg_grn_detail_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_BARCODE(" . $sfg_grn_detail_id . "," . $store_id . ")", $result_type);
		return $box_detail;
	}
}
