<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
        
        $this->load->model('Purchase_order_model','Purchase_order');        
    }
    
	public function index($method)
	{	 
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
        $this->result_type = 'row';
        $this->user_id = $this->user_detail->user_id;
        $this->client_id = $this->user_detail->client_id;
        $this->data = [];       
        $this->purchase_order_id = $this->uri->segment(2);

        switch ($method) {
            case 'purchase-order-list':
                $this->purchase_order_list();
                break;
            case 'create-purchase-order':
                $this->create_purchase_order();
                break;
            case 'purchase-order':
                $this->purchase_order_detail($this->purchase_order_id);
                break;    
            default:                
                break;
        };      
        $this->load->view('Backend/'.$this->class_name.'/index');  
	}

    function create_purchase_order(){
        $cond = array('store_id' => $this->store_id);
        if(CURRENT_MONTH < 4){
            $this->purchase_order_no = (date('y')-1).'-'.date('y').'/'.str_pad(get_last_id('purchase_order_id','tbl_purchase_order',$cond),4,"0",STR_PAD_LEFT);
        }else{
            $this->purchase_order_no = (date('y')).'-'.(date('y')+1).'/'.str_pad(get_last_id('purchase_order_id','tbl_purchase_order',$cond),4,"0",STR_PAD_LEFT);
        }
        if($this->input->post()){            
            $this->form_validation->set_rules('customer_id', 'Customer id', 'trim|required');
            $this->form_validation->set_rules('purchase_order_id', 'Customer id', 'trim');            
            $this->form_validation->set_rules('product_id_array[]', 'Product id', 'trim|required');
            $this->form_validation->set_rules('category_id_array[]', 'Category', 'trim|required');
            $this->form_validation->set_rules('quantity_array[]', 'Quantity', 'trim|required');            
            
            if ($this->form_validation->run()){               
                
                $purchase_order_id = $this->input->post('purchase_order_id');
                $po_date = $this->input->post('po_date');
                $customer_id = $this->input->post('customer_id');
                $order_date = $this->input->post('order_date');
                $delivery_date = $this->input->post('delivery_date');
                $category_id_array = $this->input->post('category_id_array');
                $product_id_array = $this->input->post('product_id_array');
                $product_alias = $this->input->post('product_alias_name_array');                
                $quantity_array = $this->input->post('quantity_array');
                $price_array = $this->input->post('price_array');
                //$unit_id_array = $this->input->post('unit_id_array');
                $additional_info = $this->input->post('additional_info');
                
                //pr($this->input->post());exit;
                $purchase_order = $this->Purchase_order->add_purchase_order(array('store_id' => $this->store_id,
                                                                         'purchase_order_no' => $this->purchase_order_no,
                                                                         'purchase_order_id' => $purchase_order_id,
                                                                         'po_date' => $po_date,
                                                                         'customer_id' => $customer_id,
                                                                         'order_date' => $order_date,
                                                                         'delivery_date' => $delivery_date,
                                                                         'created_by' => $this->user_id,
                                                                         'additional_info' => $additional_info,
                                                                         'result_type' => $this->result_type
                                                                        )
                                                                    );
                if($purchase_order->action == 1){
                    $purchase_order_id = $purchase_order->purchase_order_id;

                    for ($i=0; $i < count($product_id_array); $i++) { 
                        $purchase_order_detail = $this->Purchase_order->add_purchase_order_detail(array('purchase_order_id' => $purchase_order_id,
                                                                                               'category_id' => $category_id_array[$i],
                                                                                               'product_id' => $product_id_array[$i],
                                                                                               'product_alias' => $product_alias[$i],
                                                                                               'quantity' => $quantity_array[$i],
                                                                                               'price' => $price_array[$i],
                                                                                               'created_by' => $this->user_id,
                                                                                               'result_type' => $this->result_type
                                                                                               )
                                                                                        );
                    }
                    $this->session->set_flashdata('success_message', $purchase_order->message);                    
                }else if($purchase_order->action == 0){
                    $this->session->set_flashdata('error_message', $purchase_order->message);                   
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }               
            }
            redirect(BASE_URL . $this->page_name, 'refresh');
        }
       
        $this->category_option = $this->Options->category_option();
        $this->customer_option = $this->Options->customer_option();
        $this->uom_option = $this->Options->uom_option(); 		
    }

    public function purchase_order_list()
	{           
        $this->purchase_order_list = $this->Purchase_order->list_purchase_order(array('store_id' => $this->store_id));	
        $this->customer_option = $this->Options->customer_option();
	}

    public function purchase_order_detail($purchase_order_id)
	{   
        $this->client_detail = get_client_list($this->client_id);
        $this->purchase_order = $this->Purchase_order->list_purchase_order(array('store_id' => $this->store_id,'purchase_order_id' => $purchase_order_id,'result_type'=>$this->result_type));        
        $this->purchase_order_product = $this->Purchase_order->purchase_order_detail(array('store_id' => $this->store_id,'purchase_order_id' => $purchase_order_id));         
	}

}