<?php

class SettingSchedule extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security', 'date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Login_model', "login_model");
        $this->load->model('Clinic_model', "clinic_model");
        $this->load->model('Poli_model', "poli_model");
        $this->load->model('SSchedule_model', "sschedule_model");
    }

    function index($superUserID=""){
        $role = $this->session->userdata('role');

        if($this->authentication->isAuthorizeSuperAdmin($role)){
            // Super Admin Clinic
            $data['main_content'] = 'setting/setting_schedule_list_view';
            $data['superUserID'] = $superUserID;
            $this->load->view('template/template', $data);

        }else if($this->authentication->isAuthorizeAdmin($role)){
            // Admin Clinic
            $userID =  $this->session->userdata('userID');
            $data['superUserID'] = $superUserID;
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            $this->goToSettingDetailClinic($clinic->clinicID);

        }else if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            // Super Admin Mediagnosis
            $data['main_content'] = 'admin/setting/setting_schedule_list_view';
            $data['superUserID'] = $superUserID;
            $data['data_account'] = $this->login_model->getUserDataByUserID($superUserID, "none");
            $this->load->view('admin/template/template', $data);

        }
    }

    function indexAdmin(){
        $data['main_content'] = 'admin/master/home_super_admin_clinic_list_view';
        $data['master'] = 'SettingSchedule';
        $data['master_title'] = 'Setting Schedule';
		$data['navigation_flag'] = "setting";
        $this->load->view('admin/template/template', $data);
    }

    function dataScheduleListAjax($superUserID=""){

        //Check Super Admin Clinic
        $role = $this->session->userdata('role');
        if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
            $superUserID = $this->session->userdata('superUserID');
        }

        $clinic = '';
        if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $clinicData = $this->clinic_model->getClinicByUserID($userID);
            $clinic = $clinicData->clinicID;
        }

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

        $result = $this->sschedule_model->getScheduleListData($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit,$clinic);
        $resultTotalAll = $this->sschedule_model->count_all($superUserID,$clinic);
        $resultTotalFilter  = $this->sschedule_model->count_filtered($superUserID,$searchText,$clinic);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item['sClinicID'];
            $row[] = $item['clinicName'];
            $row[] = $item['poliName'];
            $row[] = $item['clinicID'];
            $row[] = $item['poliID'];
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $resultTotalAll,
            "recordsFiltered" => $resultTotalFilter,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function goToSettingDetailSchedule($clinicID, $poliID,$superUserID=""){
        $data['data'] = null;
        $data['msg'] = null;

        $role = $this->session->userdata('role');

        //SUPER ADMIN Mediagnosis
        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            $data['data_setting_header'] = $this->sschedule_model->getHeaderData($clinicID, $poliID,$superUserID);
            $data['data_setting_detail'] = $this->sschedule_model->getSettingDetailSchedule($clinicID, $poliID);

            if(isset($data['data_setting_header'])){
                $data['superUserID'] = $superUserID;
                $data['main_content'] = 'admin/setting/setting_schedule_detail_view';
                $this->load->view('admin/template/template', $data);
            }else{
                $welcome = site_url("SettingSchedule");
                redirect($welcome, 'refresh');
            }
        }
        //SUPER ADMIN
        else if($this->authentication->isAuthorizeSuperAdmin($role)){
            $superUserID = $this->session->userdata('superUserID');
            $data['data_setting_header'] = $this->sschedule_model->getHeaderData($clinicID, $poliID,$superUserID);
            $data['data_setting_detail'] = $this->sschedule_model->getSettingDetailSchedule($clinicID, $poliID);

            if(isset($data['data_setting_header'])){
                $data['main_content'] = 'setting/setting_schedule_detail_view';
                $this->load->view('template/template', $data);
            }else{
                $welcome = site_url("SettingSchedule");
                redirect($welcome, 'refresh');
            }

        }
        //ADMIN
        else if($this->authentication->isAuthorizeAdmin($role)){
            //ROLE
            $superUserID = $this->session->userdata('superUserID');
            $userID =  $this->session->userdata('userID');
            $clinicData = $this->clinic_model->getClinicByUserID($userID);
            $clinic = $clinicData->clinicID;

            if($clinic==$clinicID){
                //Data Selection
                $data['data_setting_header'] = $this->sschedule_model->getHeaderData($clinicID, $poliID,$superUserID);
                $data['data_setting_detail'] = $this->sschedule_model->getSettingDetailSchedule($clinicID, $poliID);
                $data['main_content'] = 'setting/setting_schedule_detail_view';
                $this->load->view('template/template', $data);
            }else{
                $welcome = site_url("Welcome");
                redirect($welcome, 'refresh');
            }
        }
        else{
            $welcome = site_url("Welcome");
            redirect($welcome, 'refresh');
        }
    }

    function saveSchedule(){
        //$this->output->enable_profiler(TRUE);
        $status="";
        $msg="";
        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->input->post('data');
        $poliID= $data[0]['poliID'];
        $clinicID= $data[0]['clinicID'];

        $this->db->trans_begin();
        // ADD NEW DATA
        if(isset($data[1])){
            foreach($data[1] as $row){
                $detail_setting = array(
                    'openTime'=>$row['openTime'],
                    'closeTime'=>$row['closeTime'],
                    'isOpen'=>$row['active'],
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );

                $addDetil = $this->sschedule_model->updateSettingSchedule($row['scheduleID'],$detail_setting);
            }
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $status="error";
            $msg="Error while saved data!";
        }
        else{
            $this->db->trans_commit();
            $status="success";
            $msg="Setting berhasil disimpan!";
        }

        // return message to AJAX
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}
?>