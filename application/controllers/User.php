<?php

class User extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->is_logged_in();
        $this->load->model('poli_model',"poli_model");
    }

    function index(){
        $data['main_content'] = 'master/poli_list_view';
        $this->load->view('template/template', $data);
    }

    function createPoli(){
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'poliName'=>$name,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('userID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->poli_model->createPoli($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Master Poli has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Poli already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function deletePoli(){
        $status = 'success';
        $msg = "Poli has been deleted successfully !";
        $id = $this->security->xss_clean($this->input->post("delID"));
        $this->poli_model->deletePoli($id);

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}