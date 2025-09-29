<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Pick_list_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function sales_order_product_location($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$sales_order_id = $input['sales_order_id'] ?? 0;
		$product_id = $input['product_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$sales_order_product_location_list = $this->Common_model->callSP("GET_SO_PRODUCT_LOCATION_LIST(" . $store_id . "," . $sales_order_id . "," . $product_id . ")", $result_type);
		// echo $this->db->last_query();
		return $sales_order_product_location_list;
	}
	function list_box($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$box_id = $input['box_id'] ?? 0;
		$gi_box_no = $input['gi_box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_RM_BOX_DETAIL(" . $box_id . "," . $gi_box_no . "," . $store_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $box_detail;
	}

	function box_list($input)
	{
		$box_id = $input['box_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$pick_list_box_no = $input['pick_list_box_no'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$pick_list_box = $this->Common_model->callSP("GET_BOX_LIST(" . $box_id . "," . $pick_list_box_no . "," . $store_id . ")", $result_type);
		return $pick_list_box;
	}

	function add_pick_list_item($input)
	{
		return $pick_list_box = $this->Common_model->insertValue('tbl_pick_list', $input);
	}

	function update_remaining_item($input)
	{
		$remaining_item = $input['remaining_item'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$box_detail_id = $input['sfg_box_detail_id'] ?? 0;
		if ($box_detail_id > 0) {
			return $pick_list_box = $this->Common_model->updateValue(array('remaining_item' => $remaining_item), 'tbl_sfg_box_detail', array('sfg_box_detail_id' => $box_detail_id));
		}
	}

	function get_pick_list_item($input)
	{
		$box_detail_id = $input['box_detail_id'] ?? 0;
		$sales_order_id = $input['sales_order_id'] ?? 0;
		return $get_pick_list_item = $this->Common_model->getAll('tbl_pick_list', $input);
	}

	function dispatch_list($input)
	{
		$pick_list_id = $input['pick_list_id'] ?? 0;
		$store_id = $input['store_id'] ?? 0;
		$customer_id = $input['customer_id'] ?? 0;
		$sales_order_id = $input['sales_order_id'] ?? 0;
		$from_date = $input['from_date'] ?? 'NULL';
		$to_date = $input['to_date'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';
		return $dispatch_list = $this->Common_model->callSP("GET_PICK_LIST(" . $pick_list_id . "," . $store_id . "," . $customer_id . "," . $sales_order_id . "," . $from_date . "," . $to_date . ")", $result_type);
	}

	function save_general_issues($input)
	{
		// pr($input);exit;
		$store_id = $input['store_id'] ?? 0;
		$general_issues_no = $input['general_issues_no'] ?? 0;
		$purchase_order_id = $input['purchase_order_id'] ?? 0;
		$department_id = $input['department_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$fg_quantity = $input['fg_quantity'] ?? 0;
		$rm_count_id = $input['no_of_rm'] ?? 0;
		$employee_id = $input['employee_id'] ?? 0;
		$is_deleted = $input['is_deleted'] ?? '0';
		$created_by = $input['created_by'] ?? 0;
		$result_type = $input['result_type'] ?? '';

		$gi_detail = $this->Common_model->callSP("SAVE_GENERAL_ISSUES(" . $store_id . "," . $purchase_order_id . ",'" . $general_issues_no . "'," . $department_id . "," . $employee_id . ",'" . $is_deleted . "'," . $created_by . "," . $fg_id . "," . $fg_quantity . "," . $rm_count_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $gi_detail;
	}

	function general_issues_list($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$general_issues_id = $input['general_issues_id'] ?? 0;
		$general_issues_no = $input['general_issues_no'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';
		return $general_issues_list = $this->Common_model->callSP("GET_GENERAL_ISSUES_LIST(" . $store_id . "," . $general_issues_id . ")", $result_type);
	}

	function po_product_location($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$po_id = $input['po_id'] ?? 0;
		$product_id = $input['product_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$po_product_location_list = $this->Common_model->callSP("GET_PO_PRODUCT_LOCATION_LIST(" . $store_id . "," . $po_id . "," . $product_id . ")", $result_type);
		return $po_product_location_list;
	}

	function update_no_of_stickers($input)
	{
		return $updateVal =  $this->Common_model->updateValue(array('no_of_stickers' => $input['no_of_stickers']), 'tbl_pick_list', array('pick_list_id' => $input['pick_list_id']));
	}
	function get_fg_list($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$po_product_location_list = $this->Common_model->callSP("GET_FG_BOM_LIST(" . $fg_id . "," . $store_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $po_product_location_list;
	}
	function get_item_barcode($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$gi_box_no = $input['gi_box_no'] ?? 0;

		$result_type = $input['result_type'] ?? '';
		$box_detail = $this->Common_model->callSP("GET_ITEM_BARCODE(" . $gi_box_no . "," . $store_id . ")", $result_type);
		return $box_detail;
	}
	function get_count_rm($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$fg_id = $input['fg_id'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$po_product_location_list = $this->Common_model->callSP("GET_COUNT_RM(" . $fg_id . "," . $store_id . ")", $result_type);
		// echo $this->db->last_query();exit;
		return $po_product_location_list;
	}
	public function check_and_update_dispatch_status($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$general_issue_id = $input['general_issue_id'] ?? 0;

		// get no_of_rm
		$this->db->select('no_of_rm');
		$this->db->from('tbl_general_issues');
		$this->db->where('general_issues_id', $general_issue_id);
		$this->db->where('store_id', $store_id);
		$result = $this->db->get()->row();

		if (!$result) {
			return [
				'status' => false,
				'message' => 'General issue not found'
			];
		}

		$no_of_rm = (int)$result->no_of_rm;

		// get distinct item_ids from pick list
		$this->db->select('item_id');
		$this->db->from('tbl_pick_list');
		$this->db->where('sales_order_id', $general_issue_id);
		$this->db->where('store_id', $store_id);
		$this->db->group_by('item_id');
		$pick_items = $this->db->get()->result();

		$item_ids = array_column($pick_items, 'item_id');
		$unique_item_count = count($item_ids);

		// check mismatch
		if ($unique_item_count != $no_of_rm) {
			return [
				'status' => false,
				'message' => "Mismatch: Found $unique_item_count item(s), but no_of_rm = $no_of_rm"
			];
		}

		// check only latest row for each item_id
		$pending = 0;
		foreach ($item_ids as $item_id) {
			$this->db->select('required_qty');
			$this->db->from('tbl_pick_list');
			$this->db->where('sales_order_id', $general_issue_id);
			$this->db->where('store_id', $store_id);
			$this->db->where('item_id', $item_id);
			$this->db->order_by('pick_list_id', 'DESC'); // take last inserted row
			$this->db->limit(1);
			$row = $this->db->get()->row();

			if ($row && (int)$row->required_qty > 0) {
				$pending++;
			}
		}

		if ($pending > 0) {
			return [
				'status' => false,
				'message' => "$pending item(s) still have required_qty > 0 (in last inserted row)"
			];
		}

		// update dispatch status
		$this->db->where('general_issues_id', $general_issue_id);
		$this->db->where('store_id', $store_id);
		$this->db->update('tbl_general_issues', ['is_dispatch' => '1']);

		return [
			'status' => true,
			'message' => 'Dispatch status updated successfully'
		];
	}

	public function get_total_picked_qty($params)
	{
		$this->db->select_sum('no_of_items');
		$this->db->from('tbl_pick_list');
		$this->db->where('sales_order_id', $params['general_issue_id']);
		$this->db->where('item_id', $params['item_id']);
		$this->db->where('is_general_issue', '1');
		$this->db->where('store_id', $this->store_id);

		$query = $this->db->get();
		$result = $query->row();

		return $result->no_of_items ?? 0;
	}
	function get_fg_details_by_issue($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$general_issues_id = $input['general_issues_id'] ?? 0;
		$general_issues_no = $input['general_issues_no'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';
		return $general_issues_list = $this->Common_model->callSP("GET_GENERAL_ISSUES_DETAIL_LIST(" . $general_issues_id . "," . $store_id . ")", $result_type);
	}
	function get_scan_detail($input)
	{
		$store_id = $input['store_id'] ?? 0;
		$general_issues_id = $input['general_issues_id'] ?? 0;
		$general_issues_no = $input['general_issues_no'] ?? 'NULL';
		$result_type = $input['result_type'] ?? '';
		return $general_issues_list = $this->Common_model->callSP("GET_SCAN_DETAIL(" . $general_issues_id . "," . $store_id . ")", $result_type);
	}
}
