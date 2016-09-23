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
        $this->load->model('Medical_record_model',"medical_record_model");
        $this->load->model('Medical_record_detail_model',"medical_record_detail_model");

    }

    function index(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeDoctor($role)){

            $userID =  $this->session->userdata('userID');
            $doctor_data = $this->doctor_model->getClinicPoliDoctorByUserID($userID);

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

    function saveMedicalRecordData(){
        $data = $this->input->post('data');

        $datetime = date('Y-m-d H:i:s', time());
        $detail_reservation = $data[0]['detail_reservation'];
        $patient = $data[0]['patient'];

        //TRANSACTION
        $this->db->trans_begin();
        $mr_data=array(
            'detailReservationID'=>$detail_reservation,
            'patientID'=>$patient,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('userID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $header_data = $this->medical_record_model->createMedicalRecordHeader($mr_data);

        // CONDITION / KELUHAN
        // MAIN CONDITION
        $main_condition = $data[0]['main_condition'];
        $check_main_condition = $this->main_condition_model->checkMainCondition($main_condition);
        if(isset($check_main_condition->id)){
            $main_condition =  $check_main_condition->id;
        }else{
            $mc_data=array(
                'mainConditionText'=>$main_condition,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $main_condition = $this->main_condition_model->createMainCondition($mc_data);
        }

        // WORKING DIAGNOSE
        $working_diagnose = $data[0]['working_diagnose'];
        $check_working_diagnose = $this->diseases_model->checkDisease($working_diagnose);
        if(isset($check_working_diagnose->id)){
            $working_diagnose =  $check_working_diagnose->id;
        }else{
            $dis_data=array(
                'diseaseName'=>$working_diagnose,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $working_diagnose = $this->diseases_model->createDisease($dis_data);
        }

        // SAVE MEDICAL RECORD DETAIL
        $condition_date = $data[0]['condition_date'];
        $reference = $data[0]['rujukan'];

        $mr_detail_data=array(
            'medicalRecordID'=>$header_data,
            'mainCondition'=>$main_condition,
            'workingDiagnose'=>$working_diagnose,
            'conditionDate'=>$condition_date,
            'reference'=>$reference,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('userID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->medical_record_detail_model->createMedicalRecordDetail($mr_detail_data);

        //ADDITIONAL CONDITION
        foreach($data[0]['additional_condition'] as $row){
            $additional_condition = $row['value'];
            $check_additional_condition = $this->additional_condition_model->checkAdditionalCondition($additional_condition);
            if(isset($check_additional_condition->id)){
                $additional_condition =  $check_additional_condition->id;
            }else{
                $ac_data=array(
                    'additionalConditionText'=>$additional_condition,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $additional_condition = $this->additional_condition_model->createAdditionalCondition($ac_data);
            }

            $mrd_data=array(
                'medicalRecordID'=>$header_data,
                'additionalConditionID'=>$additional_condition,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $this->medical_record_detail_model->createMedicalRecordDetailAdditonalCondition($mrd_data);
        }

        // SUPPORT DIAGNOSA
        foreach($data[0]['support_diagnose'] as $row){
            $support_diagnose = $row['value'];
            $check_support_diagnose = $this->diseases_model->checkDisease($support_diagnose);
            if(isset($check_support_diagnose->id)){
                $support_diagnose =  $check_support_diagnose->id;
            }else{
                $dis_data=array(
                    'diseaseName'=>$support_diagnose,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $support_diagnose = $this->diseases_model->createDisease($dis_data);
            }

            $mrd_data=array(
                'medicalRecordID'=>$header_data,
                'diseaseID'=>$support_diagnose,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $this->medical_record_detail_model->createMedicalRecordDetailSupportDiagnose($mrd_data);
        }

        // SUPPORT EXAMINATION
        foreach($data[0]['support_examination'] as $row){
            $support_examination_column = $row['column'];
            $support_examination_value = $row['value'];

            $check_support_examination = $this->support_examination_model->checkSupportExaminationColumn($support_examination_column);
            if(isset($check_support_examination->id)){
                $support_examination_column =  $check_support_examination->id;
            }else{
                $se_data=array(
                    'supportExaminationColumnName'=>$support_examination_column,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $support_examination_column = $this->support_examination_model->createSupportExaminationColumn($se_data);
            }

            $mrd_data=array(
                'medicalRecordID'=>$header_data,
                'supportExaminationColumnID'=>$support_examination_column,
                'supportExaminationValue'=>$support_examination_value,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $this->medical_record_detail_model->createMedicalRecordDetailSupportExamination($mrd_data);
        }

        //MEDICATION / TERAPI
        foreach($data[0]['medication'] as $row){
            $medication = $row['value'];
            $check_medication = $this->medication_model->checkMedication($medication);
            if(isset($check_medication->id)){
                $medication =  $check_medication->id;
            }else{
                $med_data=array(
                    'medicationText'=>$medication,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $medication = $this->medication_model->createMedication($med_data);
            }

            $mrd_data=array(
                'medicalRecordID'=>$header_data,
                'medicationID'=>$medication,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $this->medical_record_detail_model->createMedicalRecordDetailMedication($mrd_data);
        }

        // EXAMINATION / PEMERIKSAAN
        $blood_low = $data[0]['blood_low'];
        $blood_high = $data[0]['blood_high'];
        $pulse = $data[0]['pulse'];
        $respiration = $data[0]['respiration'];
        $temperature = $data[0]['temperature'];
        $height = $data[0]['height'];
        $weight = $data[0]['weight'];

        $physical_examination_data=array(
            'medicalRecordID'=>$header_data,
            'bloodPreasureLow'=>$blood_low,
            'bloodPreasureHigh'=>$blood_high,
            'pulse'=>$pulse,
            'respirationRate'=>$respiration,
            'temperature'=>$temperature,
            'weight'=>$weight,
            'height'=>$height,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('userID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->medical_record_detail_model->createMedicalRecordDetailPhysicalExamination($physical_examination_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg="Cannot save Medical Record to Database";
        }
        else {
            $this->db->trans_commit();
            $status = "success";
            $msg="Medical Record has been saved successfully.";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function saveMedicationDetail(){

    }

    function test(){
        $result = $this->main_condition_model->checkMainCondition("Sakit");
        if(isset($result->id)){
            echo $result->id;
        }else{
            echo '0';
        }

        $this->output->enable_profiler(TRUE);
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}