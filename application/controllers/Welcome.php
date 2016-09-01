<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->is_logged_in();
    }

	public function index(){
        $data['main_content'] = 'template/dashboard.php';
		$this->load->view('template/template',$data);
	}


    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}
