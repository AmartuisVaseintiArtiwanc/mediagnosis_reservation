<?php

class Patient extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->is_logged_in();
        $this->load->library("authentication");
        $this->load->model('Patient_model',"patient_model");
        $this->load->model('SPoli_model',"spoli_model");
    }

    function getLookupPatientList(){
        $status = 'error';
        $msg = "Maaf Data Pasien Anda kosong, Silahkan cek data pasien Anda ..";

        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeAdmin($role)){
            $id = $this->security->xss_clean($this->input->post('clinic'));
            $query = $this->spoli_model->getPatientLookupData($id);
            if(isset($query) && count($query)!=0){
                $status = 'success';
                $msg = "Success";
            }
        }
        echo json_encode(array('data' => $query, 'status' => $status, 'msg' => $msg));
    }

    function test(){

    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}