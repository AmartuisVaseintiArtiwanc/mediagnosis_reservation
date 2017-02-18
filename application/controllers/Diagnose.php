<?php

class Diagnose extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        $this->is_logged_in_admin();
        $this->load->model('Symptomps_model',"symptomp_model");
    }

    function index(){
        $data['main_content'] = 'admin/transaction/diagnose_home_view';
        $this->load->view('admin/template/template', $data);
    }

    function getSymptompData(){
        $data = $this->symptomp_model->getSymptompList();
        echo json_encode($data);
    }


    function is_logged_in_admin(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        $role = $this->session->userdata('role');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("LoginAdmin");
            redirect($url_login, 'refresh');
        }else{
            if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
                $url_login = site_url("LoginAdmin");
                redirect($url_login, 'refresh');
            }
        }
    }
}