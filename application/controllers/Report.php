<?php

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Report_model',"report_model");
    }

    function reportVisitType(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = $this->security->xss_clean($this->input->get("from"));
            $endDate = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDate) || empty($endDate)){
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , $startDate );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDate ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $clinic_list = $this->report_model->getReportClinicVisitType($userID, $startDate, $endDate);
            $data['report_data']  = $clinic_list;
            $data['main_content'] = 'report/report_visit_type_view';
            $this->load->view('template/template', $data);
            //print_r($clinic_list);
        }
    }

    function test(){
        $this->output->enable_profiler(true);
        $date = $this->input->post('date');
        $userID = $this->session->userdata('superUserID');
        $data = $this->report_model->getReportClinicVisitType($date);

        print_r($data);
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}