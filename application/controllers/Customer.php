<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
        
        $this->load->model('Customer_model','Customer');
        $this->user_detail = $this->session->userdata('user_detail');
    }
    
	public function index($method)
	{	 
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->data = [];

        switch ($method) {
            case 'customer-list':
                $this->customer_list();
                break;
            case 'add-customer':
                $this->add_customer();
                break;
            default:
                //--- 
                break;
        };        
	}

    public function customer_list()
	{           
        $this->data['customer_list'] = $this->Customer->list_customer([]);        
		$this->load->view('Backend/'.$this->class_name.'/index',$this->data);
	}

    public function add_customer()
	{	        
        if($this->input->post()){
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'contact no.', 'trim|required|numeric|exact_length[10]');
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');
            if ($this->form_validation->run()){

                $input['customer_id'] = $this->input->post('customer_id'); 
                $input['first_name'] = $this->input->post('first_name');
                $input['last_name'] = $this->input->post('last_name');
                $input['company_name'] = $this->input->post('company_name');
                $input['display_name'] = $this->input->post('display_name');
                $input['email'] = $this->input->post('email');
                $input['mobile_no'] = $this->input->post('mobile_no');
                $input['pan_no'] = $this->input->post('pan_no');
                $input['gst_no'] = $this->input->post('gst_no');
                $input['shipping_add'] = $this->input->post('shipping_address');
                $input['billing_add'] = $this->input->post('billing_address');
                $input['additional_info'] = $this->input->post('additional_info');
                $input['created_by'] = 1;

                $customer_detail = $this->Customer->add_customer($input);

                if($customer_detail->action == 1){
                    $this->session->set_flashdata('success_message', $customer_detail->message);
                }else if($customer_detail->action == 0){
                    $this->session->set_flashdata('error_message', $customer_detail->message);
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
                redirect(BASE_URL.'customer-list');
            }
        }
        $this->load->view('Backend/'.$this->class_name.'/index',$this->data);
	}
}