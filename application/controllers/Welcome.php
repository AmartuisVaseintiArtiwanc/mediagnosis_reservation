<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library("authentication");
        $this->is_logged_in();
    }

	public function index(){

        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeDoctor($role)){
            $doctor = site_url("ReservationDoctor");
            redirect($doctor, 'refresh');
        }else{
            $data['main_content'] = 'template/dashboard.php';
            $this->load->view('template/template',$data);
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
