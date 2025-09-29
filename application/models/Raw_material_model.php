<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Raw_material_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	function add_raw_material($input)
	{
		$raw_material_id = $input['raw_material_id'] ?? 0;
		$category_id = $input['category_id'] ?? 0;
		$unit_id = $input['unit_id'] ?? 0;
		$subcategory_id = $input['subcategory_id'] ?? 0;
		$size = $input['size'] ?? NULL;
		$hsn_code = $input['hsn_code'] ?? NULL;
		$raw_material_code = $input['raw_material_code'] ?? NULL;
		$raw_material_name = $input['raw_material_name'] ?? NULL;
		$additional_info = $input['additional_info'] ?? NULL;
		$min_level = $input['min_level'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$max_level = $input['max_level'] ?? 0;
		$inward_unit_id = $input['inward_unit_id'] ?? 0;
		$outward_unit_id = $input['outward_unit_id'] ?? 0;
		$weight = !empty($input['weight']) ? (float)$input['weight'] : 0;
		$sustainability_score = !empty($input['sustainability_score']) ? (float)$input['sustainability_score'] : 0;

		$wastage = $input['wastage'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$created_date = date('Y-m-d H:i:s');
		$is_deleted = $input['is_deleted'] ?? 0;
		$raw_material_id = $this->Common_model->callSP("SAVE_RAW_MATERIAL(" . $raw_material_id . ",'" . $raw_material_name . "','" . $raw_material_code . "','" . $category_id . "','" . $subcategory_id . "','" . $size . "','" . $hsn_code . "','" . $min_level . "','" . $max_level . "','" . $inward_unit_id . "','" . $outward_unit_id . "','" . $wastage . "','" . $additional_info . "','" . $is_deleted . "','" . $created_by . "','" . $created_date . "'," . $store_id . "," . $weight . "," . $sustainability_score . ",".$unit_id.")", "row");
		// echo $this->db->last_query();exit;
		return $raw_material_id;
	}
	function raw_material_list($input)
	{
		// pr($input);exit;
		$raw_material_id = $input['raw_material_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$raw_material_name = $input['raw_material_name'] ?? 'NULL';
		$category_id = $input['category_id'] ?? 0;
		$product_list = $this->Common_model->callSP("GET_RAW_MATERIAL_LIST(" . $raw_material_id . "," . $raw_material_name . "," . $category_id . "," . $store_id . ")", "");
		// echo $this->db->last_query();exit;
		return $product_list;
	}
	function add_rm_grn($input)
	{
		// pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$rm_grn_id = $input['rm_grn_id'] ?? 0;
		$rm_grn_no = $input['rm_grn_no'] ?? NULL;
		$po_date = $input['po_date'] ?? NULL;
		$po_no = $input['po_no'] ?? 0;
		$supplier_id = $input['supplier_id'] ?? 0;
		$additional_info = $input['additional_info'] ?? NULL;
		$created_by = $input['created_by'] ?? 0;
		$is_rm_quality_checked = $input['is_rm_quality_checked'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';

		$rm_grn = $this->Common_model->callSP("SAVE_RM_GRN(" . $store_id . "," . $rm_grn_id . ",'" . $rm_grn_no . "','" . $additional_info . "'," . $created_by . "," . $po_no . ",'" . $po_date . "'," . $supplier_id . ",'" . $is_rm_quality_checked . "')", $result_type);
		// pr($rm_grn);exit;
		return $rm_grn;
	}
	function add_rm_grn_detail($input)
	{
		//pr($input);exit;
		$rm_grn_id = $input['rm_grn_id'] ?? 0;
		$raw_material_id = $input['raw_material_id'] ?? 0;
		$hsn_code = $input['hsn_code'] ?? 0;
		$quantity = $input['quantity'] ?? 0;
		$no_of_boxes = $input['no_of_boxes'] ?? 0;
		$inward_unit_id = $input['inward_unit_id'] ?? NULL;
		$amount = $input['amount'] ?? NULL;
		$tax_id = $input['tax_id'] ?? 0;
		$after_tax = $input['after_tax'] ?? NULL;
		$rate = $input['rate'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';
		$rm_grn_detail = $this->Common_model->callSP("SAVE_RM_GRN_DETAIL(" . $rm_grn_id . "," . $hsn_code . "," . $quantity . ",'" . $inward_unit_id . "'," . $rate . ",'" . $amount . "','" . $tax_id . "','" . $after_tax . "'," . $no_of_boxes . "," . $created_by . "," . $raw_material_id . ")", $result_type);
		//pr($product_grn_detail);exit;
		// echo $this->db->last_query();exit;
		return $rm_grn_detail;
	}
	function list_rm_grn($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$rm_grn_id = $input['rm_grn_id'] ?? 0;
		$rm_grn_detail_id = $input['rm_grn_detail_id'] ?? 0;
		$rm_grn_no = $input['rm_grn_no'] ?? 'NULL';
		$rm_name = $input['rm_name'] ?? 'NULL';
		$is_rm_quality_checked = $input['is_rm_quality_checked'] ?? 0;
		$grn_date = $input['grn_date'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';

		$product_grn_list = $this->Common_model->callSP("GET_RM_GRN_LIST(" . $store_id . "," . $rm_grn_id . "," . $rm_grn_detail_id . "," . $rm_grn_no . "," . $rm_name . "," . $grn_date . ",'" . $is_rm_quality_checked . "')", $result_type);
		// echo $this->db->last_query();exit;
		return $product_grn_list;
	}
	function add_rm_quality_check($input)
	{
		// pr($input);exit;
		$rm_grn_detail_id = $input['rm_grn_detail_id'] ?? 0;
		$rm_quality_checked_boxes = $input['rm_quality_checked_boxes'] ?? 0;
		$rm_quality_checked_item = $input['rm_quality_checked_item'] ?? 0;
		$purchase_price_per_item = $input['purchase_price_per_item'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';

		$rm_grn_detail = $this->Common_model->callSP("SAVE_RM_QUALITY_CHECK(" . $rm_quality_checked_boxes . "," . $rm_quality_checked_item . "," . $purchase_price_per_item . "," . $rm_grn_detail_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $rm_grn_detail;
	}
	function create_put_away($input)
	{

		$store_id = $input['store_id'] ?? 0;
		$location_no = $input['location_no'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$created_by = $input['created_by'] ?? 0;
		$box_detail = $this->Common_model->callSP("SAVE_RM_BOX_LOCATION(" . $store_id . "," . $location_no . "," . $box_no . "," . $created_by . ")", $result_type);
		return $box_detail;
	}
	function add_box_detail($input)
	{
		// pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$rm_grn_id = $input['rm_grn_id'] ?? NULL;
		$rm_grn_detail_id = $input['rm_grn_detail_id'] ?? 0;
		$rm_id = $input['rm_id'] ?? 0;
		$box_no = $input['box_no'] ?? NULL;
		$no_of_items = $input['no_of_items'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';

		$rm_grn_detail = $this->Common_model->callSP("SAVE_RM_BOX_DETAIL(" . $store_id . "," . $rm_id . "," . $rm_grn_id . "," . $rm_grn_detail_id . ",'" . $box_no . "'," . $no_of_items . "," . $created_by . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $rm_grn_detail;
	}
	function list_box_location($input)
	{
		$box_location_id = $input['box_location_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$location_no = $input['location_no'] ?? 0;
		$box_no = $input['box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_RM_BOX_LOCATION(" . $box_location_id . "," . $store_id . "," . $box_no . "," . $location_no . ")", $result_type);
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

	function add_fg($input)
	{
		$fg_code = $input['fg_code'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$sales_qty = $input['sales_qty'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$description = $input['description'] ?? NULL;
		$created_by = $input['created_by'] ?? 0;
		$is_deleted = $input['is_deleted'] ?? 0;
		$raw_material_id = $this->Common_model->callSP("SAVE_FG(" . $fg_id . ",'" . $fg_code . "'," . $sales_qty . ",'" . $description . "'," . $created_by . ",'" . $is_deleted . "'," . $store_id . ")", "row");
		// echo $this->db->last_query();exit;
		return $raw_material_id;
	}
	function fg_list($input)
	{
		// pr($input);exit;
		$is_bom = $input['is_bom'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$fg_description = $input['description'] ?? NULL;
		$product_list = $this->Common_model->callSP("GET_FG_LIST(" . $fg_id . ",'" . $fg_description . "'," . $store_id . ")", "");
		// echo $this->db->last_query();exit;
		return $product_list;
	}
	function fg_list_dropdown($input)
	{
		// pr($input);exit;
		$is_bom = $input['is_bom'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$fg_description = $input['description'] ?? NULL;
		$product_list = $this->Common_model->callSP("GET_FG_LIST_DROPDOWN(" . $fg_id . ",'" . $fg_description . "'," . $store_id . ",'" . $is_bom . "')", "");
		// echo $this->db->last_query();exit;
		return $product_list;
	}
	function add_bom($input)
	{
		//pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$bom_id = $input['bom_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$item_id = $input['item_id'] ?? 0;
		$grn_type = $input['grn_type'] ?? 0;
		$sfg_qty = $input['sfg_qty'] ?? 0;
		$is_deleted = $input['is_deleted'] ?? 0;
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? 'row';
		$bom_detail = $this->Common_model->callSP("SAVE_BOM(" . $store_id . ",'" . $grn_type . "'," . $bom_id . "," . $item_id . "," . $sfg_qty . "," . $created_by . ",'".$is_deleted."')", $result_type);
		//pr($product_grn_detail);exit;
		// echo $this->db->last_query();exit;
		return $bom_detail;
	}
	function bom_list($input)
	{
		// pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$product_list = $this->Common_model->callSP("GET_BOM_LIST(" . $store_id . ",".$fg_id.")", "");
		// echo $this->db->last_query();exit;
		return $product_list;
	}
	function bom_detail($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$product_list = $this->Common_model->callSP("GET_BOM_DETAIL(" . $store_id . ",".$fg_id.")", "");
		return $product_list;
	}
	function list_units_of_measure($input){
		$unit_id = $input['unit_id'] ?? 0;
		$unit = $input['unit'] ?? 'NULL';
		$unit_list = $this->Common_model->callSP("GET_UNIT_OF_MEASURE_LIST(".$unit_id.",".$unit.")","");
		return $unit_list;
	}
	function add_product_alias($input){
		// pr($input);exit;
		$product_alias_id = $input['product_alias_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$product_id = $input['product_id'] ?? 0;
		$product_name = $input['product_name'] ?? 'NULL';
		$created_by = $input['created_by'] ?? 0;		
		$created_date = date('Y-m-d H:i:s');
		$supplier_id = $input['supplier_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$fg_prod_name = $input['fg_prod_name'] ?? 'NULL';
		$is_deleted = $input['is_deleted'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$product_alias_list = $this->Common_model->callSP("SAVE_PRODUCT_ALIAS(".$product_alias_id.",".$store_id.",".$product_id.",'".$product_name."','".$is_deleted."',".$created_by.",".$fg_id.",'".$fg_prod_name."',".$supplier_id.")",$result_type);
		return $product_alias_list;
	}
	function product_alias_list($input){
		$product_alias_id = $input['product_alias_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$product_id = $input['product_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$product_alias_list = $this->Common_model->callSP("GET_PRODUCT_ALIAS_LIST(".$product_alias_id.",".$store_id.",".$product_id.",".$fg_id.")",$result_type);
		return $product_alias_list;
	}
	function delete_bom($input){
		$fg_id = $input['fg_id'] ?? NULL;
		$this->Common_model->deleteData('tbl_bom',array('fg_id'=>$fg_id));
	}
}
