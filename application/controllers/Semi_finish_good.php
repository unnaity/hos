<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Semi_finish_good extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');

        $this->load->model('Semi_finish_good_model', 'Semi_finish_good');
        $this->load->model('Products_model', 'Products');
        $this->load->model('Raw_material_model', 'Raw_material');
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
        $this->sfg_id = $this->uri->segment(2);
        // pr($this->sfg_id);exit;

        switch ($method) {
            case 'sfg-list':
                $this->sfg_list();
                break;
            case 'add-sfg':
                $this->add_sfg();
                break;
            case 'edit-sfg':
                $this->edit_sfg($this->sfg_id);
                break;
            case 'delete-sfg':
                $this->delete_sfg($this->sfg_id);
                break;
            case 'sfg-grn-list':
                $this->sfg_grn_list();
                break;
            case 'create-sfg-grn':
                $this->create_sfg_grn();
                break;
            case 'sfg-put-away-list':
                $this->sfg_put_away_list();
                break;
            case 'sfg-create-put-away':
                $this->sfg_create_put_away();
                break;
            case 'sfg-quality-check-list':
                $this->sfg_quality_check_list();
                break;
            case 'sfg-quality-check':
                $this->sfg_quality_check();
                break;
            case 'show-sfg-detail':
                $this->show_product_detail();
                exit;
                // case 'sfg-grn-detail':
                //     $this->sfg_grn_detail();
                //     exit;
                //     break;
            case 'show-input-sfg-box':
                $this->show_input_sfg_box();
                exit;
                break;
            case 'get-sfg-hsn-code':
                $this->get_sfg_hsn_code();
                exit;
                break;
            case 'get-grn-items':
                $this->get_grn_items();
                exit;
                break;
            case 'get-unit-list':
                $this->get_unit_list();
                exit;
                break;
            default:
                break;
        };
        $this->load->view('Backend/' . $this->class_name . '/index', $this->data);
    }
    function sfg_list()
    {
        $this->sfg_list = $this->Semi_finish_good->sfg_list(array('store_id' => $this->store_id));
        // pr($this->sfg_list);exit;
    }
    function add_sfg()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('sfg_name', 'Sfg name', 'trim|required');
            $this->form_validation->set_rules('sfg_code', 'Sfg code', 'trim|required');
            $this->form_validation->set_rules('sustainability_score', 'Sustainability score', 'trim');
            $this->form_validation->set_rules('weight', 'Weight', 'trim|required');
            $this->form_validation->set_rules('unit_id', 'Unit Id', 'trim|required');
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');

            if ($this->form_validation->run()) {

                $category_id = $this->input->post('category_id');
                $hsn_code = $this->input->post('hsn_code');
                $sfg_code = $this->input->post('sfg_code');
                $sfg_name = $this->input->post('sfg_name');
                $sustainability_score = $this->input->post('sustainability_score');
                $weight = $this->input->post('weight');
                $unit_id = $this->input->post('unit_id');
                $additional_info = $this->input->post('additional_info');

                $sfg_detail = $this->Semi_finish_good->add_sfg(
                    array(
                        'store_id' => $this->store_id,
                        'category_id' => $category_id,
                        'hsn_code' => $hsn_code,
                        'sfg_name' => $sfg_name,
                        'weight' => $weight,
                        'sustainability_score' => $sustainability_score,
                        'sfg_code' => $sfg_code,
                        'unit_id' => $unit_id,
                        'additional_info' => htmlentities($additional_info),
                        'created_by' => $this->user_id
                    )
                );
                // echo $this->db->last_query();exit;
                if ($sfg_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $sfg_detail->message);
                    redirect(BASE_URL . 'sfg-list', 'refresh');
                } else if ($sfg_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $sfg_detail->message);
                    redirect(BASE_URL . 'add-sfg', 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'add-sfg', 'refresh');
                }
            }
        }
        $this->uom_option = $this->Raw_material->list_units_of_measure(array());
    }
    function edit_sfg($sfg_id)
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('sfg_name', 'Sfg name', 'trim|required');
            $this->form_validation->set_rules('sfg_code', 'Sfg code', 'trim|required');
            $this->form_validation->set_rules('sustainability_score', 'Sustainability score', 'trim');
            $this->form_validation->set_rules('weight', 'Weight', 'trim|required');
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');

            if ($this->form_validation->run()) {

                $category_id = $this->input->post('category_id');
                $hsn_code = $this->input->post('hsn_code');
                $sfg_code = $this->input->post('sfg_code');
                $sfg_name = $this->input->post('sfg_name');
                $sustainability_score = $this->input->post('sustainability_score');
                $weight = $this->input->post('weight');
                $additional_info = $this->input->post('additional_info');



                $sfg_detail = $this->Semi_finish_good->add_sfg(
                    array(
                        'store_id' => $this->store_id,
                        'category_id' => $category_id,
                        'hsn_code' => $hsn_code,
                        'sfg_name' => $sfg_name,
                        'weight' => $weight,
                        'sustainability_score' => $sustainability_score,
                        'sfg_code' => $sfg_code,
                        'additional_info' => htmlentities($additional_info),
                        'created_by' => $this->user_id
                    )
                );
                if ($sfg_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $sfg_detail->message);
                    redirect(BASE_URL . 'edit-sfg/' . $sfg_id, 'refresh');
                } else if ($sfg_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $sfg_detail->message);
                    redirect(BASE_URL . 'edit-sfg/' . $sfg_id, 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'edit-sfg/' . $sfg_id, 'refresh');
                }
            }
        }
        $this->sfg_list = $this->Semi_finish_good->sfg_list(array('sfg_id' => $this->sfg_id, 'store_id' => $this->store_id, 'result_type' => $this->row_type));
        $this->category_option = $this->Options->category_option(array('selected_value' => $this->sfg_list->category_id));
    }
    function delete_sfg($sfg_id)
    {
        $this->sfg_list = $this->Semi_finish_good->sfg_list(array('store_id' => $this->store_id, 'result_type' => $this->row_type));
        if (!empty($this->sfg_list)) {
            $sfg_detail = $this->Semi_finish_good->add_sfg(
                array(
                    'sfg_id' => $this->sfg_id,
                    'store_id' => $this->store_id,
                    'is_deleted' => '1'
                )
            );
            if ($sfg_detail->action == 1) {
                $this->session->set_flashdata('success_message', $sfg_detail->message);
            } else if ($sfg_detail->action == 0) {
                $this->session->set_flashdata('error_message', $sfg_detail->message);
            } else {
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }
        } else {
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }
        redirect(BASE_URL . 'sfg-list', 'refresh');
    }
    function sfg_grn_list()
    {
        $this->sfg_grn_list = $this->Semi_finish_good->list_sfg_grn(array('store_id' => $this->store_id));
        $this->data['sfg_grn_list'] = $this->sfg_grn_list;
        // pr($this->data['sfg_grn_list']);exit;
    }
    public function create_sfg_grn()
{
    $cond = ['store_id' => $this->store_id];

    // Generate GRN No based on financial year
    if (CURRENT_MONTH < 4) {
        $this->sfg_grn_no = (date('y') - 1) . '-' . date('y') . '/' . str_pad(get_last_id('sfg_grn_id', 'tbl_sfg_grn', $cond), 4, "0", STR_PAD_LEFT);
    } else {
        $this->sfg_grn_no = date('y') . '-' . (date('y') + 1) . '/' . str_pad(get_last_id('sfg_grn_id', 'tbl_sfg_grn', $cond), 4, "0", STR_PAD_LEFT);
    }

    if ($this->input->post()) {

        $grn_type        = strtolower($this->input->post('grn_type'));
        $po_date         = $this->input->post('po_date') ? $this->input->post('po_date') : NULL;
        $po_no           = $this->input->post('po_no') ? $this->input->post('po_no') : NULL;  
        $supplier_id     = $this->input->post('supplier_id');
        $additional_info = $this->input->post('additional_info');
        $created_by      = $this->user_id;

        $items = $this->input->post('items');
        $box_items_all = $this->input->post('box_item');

        if (empty($items)) {
            $this->session->set_flashdata('error_message', 'Please add at least one item.');
            redirect(BASE_URL . $this->page_name, 'refresh');
        }
        $sfg_grn_result = $this->Semi_finish_good->add_sfg_grn([
            'store_id'        => $this->store_id,
            'sfg_grn_no'      => $this->sfg_grn_no,
            'po_date'         => $po_date,
            'po_no'           => $po_no,
            'grn_type'        => $grn_type,
            'additional_info' => $additional_info,
            'created_by'      => $created_by
        ]);

        if ($sfg_grn_result->action == 1) {
            $sfg_grn_id = $sfg_grn_result->sfg_grn_id;

            foreach ($items as $item) {
                $sfg_id           = $item['sfg_id'] ?? null;
                $quantity         = $item['quantity'] ?? 0;
                $grn_no_of_boxes  = $item['grn_no_of_boxes'] ?? 0;

                $detail_result = $this->Semi_finish_good->add_sfg_grn_detail([
                    'item_type'        => $grn_type,
                    'item_id'          => $sfg_id,
                    'quantity'         => $quantity,
                    'grn_no_of_boxes' => $grn_no_of_boxes,
                    'created_by'       => $created_by,
                    'supplier_id'      => $supplier_id,
                    'sfg_grn_id'       => $sfg_grn_id,
                    'store_id'         => $this->store_id
                ]);

                if ($detail_result->action == 1) {
                    $sfg_grn_detail_id = $detail_result->sfg_grn_detail_id;

                    $box_items = $this->input->post('box_item_' . $sfg_id);
                    
                    if (!empty($box_items)) {
                        
                        foreach ($box_items as $box_item_quantity) {
                            $box_array = [
                                'store_id'           => $this->store_id,
                                'sfg_grn_id'         => $sfg_grn_id,
                                'sfg_grn_detail_id'  => $sfg_grn_detail_id,
                                'box_no'             => 'Box no ' . $sfg_id,
                                'no_of_items'        => $box_item_quantity,
                                'item_id'            => $sfg_id,
                                'grn_type'           => $grn_type,
                                'created_by'         => $created_by
                            ];
                            $this->Semi_finish_good->add_box_detail($box_array);
                        }
                    }
                }
            }

            $this->session->set_flashdata('success_message', $sfg_grn_result->message ?: 'GRN created successfully.');
            redirect(BASE_URL . $this->page_name, 'refresh');
        } else {
            $this->session->set_flashdata('error_message', $sfg_grn_result->message ?: 'Error creating GRN.');
            redirect(BASE_URL . $this->page_name, 'refresh');
        }
    }

    // Populate vendor dropdown
    $this->supplier_option = $this->Options->supplier_option(['is_oem' => '0']);
}



    function sfg_quality_check_list()
    {
        $this->data['quality_check_list'] = $this->Semi_finish_good->list_sfg_grn(array('store_id' => $this->store_id, 'is_sfg_quality_checked' => '1'));
    }
    function sfg_quality_check()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('sfg_grn_id', 'Sfg grn id', 'trim|required');
            //$this->form_validation->set_rules('product_grn_detail_id[]', 'product_grn_detail_id', 'trim|required');
            $this->form_validation->set_rules('sfg_quality_checked_item[]', ' Sfg quality checked item', 'trim|required');
            $this->form_validation->set_rules('sfg_quality_checked_boxes[]', 'Sfg Quality checked boxes', 'trim|required');
            $this->form_validation->set_rules('purchase_price_per_item[]', 'Purchase price per item', 'trim|required');
            $this->form_validation->set_rules('no_of_boxes[]', 'No. of boxes', 'trim|required');

            if ($this->form_validation->run()) {
                $sfg_grn_id = $this->input->post('sfg_grn_id');
                $no_of_boxes = $this->input->post('no_of_boxes');
                $sfg_quality_checked_item = $this->input->post('sfg_quality_checked_item');
                $sfg_quality_checked_boxes = $this->input->post('sfg_quality_checked_boxes');
                $purchase_price_per_item = $this->input->post('purchase_price_per_item');

                $created_by = $this->user_id;

                if (!empty($po_date)) {
                    $po_date = date('Y-m-d', strtotime($po_date));
                } else {
                    $po_date = date('Y-m-d');
                }

                $sfg_grn = $this->Semi_finish_good->add_sfg_grn(
                    array(
                        'store_id' => $this->store_id,
                        'sfg_grn_id' => $sfg_grn_id,
                        'is_sfg_quality_checked' => '1',
                    )
                );
                //  pr($rm_grn);exit;
                if ($sfg_grn->action == 1) {
                    $sfg_grn_list = $this->Semi_finish_good->list_sfg_grn(
                        array(
                            'store_id' => $this->store_id,
                            'sfg_grn_id' => $sfg_grn_id,
                            'is_sfg_quality_checked' => '1',
                        )
                    );
                    $i = 0;
                    foreach ($sfg_grn_list as $obj) {
                        $sfg_grn_detail_id = (int)$obj->sfg_grn_detail_id;
                        $box_item = '';
                        $box_item = $this->input->post('box_item_' . $sfg_grn_detail_id);
                        $sfg_id = $obj->sfg_id;
                        $q_checked = $this->Semi_finish_good->add_sfg_quality_check(
                            array(
                                'sfg_grn_detail_id' => $sfg_grn_detail_id,
                                'no_of_boxes' => $no_of_boxes[$i],
                                'sfg_quality_checked_item' => $sfg_quality_checked_item[$i],
                                'sfg_quality_checked_boxes' => $sfg_quality_checked_boxes[$i],
                                'purchase_price_per_item' => $purchase_price_per_item[$i],
                                'created_by' => $created_by
                            )
                        );

                        if (!empty($box_item)) {
                            for ($j = 0; $j < count($box_item); $j++) {
                                $box_array = '';
                                $box_array = array(
                                    'store_id' => $this->store_id,
                                    'sfg_grn_id' => $sfg_grn_id,
                                    'sfg_grn_detail_id' => $sfg_grn_detail_id,
                                    'box_no' => 'Box no ' . $sfg_id,
                                    'no_of_items' => $box_item[$j],
                                    'sfg_id' => $sfg_id,
                                    'created_by' => $created_by
                                );

                                $box_detail_id = $this->Semi_finish_good->add_box_detail($box_array);
                            }
                        }
                        $i++;
                    }
                    $this->session->set_flashdata('success_message', $q_checked->message);
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                } else if ($sfg_grn->action == 0) {
                    $this->session->set_flashdata('error_message', $q_checked->message);
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                }
            }
        }
        $this->data['grn_list'] = $this->Semi_finish_good->list_sfg_grn(array('store_id' => $this->store_id));
    }
    // public function sfg_grn_detail()
    // {
    //     $sfg_grn_id = $this->input->post('sfg_grn_id');
    //     $data['sfg_grn_detail'] = $this->Semi_finish_good->list_sfg_grn(array(
    //         'store_id' => $this->store_id,
    //         'sfg_grn_id' => $sfg_grn_id
    //     ));
    //     // pr($data['sfg_grn_detail']);exit;
    //     echo $this->load->view('Backend/Semi_finish_good/sfg-grn-detail', $data, TRUE);
    // }
    public function show_input_sfg_box()
    {
        $no_of_boxes = $this->input->post('no_of_boxes');
        $item_id = $this->input->post('item_id');

        $html = '';
        for ($i = 1; $i <= $no_of_boxes; $i++) {
            $html .= '
            <div class="form-group sfg-box rm-box">
                <label class="form-label">Box Quantity ' . $i . ':</label>
                <input type="text" 
                       name="box_item_' . $item_id . '[]" 
                       id="box_item_' . $item_id . '_' . $i . '" 
                       class="form-control" 
                       placeholder="Enter Quantity" 
                       required>
            </div>';
        }

        echo $html;
    }
    public function get_sfg_hsn_code()
    {
        $item_id = $this->input->post('item_id');
        $grn_type = $this->input->post('grn_type');

        if ($grn_type === 'sfg') {
            $sfg_list = $this->Semi_finish_good->sfg_list(array(
                'store_id' => $this->store_id,
                'sfg_id' => $item_id,
                'result_type' => $this->row_type
            ));
            if (!empty($sfg_list)) {
                $hsn_code = $sfg_list->sfg_code;
                $data = array('message' => '', 'status' => 1, 'hsn_code' => $hsn_code);
            } else {
                $data = array('message' => 'Sfg not found', 'status' => 0, 'hsn_code' => 0);
            }
        } else if ($grn_type === 'rm') {
            $rm_list = $this->Raw_material->raw_material_list(array(
                'store_id' => $this->store_id,
                'raw_material_id' => $item_id,
                'result_type' => $this->row_type
            ));
            if (!empty($rm_list)) {
                $hsn_code = !empty($rm_list[0]->hsn_code) ? $rm_list[0]->hsn_code : $rm_list[0]->raw_material_code;

                $data = array('message' => '', 'status' => 1, 'hsn_code' => $hsn_code);
            } else {
                $data = array('message' => 'RM not found', 'status' => 0, 'hsn_code' => 0);
            }
        }

        echo json_encode($data);
    }

    function sfg_put_away_list()
    {
        $this->data['put_away_list'] = $this->Semi_finish_good->list_box_location(array('store_id' => $this->store_id));
    }
    function sfg_create_put_away()
    {
        if ($this->input->is_ajax_request()) {
            $box_no = trim($this->input->post('box_no'));
            $location_no = trim($this->input->post('location_no'));
            $grn_type = strtolower(trim($this->input->post('grn_type'))); // 'sfg' or 'rm'
            $created_by = $this->user_id;

            $get_item_barcode = $this->Semi_finish_good->get_item_barcode([
                'box_no' => $box_no,
                'store_id' => $this->store_id,
                'result_type' => 'row'
            ]);

            if (!$get_item_barcode) {
                echo json_encode([
                    'message' => 'Box not found.',
                    'status' => 0
                ]);
                return;
            }

            $put_away_result = $this->Semi_finish_good->create_put_away(array(
                'box_no' => $box_no,
                'location_no' => $location_no,
                'created_by' => $created_by,
                'grn_type' => $grn_type,
                'store_id' => $this->store_id,
                'result_type' => $this->result_type
            ));

            $data = $put_away_result
                ? ['message' => 'Put away saved successfully.', 'status' => 1]
                : ['message' => 'Failed to save put away.', 'status' => 0];

            echo json_encode($data);
        }
    }

    function show_product_detail()
    {
        $box_detail = [];
        $box_no = $this->input->post('box_no');
        $location_no = trim($this->input->post('location_no'));
        $store_id = $this->store_id;
        $created_by = $this->user_id;

        $box_location_detail = $this->Semi_finish_good->list_box_location([
            'box_no' => $box_no,
            'store_id' => $store_id,
            'result_type' => $this->row_type
        ]);

        $box_detail = $this->Semi_finish_good->get_item_barcode([
            'box_no' => $box_no,
            'store_id' => $store_id,
            'result_type' => $this->row_type
        ]);

        if ($box_detail) {
            echo '<div class="table-responsive">
            <table class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
                <tr>
                    <td>Item name</td>
                    <td>' . strtoupper($box_detail->item_name) . '</td>
                </tr>
                <tr>
                    <td>Material Type</td>
                    <td>' . strtoupper($box_detail->grn_type) . '</td>
                </tr>
            </table>
        </div>';

            $save_put_away = $this->Semi_finish_good->create_put_away([
                'box_no' => $box_no,
                'location_no' => $location_no,
                'created_by' => $created_by,
                'grn_type' => $box_detail->grn_type,
                'store_id' => $this->store_id,
                'result_type' => $this->result_type
            ]);

            if (!empty($save_put_away) && isset($save_put_away[0]->action)) {
                if ($save_put_away[0]->action == 1) {
                    echo '<span class="bx-success-msg" id="bx-success-msg">' . htmlspecialchars($save_put_away[0]->message) . '</span>';
                    echo '<script>
                    $("#put_away_list").append("<tr><td>' . $location_no . '</td><td>' . $box_no . '</td><td>' . strtoupper($box_detail->item_name) . '</td></tr>");
                </script>';
                } else {
                    echo '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">' . htmlspecialchars($save_put_away[0]->message) . '</span>';
                }
            } else {
                echo '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">Error processing put away.</span>';
            }
        } else {
            echo '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">No record found!</span>';
        }
    }


    public function get_grn_items()
    {
        $grn_type = $this->input->post('grn_type');

        if ($grn_type == 'sfg') {
            $data = $this->Semi_finish_good->sfg_list(['store_id' => $this->store_id]);
            $result = array_map(function ($item) {
                return ['id' => $item->sfg_id, 'name' => $item->sfg_name, 'code' => $item->sfg_code];
            }, $data);
        } elseif ($grn_type == 'rm') {
            $data = $this->Raw_material->raw_material_list(['store_id' => $this->store_id]);
            $result = array_map(function ($item) {
                return ['id' => $item->raw_material_id, 'name' => $item->raw_material_name, 'code' => $item->raw_material_code];
            }, $data);
        } elseif ($grn_type == 'fg') {
            $data = $this->Raw_material->fg_list(['store_id' => $this->store_id]);
            $result = array_map(function ($item) {
                return ['id' => $item->fg_id, 'name' => $item->fg_code];
            }, $data);
        } else {
            $result = [];
        }

        echo json_encode($result);
    }

    public function get_unit_list()
    {
        $store_id = $this->store_id;

        $units = $this->Raw_material->list_units_of_measure(['store_id' => $store_id]);

        $response = [];
        foreach ($units as $unit) {
            $response[] = [
                'unit_id' => $unit->unit_id,
                'unit' => $unit->unit
            ];
        }

        echo json_encode(['status' => 1, 'data' => $response]);
    }
}
