<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pick_list extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');

        $this->load->model('Pick_list_model', 'Pick_list');
        $this->load->model('Sales_order_model', 'Sales_order');
        $this->load->model('Raw_material_model', 'Raw_material');
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
            case 'create-pick-list':
                $this->create_pick_list();
                break;
            case 'get-order-list':
                $this->get_order_list();
                exit;
                break;
            case 'show-box-detail':
                $this->show_box_detail();
                exit;
                break;
            case 'save-box-detail':
                $this->save_box_detail();
                exit;
                break;
            case 'dispatch-list':
                $this->dispatch_list();
                break;
            case 'general-issues':
                $this->general_issues();
                break;
            case 'general-issues-list':
                $this->general_issues_list();
                break;
            case 'show-gi-box-detail':
                $this->show_gi_box_detail();
                exit;
            case 'sales-order-product-list':
                $this->sales_order_product_location();
                exit;
            case 'po-product-list':
                $this->po_product_location();
                exit;
            case 'get-pick-list-item':
                $this->get_pick_list_item();
                exit;
            case 'check-pick-list':
                $this->check_pick_list();
                exit;
            case 'update-no-of-stickers':
                $this->update_no_of_stickers();
                exit;
                break;
            case 'get-fg-list':
                $this->get_fg_list();
                exit;
                break;
            case 'get-total-fg-list':
                $this->get_total_fg_list();
                exit;
                break;
            case 'get-gi-detail':
                $this->get_gi_box_detail();
                exit;
                break;
            case 'get-gi-box-detail':
                $this->get_box_detail();
                exit;
                break;
            case 'general-issue-detail':
                $this->general_issue_id = base64_decode($this->uri->segment(2));
                $this->general_issues_detail($this->general_issue_id);
                break;
            default:
                break;
        };
        $this->load->view('Backend/' . $this->class_name . '/index', $this->data);
    }

    function get_order_list()
    {
        $customer_id = $this->input->post('customer_id');
        if (!empty($customer_id)) {
            $sales_order_list = $this->Sales_order->list_sales_order(array('store_id' => $this->store_id, 'customer_id' => $customer_id));
            $order_option = '';
            if ($sales_order_list) {
                $order_option .= '<option value="">Choose one</option>';
                foreach ($sales_order_list as $obj) {
                    if ($obj->sales_order_status_id != 6) {
                        $order_option .= '<option value="' . $obj->sales_order_id . '">' . $obj->sales_order_no . '</option>';
                    }
                }
            }
        }
        echo $order_option;
    }

    function sales_order_product_location()
    {
        $sales_order_id = $this->input->post('sales_order_id');
        $sales_order_product_location = $this->Pick_list->sales_order_product_location(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id));
        // echo $this->db->last_query();exit;
        $td = '';
        if ($sales_order_product_location) {
            $i = 1;
            foreach ($sales_order_product_location as $obj) {
                $location_name = $obj->location_name;
                $location = [];
                if (!empty($location_name)) {
                    $array = explode(',', $location_name);
                    $vals = array_flip(array_count_values($array));
                    foreach ($vals as $key => $v) {
                        $location[] = $v . ' - <b>' . $key . ' Box</b>';
                    }
                }

                $td .= '<tr><td>' . $i . '.</td><td>' . $obj->product_name . '</td><td>' . $obj->quantity . '</td>
                <td>' . implode(', ', $location) . '</td></tr>';
                $i++;
            }
        }
        echo $td;
    }

    function get_pick_list_item()
    {
        $sales_order_id = $this->input->post('sales_order_id');
        $get_pick_list_item = $this->Pick_list->dispatch_list(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id));
        $td = '';
        if ($get_pick_list_item) {
            $i = 1;
            foreach ($get_pick_list_item as $obj) {
                $td .= '<tr><td>' . (!empty($obj->product_alias) ? $obj->product_alias : $obj->product_name) . '</td><td>' . $obj->box_no . '</td><td>' . $obj->box_no . '</td><td>' . $obj->no_of_items . '</td></tr>';
                $i++;
            }
        }
        echo $td;
    }

    function create_pick_list()
    {

        if ($this->input->post()) {
            $this->session->set_flashdata('success_message', 'Pick list created successfully.');
            redirect(BASE_URL . $this->page_name, 'refresh');
        }

        $this->category_option = $this->Options->category_option();
        $this->customer_option = $this->Options->customer_option();
        $this->uom_option = $this->Options->uom_option();
    }

    function save_box_detail_old()
    {
        $sales_order_id = $this->input->post('order_id');
        $pick_list_box_no = $this->input->post('pick_list_box_no');
        $delivery_date = $this->input->post('delivery_date');
        //$delivery_date = date('Y-m-d');
        $product_id = [];
        $sales_order_qty = 0;
        $no_of_items = 0;

        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'pick_list_box_no' => $pick_list_box_no, 'result_type' => $this->result_type));
        $data = [];
        if (!empty($pick_list_boxes)) {

            $box_product_id = $pick_list_boxes->product_id;
            $box_detail_id = $pick_list_boxes->box_detail_id;

            if ($pick_list_boxes->remaining_item > 0) {
                $sales_order_product_location = $this->Pick_list->sales_order_product_location(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id));
                if ($sales_order_product_location) {
                    foreach ($sales_order_product_location as $obj) {
                        $product_id[] = $obj->product_id;
                        if ($box_product_id == $obj->product_id) {
                            $sales_order_qty = $obj->quantity;
                        }
                    }
                }

                if (in_array($box_product_id, $product_id)) {
                    //echo $sales_order_qty;
                    //echo "test";
                    //echo $pick_list_boxes->no_of_items;
                    if ($sales_order_qty <= $pick_list_boxes->no_of_items) {
                        $remaining_item = $pick_list_boxes->remaining_item - $sales_order_qty;
                        $pick_list_qty = $sales_order_qty;
                    } else {
                        $pick_list_qty = $pick_list_boxes->remaining_item;
                        $remaining_item = 0;
                    }

                    $get_pick_list_item = $this->Pick_list->get_pick_list_item(
                        array(
                            'sales_order_id' => $sales_order_id,
                            'product_id' => $box_product_id,
                        )
                    );
                    //pr($get_pick_list_item);
                    if ($get_pick_list_item) {
                        foreach ($get_pick_list_item as $pObj) {
                            $no_of_items = $no_of_items + $pObj->no_of_items;
                        }
                    }
                    //echo $no_of_items."<br>";
                    //echo $sales_order_qty."<br>";exit;

                    if (empty($get_pick_list_item) || ($no_of_items < $sales_order_qty)) {

                        if ($no_of_items < $sales_order_qty) {
                            if ($no_of_items == 0) {
                                $pick_list_qty = $pick_list_boxes->no_of_items;
                            } else {
                                $pick_list_qty = $sales_order_qty - $no_of_items;
                            }
                            $remaining_item = $pick_list_boxes->remaining_item - $pick_list_qty;
                        }

                        $update_remaining_item = $this->Pick_list->update_remaining_item(array('box_detail_id' => $box_detail_id, 'remaining_item' => $remaining_item));

                        $add_pick_list_item = $this->Pick_list->add_pick_list_item(
                            array(
                                'sales_order_id' => $sales_order_id,
                                'product_id' => $box_product_id,
                                'box_detail_id' => $box_detail_id,
                                'no_of_items' => $pick_list_qty,
                                'delivery_date' => $delivery_date,
                                'created_by' => $this->user_id
                                //'result_type'=>$this->result_type
                            )
                        );

                        $box_detail_html = '<tr id="tr_' . $pick_list_boxes->box_no . '"><td>' . $pick_list_boxes->product_name . '</td>
                        <td>' . $pick_list_boxes->box_no . '</td>
                        <td>' . $pick_list_boxes->location_name . '</td>
                        <td>' . $pick_list_qty . '</td>
                        </tr>';
                        //<td><button type="button" name="remove" onclick="remove_pick_list('.$add_pick_list_item.','.$sales_order_id.')" class="btn btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button></td>
                        $data = array('message' => '', 'status' => 1, 'data' => $box_detail_html);
                    } else {
                        $data = array('message' => 'Pick list is already completed for this item.', 'status' => 0, 'data' => json_encode($pick_list_boxes));
                    }
                } else {
                    $data = array('message' => 'Sales order item is not available in this box.', 'status' => 0, 'data' => $pick_list_boxes);
                }
            } else {
                $data = array('message' => 'Product is out of stock.', 'status' => 0);
            }
        } else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        echo json_encode($data);
    }

    function show_box_detail()
    {
        $sales_order_id = $this->input->post('order_id');
        $pick_list_box_no = $this->input->post('pick_list_box_no');
        $delivery_date = $this->input->post('delivery_date');
        //$delivery_date = date('Y-m-d');
        $product_id = [];
        $sales_order_qty = 0;
        $no_of_items = 0;

        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'pick_list_box_no' => $pick_list_box_no, 'result_type' => $this->result_type));
        $data = [];
        if (!empty($pick_list_boxes)) {

            $box_product_id = $pick_list_boxes->product_id;
            $box_detail_id = $pick_list_boxes->box_detail_id;

            if ($pick_list_boxes->remaining_item > 0) {
                $sales_order_product_location = $this->Pick_list->sales_order_product_location(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id));
                if ($sales_order_product_location) {
                    foreach ($sales_order_product_location as $obj) {
                        $product_id[] = $obj->product_id;
                        if ($box_product_id == $obj->product_id) {
                            $sales_order_qty = $obj->quantity;
                        }
                    }
                }

                if (in_array($box_product_id, $product_id)) {
                    $box_detail_html = '<div class="col-md-12 rm_cls"><h4>Box Detail: </h4></div>
                    
                    <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Category: </label><input class="form-control" name="category" id="category" value="' . $pick_list_boxes->category_name . '" readonly type="text"></div></div>
                    
                    <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Product Name: </label><input class="form-control" name="category" value="' . $pick_list_boxes->product_name . '" readonly type="text"><input name="product_id" value="' . $pick_list_boxes->product_id . '" id="product_id" readonly type="hidden"></div></div>
                    
                    <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">OEM: </label><input class="form-control" name="category" id="oem_id" value="' . $pick_list_boxes->company_name . '" readonly type="text"></div></div>
                    
                    <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Qty in Stock: </label><input class="form-control" name="category" id="remaining_item" value="' . $pick_list_boxes->remaining_item . '" readonly type="text"></div></div>
                    
                    <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Quantity: </label><input class="form-control" name="category" id="qty" value="" type="number" min="1"></div></div>

                    <div class="col-md-12 rm_cls"><label class="form-label"></label><button class="btn-sm ripple btn-secondary add_pick_list_btn" type="button" onclick="add_to_pick_list()">Add to Pick List</button></div>
                    ';
                    $data = array('message' => '', 'status' => 1, 'data' => $box_detail_html);
                } else {
                    $data = array('message' => 'Sales order item is not available in this box.', 'status' => 0, 'data' => $pick_list_boxes);
                }
            } else {
                $data = array('message' => 'Product is out of stock.', 'status' => 0);
            }
        } else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        echo json_encode($data);
    }

    function save_box_detail()
    {
        $sales_order_id = $this->input->post('order_id');
        $pick_list_box_no = $this->input->post('pick_list_box_no');
        $order_qty = $this->input->post('order_qty');
        $remaining_item = $this->input->post('remaining_item');
        $delivery_date = $this->input->post('delivery_date');
        //$delivery_date = date('Y-m-d');
        $product_id = 0;
        $sales_order_qty = 0;
        $no_of_items = 0;
        $unit_name = '';

        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'pick_list_box_no' => $pick_list_box_no, 'result_type' => $this->result_type));
        $data = [];
        if (!empty($pick_list_boxes)) {

            $box_product_id = $pick_list_boxes->product_id;
            $box_detail_id = $pick_list_boxes->box_detail_id;

            if ($pick_list_boxes->remaining_item > 0) {
                $sales_order_product_location = $this->Pick_list->sales_order_product_location(
                    array(
                        'store_id' => $this->store_id,
                        'sales_order_id' => $sales_order_id,
                        'product_id' => $box_product_id,
                        'result_type' => $this->result_type
                    )
                );
                if ($sales_order_product_location) {

                    $product_id = $sales_order_product_location->product_id;
                    $sales_order_qty = $sales_order_product_location->quantity;
                    $unit_name = $sales_order_product_location->unit;
                }
                //exit;
                if ($box_product_id == $product_id) {

                    $get_pick_list_item = $this->Pick_list->get_pick_list_item(
                        array(
                            'sales_order_id' => $sales_order_id,
                            'product_id' => $box_product_id,
                        )
                    );
                    if ($get_pick_list_item) {
                        foreach ($get_pick_list_item as $pObj) {
                            $no_of_items = $no_of_items + $pObj->no_of_items;
                        }
                    }

                    if ($sales_order_qty == $no_of_items) {
                        $data = array('message' => 'Pick list is already completed for this item.', 'status' => 0, 'data' => json_encode($pick_list_boxes));
                    } else {
                        $remaining_pick_item = $sales_order_qty - $no_of_items;

                        if ($remaining_pick_item < $pick_list_boxes->remaining_item) {
                            $remaining_item = $pick_list_boxes->remaining_item - $remaining_pick_item;
                            $pick_list_qty = $remaining_pick_item;
                        } else if ($remaining_pick_item == $pick_list_boxes->remaining_item) {
                            $remaining_item = 0;
                            $pick_list_qty = $remaining_pick_item;
                        } else if ($remaining_pick_item > $pick_list_boxes->remaining_item) {
                            $remaining_item = 0;
                            $pick_list_qty = $pick_list_boxes->remaining_item;
                        }

                        $update_remaining_item = $this->Pick_list->update_remaining_item(array('box_detail_id' => $box_detail_id, 'remaining_item' => $remaining_item));

                        $add_pick_list_item = $this->Pick_list->add_pick_list_item(
                            array(
                                'sales_order_id' => $sales_order_id,
                                'product_id' => $box_product_id,
                                'box_detail_id' => $box_detail_id,
                                'no_of_items' => $pick_list_qty,
                                'delivery_date' => $delivery_date,
                                'created_by' => $this->user_id
                                //'result_type'=>$this->result_type
                            )
                        );

                        $box_detail_html = '<tr id="tr_' . $pick_list_boxes->box_no . '"><td>' . $pick_list_boxes->product_name . '</td>
                        <td>' . $pick_list_boxes->box_no . '</td>
                        <td>' . $pick_list_boxes->location_name . '</td>
                        <td>' . $pick_list_qty . ' ' . $unit_name . '</td>
                        </tr>';
                        //<td><button type="button" name="remove" onclick="remove_pick_list('.$add_pick_list_item.','.$sales_order_id.')" class="btn btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button></td>
                        $data = array('message' => '', 'status' => 1, 'data' => $box_detail_html);
                        //}else{                    

                    }
                } else {
                    $data = array('message' => 'Sales order item is not available in this box.', 'status' => 0, 'data' => $pick_list_boxes);
                }
            } else {
                $data = array('message' => 'Product is out of stock.', 'status' => 0);
            }
        } else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        echo json_encode($data);
    }

    function dispatch_list()
    {
        $this->data['dispatch_list'] = $this->Pick_list->dispatch_list(array('store_id' => $this->store_id));
        $this->customer_option = $this->Options->customer_option();
    }

    function check_pick_list()
    {
        $sales_order_id = $this->input->post('sales_order_id');
        $customer_id = $this->input->post('customer_id');

        //$sales_order_product_location = $this->Pick_list->sales_order_product_location(array('store_id' => $this->store_id,'sales_order_id' => $sales_order_id));

        $sales_order = $this->Sales_order->list_sales_order(array('store_id' => $this->store_id, 'customer_id' => $customer_id, 'sales_order_id' => $sales_order_id, 'result_type' => $this->result_type));
        //pr($sales_order);
        if ($sales_order->sales_order_status_id != 6) {

            $sales_order_list = $this->Sales_order->sales_order_detail(array('store_id' => $this->store_id, 'customer_id' => $customer_id, 'sales_order_id' => $sales_order_id));

            $get_pick_list_item = $this->Pick_list->dispatch_list(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id));
            $total_pick_item = 0;

            if (!empty($get_pick_list_item)) {
                foreach ($get_pick_list_item as $obj) {
                    $total_pick_item = $total_pick_item + $obj->no_of_items;
                }
            }

            $total_sales_item = 0;
            if (!empty($sales_order_list)) {
                foreach ($sales_order_list as $sObj) {
                    $total_sales_item = $total_sales_item + $sObj->quantity;
                }
            }

            if ($total_pick_item == 0) {
                $data = array('message' => 'Please add all items in pick list.', 'status' => 0);
            } else if ($total_pick_item > 0 && $total_pick_item < $total_sales_item) {
                $data = array('message' => 'Some items are not added in pick list.', 'status' => 0);
            } else if ($total_pick_item > 0 && $total_pick_item == $total_sales_item) {
                $update_sales_order_status = $this->Sales_order->update_sales_order(array('store_id' => $this->store_id, 'sales_order_id' => $sales_order_id, 'sales_order_status_id' => 6));
                //echo $this->db->last_query();exit;
                $data = array('message' => '', 'status' => 1);
            }
        } else {
            $data = array('message' => 'Pick list already created.', 'status' => 0);
        }

        echo json_encode($data);
    }

    function general_issues()
    {
        $this->general_issue_id = base64_decode($this->uri->segment(2));
        if (isset($this->general_issue_id) && $this->general_issue_id != '') {
            $this->data['gi_detail'] = $this->Pick_list->general_issues_list(array('store_id' => $this->store_id, 'general_issues_id' => $this->general_issue_id, 'result_type' => $this->result_type));
            $this->data['fg_detail'] = $this->Pick_list->get_fg_details_by_issue(array('store_id' => $this->store_id, 'general_issues_id' => $this->general_issue_id));
            $this->data['box_detail'] = $this->Pick_list->get_scan_detail(array('store_id' => $this->store_id, 'general_issues_id' => $this->general_issue_id));
            // echo $this->db->last_query();exit;
            // pr($this->data['box_detail']);exit;
        }

        $cond = array('store_id' => $this->store_id);

        if (CURRENT_MONTH < 4) {
            $this->gi_no = 'PGI/' . (date('y') - 1) . '-' . date('y') . '/' . str_pad(get_last_id('general_issues_id', 'tbl_general_issues', $cond), 4, "0", STR_PAD_LEFT);
        } else {
            $this->gi_no = 'PGI/' . (date('y')) . '-' . (date('y') + 1) . '/' . str_pad(get_last_id('general_issues_id', 'tbl_general_issues', $cond), 4, "0", STR_PAD_LEFT);
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('gi_qty[]', 'Gi qty', 'trim');
            $this->form_validation->set_rules('fg_id[]', 'Fg Id', 'trim|required');
            $this->form_validation->set_rules('sfg_box_detail_id[]', 'Box No.', 'trim');

            if ($this->form_validation->run()) {

                $gi_no = $this->input->post('gi_no');
                $fg_id_array = $this->input->post('fg_id');
                $employee_id_array = $this->input->post('employee_id_array');
                $purchase_order_id = $this->input->post('purchase_order_id');
                $product_id = $this->input->post('product_id');
                $gi_box_no = $this->input->post('gi_box_no');
                $sfg_box_detail_id = $this->input->post('sfg_box_detail_id');
                $issued_quantity_array = $this->input->post('gi_qty'); // issued qty
                $remaining_item_array = $this->input->post('no_of_items'); // current stock
                for ($i = 0; $i < count($fg_id_array); $i++) {
                    $gi_detail = $this->Pick_list->save_general_issues(
                        array(
                            'store_id' => $this->store_id,
                            'general_issues_no' => $gi_no,
                            'fg_id' => $fg_id_array[$i],
                            'is_deleted' => '0',
                            'created_by' => $this->user_id,
                            'result_type' => $this->result_type
                        )
                    );
                    if (isset($gi_detail->general_issues_id) && $gi_detail->general_issues_id > 0) {
                        $general_issues_id = $gi_detail->general_issues_id;
                        $add_pick_list_item = $this->Pick_list->add_pick_list_item(
                            array(
                                'sales_order_id' => $general_issues_id,
                                'fg_id' => $fg_id_array[$i],
                                'is_general_issue' => '1',
                                'sfg_box_detail_id' => $sfg_box_detail_id[$i],
                                'no_of_items' => $issued_quantity_array[$i],
                                'created_by' => $this->user_id
                            )
                        );
                        // pr($add_pick_list_item);exit;
                        // echo $this->db->last_query();exit;
                        $remaining_item = $remaining_item_array[$i] - $issued_quantity_array[$i];

                        $update_remaining_item = $this->Pick_list->update_remaining_item(array('sfg_box_detail_id' => $sfg_box_detail_id[$i], 'remaining_item' => $remaining_item));
                    }
                }

                if ($gi_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $gi_detail->message);
                } else if ($gi_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $gi_detail->message);
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
                redirect(BASE_URL . 'general-issues', 'refresh');
            }
        }
        $this->data['fg_list'] = $this->Raw_material->fg_list_dropdown(array('store_id' => $this->store_id, 'is_bom' => '1'));
        // pr($this->data['fg_list']);exit;
        $this->category_option = $this->Options->category_option();
        $this->po_option = $this->Options->po_option(array('store_id' => $this->store_id));
        $this->deparmtent_option = $this->Options->department_option();
    }

    function general_issues_list()
    {
        $this->data['general_issues_list'] = $this->Pick_list->general_issues_list(array('store_id' => $this->store_id));
        // pr($this->data['general_issues_list']);exit;
        $this->customer_option = $this->Options->customer_option();
    }

    function po_product_location()
    {
        $po_id = $this->input->post('po_id');
        $po_product_location = $this->Pick_list->po_product_location(array('store_id' => $this->store_id, 'po_id' => $po_id));
        $td = '';

        if ($po_product_location) {
            $i = 1;
            foreach ($po_product_location as $obj) {

                $td .= '<tr><td>' . $i . '.</td><td>' . (!empty($obj->product_alias) ? $obj->product_alias : $obj->product_name) . '</td><td>' . $obj->quantity . ' ' . $obj->unit . '</td><td>' . $obj->location_name . '</td></tr>';
                $i++;
            }
        }
        echo $td;
    }

    function show_gi_box_detail()
    {
        $box_no = $this->input->post('box_no');

        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'gi_box_no' => $box_no, 'result_type' => $this->result_type));
        $product_id = [];
        $data = [];
        // pr($pick_list_boxes);
        if (!empty($pick_list_boxes)) {

            $box_product_id = $pick_list_boxes->product_id;
            $box_detail_id = $pick_list_boxes->box_detail_id;
            $product_unit = $pick_list_boxes->unit;
            $po_product_location = $this->Pick_list->po_product_location(array('store_id' => $this->store_id));

            if ($po_product_location) {
                $i = 1;
                foreach ($po_product_location as $obj) {
                    $product_id[] = $obj->product_id;
                }
            }
            if (in_array($box_product_id, $product_id)) {
                if ($pick_list_boxes->remaining_item > 0) {
                    $box_detail_html = '<div class="col-md-12 rm_cls mg-t-20"><h4>Box Detail: </h4></div>
                    
                    <div class="row row-xs align-items-center mg-b-13"><div class="col-md-4"><label class="mg-b-0">Product Name:</label></div>	
                        <div class="col-md-8 mg-t-5 mg-md-t-0">		
                            <input class="form-control" name="p_name" id="p_name" value="' . $pick_list_boxes->product_name . '" readonly type="text">
                            <input class="form-control" name="p_name_id" id="p_name_id" value="' . $pick_list_boxes->product_id . '" readonly type="hidden">
                        </div>
                    </div>

                    <div class="row row-xs align-items-center mg-b-13"><div class="col-md-4"><label class="mg-b-0">Qty in Stock:</label></div>	
                        <div class="col-md-8 mg-t-5 mg-md-t-0">		
                            <input class="form-control" name="remaining_item" id="remaining_item" value="' . $pick_list_boxes->remaining_item . '" readonly type="text">
                            <input class="form-control" name="box_detail_id" id="box_detail_id" value="' . $box_detail_id . '" readonly type="hidden">
                        </div>
                    </div>

                    <div class="row row-xs align-items-center mg-b-13"><div class="col-md-4"><label class="mg-b-0">Quantity:</label></div>	
                        <div class="col-md-8 mg-t-5 mg-md-t-0">		
                            <input class="form-control" name="qty" id="qty" value="" type="number" min="1" required>
                            <input name="unit_name" id="unit_name" value="' . $product_unit . '" type="hidden" required>
                        </div>
                    </div>

                    <div class="col-md-12"><label class="form-label"></label><button class="btn-sm ripple btn-secondary add_pick_list_btn" type="button" onclick="add_to_gi_list()">Add to List</button></div>
                    ';

                    $data = array('message' => '', 'status' => 1, 'data' => $box_detail_html);
                } else {
                    $data = array('message' => 'Sacnned box is empty.', 'status' => 0);
                }
            } else {
                $data = array('message' => 'Invalid box no.', 'status' => 0);
            }
        } else {
            $data = array('message' => 'Invalid box no.', 'status' => 0);
        }
        echo json_encode($data);
    }

    public function update_no_of_stickers()
    {
        $pick_list_id = $this->input->post('pick_list_id');
        $no_of_stickers = $this->input->post('no_of_stickers');

        $pick_list = $this->Pick_list->update_no_of_stickers(
            array(
                'store_id' => $this->store_id,
                'no_of_stickers' => $no_of_stickers,
                'pick_list_id' => $pick_list_id,
                'result_type' => $this->result_type
            )
        );
        $this->data['product_grn_list'] = $pick_list;
    }
    function get_fg_list()
    {
        $fg_id = $this->input->post('fg_id');
        $get_fg_list = $this->Pick_list->get_fg_list(array('store_id' => $this->store_id, 'fg_id' => $fg_id));
        // pr($get_fg_list); exit;

        $td = '';
        if (!empty($get_fg_list)) {
            $i = 1;
            foreach ($get_fg_list as $obj) {
                $item_name = '';
                if ($obj->grn_type == 'sfg') {
                    $item_name = $obj->sfg_name;
                } else if ($obj->grn_type == 'rm') {
                    $item_name = $obj->raw_material_name;
                }

                $td .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $obj->fg_code . '</td>
                        <td>' . $obj->grn_type . '</td>
                        <td>' . $item_name . '</td>
                        <td>' . $obj->quantity . '</td>
                    </tr>';
            }
        }

        echo $td;
    }
    function get_total_fg_list()
    {
        $fg_id = $this->input->post('fg_id');
        $fg_quantity = $this->input->post('fg_quantity');
        $get_fg_list = $this->Pick_list->get_fg_list(array('store_id' => $this->store_id, 'fg_id' => $fg_id));
        $get_count_rm = $this->Pick_list->get_count_rm(array('store_id' => $this->store_id, 'fg_id' => $fg_id));
        // pr($get_count_rm); exit;
        $td = '';
        if (!empty($get_fg_list)) {
            $i = 1;
            foreach ($get_fg_list as $obj) {
                $total_quantity = $fg_quantity * $obj->quantity;

                $grn_type = strtolower(trim($obj->grn_type));
                $grn_type_id = '';
                if ($grn_type == 'rm') {
                    $grn_type_id = 'rm_id_' . $obj->item_id;
                } elseif ($grn_type == 'sfg') {
                    $grn_type_id = 'sfg_id_' . $obj->item_id;
                }

                $scanned_qty_id = $grn_type . '_scanned_qty_id_' . $obj->item_id;
                $no_of_rm = !empty($get_count_rm[0]->no_of_rm) ? $get_count_rm[0]->no_of_rm : 0;

                $td .= '<tr>
                    <td>' . $i++ . '</td>
                    <td>' . $obj->fg_code . '</td>
                    <td>' . strtoupper($obj->grn_type) . '</td>
                    <td>' . strtoupper($obj->item_name) . '</td>
                    <td>' . $obj->quantity . '</td>
                    <td>' . $total_quantity . '</td>
                    <td class="scanned-qty">0</td> 

                    <input type="hidden" id="' . $grn_type_id . '" value="' . $total_quantity . '">
                    <input type="hidden" class="fg-item-id" value="' . $obj->item_id . '">
                    <input type="hidden" id="' . $scanned_qty_id . '" value="0">
                    <input type="hidden" id="rm_count_id" value="' . $no_of_rm . '"> 

                </tr>';
            }
        }

        echo $td;
    }


    public function get_gi_box_detail()
    {
        $cond = array('store_id' => $this->store_id);

        if (CURRENT_MONTH < 4) {
            $this->gi_no = 'PGI/' . (date('y') - 1) . '-' . date('y') . '/' . str_pad(get_last_id('general_issues_id', 'tbl_general_issues', $cond), 4, "0", STR_PAD_LEFT);
        } else {
            $this->gi_no = 'PGI/' . (date('y')) . '-' . (date('y') + 1) . '/' . str_pad(get_last_id('general_issues_id', 'tbl_general_issues', $cond), 4, "0", STR_PAD_LEFT);
        }
        $gi_box_no        = $this->input->post('gi_box_no');
        $fg_id            = $this->input->post('fg_id');
        $fg_quantity      = $this->input->post('fg_quantity');
        $general_issue_id = $this->input->post('general_issue_id');
        $rm_count_id      = $this->input->post('rm_count_id');

        if (empty($general_issue_id) || $general_issue_id == 0) {
            $sp_result = $this->Pick_list->save_general_issues(array(
                'store_id'          => $this->store_id,
                'purchase_order_id' => 0,
                'general_issues_no' => $this->gi_no,
                'department_id'     => 0,
                'employee_id'       => $this->user_id,
                'is_deleted'        => '0',
                'created_by'        => $this->user_id,
                'fg_id'             => $fg_id,
                'no_of_rm'          => $rm_count_id,
                'fg_quantity'       => $fg_quantity,
                'result_type'       => 'row'
            ));

            if (!empty($sp_result) && isset($sp_result->general_issues_id) && $sp_result->action == 1) {
                $general_issue_id = $sp_result->general_issues_id;
            } else {
                $data = array(
                    'message'          => $sp_result->message ?? 'Unable to create General Issue',
                    'status'           => 0,
                    'general_issue_id' => 0
                );
                echo json_encode($data);
                return;
            }
        }

        $get_fg_list = $this->Pick_list->get_item_barcode(array(
            'store_id'    => $this->store_id,
            'gi_box_no'   => $gi_box_no,
            'result_type' => $this->result_type
        ));

        if (empty($get_fg_list)) {
            $data = array('message' => 'Invalid box no.', 'status' => 0, 'general_issue_id' => $general_issue_id);
            echo json_encode($data);
            return;
        }

        $sfg_box_detail_id = $get_fg_list->sfg_box_detail_id;

        if ($get_fg_list->remaining_item <= 0) {
            $data = array('message' => 'This box has no items remaining.', 'status' => 0, 'general_issue_id' => $general_issue_id);
            echo json_encode($data);
            return;
        }

        $fg_detail_list = $this->Pick_list->get_fg_list(array(
            'store_id' => $this->store_id,
            'fg_id'    => $fg_id
        ));

        $item_valid        = false;
        $scanned_item_name = strtolower(trim($get_fg_list->item_name));
        $scanned_grn_type  = strtolower(trim($get_fg_list->grn_type));

        if (!empty($fg_detail_list)) {
            foreach ($fg_detail_list as $fg_item) {
                if (
                    strtolower(trim($fg_item->item_name)) === $scanned_item_name &&
                    strtolower(trim($fg_item->grn_type)) === $scanned_grn_type
                ) {
                    $item_valid = true;
                    break;
                }
            }
        }

        if (!$item_valid) {
            $data = array('message' => 'Scanned item does not belong to selected FG.', 'status' => 0, 'general_issue_id' => $general_issue_id);
            echo json_encode($data);
            return;
        }
        if ($get_fg_list->is_put_away == 0) {
            $data = array('message' => 'This scanned box is not in stock.', 'status' => 0, 'general_issue_id' => $general_issue_id);
            echo json_encode($data);
            return;
        }
        $box_detail_html = '
        <div class="col-md-12 rm_cls mg-t-20"><h4>Box Detail: </h4></div>
        <div class="row row-xs align-items-center mg-b-13">
            <div class="col-md-4"><label class="mg-b-0">Item Name:</label></div>
            <div class="col-md-8 mg-t-5 mg-md-t-0">
                <input class="form-control" name="item_name" id="item_name" value="' . $get_fg_list->item_name . '" readonly type="text">
                <input class="form-control" name="item_id" id="item_id" value="' . $get_fg_list->sfg_id . '" readonly type="hidden">
                <input class="form-control" name="sfg_id" id="sfg_id" value="' . $get_fg_list->sfg_id . '" readonly type="hidden">
                <input class="form-control" name="grn_type" id="grn_type" value="' . $get_fg_list->grn_type . '" readonly type="hidden">
            </div>
        </div>

        <div class="row row-xs align-items-center mg-b-13">
            <div class="col-md-4"><label class="mg-b-0">Qty in Stock:</label></div>
            <div class="col-md-8 mg-t-5 mg-md-t-0">
                <input class="form-control" name="gi_no_of_items" id="gi_no_of_items" value="' . $get_fg_list->remaining_item . '" readonly type="text">
                <input class="form-control" name="sfg_box_detail_id" id="sfg_box_detail_id" value="' . $sfg_box_detail_id . '" readonly type="hidden">
            </div>
        </div>

        <div class="row row-xs align-items-center mg-b-13">
            <div class="col-md-4"><label class="mg-b-0">Quantity: <span class="tx-danger">*</span></label></div>
            <div class="col-md-8 mg-t-5 mg-md-t-0">
                <input class="form-control" name="gi_qty" id="gi_qty" value="" type="number" min="1" required>
            </div>
        </div>
    ';
        $data = array(
            'message'          => '',
            'status'           => 1,
            'data'             => $box_detail_html,
            'general_issue_id' => $general_issue_id
        );
        echo json_encode($data);
    }

    // function get_gi_detail()
    // {
    //     $gi_box_no = $this->input->post('gi_box_no');
    //     $get_fg_list = $this->Pick_list->get_item_barcode(array('store_id' => $this->store_id, 'gi_box_no' => $gi_box_no));
    //     // pr($get_fg_list);
    //     exit;

    //     $td = '';
    //     if (!empty($get_fg_list)) {
    //         foreach ($get_fg_list as $obj) {
    //             $td .= '<tr>
    //                         <td>' . $obj->grn_type . '</td>
    //                         <td>' . $obj->item_name . '</td>
    //                         <td>' . $obj->box_no . '</td>
    //                     </tr>';
    //         }
    //     }

    //     echo $td;
    // }
    public function get_box_detail()
    {
        $fg_name       = $this->input->post('fg_name');
        $fg_id         = $this->input->post('fg_id');
        $quantity      = (int)$this->input->post('quantity');
        $gi_box_no     = $this->input->post('gi_box_no');
        $no_of_items   = (int)$this->input->post('no_of_items');
        $box_detail_id = $this->input->post('box_detail_id');
        $general_issue_id = $this->input->post('general_issue_id');
        $item_id = $this->input->post('item_id');
        $grn_type = $this->input->post('grn_type');
        $issue_qty = $this->input->post('issue_qty');

        $get_fg_list = $this->Pick_list->get_item_barcode(array(
            'store_id'  => $this->store_id,
            'gi_box_no' => $gi_box_no,
            'result_type' => $this->result_type
        ));
        // pr($get_fg_list); exit;

        if (empty($get_fg_list)) {
            echo json_encode([
                'status'  => 0,
                'message' => 'No matching FG items found in box.',
                'general_issue_id' => $general_issue_id
            ]);
            return;
        }
        $already_picked_qty = $this->Pick_list->get_total_picked_qty(array(
            'general_issue_id' => $general_issue_id,
            'item_id'          => $item_id
        ));
        $already_picked_qty = (int) $already_picked_qty;

        $required_qty = $issue_qty - $already_picked_qty - $quantity;
        if ($required_qty < 0) {
            $required_qty = 0;
        }

        $this->Pick_list->add_pick_list_item(array(
            'sales_order_id'     => $general_issue_id,
            'fg_id'              => $fg_id,
            'item_id'            => $item_id,
            'grn_type'           => $grn_type,
            'sfg_box_detail_id'  => $box_detail_id,
            'no_of_items'        => $quantity,
            'required_qty'       => $required_qty,
            'is_general_issue'   => '1',
            'store_id'           => $this->store_id,
            'created_by'         => $this->user_id
        ));

        $remaining_item = $no_of_items - $quantity;
        $this->Pick_list->update_remaining_item(array(
            'sfg_box_detail_id' => $box_detail_id,
            'remaining_item'    => $remaining_item
        ));

        $this->Pick_list->check_and_update_dispatch_status(array(
            'general_issue_id' => $general_issue_id,
            'store_id'         => $this->store_id
        ));

        $td = '<tr>
        <td>' . $fg_name . '</td>
        <td>' . strtoupper($get_fg_list->item_name) . '</td>
        <td>' . $quantity . '</td>
        <td>' . $gi_box_no . '</td>
    </tr>';

        echo json_encode(array(
            'status'           => 1,
            'data'             => $td,
            'general_issue_id' => $general_issue_id
        ));
    }
    function general_issues_detail(){
        if (isset($this->general_issue_id) && $this->general_issue_id != '') {
            $this->data['fg_detail'] = $this->Pick_list->get_fg_details_by_issue(array('store_id' => $this->store_id, 'general_issues_id' => $this->general_issue_id));
            // pr($this->data['fg_detail']);exit;
        }
        
    }
}
