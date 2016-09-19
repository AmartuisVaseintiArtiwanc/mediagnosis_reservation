<?php

class MedicalRecord extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Main_condition_model',"main_condition_model");
        $this->load->model('Additional_condition_model',"additional_condition_model");
        $this->load->model('Diseases_model',"diseases_model");
        $this->load->model('Medication_model',"medication_model");
        $this->load->model('Support_examination_model',"support_examination_model");

    }

    function index(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'reservation/doctor/home_view';
            $this->load->view('template/template', $data);
        }else if($this->authentication->isAuthorizeDoctor($role)){

            $userID =  $this->session->userdata('userID');
            $doctor_data = $this->doctor_model->getClinicPoliDoctorByUserID($userID);

            // CREATE & CHECK RESERVATION CLINIC POLI
            $this->createHeaderReservation($doctor_data->clinicID,$doctor_data->poliID );

            $headerData = $this->test_model->getHeaderReservationDataByDoctor($doctor_data->clinicID,$doctor_data->poliID);
            $data['reversation_clinic_data']  = $headerData;
            $data['main_content'] = 'reservation/doctor/home_view';
            $this->load->view('template/template', $data);
        }
    }

    function getMainConditionList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->main_condition_model->getMainConditionAutocomplete($search);

        echo json_encode($result);
    }

    function getAdditionalConditionList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->additional_condition_model->getAdditionalConditionAutocomplete($search);

        echo json_encode($result);
    }

    function getDiseaseList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->diseases_model->getDiseaseAutocomplete($search);

        echo json_encode($result);
    }

    function getMedicationList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->medication_model->getMedicationAutocomplete($search);

        echo json_encode($result);
    }

    function getSupportExaminationColumnList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->support_examination_model->getSupportExaminationColumnAutocomplete($search);

        echo json_encode($result);
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}