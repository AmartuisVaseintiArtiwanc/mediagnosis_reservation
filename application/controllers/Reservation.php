<?php

class Reservation extends CI_Controller {

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
            $this->goToListReservationQueue($clinic->clinicID);
        }
    }

    /*Reservasi Antrian Tiap Clinic Pada HARI INI*/
    function goToListReservationQueue($clinicID){
        $clinicPoliList = $this->sclinic_model->getSettingDetailClinic($clinicID);

        // CREATE & CHECK RESERVATION CLINIC EACH POLI
        $this->createHeaderReservation($clinicPoliList,$clinicID );

        $data['reversation_clinic_data']  = $this->test_model->getHeaderReservationData($clinicID);
        $data['reservation_latest_queue'] = $this->test_model->getReservationLatestQueue($clinicID);
        $data['main_content'] = 'reservation/reservation_home_view';
        $this->load->view('template/template', $data);
    }

    /*Create Header Reservasi untuk HARI INI*/
    function createHeaderReservation($clinicPoliList,$clinicID){
        $datetime = date('Y-m-d H:i:s', time());
        $userID = $this->session->userdata('userID');
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
        $data = $this->test_model->getCurrentQueue($clinicID);

        $output="";
        $status="error";
        if(isset($data)){
            $output = array(
                "headerID"=>$data->reservationID,
                "detailID"=>$data->detailReservationID,
                "noQueue"=>$data->noQueue,
                "poliName" => strtoupper($data->poliName),
                "doctorName" => $data->doctorName
            );
            $status="success";
        }
        echo json_encode(array('status' => $status, 'output' => $output));
    }

    function getQueueNext(){
        $clinicID = $this->security->xss_clean($this->input->post('clinic'));
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

    function goToReservationReportDateList(){

        $clinicID = $this->security->xss_clean($this->input->post('clinicID'));
        $user = $this->login_model->validate($username, $password);
        echo json_encode(array('status' => $status, 'msg' => $msg));
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


    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}