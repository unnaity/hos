<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
	function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
        
        $this->load->model('Products_model','Products'); 
        $this->load->model('Settings_model','Settings');       
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');        
    }
    
	public function index($method)
	{	 
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->result_type = 'result';
        $this->row_type = 'row';
        $this->data = [];
        $this->user_id = $this->user_detail->user_id;
        $this->product_id = $this->uri->segment(2);
        
        switch ($method) {
            case 'product-list':
                $this->product_list();
                break;
            case 'add-product':
                $this->add_product($this->product_id);
                break;
            case 'product-edit':
                $this->edit_product($this->product_id);
                break;
            case 'product-delete':
                $this->delete_product($this->product_id);exit;
                exit;
                break;
            case 'create-product-grn':
                $this->create_product_grn();
                break; 
            case 'product-grn-list':
                $this->product_grn_list();
                break;            
            case 'product-quality-check':
                $this->product_quality_check();
                break;
            case 'quality-check-list':
                $this->quality_check_list();
                break;    
            case 'update-purchase-price':
                $this->update_purchase_price();exit;
                break;
            case 'add-variant':
                $this->add_variant();
                break;
            case 'variant-list':
                $this->variant_list();
                break; 
            case 'show-input-box':
                $this->show_input_box();
                break;
            case 'create-put-away':
                $this->create_put_away();
                break;
            case 'put-away-list':
                $this->put_away_list();
                break;
            case 'product-alias':
                $this->product_alias($this->product_id);
                break;  
            case 'delete-product-alias':
                $this->delete_product_alias($this->product_id);exit;
                break;
            case 'show-location-detail':
                $this->show_location_detail(); exit;
                break;
            case 'show-product-detail':
                $this->show_product_detail(); exit;            
            case 'product-grn-detail':
                $this->product_grn_detail(); exit;
                break;
            default:
                break;
        };
        $this->load->view('Backend/'.$this->class_name.'/index',$this->data);  
	}

    public function product_list()
	{           
        $this->product_list = $this->Products->list_product(array());        
	}

    public function add_product($category_id = NULL)
	{
        if($this->input->post()){
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            $this->form_validation->set_rules('oem_id', 'OEM', 'trim|required');
            $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');            
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');

            if ($this->form_validation->run()){                

                $category_id = $this->input->post('category_id');
                $oem_id = $this->input->post('oem_id');
                $model = $this->input->post('model_id');
                $quality = $this->input->post('quality_id');
                $size = $this->input->post('size_id');
                $product_sku = $this->input->post('product_sku');
                $hsn_code = $this->input->post('hsn_code');
                $article_code = $this->input->post('article_code');
                $unit_id = $this->input->post('unit_id');
                $product_name = $this->input->post('product_name');                
                $additional_info = $this->input->post('additional_info');

                $product_detail = $this->Products->add_product(array(
                                                                     'category_id' => $category_id,
                                                                     'oem_id' => $oem_id,
                                                                     'model' => $model,
                                                                     'quality' => $quality,
                                                                     'product_sku' => $product_sku,
                                                                     'size' => $size,
                                                                     'hsn_code' => $hsn_code,
                                                                     'article_code' => $article_code,
                                                                     'unit_id' => $unit_id,
                                                                     'product_name' => $product_name,
                                                                     'additional_info' => htmlentities($additional_info),
                                                                     'created_by' => $this->user_id
                                                                    )
                                                               );

                if($product_detail->action == 1){
                    $this->session->set_flashdata('success_message', $product_detail->message);
                    redirect(BASE_URL . 'product-list', 'refresh');
                }else if($product_detail->action == 0){
                    $this->session->set_flashdata('error_message', $product_detail->message);
                    redirect(BASE_URL . 'add-product', 'refresh');
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'add-product', 'refresh');
                }               
            }
        }
        $this->oem_option = $this->Options->supplier_option(array('is_oem'=>'1'));
        $this->category_option = $this->Options->category_option(array('selected_value'=>$category_id));
        $this->variant_list = $this->Products->list_variant(array('result_type'=>$this->result_type));
        $this->uom_option = $this->Options->uom_option(); 
        $this->size_option = $this->Options->size_option(array('store_id'=>$this->store_id,'category_id'=>$category_id));         

        $this->model_option = $this->Options->model_option(array('store_id'=>$this->store_id,'category_id'=>$category_id)); 
        $this->quality_option = $this->Options->quality_option(array('store_id'=>$this->store_id,'category_id'=>$category_id)); 
	}

    function edit_product($product_id){        
        if($this->input->post()){
            $this->form_validation->set_rules('product_id', 'Product id', 'trim|required');
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
            $this->form_validation->set_rules('oem_id', 'OEM', 'trim|required');
            $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');            
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');

            if ($this->form_validation->run()){                

                $product_id = $this->input->post('product_id');
                $category_id = $this->input->post('category_id');
                $oem_id = $this->input->post('oem_id');
                $model = $this->input->post('model_id');
                $quality = $this->input->post('quality_id');
                $size = $this->input->post('size_id');
                $product_sku = $this->input->post('product_sku');
                $hsn_code = $this->input->post('hsn_code');
                $article_code = $this->input->post('article_code');
                $unit_id = $this->input->post('unit_id');
                $product_name = $this->input->post('product_name');                
                $additional_info = $this->input->post('additional_info');

                $product_detail = $this->Products->add_product(array('product_id' => $product_id,
                                                                     'category_id' => $category_id,
                                                                     'oem_id' => $oem_id,
                                                                     'model' => $model,
                                                                     'quality' => $quality,
                                                                     'product_sku' => $product_sku,
                                                                     'size' => $size,
                                                                     'hsn_code' => $hsn_code,
                                                                     'article_code' => $article_code,
                                                                     'unit_id' => $unit_id,
                                                                     'product_name' => $product_name,
                                                                     'additional_info' => htmlentities($additional_info),
                                                                     'created_by' => $this->user_id
                                                                    )
                                                               );
                
                if($product_detail->action == 1){
                    $this->session->set_flashdata('success_message', $product_detail->message);
                    redirect(BASE_URL . 'product-edit/'.$this->product_id, 'refresh');
                }else if($product_detail->action == 0){
                    $this->session->set_flashdata('error_message', $product_detail->message);
                    redirect(BASE_URL . 'product-edit/'.$this->product_id, 'refresh');
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'product-edit/'.$this->product_id, 'refresh');
                }
            }
        }
        $this->product_list = $this->Products->list_product(array('product_id'=>$product_id,
                                                                  'result_type'=>$this->row_type
                                                                )
                                                            ); 
        
        $this->oem_option = $this->Options->supplier_option(array('is_oem'=>'1', 
                                                                  'selected_value'=>$this->product_list->oem_id
                                                                 ));
        $this->category_option = $this->Options->category_option(array('selected_value'=>$this->product_list->category_id));
        $this->variant_list = $this->Products->list_variant(array('result_type'=>$this->result_type));
        $this->uom_option = $this->Options->uom_option(array('selected_value'=>$this->product_list->unit_id));
        $this->size_option = $this->Options->size_option(array('store_id'=>$this->store_id,'selected_value'=>$this->product_list->size_id)); 
        $this->model_option = $this->Options->model_option(array('store_id'=>$this->store_id,'selected_value'=>$this->product_list->model_id)); 
        $this->quality_option = $this->Options->quality_option(array('store_id'=>$this->store_id,'selected_value'=>$this->product_list->quality_id)); 
    }

    function delete_product($product_id){
        $this->product_list = $this->Products->list_product(array('product_id'=>$product_id,
                                                                  'result_type'=>$this->row_type
                                                                )
                                                            );
        if(!empty($this->product_list)){
            $product_detail = $this->Products->add_product(array('product_id' => $product_id,
                                                                 'is_deleted' => '1'
                                                                )
                                                          );                
            if($product_detail->action == 1){                
                $this->session->set_flashdata('success_message', $product_detail->message);                
            }else if($product_detail->action == 0){
                $this->session->set_flashdata('error_message', $product_detail->message);
            }else{
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }         
        }else{
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }   
        redirect(BASE_URL . 'product-list', 'refresh');
    }

    function create_product_grn(){
        $cond = array('store_id' => $this->store_id);  
        
        if(CURRENT_MONTH < 4){
            $this->grn_no = 'PGN/'.(date('y')-1).'-'.date('y').'/'.str_pad(get_last_id('product_grn_id','tbl_product_grn',$cond),4,"0",STR_PAD_LEFT);
        }else{
            $this->grn_no = 'PGN/'.(date('y')).'-'.(date('y')+1).'/'.str_pad(get_last_id('product_grn_id','tbl_product_grn',$cond),4,"0",STR_PAD_LEFT);
        }
        if($this->input->post()){            
            $this->form_validation->set_rules('product_id[]', 'Product id', 'trim|required');
            $this->form_validation->set_rules('category_id[]', 'Category', 'trim|required');
            $this->form_validation->set_rules('grn_type_id', 'GRN type', 'trim|required');
            $this->form_validation->set_rules('no_of_items[]', 'No. of items', 'trim|required');
            $this->form_validation->set_rules('part_code', 'Part Code');
            $this->form_validation->set_rules('serial_no', 'Serial No.');
            $this->form_validation->set_rules('lot_no', 'Lot No.');
            $this->form_validation->set_rules('mfg_date[]', 'Mfg Date');
            
            if ($this->form_validation->run()){
                //pr($this->input->post()); exit;
                $product_id = $this->input->post('product_id');
                $category_id = $this->input->post('category_id');
                //$no_of_boxes = $this->input->post('no_of_boxes');
                $grn_type_id = $this->input->post('grn_type_id');
                $no_of_items = $this->input->post('no_of_items');
                $bill_no = $this->input->post('bill_no');
                $po_date = $this->input->post('po_date');
                $mfg_date = $this->input->post('mfg_date_array');
                $part_code = $this->input->post('part_code');
                $serial_no = $this->input->post('serial_no');
                $lot_no = $this->input->post('lot_no');
                $packing = $this->input->post('packing');
                $expiry_date = $this->input->post('expiry_date_array');
                $invoice_type = $this->input->post('invoice_type');
                $supplier_id = $this->input->post('supplier_id');
                $created_by = $this->user_id;
                $additional_info = $this->input->post('additional_info');

                if(!empty($po_date)){
                    $po_date = date('Y-m-d',strtotime($po_date));
                }else{
                    $po_date = date('Y-m-d');
                }

                $product_grn = $this->Products->add_product_grn(array('store_id' => $this->store_id,
                                                                       'grn_type_id' => $grn_type_id,
                                                                      'product_grn_no' => $this->grn_no,
                                                                      'additional_info' => $additional_info,
                                                                      'created_by' => $created_by
                                                                    )
                                                                );
                if($product_grn->action == 1){
                    $product_grn_id = $product_grn->product_grn_id;

                    for ($i=0; $i < count($product_id); $i++) { 
                        $p_id = $product_id[$i];
                        
                        $product_grn_detail_id = $this->Products->add_product_grn_detail(array('product_grn_id' => $product_grn_id,
                                                                                     'category_id' => $category_id[$i],
                                                                                     'product_id' => $p_id,
                                                                                     'mgf_date' => $mfg_date[$i],
                                                                                     'expiry_date' => $expiry_date[$i],
                                                                                     'packing' => $packing,
                                                                                     'no_of_boxes' => 0,
                                                                                     'part_code' => $part_code,
                                                                                     'serial_no' => $serial_no,
                                                                                     'lot_no' => $lot_no,
                                                                                     'no_of_items' => $no_of_items[$i],
                                                                                     'invoice_type' => $invoice_type,
                                                                                     'bill_no' => $bill_no,
                                                                                     'po_date'  => $po_date,
                                                                                     'supplier_id' => $supplier_id,
                                                                                     'created_by' => $created_by
                                                                                    )
                                                                              );
                        //  echo $this->db->last_query();exit;
                        //$box = $this->input->post('box_no_'.$p_id);

                        /*for($j=0;$j<count($box);$j++){
                            $box_array = '';
                            $box_array = array('store_id' => $this->store_id,
                                               'product_grn_id' => $product_grn_id,
                                               'product_grn_detail_id' => $product_grn_detail_id->product_grn_detail_id,
                                               'box_no' => 'Box no '.$p_id,
                                               'no_of_items' => $box[$j],
                                               'product_id' => $p_id,
                                               'created_by' =>$created_by
                                              );
                            $box_detail_id = $this->Products->add_box_detail($box_array);
                        }*/

                    }
                    $this->session->set_flashdata('success_message', $product_grn->message);
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }else if($product_grn->action == 0){
                    $this->session->set_flashdata('error_message', $product_grn->message);
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }               
            }
        }
        $this->data['grn_type'] = $this->Common_model->getAll('tbl_grn_type',array('status' => '1'));
        $this->supplier_option = $this->Options->supplier_option(array('is_oem'=>'0'));
        $this->category_option = $this->Options->category_option();
    }

    function product_quality_check(){
        
        if($this->input->post()){            
            $this->form_validation->set_rules('product_grn_id', 'GRN No.', 'trim|required');
            //$this->form_validation->set_rules('product_grn_detail_id[]', 'product_grn_detail_id', 'trim|required');
            $this->form_validation->set_rules('quality_checked_item[]', 'Quality checked item', 'trim|required');
            $this->form_validation->set_rules('purchase_price_per_item[]', 'Purchase price per item', 'trim|required');
            $this->form_validation->set_rules('no_of_boxes[]', 'No. of boxes', 'trim|required');
            
            if ($this->form_validation->run()){
                $product_grn_id = $this->input->post('product_grn_id');
                $no_of_boxes = $this->input->post('no_of_boxes');
                $quality_checked_item = $this->input->post('quality_checked_item');
                $purchase_price_per_item = $this->input->post('purchase_price_per_item');
                
                $created_by = $this->user_id;

                if(!empty($po_date)){
                    $po_date = date('Y-m-d',strtotime($po_date));
                }else{
                    $po_date = date('Y-m-d');
                }

                $product_grn = $this->Products->add_product_grn(array('store_id' => $this->store_id,
                                                                      'product_grn_id' => $product_grn_id,
                                                                      'is_quality_checked' => '1',
                                                                      'created_by' => $created_by
                                                                    )
                                                                );
                
                if($product_grn->action == 1){
                    $product_grn_list = $this->Products->list_product_grn(array('store_id' => $this->store_id,
                                                                    'product_grn_id'=>$product_grn_id,
                                                                    'is_quality_checked' => '1',
                                                                    )
                                                             );
                    
                    $i=0;
                    foreach($product_grn_list as $obj){
                        $product_grn_detail_id = $obj->product_grn_detail_id;
                        $box_item = '';
                        $box_item = $this->input->post('box_item_'.$product_grn_detail_id);
                        $product_id = $obj->product_id;
                        $q_checked = $this->Products->add_product_quality_check(array('product_grn_detail_id' => $product_grn_detail_id,
                                                                                     'no_of_boxes' => $no_of_boxes[$i],
                                                                                     'quality_checked_item' => $quality_checked_item[$i],
                                                                                     'purchase_price_per_item' => $purchase_price_per_item[$i],
                                                                                     'created_by' => $created_by
                                                                                    )
                                                                                );
                        if(!empty($box_item)){
                            for($j=0;$j<count($box_item);$j++){
                                $box_array = '';
                                $box_array = array('store_id' => $this->store_id,
                                                    'product_grn_id' => $product_grn_id,
                                                    'product_grn_detail_id' => $product_grn_detail_id,
                                                    'box_no' => 'Box no '.$product_id,
                                                    'no_of_items' => $box_item[$j],
                                                    'product_id' => $product_id,
                                                    'created_by' =>$created_by
                                                    );
                                
                                $box_detail_id = $this->Products->add_box_detail($box_array);
                            }
                        }
                        $i++;
                    }
                    $this->session->set_flashdata('success_message', $q_checked->message);
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }else if($product_grn->action == 0){
                    $this->session->set_flashdata('error_message', $q_checked->message);
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }               
            }
        }
        $this->data['grn_list'] = $this->Products->get_grn_list(array('store_id' => $this->store_id));
    }

    public function quality_check_list()
	{           
        $this->data['quality_check_list'] = $this->Products->list_product_grn(array('store_id' => $this->store_id,'is_quality_checked' => '1'));
	}

    public function update_purchase_price(){
        $product_grn_detail_id = $this->input->post('product_grn_detail_id');
        $purchase_price_per_item = $this->input->post('purchase_price_per_item');

        $this->product_grn_list = $this->Products->update_purchase_price(array('store_id' => $this->store_id,
                                                                               'product_grn_detail_id' => $product_grn_detail_id,
                                                                               'purchase_price_per_item' => $purchase_price_per_item,
                                                                               'result_type' => $this->result_type
                                                                               )
                                                                            );
        $this->data['product_grn_list'] = $this->product_grn_list;
    }

    public function product_grn_list()
	{           
        $this->product_grn_list = $this->Products->list_product_grn(array('store_id' => $this->store_id));
        $this->data['product_grn_list'] = $this->product_grn_list;
	}
    
    public function product_grn_detail()
	{   
        $product_grn_id = $this->input->post('product_grn_id');
        $data['product_grn_detail'] = $this->Products->list_product_grn(array('store_id' => $this->store_id,
                                                                    'product_grn_id'=>$product_grn_id
                                                                    )
                                                             );
        echo $this->load->view('Backend/Products/product-grn-detail',$data,TRUE);
	}

    public function variant_list()
	{
        $variant_id = $this->add_variant();

        if($variant_id){            
            redirect(BASE_URL . 'variant-list', 'refresh');
        }

        $this->page_title = 'Variant';        
        $this->variant_list = $this->Products->list_variant(array('result_type'=>$this->result_type));
	}

    function add_variant(){
        if($this->input->post()){
            $this->form_validation->set_rules('variant_title', 'variant title', 'trim|required');
            $this->form_validation->set_rules('variant_option', 'variant option', 'trim|required');
            if ($this->form_validation->run()){

                $input['variant_title'] = trim($this->input->post('variant_title'));
                $input['variant_slug'] = create_unique_slug($input['variant_title'], 'tbl_variant');
                $input['variant_option'] = trim($this->input->post('variant_option'));
                $input['created_by'] = $this->user_id;
                $input['row_type'] = $this->row_type;

                $variant_id = $this->Products->add_variant($input);

                if($variant_id->action == 1){
                    $this->session->set_flashdata('success_message', $variant_id->message);
                }else if($variant_id->action == 0){
                    $this->session->set_flashdata('error_message', $variant_id->message);
                }else{
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
                return $variant_id;
            }
        }        
    }

    function show_input_box(){
        $this->no_of_boxes = $this->input->post('no_of_boxes');
        $this->grn_detail_id = $this->input->post('grn_detail_id');
    }

    function show_location_detail(){
        $location_detail = [];
        $location_no = $this->input->post('location_no');
        $location_detail = $this->Settings->list_location(array('location_no'=>$location_no,'store_id'=>$this->store_id,'result_type'=>$this->row_type));          
        if($location_detail){
            echo json_encode($location_detail);
        }
    }

    function show_product_detail(){
        $box_detail = [];
        $box_no = $this->input->post('box_no');

        $box_location_detail = $this->product_list = $this->Products->list_box_location(array('box_no'=>$box_no,'store_id'=>$this->store_id,'result_type'=>$this->row_type));
        
        $box_detail = $this->product_list = $this->Products->list_box(array('box_no'=>$box_no,'store_id'=>$this->store_id,'result_type'=>$this->row_type));
        if(isset($box_location_detail->box_no) && $box_location_detail->box_no > 0){
            echo $box_detail_html = '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">Put away process already completed for this box!</span>';
        }else{
            if($box_detail){
                echo $box_detail_html = '<div class="table-responsive">
                    <table class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
                        <tr>
                            <td>Product name</td>
                            <td>'.$box_detail->product_name.'</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>'.$box_detail->category_name.'</td>
                        </tr>                        
                    </table>
                </div>';
            }else{
                echo $box_detail_html = '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">No record found!</span>';
            }
        }
    }

    function create_put_away(){
        
        if($this->input->post()){            
            $this->form_validation->set_rules('box_no', 'Box No.', 'trim|required');
            $this->form_validation->set_rules('location_no', 'Location No.', 'trim|required');

            if ($this->form_validation->run()){
                $this->box_no = $this->input->post('box_no');
                $this->location_no = $this->input->post('location_no');
                $created_by = $this->user_id;

                $box_location = $this->Products->create_put_away(array('store_id' => $this->store_id,
                                                                       'box_no' => $this->box_no,
                                                                       'location_no' => $this->location_no,
                                                                       'created_by' => $created_by,
                                                                       'result_type'=>$this->row_type
                                                                      )
                                                                );
                if($box_location->action == 1){
                    $this->session->set_flashdata('success_message', $box_location->message);                    
                }else{
                    $this->session->set_flashdata('error_message', $box_location->message);                    
                }
            }
            redirect(BASE_URL . $this->page_name, 'refresh');
        }
    }

    function put_away_list(){
        $this->data['put_away_list'] = $this->Products->put_away_list(array('store_id'=>$this->store_id));
    }

    function product_alias($product_id = NULL){

        if($this->input->post()){
            if($this->input->post()){
                $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
                $this->form_validation->set_rules('product_id', 'Product', 'trim|required');
                $this->form_validation->set_rules('new_prod_name', 'Product alias name', 'trim|required');
    
                if ($this->form_validation->run()){                
    
                    $category_id = $this->input->post('category_id');
                    $product_id = $this->input->post('product_id');
                    $product_name = $this->input->post('new_prod_name');                    
    
                    $product_detail = $this->Products->add_product_alias(array(
                                                                         'category_id' => $category_id,
                                                                         'store_id' => $this->store_id,
                                                                         'product_id' => $product_id,
                                                                         'product_name' => $product_name,
                                                                         'created_by' => $this->user_id,
                                                                         'result_type' => $this->row_type
                                                                        )
                                                                   );
    
                    if($product_detail->action == 1){
                        $this->session->set_flashdata('success_message', $product_detail->message);
                    }else if($product_detail->action == 0){
                        $this->session->set_flashdata('error_message', $product_detail->message);
                    }else{
                        $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    }
                    //redirect(BASE_URL . 'product-alias/'.$product_id, 'refresh');
                }
            }
        }

        $product_detail = $this->Products->list_product(array('product_id'=>$product_id,
                                                              'result_type'=>$this->row_type
                                                             )
                                                        );

        if(isset($product_detail) && !empty($product_detail)){
            $this->category_option = $this->Options->category_option(array('selected_value'=>$product_detail->category_id));
            $product_detail = $this->Products->list_product(array('product_id'=>$product_id,
                                                                  'result_type'=>$this->row_type
                                                                )
                                                            ); 
            $this->product_alias_list = $this->Products->product_alias_list(array('product_id'=>$product_id,'store_id' => $this->store_id));
            
            $this->product_list = $this->Options->cateory_wise_product_list(array('selected_value'=>$product_id,'category_id'=>$product_detail->category_id));            
            $this->data['product_detail'] = $product_detail; 
        }
    }

    function delete_product_alias($product_alias_id){
        $product_alias_detail = $this->Products->product_alias_list(array('product_alias_id'=>$product_alias_id,
                                                                          'store_id' => $this->store_id,
                                                                          'result_type'=>$this->row_type
                                                                        )
                                                                    );
        
        if(!empty($product_alias_detail)){
            $product_detail = $this->Products->add_product_alias(array('product_alias_id'=>$product_alias_id,
                                                                       'store_id' => $this->store_id,
                                                                       'is_deleted' => '1',
                                                                       'created_by' => $this->user_id,
                                                                       'result_type'=>$this->row_type
                                                                      )
                                                                );                
            if($product_detail->action == 1){                
                $this->session->set_flashdata('success_message', $product_detail->message);                
            }else if($product_detail->action == 0){
                $this->session->set_flashdata('error_message', $product_detail->message);
            }else{
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }         
        }else{
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }   
        redirect(BASE_URL . 'product-alias/'.$product_alias_detail->product_id, 'refresh');
    }
}