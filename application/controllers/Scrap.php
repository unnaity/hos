<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scrap extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_loggedin') != 1)
            redirect(BASE_URL . 'login', 'refresh');
        $this->load->model('Scrap_model', 'Scrap');
        $this->load->model('Pick_list_model', 'Pick_list');
        $this->user_detail = $this->session->userdata('user_detail');
        $this->branch_id = $this->session->userdata('branch_id');
        $this->store_id = $this->session->userdata('store_id');
    }

    public function index($method)
    {
        $this->class_name = $this->router->fetch_class();
        $this->page_name = $method;
        $this->result_type = 'row';
        $this->data = [];
        $this->user_id = $this->user_detail->user_id;
        $this->scrap_id = $this->uri->segment(2);

        switch ($method) {
            case 'scrap-list':
                $this->scrap_list();
                break;
            case 'create-scrap':
                $this->create_scrap();
                break;
            case 'get-box-detail':
                $this->get_box_detail();
                exit;
                break;
            case 'save-scrap-detail':
                $this->save_scrap_detail();
                exit;
                break;
            case 'delete-scrap-detail':
                $this->delete_scrap_detail();
                exit;
                break;
            case 'delete-scrap':
                $this->delete_scrap($this->scrap_id);
                exit;
                break;
            default:
                break;
        };
        $this->load->view('Backend/' . $this->class_name . '/index', $this->data);
    }
    function scrap_list()
    {
        $this->scrap_list = $this->Scrap->scrap_list(array());
        // print_r($this->scrap_list);
    }

    function create_scrap() {}
    function get_box_detail()
    {
        $scrap_box_no = $this->input->post('scrap_box_no');
        $box_detail_html = '';
        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'pick_list_box_no' => $scrap_box_no, 'result_type' => $this->result_type));
        $data = [];

        if (!empty($pick_list_boxes)) {

            $box_detail_html = '<div class="col-md-12 rm_cls"><h4>Box Detail: </h4></div>
                
                <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Qty in Stock: </label><input class="form-control" name="remaining_item" id="remaining_item" value="' . $pick_list_boxes->remaining_item . '" readonly type="text"></div></div>
                
                <div class="col-md-12 rm_cls"><div class="form-group"><label class="form-label">Quantity: <span class="tx-danger">*</span></label><input class="form-control" name="qty" id="qty" value="" type="number" min="1" required></div></div>

                <div class="col-md-12 rm_cls"><label class="form-label"></label><button class="btn-sm ripple btn-secondary add_scrap_list_btn" type="button" onclick="add_to_scrap_list()">Save Scrap List</button></div>';
            $data = array('message' => 'Scrap details are saved', 'status' => 1, 'data' => $box_detail_html);
        } 
        else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        echo json_encode($data);
    }
    function save_scrap_detail()
    {
        $scrap_box_no = $this->input->post('scrap_box_no');
        $remaining_item = $this->input->post('remaining_item');
        $scrap_qty = $this->input->post('scrap_qty');
        $remark = $this->input->post('remark');
        $box_detail_html = '';
        $pick_list_boxes = $this->Pick_list->box_list(array('store_id' => $this->store_id, 'pick_list_box_no' => $scrap_box_no, 'result_type' => $this->result_type));
        $data = [];
        if (!empty($pick_list_boxes)) {
            $add_scrap_list_item = $this->Scrap->add_scrap_list_item(
                array(
                    'scrap_box_no' => $scrap_box_no,
                    'remaining_item' => $remaining_item,
                    'scrap_qty' => $scrap_qty,
                    'remark' => $remark,
                    'created_by' => $this->user_id,
                    'result_type' => $this->result_type
                )
            );

            $box_detail_html = '<tr id="tr_' . $add_scrap_list_item->scrap_id . '"><td>' . $pick_list_boxes->product_name . '</td>
                    <td>' . $pick_list_boxes->box_no . '</td>
                    <td>' . $pick_list_boxes->location_name . '</td>
                    <td>' . $scrap_qty . '</td>
                    <td><button type="button" name="remove" onclick="delete_tr(' . $add_scrap_list_item->scrap_id . ')" class="btn  btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button></td>
                    </tr>';
            $data = array('message' => '', 'status' => 1, 'data' => $box_detail_html);
        } else {
            $data = array('message' => 'Scanned box is not valid.', 'status' => 0);
        }
        echo json_encode($data);
    }
    function delete_scrap_detail()
    {
        $scrap_id = $this->input->post('scrap_id');
        if (!empty($scrap_id)) {
            $delete_scrap_list = $this->Scrap->delete_scrap_list(array('scrap_id' => $scrap_id));
            // echo $this->db->last_query();exit;
            $data = array('message' => 'Scrap is deleted', 'is_deleted' => 1, 'data' => $delete_scrap_list);
        } else {
            $data = array('message' => 'Scrap is not deleted', 'is_deleted' => 0);
        }
        echo json_encode($data);
    }

    function delete_scrap($scrap_id){

        $this->scrap_list = $this->Scrap->scrap_list(array('scrap_id'=>$scrap_id,
                                                             'result_type' => $this->result_type)
                                                            );
        if(!empty($this->scrap_list)){
            $scrap_detail = $this->Scrap->add_scrap_list_item(array('scrap_id'=>$scrap_id,
                                                                 'is_deleted' => '1'
                                                                )
                                                          ); 
            if($scrap_detail->action == 1){                
                $this->session->set_flashdata('success_message', $scrap_detail->message);                
            }else if($scrap_detail->action == 0){
                $this->session->set_flashdata('error_message', $scrap_detail->message);
            }else{
                $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
            }         
        }else{
            $this->session->set_flashdata('error_message', "Some error occured. Please try after sometime.");
        }   
        redirect(BASE_URL . 'scrap-list', 'refresh');
    }
}
