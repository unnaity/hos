<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Raw_material extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');

        $this->load->model('Raw_material_model', 'Raw_material');
        $this->load->model('Products_model', 'Products');
        $this->load->model('Semi_finish_good_model', 'Semi_finish_good');
        $this->load->model('Options_model', 'Option');
        $this->load->model('Settings_model', 'Settings');
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
        // pr($this->store_id);exit;
    }

    public function index($method)
    {
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->result_type = 'result';
        $this->row_type = 'row';
        $this->data = [];
        $this->user_id = $this->user_detail->user_id;
        $this->raw_material_id = $this->uri->segment(2);


        switch ($method) {
            case 'create-raw-material-grn':
                $this->create_raw_material_grn();
                break;
            case 'rm-grn-list':
                $this->rm_grn_list();
                break;
            case 'raw-material-list':
                $this->raw_material_list();
                break;
            case 'add-raw-material':
                $this->add_raw_material();
                break;
            case 'raw-material-delete':
                $this->raw_material_delete($this->raw_material_id);
                exit;
                break;
            case 'raw-material-edit':
                $this->raw_material_edit();exit;
                break;
            case 'edit-fg':
                $this->edit_fg();exit;
                break;
            case 'get-rm-hsn-code':
                $this->get_rm_hsn_code();
                exit;
                break;
            case 'get-rm-unit':
                $this->get_rm_unit();
                exit;
                break;
            case 'rm-quality-check-list':
                $this->rm_quality_check_list();
                break;
            case 'rm-quality-check':
                $this->rm_quality_check();
                break;
            case 'rm-grn-detail':
                $this->rm_grn_detail();
                exit;
                break;
            case 'show-input-rm-box':
                $this->show_input_rm_box();
                exit;
                break;
            case 'rm-put-away-list':
                $this->rm_put_away_list();
                break;
            case 'rm-create-put-away':
                $this->rm_create_put_away();
                break;
            case 'show-location-detail-for-rm':
                $this->show_location_detail();
                exit;
            case 'show-rm-detail':
                $this->show_product_detail();
                exit;
            case 'show-rm-input-box':
                $this->show_input_box();
                exit;
            case 'create-bom':
                $this->create_bom();
                break;
            case 'bom-list':
                $this->bom_list();
                break;
            case 'bom-detail':
                $this->fg_id = $this->uri->segment(2);
                $this->bom_detail($this->fg_id);
                break;
            case 'bom-edit':
                $this->fg_id = $this->uri->segment(2);
                $this->bom_edit($this->fg_id);
                break;
            case 'delete-bom':
                $this->fg_id = $this->uri->segment(2);
                $this->bom_delete($this->fg_id);
                break;
            case 'fg-list':
                $this->fg_list();
                break;
            case 'fg-list':
                $this->fg_list();
                break;
            case 'add-fg':
                $this->add_fg();
                break;
            case 'fg-alias':
                $this->fg_id = $this->uri->segment(2);
                $this->fg_alias($this->fg_id);
                break;
            case 'fg-alias-list':
                $this->fg_alias_list();
                break;
            case 'get-bom-items':
                $this->get_bom_items();
                exit;
                break;
            case 'delete-fg-alias':
                $this->product_alias_id = $this->uri->segment(2);
                $this->delete_fg_alias($this->product_alias_id);exit;
                break;
            case 'delete-fg':
                $this->fg_id = $this->uri->segment(2);
                $this->delete_fg($this->fg_id);exit;
                break;
            case 'delete-alias':
                $this->product_alias_id = $this->uri->segment(2);
                $this->delete_alias($this->product_alias_id);exit;
                break;
            default:
                break;
        };
        $this->load->view('Backend/' . $this->class_name . '/index', $this->data);
    }



    public function create_raw_material_grn()
    {
        $cond = array('store_id' => $this->store_id);

        if (CURRENT_MONTH < 4) {
            $this->rm_grn_no = (date('y') - 1) . '-' . date('y') . '/' . str_pad(get_last_id('rm_grn_id', 'tbl_rm_grn', $cond), 4, "0", STR_PAD_LEFT);
        } else {
            $this->rm_grn_no = date('y') . '-' . (date('y') + 1) . '/' . str_pad(get_last_id('rm_grn_id', 'tbl_rm_grn', $cond), 4, "0", STR_PAD_LEFT);
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('raw_material_id[]', 'Raw material ID', 'required');
            $this->form_validation->set_rules('quantity[]', 'Quantity', 'required');
            $this->form_validation->set_rules('no_of_boxes[]', 'No. of boxes', 'required');

            if ($this->form_validation->run()) {
                $raw_material_id = $this->input->post('raw_material_id');
                $hsn_codes = $this->input->post('hsn_code');
                $quantities = $this->input->post('quantity');
                $units = $this->input->post('inward_unit_id');
                $rates = $this->input->post('rate');
                $amounts = $this->input->post('amount');
                $after_taxes = $this->input->post('after_tax');
                $tax_ids = $this->input->post('tax_id');
                $no_of_boxes = $this->input->post('no_of_boxes');
                $po_date = $this->input->post('po_date');
                $po_no = $this->input->post('po_no');
                $supplier_id = $this->input->post('supplier_id');
                $inward_unit_id = $this->input->post('inward_unit_id');
                $additional_info = $this->input->post('additional_info');
                $created_by = $this->user_id;

                $rm_grn_result = $this->Raw_material->add_rm_grn([
                    'store_id' => $this->store_id,
                    'rm_grn_no' => $this->rm_grn_no,
                    'po_date'   => $po_date,
                    'po_no' => $po_no,
                    'additional_info' => $additional_info,
                    'supplier_id'   => $supplier_id,
                    'created_by' => $created_by
                ]);

                if ($rm_grn_result->action == 1) {
                    $rm_grn_id = $rm_grn_result->rm_grn_id;

                    for ($i = 0; $i < count($raw_material_id); $i++) {
                        $this->Raw_material->add_rm_grn_detail([
                            'rm_grn_id' => $rm_grn_id,
                            'raw_material_id' => $raw_material_id[$i],
                            'hsn_code' => $hsn_codes[$i],
                            'quantity' => $quantities[$i],
                            'unit' => $units[$i],
                            'rate' => $rates[$i],
                            'amount' => $amounts[$i],
                            'after_tax' => $after_taxes[$i],
                            'tax_id' => $tax_ids[$i],
                            'inward_unit_id' => $inward_unit_id,
                            'no_of_boxes' => $no_of_boxes[$i],
                            'created_by' => $created_by
                        ]);
                    }

                    $this->session->set_flashdata('success_message', $rm_grn_result->message);
                    redirect(BASE_URL . $this->page_name, 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', $rm_grn_result->message ?: "Some error occurred. Please try again.");
                    redirect(BASE_URL . $this->page_name, 'refresh');
                }
            }
        }
        $this->data['grn_type'] = $this->Common_model->getAll('tbl_grn_type', ['status' => '1']);
        $this->supplier_option = $this->Options->supplier_option(['is_oem' => '0']);
        $this->data['rm'] = $this->Raw_material->raw_material_list(['store_id' => $this->store_id]);

        $this->data['tax_rate_list'] = $this->Settings->list_tax_rate([]);
    }

    function raw_material_list()
    {
        $this->raw_material_list = $this->Raw_material->raw_material_list(array('store_id' => $this->store_id));
        // pr($this->raw_material_list );exit;
    }
    function add_raw_material()
    {

        if ($this->input->post()) {
            $this->form_validation->set_rules('raw_material_name', 'Raw material name', 'trim|required');
            $this->form_validation->set_rules('raw_material_code', 'Raw material code', 'trim|required');
            $this->form_validation->set_rules('sustainability_score', 'Sustainability score', 'trim');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $this->form_validation->set_rules('weight', 'Weight', 'trim|required');
            $this->form_validation->set_rules('unit_id', 'Unit Id', 'trim|required');
            $this->form_validation->set_rules('additional_info', 'additional info', 'trim');

            if ($this->form_validation->run()) {

                $category_id = $this->input->post('category_id');
                $size = $this->input->post('size_id');
                $unit_id = $this->input->post('unit_id');
                $hsn_code = $this->input->post('hsn_code');
                $min_level = $this->input->post('min_level');
                $max_level = $this->input->post('max_level');
                $inward_unit_id = $this->input->post('inward_unit_id');
                $outward_unit_id = $this->input->post('outward_unit_id');
                $wastage = $this->input->post('wastage');
                $raw_material_code = $this->input->post('raw_material_code');
                $sustainability_score = $this->input->post('sustainability_score');
                $weight = $this->input->post('weight');
                $price = $this->input->post('price');
                $raw_material_name = $this->input->post('raw_material_name');
                $additional_info = $this->input->post('additional_info');

                $raw_material_detail = $this->Raw_material->add_raw_material(
                    array(
                        'store_id' => $this->store_id,
                        'category_id' => $category_id,
                        'min_level' => $min_level,
                        'max_level' => $max_level,
                        'inward_unit_id' => $inward_unit_id,
                        'size' => $size,
                        'hsn_code' => $hsn_code,
                        'outward_unit_id' => $outward_unit_id,
                        'wastage' => $wastage,
                        'weight' => $weight,
                        'price' => $price,
                        'unit_id' => $unit_id,
                        'sustainability_score' => $sustainability_score,
                        'raw_material_name' => $raw_material_name,
                        'raw_material_code' => $raw_material_code,
                        'raw_material_id' => $this->raw_material_id,
                        'additional_info' => htmlentities($additional_info),
                        'created_by' => $this->user_id
                    )
                );
                if ($raw_material_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $raw_material_detail->message);
                    redirect(BASE_URL . 'raw-material-list', 'refresh');
                } else if ($raw_material_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $raw_material_detail->message);
                    redirect(BASE_URL . 'raw-material-list', 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'raw-material-list', 'refresh');
                }
            }
        }

        $this->uom_option = $this->Options->uom_option(array('store_id' => $this->store_id));
    }
    function raw_material_delete($raw_material_id)
    {
        $this->raw_material_list = $this->Raw_material->raw_material_list(array(
            'raw_material_id' => $raw_material_id,
            'store_id' => $this->store_id,
            'row' => $this->row_type
        ));
        // pr($this->raw_material_list);exit;
        if (!empty($this->raw_material_list)) {
            $raw_material_detail = $this->Raw_material->add_raw_material(
                array(
                    'raw_material_id' => $raw_material_id,
                    'store_id' => $this->store_id,
                    'is_deleted' => '1'
                )
            );
            if ($raw_material_detail->action == 1) {
                $this->session->set_flashdata('success_message', $raw_material_detail->message);
            } else if ($raw_material_detail->action == 0) {
                $this->session->set_flashdata('error_message', $raw_material_detail->message);
            } else {
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }
        } else {
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }
        redirect(BASE_URL . 'raw-material-list', 'refresh');
    }

    function raw_material_edit()
    {
        if ($this->input->post()) {
            // pr($this->input->post());
            $this->form_validation->set_rules('rm_name', 'Rm name', 'trim|required');
            $this->form_validation->set_rules('rm_code', 'Rm code', 'trim|required');
            $this->form_validation->set_rules('s_score', 'S score', 'trim');
            $this->form_validation->set_rules('_price', 'Price', 'trim|required');
            $this->form_validation->set_rules('unit_weight', 'Unit Weight', 'trim|required');
            $this->form_validation->set_rules('unit', 'Unit Id', 'trim|required');

            if ($this->form_validation->run()) {

                $category_id = $this->input->post('category_id');
                $raw_material_id = $this->input->post('raw_material_id');
                $size = $this->input->post('size_id');
                $hsn_code = $this->input->post('hsn_code');
                $unit_id = $this->input->post('unit');
                $min_level = $this->input->post('min_level');
                $max_level = $this->input->post('max_level');
                $inward_unit_id = $this->input->post('inward_unit_id');
                $outward_unit_id = $this->input->post('outward_unit_id');
                $wastage = $this->input->post('wastage');
                $raw_material_code = $this->input->post('rm_code');
                $sustainability_score = $this->input->post('s_score');
                $weight = $this->input->post('unit_weight');
                $raw_material_name = $this->input->post('rm_name');
                $price = $this->input->post('_price');
                $additional_info = $this->input->post('additional_info');

                $raw_material_detail = $this->Raw_material->add_raw_material(
                    array(
                        'store_id' => $this->store_id,
                        'category_id' => $category_id,
                        'min_level' => $min_level,
                        'max_level' => $max_level,
                        'inward_unit_id' => $inward_unit_id,
                        'size' => $size,
                        'price' => $price,
                        'hsn_code' => $hsn_code,
                        'outward_unit_id' => $outward_unit_id,
                        'wastage' => $wastage,
                        'unit_id' => $unit_id,
                        'raw_material_id' => $raw_material_id,
                        'weight' => $weight,
                        'sustainability_score' => $sustainability_score,
                        'raw_material_name' => $raw_material_name,
                        'raw_material_code' => $raw_material_code,
                        'additional_info' => htmlentities($additional_info),
                        'created_by' => $this->user_id
                    )
                );
                // echo $this->db->last_query();exit;

                if ($raw_material_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $raw_material_detail->message);
                } else if ($raw_material_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $raw_material_detail->message);
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
            }
        }
        $this->raw_material_list = $this->Raw_material->raw_material_list(
            array(
                'raw_material_id' => $raw_material_id,
                'result_type' => $this->row_type
            )
        );
        redirect(BASE_URL . 'raw-material-list', 'refresh');
    }

    function get_rm_hsn_code()
    {
        $raw_material_id = $this->input->post('raw_material_id');
        $raw_material_list = $this->Raw_material->raw_material_list(array(
            'store_id' => $this->store_id,
            'raw_material_id' => $raw_material_id,
            'result_type' => $this->row_type
        ));
        // pr($raw_material_list);
        if (!empty($raw_material_list)) {
            $hsn_code = $raw_material_list->hsn_code;
            $data = array('message' => '', 'status' => 1, 'hsn_code' => $hsn_code);
        } else {
            $data = array('message' => 'Raw material not found', 'status' => 0, 'hsn_code' => 0);
        }
        echo json_encode($data);
    }
    function get_rm_unit()
    {
        $raw_material_id = $this->input->post('raw_material_id');
        $raw_material_list = $this->Raw_material->raw_material_list(array(
            'store_id' => $this->store_id,
            'raw_material_id' => $raw_material_id,
            'result_type' => $this->row_type
        ));
        // pr($raw_material_list);
        if (!empty($raw_material_list)) {
            $inward_unit_id = $raw_material_list->unit;
            $data = array('message' => '', 'status' => 1, 'inward_unit_id' => $inward_unit_id);
        } else {
            $data = array('message' => 'Raw material not found', 'status' => 0, 'inward_unit_id' => 0);
        }
        echo json_encode($data);
    }
    function rm_grn_list()
    {
        $this->rm_grn_list = $this->Raw_material->list_rm_grn(array('store_id' => $this->store_id));
        $this->data['rm_grn_list'] = $this->rm_grn_list;
    }
    public function rm_quality_check_list()
    {
        $this->data['quality_check_list'] = $this->Raw_material->list_rm_grn(array('store_id' => $this->store_id, 'is_rm_quality_checked' => '1'));
    }
    function rm_quality_check()
    {

        if ($this->input->post()) {
            $this->form_validation->set_rules('rm_grn_id', 'Rm grn id', 'trim|required');
            //$this->form_validation->set_rules('product_grn_detail_id[]', 'product_grn_detail_id', 'trim|required');
            $this->form_validation->set_rules('rm_quality_checked_item[]', ' Rm quality checked item', 'trim|required');
            $this->form_validation->set_rules('rm_quality_checked_boxes[]', 'Rm Quality checked boxes', 'trim|required');
            $this->form_validation->set_rules('purchase_price_per_item[]', 'Purchase price per item', 'trim|required');
            $this->form_validation->set_rules('no_of_boxes[]', 'No. of boxes', 'trim|required');

            if ($this->form_validation->run()) {
                $rm_grn_id = $this->input->post('rm_grn_id');
                $no_of_boxes = $this->input->post('no_of_boxes');
                $rm_quality_checked_item = $this->input->post('rm_quality_checked_item');
                $rm_quality_checked_boxes = $this->input->post('rm_quality_checked_boxes');
                $purchase_price_per_item = $this->input->post('purchase_price_per_item');

                $created_by = $this->user_id;

                if (!empty($po_date)) {
                    $po_date = date('Y-m-d', strtotime($po_date));
                } else {
                    $po_date = date('Y-m-d');
                }

                $rm_grn = $this->Raw_material->add_rm_grn(
                    array(
                        'store_id' => $this->store_id,
                        'rm_grn_id' => $rm_grn_id,
                        'is_rm_quality_checked' => '1',
                    )
                );
                //  pr($rm_grn);exit;
                if ($rm_grn->action == 1) {
                    $rm_grn_list = $this->Raw_material->list_rm_grn(
                        array(
                            'store_id' => $this->store_id,
                            'rm_grn_id' => $rm_grn_id,
                            'is_rm_quality_checked' => '1',
                        )
                    );
                    $i = 0;
                    foreach ($rm_grn_list as $obj) {
                        $rm_grn_detail_id = (int)$obj->rm_grn_detail_id;
                        $box_item = '';
                        $box_item = $this->input->post('box_item_' . $rm_grn_detail_id);
                        $rm_id = $obj->rm_id;
                        $q_checked = $this->Raw_material->add_rm_quality_check(
                            array(
                                'rm_grn_detail_id' => $rm_grn_detail_id,
                                'no_of_boxes' => $no_of_boxes[$i],
                                'rm_quality_checked_item' => $rm_quality_checked_item[$i],
                                'rm_quality_checked_boxes' => $rm_quality_checked_boxes[$i],
                                'purchase_price_per_item' => $purchase_price_per_item[$i],
                                'created_by' => $created_by
                            )
                        );

                        if (!empty($box_item)) {
                            for ($j = 0; $j < count($box_item); $j++) {
                                $box_array = '';
                                $box_array = array(
                                    'store_id' => $this->store_id,
                                    'rm_grn_id' => $rm_grn_id,
                                    'rm_grn_detail_id' => $rm_grn_detail_id,
                                    'box_no' => 'Box no ' . $rm_id,
                                    'no_of_items' => $box_item[$j],
                                    'rm_id' => $rm_id,
                                    'created_by' => $created_by
                                );

                                $box_detail_id = $this->Raw_material->add_box_detail($box_array);
                            }
                        }
                        $i++;
                    }
                    $this->session->set_flashdata('success_message', $q_checked->message);
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                } else if ($rm_grn->action == 0) {
                    $this->session->set_flashdata('error_message', $q_checked->message);
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    // redirect(BASE_URL . $this->page_name, 'refresh');
                }
            }
        }
        $this->data['grn_list'] = $this->Raw_material->list_rm_grn(array('store_id' => $this->store_id));
    }
    public function rm_grn_detail()
    {
        $rm_grn_id = $this->input->post('rm_grn_id');
        $data['rm_grn_detail'] = $this->Raw_material->list_rm_grn(array(
            'store_id' => $this->store_id,
            'rm_grn_id' => $rm_grn_id,
            'rm_grn_id' => $rm_grn_id
        ));
        // pr($data['rm_grn_detail']);exit;
        echo $this->load->view('Backend/Raw_material/rm-grn-detail', $data, TRUE);
    }
    function show_input_rm_box()
    {
        $this->no_of_boxes = $this->input->post('no_of_boxes');
        $this->rm_grn_detail_id = $this->input->post('rm_grn_detail_id');
    }
    function show_location_detail()
    {
        $location_detail = [];
        $location_no = $this->input->post('location_no');
        $location_detail = $this->Settings->list_location(array('location_no' => $location_no, 'store_id' => $this->store_id, 'result_type' => $this->row_type));
        if ($location_detail) {
            echo json_encode($location_detail);
        }
    }
    function rm_put_away_list()
    {
        $this->data['put_away_list'] = $this->Products->put_away_list(array('store_id' => $this->store_id));
    }
    function rm_create_put_away()
    {
        if ($this->input->is_ajax_request()) {
            $box_no = trim($this->input->post('box_no'));
            $location_no = trim($this->input->post('location_no'));
            $created_by = $this->user_id;
            $put_away_result = $this->Raw_material->create_put_away(array(
                'box_no' => $box_no,
                'location_no' => $location_no,
                'created_by' => $created_by,
                'result_type' => $this->result_type
            ));

            $data = $put_away_result
                ? array('message' => 'Put away saved successfully.', 'status' => 1)
                : array('message' => 'Failed to save put away.', 'status' => 0);
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

        $box_location_detail = $this->product_list = $this->Raw_material->list_box_location(array('box_no' => $box_no, 'store_id' => $this->store_id, 'result_type' => $this->row_type));

        $box_detail = $this->product_list = $this->Raw_material->list_box(array('box_no' => $box_no, 'store_id' => $this->store_id, 'result_type' => $this->row_type));
        if (isset($box_location_detail->box_no) && $box_location_detail->box_no > 0) {
            echo $box_detail_html = '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">Put away process already completed for this box!</span>';
        } else {
            if ($box_detail) {
                echo $box_detail_html = '<div class="table-responsive">
                    <table class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
                        <tr>
                            <td>RM name</td>
                            <td>' . $box_detail->raw_material_name . '</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>' . $box_detail->category_name . '</td>
                        </tr>                        
                    </table>
                </div>';
                $put_away_result = $this->Raw_material->create_put_away(array(
                    'box_no' => $box_no,
                    'location_no' => $location_no,
                    'created_by' => $created_by,
                    'result_type' => $this->result_type
                ));
            } else {
                echo $box_detail_html = '<span class="parsley-errors-list bx-error-msg" id="bx-error-msg">No record found!</span>';
            }
        }
    }
    function show_input_box()
    {
        $this->no_of_boxes = $this->input->post('no_of_boxes');
        $this->rm_grn_detail_id = $this->input->post('rm_grn_detail_id');
    }

    function create_bom()
    {
        if ($this->input->post()) {

            $this->form_validation->set_rules('bom_id[]', 'Bom id', 'trim|required');
            $this->form_validation->set_rules('grn_type[]', 'Grn type', 'trim|required');
            $this->form_validation->set_rules('item_id[]', 'Item', 'trim|required');
            $this->form_validation->set_rules('sfg_qty[]', 'Sfg qty', 'trim|required');

            if ($this->form_validation->run()) {

                $bom_id     = $this->input->post('bom_id');
                $grn_type   = $this->input->post('grn_type');
                $item_id    = $this->input->post('item_id');
                $sfg_qty    = $this->input->post('sfg_qty');
                $created_by = $this->user_id;

                $inserted_keys = [];

                for ($i = 0; $i < count($bom_id); $i++) {

                    $key = $bom_id[$i] . '-' . $grn_type[$i] . '-' . $item_id[$i];
                    if (in_array($key, $inserted_keys)) {
                        continue;
                    }

                    $inserted_keys[] = $key;

                    $this->Raw_material->add_bom(
                        array(
                            'store_id'   => $this->store_id,
                            'bom_id'     => $bom_id[$i],
                            'grn_type'   => $grn_type[$i],
                            'item_id'    => $item_id[$i],
                            'sfg_qty'    => $sfg_qty[$i],
                            'created_by' => $created_by
                        )
                    );
                }

                $this->session->set_flashdata('success_message', "BOM saved successfully!");
                redirect(BASE_URL . 'create-bom', 'refresh');
            }
        }

        $this->data['fg_list']  = $this->Raw_material->fg_list_dropdown(array('store_id' => $this->store_id , 'is_bom' => '0'));
        // pr($this->data['fg_list']);exit;
        $this->data['sfg_list'] = $this->Semi_finish_good->sfg_list(array('store_id' => $this->store_id));
    }


    function bom_list()
    {
        $this->data['bom_list'] = $this->Raw_material->bom_list(array('store_id' => $this->store_id));
        // pr($this->data['bom_list']);exit;

    }
    function bom_detail($fg_id)
    {
        $this->data['bom_list_detail'] = $this->Raw_material->bom_detail(array('store_id' => $this->store_id, 'fg_id' => $fg_id));
        // pr($this->data['bom_list_detail']);exit;

    }
    function fg_list()
    {
        $this->fg_list = $this->Raw_material->fg_list(array('store_id' => $this->store_id));
    }
    function add_fg()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fg_code', 'Fg code', 'trim|required');
            $this->form_validation->set_rules('sales_qty', 'Sales qty', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');

            if ($this->form_validation->run()) {

                $fg_code = $this->input->post('fg_code');
                $sales_qty = $this->input->post('sales_qty');
                $description = $this->input->post('description');
                if ($sales_qty == 1) {
                $sales_qty_name = 'Pcs';
                    } elseif ($sales_qty == 2) {
                        $sales_qty_name = 'Pair';
                    } elseif ($sales_qty == 3) {
                        $sales_qty_name = 'Set';
                    }

                $fg_detail = $this->Raw_material->add_fg(
                    array(
                        'store_id' => $this->store_id,
                        'fg_code' => $fg_code,
                        'sales_qty' => $sales_qty_name,
                        'description' => $description,
                        'created_by' => $this->user_id
                    )
                );

                if ($fg_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $fg_detail->message);
                    redirect(BASE_URL . 'fg-list', 'refresh');
                } else if ($fg_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $fg_detail->message);
                    redirect(BASE_URL . 'fg-list', 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    redirect(BASE_URL . 'fg-list', 'refresh');
                }
            }
        }
    }
    function edit_fg()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('fgs_code', 'Fg code', 'trim|required');
            $this->form_validation->set_rules('fg_sales_qty', 'Fg Sales qty', 'trim|required');
            $this->form_validation->set_rules('fgs_description', 'Fg Description', 'trim|required');

            if ($this->form_validation->run()) {

                $fg_code = $this->input->post('fgs_code');
                $sales_qty = $this->input->post('fg_sales_qty');
                $description = $this->input->post('fgs_description');
                $fg_id = $this->input->post('fg_id');
                 if ($sales_qty == 1) {
                $sales_qty_name = 'Pcs';
                    } elseif ($sales_qty == 2) {
                        $sales_qty_name = 'Pair';
                    } elseif ($sales_qty == 3) {
                        $sales_qty_name = 'Set';
                    }
                $fg_detail = $this->Raw_material->add_fg(
                    array(
                        'store_id' => $this->store_id,
                        'fg_code' => $fg_code,
                        'fg_id' => $fg_id,
                        'sales_qty' => $sales_qty_name,
                        'description' => $description,
                        'created_by' => $this->user_id
                    )
                );

                if ($fg_detail->action == 1) {
                    $this->session->set_flashdata('success_message', $fg_detail->message);
                } else if ($fg_detail->action == 0) {
                    $this->session->set_flashdata('error_message', $fg_detail->message);
                } else {
                    $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                }
            }
        }
        $this->fg_list = $this->Raw_material->fg_list(array('store_id' => $this->store_id,'fg_id' => $fg_id));
        redirect(BASE_URL . 'fg-list', 'refresh');
    }
        
    public function get_bom_items()
    {
        $bom_type = $this->input->post('bom_type');

        if ($bom_type == 'sfg') {
            $data = $this->Semi_finish_good->sfg_list(['store_id' => $this->store_id]);
            $result = array_map(function ($bom) {
                return ['id' => $bom->sfg_id, 'name' => $bom->sfg_name];
            }, $data);
        } elseif ($bom_type == 'fg') {
            $data = $this->Raw_material->fg_list(['store_id' => $this->store_id]);
            $result = array_map(function ($bom) {
                return ['id' => $bom->fg_id, 'name' => $bom->fg_code];
            }, $data);
        } else {
            $result = [];
        }

        echo json_encode($result);
    }
    public function fg_alias($fg_id)
    {

        if ($this->input->post()) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('category_id', 'Category', 'trim');
                $this->form_validation->set_rules('product_id', 'Product', 'trim');
                $this->form_validation->set_rules('fg_id', 'Fg id', 'trim|required');
                $this->form_validation->set_rules('new_prod_name', 'Product alias name', 'trim|required');
                $this->form_validation->set_rules('fg_prod_name', 'Fg alias name', 'trim');

                if ($this->form_validation->run()) {

                    $category_id = $this->input->post('category_id');
                    $product_id = $this->input->post('product_id');
                    $fg_id = $this->input->post('fg_id');
                    $supplier_id= $this->input->post('supplier');
                    $product_name = $this->input->post('new_prod_name');
                    $fg_prod_name = $this->input->post('fg_prod_name');

                    $product_detail = $this->Raw_material->add_product_alias(
                        array(
                            'category_id' => $category_id,
                            'store_id' => $this->store_id,
                            'product_id' => $product_id,
                            'fg_id' => $fg_id,
                            'supplier_id' => $supplier_id,
                            'fg_prod_name' => $fg_prod_name,
                            'product_name' => $product_name,
                            'created_by' => $this->user_id,
                            'result_type' => $this->row_type
                        )
                    );

                    if ($product_detail->action == 1) {
                        $this->session->set_flashdata('success_message', $product_detail->message);
                    } else if ($product_detail->action == 0) {
                        $this->session->set_flashdata('error_message', $product_detail->message);
                    } else {
                        $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
                    }
                    //redirect(BASE_URL . 'product-alias/'.$product_id, 'refresh');
                }
            }
        }
        $this->fg_id    = $fg_id;
        $this->fg_list = $this->Raw_material->fg_list(array('store_id' => $this->store_id));

        
            $this->product_alias_list = $this->Raw_material->product_alias_list(array('fg_id' => $fg_id, 'store_id' => $this->store_id));
            // pr($this->product_alias_list);exit;
            $this->supplier_option = $this->Options->supplier_option(['is_oem' => '0']);
        
    }
    function bom_edit($fg_id)
    {
        $this->fg_id = $fg_id;
        if ($this->input->post()) {

            $this->form_validation->set_rules('bom_id[]', 'Bom id', 'trim|required');
            $this->form_validation->set_rules('grn_type[]', 'Grn type', 'trim|required');
            $this->form_validation->set_rules('item_id[]', 'Item', 'trim|required');
            $this->form_validation->set_rules('sfg_qty[]', 'Sfg qty', 'trim|required');

            if ($this->form_validation->run()) {

                $bom_id     = $this->input->post('bom_id');
                $grn_type   = $this->input->post('grn_type');
                $item_id    = $this->input->post('item_id');
                $sfg_qty    = $this->input->post('sfg_qty');
                $created_by = $this->user_id;

                $delete_sales_order = $this->Raw_material->delete_bom(array('fg_id' => $fg_id));

                $inserted_keys = [];

                for ($i = 0; $i < count($bom_id); $i++) {

                    $key = $bom_id[$i] . '-' . $grn_type[$i] . '-' . $item_id[$i];
                    if (in_array($key, $inserted_keys)) {
                        continue;
                    }

                    $inserted_keys[] = $key;

                    $this->Raw_material->add_bom(
                        array(
                            'store_id'   => $this->store_id,
                            'bom_id'     => $bom_id[$i],
                            'grn_type'   => $grn_type[$i],
                            'item_id'    => $item_id[$i],
                            'sfg_qty'    => $sfg_qty[$i],
                            'created_by' => $created_by
                        )
                    );
                }

                $this->session->set_flashdata('success_message', "BOM saved successfully!");
                redirect(BASE_URL . 'bom-edit/' . $this->fg_id, 'refresh');
            }
            
        }
        
            $this->fg_list  = $this->Raw_material->fg_list(array('store_id' => $this->store_id ));
            // pr($this->data['fg_list']);exit;
            $this->bom_list_detail = $this->Raw_material->bom_detail(array('store_id' => $this->store_id, 'fg_id' => $fg_id));
            // echo $this->db->last_query();exit;
            // pr($this->bom_list_detail);exit;
    }
    function delete_fg_alias($product_alias_id){
        $product_alias_detail = $this->Raw_material->product_alias_list(array('product_alias_id'=>$product_alias_id,
                                                                          'store_id' => $this->store_id,
                                                                          'result_type'=>$this->row_type
                                                                        )
                                                                    );
        
        if(!empty($product_alias_detail)){
            $product_detail = $this->Raw_material->add_product_alias(array('product_alias_id'=>$product_alias_id,
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
        redirect(BASE_URL . 'fg-alias/'.$product_alias_detail->fg_id, 'refresh');
    }
    function delete_alias($product_alias_id){
        $product_alias_detail = $this->Raw_material->fg_alias_list(array('product_alias_id'=>$product_alias_id,
                                                                          'store_id' => $this->store_id,
                                                                          'result_type'=>$this->row_type
                                                                        )
                                                                    );
        
        if(!empty($product_alias_detail)){
            $product_detail = $this->Raw_material->update_delete_alias(array('product_alias_id'=>$product_alias_id,
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
        redirect(BASE_URL . 'fg-alias-list', 'refresh');
    }
    function bom_delete($fg_id)
    {
        $this->fg_id = $fg_id;
       $bom_list = $this->Raw_material->bom_list(array('store_id' => $this->store_id,'fg_id' => $fg_id,'$result_type' => $this->result_type,));
    //    echo $this->db->last_query();exit;
        // pr($bom_list);exit;

        if (!empty($bom_list)) {
            $bom_detail = $this->Raw_material->add_bom(
                array(
                    'bom_id' => $fg_id,
                    'is_deleted' => '1',
                    'result_type' => $this->result_type,
                )
            );
            // echo $this->db->last_query();exit;
            if ($bom_detail->action == 1) {
                $this->session->set_flashdata('success_message', $bom_detail->message);
            } else if ($bom_detail->action == 0) {
                $this->session->set_flashdata('error_message', $bom_detail->message);
            } else {
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }
        } else {
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }
        redirect(BASE_URL . 'bom-list', 'refresh');
    }
    function fg_alias_list(){
        $this->data['fg_alias_list'] = $this->Raw_material->fg_alias_list(array('store_id' => $this->store_id));
        // pr($this->data['fg_alias_list']);exit;
    }

    
}