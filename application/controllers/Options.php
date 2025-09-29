<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');

        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
        $this->load->model('Employee_model','Employee');
        
    }
    
	public function index($method)
	{	        
        $this->data = [];
        switch ($method) {
            case 'category-option':
                $this->category_option();
                break;
            case 'customer-option':
                $this->customer_option();
                break;
            case 'supplier-option':
                $this->supplier_option();
                break; 
            case 'branch-option':
                $this->branch_option();
                break;
            case 'size-option':
                $this->size_option();
                break;
            case 'model-option':
                $this->model_option();
                break;
            case 'quality-option':
                $this->quality_option();
                break;    
            case 'cateory-wise-product-list':
                $this->cateory_wise_product_list();
                break;
            case 'product-alias-list':
                $this->product_alias_list();
                break;
            case 'department-wise-employee-list':
                $this->department_wise_employee_option();
                break;
            case 'department-option':
                $this->department_option();
                break;    
            case 'occupied-location-option':
                $this->occupied_location_option();
                break;  
            default:
                break;
        };
        
	}

    public function supplier_option()
	{
        $supplier_option = $this->Options->supplier_option([]);
        echo $supplier_option;
	}

    public function branch_option()
	{        
        $branch_option = $this->Options->branch_option([]);
        echo $branch_option;
	}

    function cateory_wise_product_list() {
        $input['category_id'] = $this->input->post('category_id');
        echo $product_list_option = $this->Options->cateory_wise_product_list($input);
    }

    function category_option() {
        echo $category_option = $this->Options->category_option();
    }

    function customer_option() {        
        echo $customer_option = $this->Options->customer_option();
    }

    function uom_option() {        
        echo $uom_option = $this->Options->uom_option();
    }

    function size_option() {        
        echo $size_option = $this->Options->size_option();
    }

    function model_option() {        
        echo $model_option = $this->Options->model_option();
    }

    function quality_option() {        
        echo $quality_option = $this->Options->quality_option();
    }
    function product_alias_list(){
        $product_id = $this->input->post('product_id');
        echo $product_alias_option = $this->Options->product_alias_list(array('store_id'=>$this->store_id,'product_id'=>$product_id));
    }

    function department_wise_employee_option() {
        $input = array('department_id' => $this->input->post('department_id'),
                       'store_id' => $this->store_id
                      );

        echo $employee_list_option = $this->Options->employee_option($input);
    }
    function occupied_location_option() {        
        echo $occupied_location_option = $this->Options->occupied_location_option();
    }
    
}