<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
        
        $this->load->model('Stocks_model','Stocks');
    }
    
	public function index($method)
	{	 
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
        // pr($this->store_id);exit;
        $this->result_type = 'row';
        $this->user_id = $this->user_detail->user_id;
        $this->client_id = $this->user_detail->client_id;
        $this->data = [];       
        

        switch ($method) {
            case 'product-stock':
                $this->category_id = $this->uri->segment(2);
                $this->product_stock();
                break; 
            case 'grn-stock':
                $this->grn_stock();
                break;
            case 'total-stock-value':
                $this->total_stock_value();
                break;
            case 'put-away-pending':
                $this->put_away_pending();
                break;
            case 'product-stock-export':
                $this->category_id = $this->uri->segment(2);
                $this->product_stock_export();
                exit;
                break;
            case 'stock-audit':
                $this->stock_audit();
                break;
            case 'location-detail':
                $this->location_detail();
                exit;
                break;  
            case 'box-detail':
                $this->box_detail();
                exit;
                break;
            default:                
                break;
        };      
        $this->load->view('Backend/'.$this->class_name.'/index',$this->data);  
	}

   
    function product_stock($category_id=NULL){
        $this->data['product_stock'] = $this->Stocks->product_stock(array('store_id' => $this->store_id,'category_id'=>$this->category_id));  
        //pr($this->data['product_stock']);exit;      
    }

    function grn_stock(){
        $this->data['grn_stock'] = $this->Stocks->product_stock_at_grn(array('store_id' => $this->store_id));        
    }

    function total_stock_value(){
        $this->data['total_stock_value'] = $this->Stocks->total_stock_value(array('store_id' => $this->store_id));  
        // pr($this->data['total_stock_value']);exit;      
    }

    function put_away_pending(){
        $this->data['put_away_pending'] = $this->Stocks->put_away_pending(array('store_id' => $this->store_id)); 
        // pr($this->data['put_away_pending']);exit;       
    }

    function product_stock_export() {
        $i=0; $j=1;
        $product_stock = $this->Stocks->product_stock(array('store_id' => $this->store_id,'category_id'=>$this->category_id));
        foreach($product_stock as $obj){
            
            $company_name = $obj->company_name;
            $category_name = $obj->category_name;
            $product_name = $obj->product_name;
            $customer_name = strtoupper(str_replace(',',', ', $obj->customer_name));
            if($obj->last_sale_day > 0){ 
                $last_sale_day = $obj->last_sale_date_day; 
            }else{ 
                $last_sale_day = ''; 
            }
            $total_item = $obj->total_item ?? 0;
            $total_value = number_format(($obj->total_value ?? 0),2);
                                        
            $array = explode(',',$obj->location_name);
            $vals = array_flip(array_count_values($array));
            
            foreach($vals as $key => $v){
                $location = [];	
                $loc_text = '';
                //echo $v.' - <b>'.$key.' Box </b>, ';
                $location[] = $v.' - '.$key.' Box ';
                $loc_text =  implode(',',$location);
            }
            $product_stock_data[$i] = array($j,$company_name,$category_name,$product_name, $customer_name,$last_sale_day,$total_item,$total_value,$loc_text);
            $i++; $j++;
        }
        //pr($product_stock_data);exit;
        $ex_header = ['OEM', 'Category', 'Product Name', 'Customer Name', 'Last Sale', 'Total Item', 'Total Price', 'Location'];		
        excelExport($ex_header,$product_stock_data,$this->page_name); 
    }
    function stock_audit(){        
        $this->occupied_location_option = $this->Options->occupied_location_option(); 
        // echo $this->db->last_query();exit;
    }

    public function location_detail() {
        $location_no = $this->input->post('location_no'); 
        $location_detail = $this->Stocks->location_detail(array('store_id' => $this->store_id,'location_no' => $location_no));
        // pr($location_detail);exit;
        $td = '';
        if ($location_detail) {
            $i = 0;
            foreach ($location_detail as $obj) {
                $td .= '<tr><td>' . ++$i . '</td>'  
                     . '<td>' . strtoupper($obj->grn_type) . '</td>'
                     . '<td>' . $obj->item_name . '</td>'
                     . '<td>' . $obj->no_of_items . '</td>'
                     . '<td>' . $obj->remaining_item . '</td>'
                     . '<td>' . $obj->box_no . '</td></tr>';
            }
        } 
        echo json_encode(['status' => 1, 'data' => $td]);  
    }
    
    public function box_detail() {
        $box_no = $this->input->post('box_no');
        $location_no = $this->input->post('location_no');  
    
        $box_detail = $this->Stocks->location_detail(array('box_no' => $box_no,'store_id' => $this->store_id));
        
        $data = [];
        $box = '';
        $audit_date = date('Y-m-d');
    
        if (!empty($box_detail)) {
            if ($box_detail[0]->location_no == $location_no) {
                $i = 0;
                foreach ($box_detail as $obj) {
                    $box .= '<tr>
                                <td>' . ++$i . ' </td>
                                <td>' . $obj->box_no . ' </td>
                                <td>' . $obj->location_no . ' </td>
                                <td>' . $obj->location_name . ' </td>
                                <td>' . $audit_date . ' </td> 
                            </tr>';
                }
                $save_stock_audit = $this->Stocks->save_stock_audit(array('box_no' => $box_no,
                                                                          'location_no' =>$location_no,
                                                                          'store_id' =>$this->store_id));

                $data = array('message' => '', 'status' => 1, 'data' => $box);
            } else {
                $data = array('message' => 'The scanned box does not belong to this location.', 'status' => 0);
            }
        } else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        
        echo json_encode($data);
    }
}