<?php

class ReservationDoctor extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('clinic_model',"clinic_model");
        $this->load->model('poli_model',"poli_model");
        $this->load->model('sclinic_model',"sclinic_model");
        $this->load->model('sschedule_model',"sschedule_model");
        $this->load->model('test_model',"test_model");
    }

    function index(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'reservation/reservation_clinic_list_view';
            $this->load->view('template/template', $data);
        }else if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            $this->goToListReservationClinic($clinic->clinicID);
        }
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}