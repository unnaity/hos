<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
		
		$this->user_detail = $this->session->userdata('user_detail');
		$this->store_id = $this->session->userdata('store_id'); 
		//  pr($this->session->userdata('store_id'));
		$this->load->model('Stocks_model', 'Stocks');
    }
	public function index()
	{	
		$this->data['page_name'] = "dashboard";
		$this->data['page_title'] = "dashboard";
		$this->data['total_stock_data'] = $this->total_stock_data();
		$this->sales_count = $this->db->query("SELECT COUNT(sales_order_no) as count FROM tbl_sales_order WHERE store_id = ?", array($this->store_id))->row()->count;
		$this->dispatch_count = $this->db->query("SELECT COUNT(pick_list_id) as count FROM tbl_pick_list ")->row()->count;
		$this->purchase_count = $this->db->query("SELECT COUNT(purchase_order_no) as count From tbl_purchase_order WHERE store_id = ?", array($this->store_id))->row()->count; 

		$this->load->view('Backend/Dashboard/index', $this->data);
	}	
	public function total_stock_data()
	{
		// $total_stock = $this->Stocks->total_stock_value(array('store_id' => $this->store_id));
	    // // print_r ($total_stock);
		// $total_value = 0;
		// foreach ($total_stock as $ts) {
		// 	$total_value = $total_value + $ts->total_value;			
		// }
		// $total_value_per = [];

		// $stockDataObj[] = "['Category', 'Total Value']";
		// $this->category_name = '[';
		// foreach($total_stock as $obj){			
		// 	$total_value_per[] = round((($obj->total_value * 100)/$total_value),2);
		// 	$this->category_name .= '"'.$obj->name.'",';
		// }
		// $this->category_name .= ']';
		// $this->category_name = strtoupper($this->category_name);
		// $this->value_percentage = '[' . implode(', ', $total_value_per) . ']';

		
	}	
}
