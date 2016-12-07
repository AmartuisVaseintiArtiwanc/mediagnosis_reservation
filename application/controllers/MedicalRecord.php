<?php

class MedicalRecord extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('Patient_model',"patient_model");
        $this->load->model('Test_model',"test_model");
        $this->load->model('Main_condition_model',"main_condition_model");
        $this->load->model('Additional_condition_model',"additional_condition_model");
        $this->load->model('Diseases_model',"diseases_model");
        $this->load->model('Medication_model',"medication_model");
        $this->load->model('Support_examination_model',"support_examination_model");
        $this->load->model('Medical_record_model',"medical_record_model");
        $this->load->model('Medical_record_detail_model',"medical_record_detail_model");
        $this->load->model('UserOtp_model',"userOtp_model");

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

    // Auto Complete Main Condition (Keluhan Utama)
    function getMainConditionList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->main_condition_model->getMainConditionAutocomplete($search);

        echo json_encode($result);
    }

    // Auto Complete Additional Condition (Keluhan Tambahan)
    function getAdditionalConditionList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->additional_condition_model->getAdditionalConditionAutocomplete($search);

        echo json_encode($result);
    }

    // Auto Complete Disease (Working Diagnose, Support Diagnose) - (Diagnosa Kerja - Diagnosa Banding)
    function getDiseaseList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->diseases_model->getDiseaseAutocomplete($search);

        echo json_encode($result);
    }

    // Auto Complete Medication(Terapi)
    function getMedicationList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->medication_model->getMedicationAutocomplete($search);

        echo json_encode($result);
    }

    // Auto Complete Support Examination (Pemeriksaan Penunjang)
    function getSupportExaminationColumnList(){
        $search = $this->security->xss_clean($this->input->post('phrase'));
        $result = $this->support_examination_model->getSupportExaminationColumnAutocomplete($search);

        echo json_encode($result);
    }

    // Save Medical Record
    function saveMedicalRecordData(){
        $data = $this->input->post('data');

        $datetime = date('Y-m-d H:i:s', time());
        $detail_reservation = $data[0]['detail_reservation'];
        $patient = $data[0]['patient'];

        $this->db->trans_begin();
        //GET PATIENT DATA
        $patient_data = $this->patient_model->getPatientByID($patient);
        // SAVE PATIENT DATA
        $profile_patient_data=array(
            'patientID'=>$patient,
            'patientName'=>$patient_data->patientName,
            'ktpID'=>$patient_data->ktpID,
            'bpjsID'=>$patient_data->bpjsID,
            'phoneNumber'=>$patient_data->phoneNumber,
            'address'=>$patient_data->address,
            'dob'=>$patient_data->dob,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $save_patient_data = $this->patient_model->insertTransProfilePatient($profile_patient_data);

        //TRANSACTION
        $mr_data=array(
            'detailReservationID'=>$detail_reservation,
            'patientID'=>$patient,
            'tPatientProfileID'=>$save_patient_data,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
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
                "createdBy" => $this->session->userdata('superUserID'),
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
                "createdBy" => $this->session->userdata('superUserID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $working_diagnose = $this->diseases_model->createDisease($dis_data);
        }

        // SAVE MEDICAL RECORD DETAIL
        $condition_date = $data[0]['condition_date'];
        $reference = $data[0]['rujukan'];
        $visitType = $data[0]['visit_type'];
        $treatment = $data[0]['treatment'];
        $statusDiagnose = $data[0]['status_diagnose'];

        $mr_detail_data=array(
            'medicalRecordID'=>$header_data,
            'mainCondition'=>$main_condition,
            'workingDiagnose'=>$working_diagnose,
            'conditionDate'=>$condition_date,
            'reference'=>$reference,
            'visitType'=>$visitType,
            'treatment'=>$treatment,
            'statusDiagnose'=>$statusDiagnose,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->medical_record_detail_model->createMedicalRecordDetail($mr_detail_data);

        //ADDITIONAL CONDITION
        if(isset($data[0]['additional_condition'])){
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
                        "createdBy" => $this->session->userdata('superUserID'),
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
                    "createdBy" => $this->session->userdata('superUserID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $this->medical_record_detail_model->createMedicalRecordDetailAdditonalCondition($mrd_data);
            }
        }

        // SUPPORT DIAGNOSA
        if(isset($data[0]['support_diagnose'])){
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
                        "createdBy" => $this->session->userdata('superUserID'),
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
                    "createdBy" => $this->session->userdata('superUserID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $this->medical_record_detail_model->createMedicalRecordDetailSupportDiagnose($mrd_data);
            }
        }

        // SUPPORT EXAMINATION
        if(isset($data[0]['support_examination'])){
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
                        "createdBy" => $this->session->userdata('superUserID'),
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
                    "createdBy" => $this->session->userdata('superUserID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );
                $this->medical_record_detail_model->createMedicalRecordDetailSupportExamination($mrd_data);
            }
        }

        //MEDICATION / TERAPI
        if(isset($data[0]['medication'])) {
            foreach ($data[0]['medication'] as $row) {
                $medication = $row['value'];
                $check_medication = $this->medication_model->checkMedication($medication);
                if (isset($check_medication->id)) {
                    $medication = $check_medication->id;
                } else {
                    $med_data = array(
                        'medicationText' => $medication,
                        'isActive' => 1,
                        'created' => $datetime,
                        "createdBy" => $this->session->userdata('superUserID'),
                        "lastUpdated" => $datetime,
                        "lastUpdatedBy" => $this->session->userdata('userID')
                    );
                    $medication = $this->medication_model->createMedication($med_data);
                }

                $mrd_data = array(
                    'medicalRecordID' => $header_data,
                    'medicationID' => $medication,
                    'isActive' => 1,
                    'created' => $datetime,
                    "createdBy" => $this->session->userdata('superUserID'),
                    "lastUpdated" => $datetime,
                    "lastUpdatedBy" => $this->session->userdata('userID')
                );
                $this->medical_record_detail_model->createMedicalRecordDetailMedication($mrd_data);
            }
        }

        // EXAMINATION / PEMERIKSAAN
        $conscious = $data[0]['conscious'];
        $blood_low = $data[0]['blood_low'];
        $blood_high = $data[0]['blood_high'];
        $pulse = $data[0]['pulse'];
        $respiration = $data[0]['respiration'];
        $temperature = $data[0]['temperature'];
        $height = $data[0]['height'];
        $weight = $data[0]['weight'];

        $physical_examination_data=array(
            'medicalRecordID'=>$header_data,
            'conscious'=>$conscious,
            'bloodPreasureLow'=>$blood_low,
            'bloodPreasureHigh'=>$blood_high,
            'pulse'=>$pulse,
            'respirationRate'=>$respiration,
            'temperature'=>$temperature,
            'weight'=>$weight,
            'height'=>$height,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        // UPDATE PHYSICAL EXAMINATION
        $this->medical_record_detail_model->updateMedicalRecordDetailPhysicalExamination($physical_examination_data,$detail_reservation);

        //UPDATE RESERVATION DONE
        $data_reservation=array(
            'status'=>'done',
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $query_detail = $this->test_model->updateReservationDetail($data_reservation,$detail_reservation);

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

    // Reject Medical Record by Doctor
    function rejectReservationMedicalRecord(){
        $detail_reservation = $this->input->post('detailReservation');
        $datetime = date('Y-m-d H:i:s', time());
        //UPDATE RESERVATION REJECT
        $data_reservation=array(
            'status'=>'reject',
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        $query_detail = $this->test_model->updateReservationDetail($data_reservation,$detail_reservation);
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

    function checkUserOTPView($detailReservationID, $patientID){
        $patient_data = $this->patient_model->getPatientByID($patientID);
        $data['patient_data']  = $patient_data;
        $data['detail_reservation']  = $detailReservationID;

        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getDoctorByUserID($userID);

        // Check validate role Doctor
        if(isset($doctor_data)){
            // Check Status Reservation Doctor
            if($this->checkDoctorReservation($detailReservationID,$doctor_data->doctorID,$patientID)){
                $this->load->view('mr/otp_medical_record_view', $data);
            }else{
                $this->load->view('template/error');
            }
        }else{
            $this->load->view('template/error');
        }
    }

    function checkUserOTP(){
        $patient = $this->security->xss_clean($this->input->post('patient'));
        $detail_reservation = $this->security->xss_clean($this->input->post('detail_reservation'));
        $otp = $this->security->xss_clean($this->input->post('otp'));

        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getDoctorByUserID($userID);

        // Check validate role Doctor
        if(isset($doctor_data)){
            // Check Status Reservation Doctor
            if($this->checkDoctorReservation($detail_reservation,$doctor_data->doctorID,$patient)){
                $data = $this->userOtp_model->validateOTP($patient,$otp);
                $datetime = date('Y-m-d H:i:s', time());
                if(isset($data)){
                    //UPDATE OTP
                    $otp_data=array(
                        'doctorID'=>$doctor_data->doctorID,
                        'isActive'=>1,
                        "lastUpdated"=>$datetime,
                        "lastUpdatedBy"=>$this->session->userdata('userID')
                    );
                    $this->userOtp_model->updateOtp($patient,$otp_data);

                    $status = "success";
                    $msg= "Success";
                }else{
                    $status = "error";
                    $msg= "Kode OTP Anda salah atau sudah habis masa berlakunya !";
                }
            }else{
                $status = "error";
                $msg= "Maaf Anda bukan Dokter untuk pasien ini !";
            }
        }else{
            $status = "error";
            $msg= "Maaf Anda Tidak berhas mengakses halaman ini !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    // Check Doctor Reservation on This Patient
    function checkDoctorReservation($detailReservation, $doctor, $patient){
        // Check Detail Reservation status Confirm (dalam proses pemeriksaan)
        $return = $this->test_model->checkOTPMedicalRecord($detailReservation, $doctor, $patient);
        return $return;
    }

    // Check Doctor Reservation on This Patient
    function checkDoctorValidateOTP($doctor, $patient){
        // Check Detail Reservation status Confirm (dalam proses pemeriksaan)
        $return = $this->userOtp_model->checkOtpByPatientDoctor($doctor, $patient);
        return $return;
    }

    // Get Medical Record List For OTP
    function getMedicalRecordList($detailReservation,$patient){
        //$data = $this->input->post('data');

        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getDoctorByUserID($userID);

        if(isset($doctor_data)){
            $checkReservation = $this->checkDoctorReservation($detailReservation,$doctor_data->doctorID,$patient);
            $checkOTP = $this->checkDoctorValidateOTP($doctor_data->doctorID,$patient);
            if($checkReservation && $checkOTP){

                $medical_record_header = $this->medical_record_model->getMedicalRecordListByPatient($patient);
                $patient_data = $this->patient_model->getPatientByID($patient);

                $data['medical_record_data']  = $medical_record_header;
                $data['patient_data']  = $patient_data;
                $data['detail_reservation']  = $detailReservation;

                $this->load->view('mr/medical_record_list_view', $data);

            }else{
                $this->load->view('template/error');
            }
        }else{
            $this->load->view('template/error');
        }
    }

    // Get Medical Record List For OTP by Date
    function getMedicalRecordBySearchDate($detailReservation, $patientID){
        //$data = $this->input->post('data');
        $medical_record_header = $this->medical_record_model->getMedicalRecordListByPatient($patientID);
        $patient_data = $this->patient_model->getPatientByID($patientID);

        $data['medical_record_data']  = $medical_record_header;
        $data['patient_data']  = $patient_data;
        $this->load->view('mr/medical_record_list_view', $data);
    }

    // Get Medical Record List For OTP by Periode
    function getMedicalRecordBySearchPeriod($detailReservation, $patientID){
        //$data = $this->input->post('data');
        $medical_record_header = $this->medical_record_model->getMedicalRecordListByPatient($patientID);
        $patient_data = $this->patient_model->getPatientByID($patientID);

        $data['medical_record_data']  = $medical_record_header;
        $data['patient_data']  = $patient_data;
        $this->load->view('mr/medical_record_list_view', $data);
    }

    // Get Medical Record Detail For OTP
    function getMedicalRecordDetail($detailReservation,$patient,$medicalRecordID){
        //$data = $this->input->post('data');
        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getDoctorByUserID($userID);

        if(isset($doctor_data)){
            $checkReservation = $this->checkDoctorReservation($detailReservation,$doctor_data->doctorID,$patient);
            $checkOTP = $this->checkDoctorValidateOTP($doctor_data->doctorID,$patient);

            if($checkReservation && $checkOTP){
                $medical_record_header = $this->medical_record_model->getMedicalRecordByID($medicalRecordID);
                $medical_record_detail = $this->medical_record_detail_model->getMedicalRecordDetailByID($medicalRecordID);
                $addtional_condition = $this->medical_record_detail_model->getAdditionalConditionByID($medicalRecordID);
                $physical_examination = $this->medical_record_detail_model->getPhysicalExaminationByID($medicalRecordID);
                $support_examination = $this->medical_record_detail_model->getSupportExaminationByID($medicalRecordID);
                $support_diagnose = $this->medical_record_detail_model->getSupportDiagnoseByID($medicalRecordID);
                $medication = $this->medical_record_detail_model->getMedicationByID($medicalRecordID);

                $data['header']  = $medical_record_header;
                $data['detail']  = $medical_record_detail;
                $data['additional_condition']  = $addtional_condition;
                $data['physical_examination']  = $physical_examination;
                $data['support_examination']  = $support_examination;
                $data['support_diagnose']  = $support_diagnose;
                $data['medication']  = $medication;

                $data['detailReservation']  = $detailReservation;
                $data['patient']  = $patient;
                $this->load->view('mr/medical_record_detail_view', $data);
            }else{
                $this->load->view('template/error');
            }
        }else{
            $this->load->view('template/error');
        }
        //$this->output->enable_profiler(TRUE);
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