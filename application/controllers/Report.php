<?php

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Report_model',"report_model");
        $this->load->model('Clinic_model',"clinic_model");
        $this->load->model('Poli_model',"poli_model");
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('Diseases_model',"diseases_model");
    }

    // REPORT Kunjungan Sehat Sakit
    function reportVisitType(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportClinicVisitType($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_visit_type_view';
            $this->load->view('template/template', $data);
            //print_r($clinic_list);
        }else{
           $this->goToErrorPage();
        }
    }
    // DETAIL REPORT Kunjungan Sehat Sakit
    function reportVisitTypeDetail($clinicID,$from,$to){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDatePost = $from;
            $endDatePost = $to;

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $clinic = $this->clinic_model->getClinicByID($clinicID);

            if(isset($clinic)){
                $report_list = $this->report_model->getReportClinicVisitTypeDetail($startDate, $endDate, $clinicID);
                $data['start_date'] = $startDatePost;
                $data['end_date'] = $endDatePost;
                $data['clinic_name'] = $clinic->clinicName;
                $data['report_detail_data']  = $report_list;
                $data['main_content'] = 'report/report_visit_type_detail_view';
                $this->load->view('template/template', $data);
                //$this->output->enable_profiler(TRUE);
            }else{
                $this->goToErrorPage();
            }
        }else{
            $this->goToErrorPage();
        }
    }

    // REPORT Kunjungan Per Klinik
    function reportClinicPoliVisit(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportClinicPoliVisit($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_clinic_poli_visit_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }
    // DETAIL REPORT Kunjungan Per Klinik
    function reportClinicPoliVisitDetail($clinicID,$poliID,$from,$to){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

       //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDatePost = $from;
            $endDatePost = $to;

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $clinic = $this->clinic_model->getClinicByID($clinicID);
            $poli = $this->poli_model->getPoliByID($poliID);

            if(isset($clinic) && isset($poli)){
                $report_list = $this->report_model->getReportClinicPoliVisitDetail($startDate, $endDate, $clinicID, $poliID);
                $data['start_date'] = $startDatePost;
                $data['end_date'] = $endDatePost;
                $data['clinic_name'] = $clinic->clinicName;
                $data['poli_name'] = $poli->poliName;
                $data['report_detail_data']  = $report_list;
                $data['main_content'] = 'report/report_clinic_poli_visit_detail_view';
                $this->load->view('template/template', $data);
                //$this->output->enable_profiler(TRUE);
            }else{
                $this->goToErrorPage();
            }
        }else{
            $this->goToErrorPage();
        }
    }

    // REPORT Kunjungan Per Dokter
    function reportDoctorVisit(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportDoctorVisit($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_doctor_visit_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }
    // DETAIL REPORT Kunjungan Per Dokter
    function reportDoctorVisitDetail($doctorID,$from,$to){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDatePost = $from;
            $endDatePost = $to;

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $doctor = $this->doctor_model->getDoctorByIdWithoutIsactive($doctorID);

            if(isset($doctor)){
                $report_list = $this->report_model->getReportDoctorVisitDetail($startDate, $endDate, $doctorID);
                $data['start_date'] = $startDatePost;
                $data['end_date'] = $endDatePost;
                $data['doctor_name'] = $doctor->doctorName;
                $data['report_detail_data']  = $report_list;
                $data['main_content'] = 'report/report_doctor_visit_detail_view';
                $this->load->view('template/template', $data);
                //$this->output->enable_profiler(TRUE);
            }else{
                $this->goToErrorPage();
            }
        }else{
            $this->goToErrorPage();
        }
    }

    // REPORT Kunjungan Per Penyakit
    function reportDiseaseVisit(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportDiseaseVisit($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_disease_visit_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }
    // DETAIL REPORT Kunjungan Per Penyakit
    function reportDiseaseVisitDetail($diseaseID,$from,$to){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDatePost = $from;
            $endDatePost = $to;

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $disease = $this->diseases_model->getDiseaseByIdWithoutIsacitve($diseaseID);

            if(isset($disease)){
                $report_list = $this->report_model->getReportDiseaseVisitDetail($startDate, $endDate, $diseaseID);
                $data['start_date'] = $startDatePost;
                $data['end_date'] = $endDatePost;
                $data['disease_name'] = $disease->diseaseName;
                $data['report_detail_data']  = $report_list;
                $data['main_content'] = 'report/report_disease_visit_detail_view';
                $this->load->view('template/template', $data);
                //$this->output->enable_profiler(TRUE);
            }else{
                $this->goToErrorPage();
            }
        }else{
            $this->goToErrorPage();
        }
    }

    // REPORT Kunjungan BPJS/UMUM
    function reportPatientType(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportPatientType($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_patient_type_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }
    // DETAIL REPORT Kunjungan BPJS/UMUM
    function reportPatientTypeDetail($clinicID,$from,$to){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDatePost = $from;
            $endDatePost = $to;

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;
                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $clinic = $this->clinic_model->getClinicByID($clinicID);

            if(isset($clinic)){
                $report_list = $this->report_model->getReportClinicVisitTypeDetail($startDate, $endDate, $clinicID);
                $data['start_date'] = $startDatePost;
                $data['end_date'] = $endDatePost;
                $data['clinic_name'] = $clinic->clinicName;
                $data['report_detail_data']  = $report_list;
                $data['main_content'] = 'report/report_patient_type_detail_view';
                $this->load->view('template/template', $data);
                //$this->output->enable_profiler(TRUE);
            }else{
                $this->goToErrorPage();
            }
        }else{
            $this->goToErrorPage();
        }
    }


    // REPORT Rating per Doctor
    function reportDoctorRating(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportDoctorRating($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_doctor_rating_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }

    // REPORT Rating per Clinic - Poli
    function reportClinicPoliRating(){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('superUserID');
        $today = date('Y-m-d', time());

        //$this->output->enable_profiler(true);
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $startDate = "";
            $endDate = "";
            $startDatePost = $this->security->xss_clean($this->input->get("from"));
            $endDatePost = $this->security->xss_clean($this->input->get("to"));

            if(empty($startDatePost) || empty($endDatePost)){
                $startDatePost = $today;
                $endDatePost = $today;

                $startDate = $today;
                $endDate =  strtotime ( '1 day' , strtotime ( $today ) );
            }else{
                $startDate = date ( 'Y-m-d' , strtotime ( $startDatePost ) );
                //END DATE + 1
                $endDate = strtotime ( '1 day' , strtotime ( $endDatePost ) ) ;
                $endDate = date ( 'Y-m-d' , $endDate );
            }

            $report_list = $this->report_model->getReportClinicRating($userID, $startDate, $endDate);
            $data['start_date'] = $startDatePost;
            $data['end_date'] = $endDatePost;
            $data['report_data']  = $report_list;
            $data['main_content'] = 'report/report_clinic_poli_rating_view';
            $this->load->view('template/template', $data);
        }else{
            $this->goToErrorPage();
        }
    }


    function goToErrorPage(){
        $data['err_msg'] = "Maaf Anda tidak dapat mengakses halaman ini..";
        $data['main_content'] = 'template/error';
        $this->load->view('template/template', $data);
    }


    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}