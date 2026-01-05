<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrap_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
	
    // function scrap_list($input){
    //     return $pick_list_box = $this->Common_model->getAll('tbl_scrap_list',$input);
    // }
    function delete_scrap_list($input){
    return $pick_list_box = $this->Common_model->deleteData('tbl_scrap_list',$input);   
    }
    function add_scrap_list_item($input){
		$scrap_id = $input['scrap_id'] ?? 0;
		$scrap_box_no = $input['scrap_box_no'] ?? 0;
		$scrap_qty = $input['scrap_qty'] ?? 0;
		$remaining_item = $input['remaining_item'] ?? 0;
        $scrap_remaining_item = $input['scrap_remaining_item'] ?? 0;
        $remark = $input['remark'] ?? '';
		$created_by = $input['created_by'] ?? 0;
		$is_deleted = $input['is_deleted'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$add_scrap_list_item = $this->Common_model->callSP("SAVE_SCRAP(".$scrap_id.",".$scrap_box_no.",".$scrap_qty.",".$remaining_item.",'".$remark."',".$scrap_remaining_item.",'".$is_deleted."',".$created_by.")",$result_type);
        // echo $this->db->last_query();exit;
        return $add_scrap_list_item;

	}
    function scrap_list($input){
		$scrap_id = $input['scrap_id'] ?? 0;
		$scrap_qty = $input['scrap_qty'] ?? 0;
        $scrap_box_no = $input['scrap_box_no'] ?? 0;
		$remaining_item = $input['remaining_item'] ?? 0;
        $is_deleted = $input['is_deleted'] ?? 0;
		$result_type = $input['result_type'] ?? '';
		$scrap_list= $this->Common_model->callSP("GET_SCRAP_LIST(".$scrap_id.",".$scrap_box_no.",".$scrap_qty.",".$remaining_item.",'".$is_deleted."')",$result_type);
        // echo $this->db->last_query();
        return $scrap_list;
    }

    
}