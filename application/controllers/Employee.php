<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');

        $this->load->model('Settings_model','Settings');
        $this->load->model('Employee_model','Employee');
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
    }
    
	public function index($method)
	{	
		$this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->data = [];
        $this->user_id = $this->user_detail->user_id;

        switch ($method) {
            case 'employee-list':
                $this->employee_list();
                break;
            case 'add-employee':
                $this->add_employee();
                break;
            default:
                //--- 
                break;
        };        
	}

    public function employee_list()
    {           
        $this->employee_list = $this->Employee->list_employee(array('store_id'=> $this->store_id, 'result_type'=>"result"));
        $this->page_name = "employee-list";
        $this->page_title = "employee";
        $this->load->view('Backend/Employee/index');
    }

    public function add_employee()
    {	
        $input['employee_id'] = 0;
        if($this->input->post()){
            $this->form_validation->set_rules('employee_name', 'Employee name', 'trim|required');
            $this->form_validation->set_rules('employee_dob', 'Employee dob', 'trim');
            $this->form_validation->set_rules('employee_gender', 'Employe gender', 'trim');
            
            if ($this->form_validation->run()){

                $input['employee_name'] = trim($this->input->post('employee_name'));
                $input['employee_dob'] = trim($this->input->post('employee_dob'));
                $input['employee_gender'] = trim($this->input->post('employee_gender'));
                $input['employee_address'] = trim($this->input->post('employee_address'));
                $input['mobile_no'] = trim($this->input->post('mobile_no'));
                $input['email'] = trim($this->input->post('email'));
                $input['employee_designation'] = trim($this->input->post('employee_designation'));
                $input['department_id'] = trim($this->input->post('department_id'));
                $input['store_id'] = $this->store_id;
                $input['created_by'] = $this->user_id;
                
                $employee_detail = $this->Employee->add_employee($input);
                if($employee_detail->action == 1){
                    $this->session->set_flashdata('success_message', $employee_detail->message);
                }else if($employee_detail->action == 0){
                    $this->session->set_flashdata('error_message', $employee_detail->message);
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
                redirect(BASE_URL . 'employee-list', 'refresh');
            }
        }
        $input['result_type'] = "result";
        $data['department_list'] = $this->Settings->list_department($input);        
        $this->page_title = "employee";
        $this->load->view('Backend/Employee/index',$data);
    }
}