<?php

class Reservation extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('doctor_model',"doctor_model");
        $this->load->model('patient_model',"patient_model");
        $this->load->model('clinic_model',"clinic_model");
        $this->load->model('poli_model',"poli_model");
        $this->load->model('sClinic_model',"sclinic_model");
        $this->load->model('medical_record_detail_model',"medical_record_detail_model");
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
            $this->goToListReservationQueue($clinic->clinicID);
        }
    }

    /*Reservasi Antrian Tiap Clinic Pada HARI INI*/
    function goToListReservationQueue($clinicID){
        $clinicPoliList = $this->sclinic_model->getSettingDetailClinic($clinicID);

        // CREATE & CHECK RESERVATION CLINIC EACH POLI
        $this->createHeaderReservation($clinicPoliList,$clinicID );

        $data['reversation_clinic_data']  = $this->test_model->getHeaderReservationData($clinicID);
        $data['reservation_latest_queue'] = $this->test_model->getReservationNextQueue($clinicID);
        $data['poli_list']  = $this->sclinic_model->getClinicListByID($clinicID);

        $data['main_content'] = 'reservation/reservation_home_view';
        $this->load->view('template/template', $data);
    }

    /*Create Header Reservasi untuk HARI INI*/
    private function createHeaderReservation($clinicPoliList,$clinicID){
        $datetime = date('Y-m-d H:i:s', time());
        $userID = $this->session->userdata('userID');
        //$userID = $this->session->userdata('userID');

        foreach($clinicPoliList as $row){
            $poliID = $row['poliID'];
            $verifyReservation = $this->test_model->checkReservationToday($clinicID,$poliID);
            if($verifyReservation == 0) {
                //insert baru
                $data_reservasi = array(
                    'clinicID' => $clinicID,
                    'poliID' => $poliID,
                    'currentQueue' => 0,
                    'totalQueue' => 0,
                    'isActive' => 1,
                    'created' => $datetime,
                    'createdBy' => $userID,
                    'lastUpdated' => $datetime,
                    'lastUpdatedBy' => $userID
                );

                $query = $this->test_model->insertReservation($data_reservasi);

                if ($this->db->trans_status() === FALSE) {
                    // Failed to save Data to DB
                    $this->db->trans_rollback();
                }
                else{
                    $this->db->trans_commit();
                }
            }
        }
    }

    /* Get Antrian Sekarang, Per Clinic*/
    function getQueueCurrent(){
        $clinicID = $this->security->xss_clean($this->input->post('clinic'));
        $poliID = $this->security->xss_clean($this->input->post('poli'));
        $data = $this->test_model->getCurrentQueue($clinicID,$poliID);

        $output="";
        $status="error";
        if(isset($data)){
            $output = array(
                "headerID"=>$data->reservationID,
                "detailID"=>$data->detailReservationID,
                "noQueue"=>$data->noQueue,
                "poliID"=>$data->poliID,
                "poliName" => strtoupper($data->poliName),
                "doctorName" => $data->doctorName,
                "patientName" => $data->patientName
            );
            $status="success";
        }
        echo json_encode(array('status' => $status, 'output' => $output));
    }

    function getQueueNext(){
        $clinicID = $this->security->xss_clean($this->input->post('clinic'));

        $data= $this->test_model->getReservationNextQueue($clinicID);
        $output="";
        $status="error";
        if(isset($data)){
            $output =$data;
            $status="success";
        }
        echo json_encode(array('status' => $status, 'output' => $output));
    }

    function saveCurrentQueue(){

        $datetime = date('Y-m-d H:i:s', time());
        $status_rev = $this->security->xss_clean($this->input->post('status'));
        $detailID = $this->security->xss_clean($this->input->post('detailID'));

        $data=array(
            'status'=>$status_rev,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        $query = $this->test_model->updateReservationDetail($data,$detailID);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save to Database !";
        } else {
            if ($query) {
                $this->db->trans_commit();
                $status = "success";
                $msg = "Save data successfully !";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Failed to save data !";
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }


    function goToReservationReportClinicList(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'reservation/reservation_report_list_view';
            $this->load->view('template/template', $data);
        }else if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            $this->goToReservationReportPoliList($clinic->clinicID);
        }
    }

    function goToReservationReportPoliList($clinicID){
        $data['data_clinic'] = $this->clinic_model->getClinicByID($clinicID);
        $data['data_poli']  = $this->sclinic_model->getSettingDetailClinic($clinicID);
        $data['main_content'] = 'reservation/reservation_report_poli_list_view';
        $this->load->view('template/template', $data);
    }

    function dataReservationClinicPoliListAjax(){

        $searchText = $this->security->xss_clean($_POST['search']['value']);
        $limit = $_POST['length'];
        $start = $_POST['start'];

        // here order processing
        if(isset($_POST['order'])){
            $orderByColumnIndex = $_POST['order']['0']['column'];
            $orderDir =  $_POST['order']['0']['dir'];
        }
        else {
            $orderByColumnIndex = 1;
            $orderDir = "ASC";
        }

        $result = $this->clinic_model->getClinicListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->clinic_model->count_all();
        $resultTotalFilter  = $this->clinic_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['clinicID'];
            $row[] = $item['clinicName'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['createdBy'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['lastUpdatedBy'];
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $resultTotalAll,
            "recordsFiltered" => $resultTotalFilter,
            "data" => $data,
        );

        //$this->output->enable_profiler(TRUE);
        //output to json format
        echo json_encode($output);
    }

    function goToPhysicalExamination($detailReservation){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('userID');
        if($this->authentication->isAuthorizeAdmin($role)){
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            if(isset($clinic)){
                $header_data = $this->test_model->checkReservationClinicAdminRole($detailReservation,$clinic->clinicID);
                if(isset($header_data)){
                    $status = $header_data->status;
                    if($status == "check" || $status == "examine" ){
                        $this->goToExamineForm($detailReservation,$header_data);
                    }else{
                        echo "Pasien ini tidak terdapat dalam proses reservasi ..";
                    }
                }else{
                    echo "Anda tidak berhak mengakses halaman ini..";
                }
            }else{
                echo "Anda tidak berhak mengakses halaman ini..";
            }
        }else{
            echo "Anda tidak berhak mengakses halaman ini..";
        }
    }

    private function goToExamineForm($detailReservation,$header_data){
        $datetime = date('Y-m-d H:i:s', time());

        $reservationData=array(
            'status'=>"examine",
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        // Update Reservation Status to Examine
        $query = $this->test_model->updateReservationDetail($reservationData,$detailReservation);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save to Database !";
        } else {
            $this->db->trans_commit();
            $status = "success";
            $msg = "Save data successfully !";
        }

        $doctor_data = $this->doctor_model->getDoctorByID($header_data->doctorID);
        $patient_data = $this->patient_model->getPatientByID($header_data->patientID);

        $data['header_data'] = $header_data;
        $data['doctor_data'] = $doctor_data;
        $data['patient_data']  = $patient_data;
        $data['detailReservation'] = $detailReservation;
        $this->load->view('reservation/physical_examination_view', $data);
    }

    function savePhysicalExamination(){

        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->security->xss_clean($this->input->post('data'));
        $detailID = $this->security->xss_clean($this->input->post('detail_reservation'));

        // EXAMINATION / PEMERIKSAAN
        $conscious = $data['conscious'];
        $blood_low = $data['blood_low'];
        $blood_high = $data['blood_high'];
        $pulse = $data['pulse'];
        $respiration = $data['respiration'];
        $temperature = $data['temperature'];
        $height = $data['height'];
        $weight = $data['weight'];

        $physical_examination_data=array(
            'detailReservationID'=>$detailID,
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

        $reservationData=array(
            'status'=>"confirm",
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->db->trans_begin();
        // Update Reservation Status to Confirm
        $query = $this->test_model->updateReservationDetail($reservationData,$detailID);
        // Save Physical Examination
        $this->medical_record_detail_model->createMedicalRecordDetailPhysicalExamination($physical_examination_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save to Database !";
        } else {
            $this->db->trans_commit();
            $status = "success";
            $msg = "Save data successfully !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    private function validateSavePhysicalExamination($data){

    }

    private function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}