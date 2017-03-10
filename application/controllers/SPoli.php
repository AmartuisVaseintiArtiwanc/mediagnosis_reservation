<?php

class SPoli extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Login_model',"login_model");
        $this->load->model('Doctor_model',"doctor_model");
		$this->load->model('Poli_model',"poli_model");
        $this->load->model('Clinic_model',"clinic_model");
		$this->load->model('SPoli_model',"spoli_model");
        $this->load->model('SSchedule_model',"sschedule_model");
    }
    
	function index($superUserID=""){
        $role = $this->session->userdata('role');

        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            // Super Admin Mediagnosis
            $data['main_content'] = 'admin/setting/setting_poli_list_view';
            $data['superUserID'] = $superUserID;
            $data['data_account'] = $this->login_model->getUserDataByUserID($superUserID, "none");
            $this->load->view('admin/template/template', $data);

        }else{
            $data['main_content'] = 'setting/setting_poli_list_view';
            $this->load->view('template/template', $data);
        }
	}

    function indexAdmin(){
        $data['main_content'] = 'admin/master/home_super_admin_clinic_list_view';
        $data['master'] = 'SPoli';
        $data['master_title'] = 'Setting Poli - Doctor';
		$data['navigation_flag'] = "setting";
        $this->load->view('admin/template/template', $data);
    }

    function dataSPoliListAjax($superUserID=""){

        $searchText = $this->security->xss_clean($_POST['search']['value']);
        $limit = $_POST['length'];
        $start = $_POST['start'];

        //Check Super Admin Clinic
        $role = $this->session->userdata('role');
        $clinic = '';

        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $superUserID = $this->session->userdata('superUserID');
        }

        if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $superUserID = $this->session->userdata('superUserID');
            $clinicData = $this->clinic_model->getClinicByUserID($userID);
            $clinic = $clinicData->clinicID;
        }

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
	
	function goToSettingDetailPoli($id,$superUserID=""){
        //Data Selection
        $data['data_setting_header'] = $this->spoli_model->getSettingHeaderPoli($id);
        $data['data_setting_detail'] = $this->spoli_model->getSettingDetailPoli($id,$superUserID);

        $data['data'] = null;
        $data['msg'] = null;

        //Check Super Admin Mediagnosis
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            //Super Admin Mediagnosis
            $data['main_content'] = 'admin/setting/setting_poli_detail_view';
            $data['superUserID'] = $superUserID;
            $this->load->view('admin/template/template', $data);

        }else{
            $data['main_content'] = 'setting/setting_poli_detail_view';
            $this->load->view('template/template', $data);

        }
    }
	
	function savePoli(){
        //$this->output->enable_profiler(TRUE);
        $status="";
        $msg="";
        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->input->post('data');
        $sClinicID= $data[0]['sClinicID'];

        if(!isset($data[0]['superUserID'])){
            $superUserID = $this->session->userdata('superUserID');
        }else{
            $superUserID= $data[0]['superUserID'];
        }

		$this->db->trans_begin();
		// ADD NEW DATA 
		if(isset($data[1])){
			foreach($data[1] as $row){
				$detail_setting = array(
					'sClinicID'=>$sClinicID,
					'doctorID'=>$row['doctorID'],
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $superUserID,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
				);

				$addDetil = $this->spoli_model->createSettingPoli($detail_setting);			
			}
		}
		
		//DELETE DATA
		if(isset($data[2])){
			foreach($data[2] as $row){			
				$deteleDetil = $this->spoli_model->deleteSettingPoli($sClinicID,$row['doctorID']);
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

    function checkDuplicateMaster($name, $isEdit, $old_data){
        $query = $this->poli_model->getPoliByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function dataLookupDoctorListAjax($superUserID=""){

        //Check Super Admin Clinic
        $role = $this->session->userdata('role');
        if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
            $superUserID = $this->session->userdata('superUserID');
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

        $result = $this->doctor_model->getDoctorLookupListData($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->doctor_model->count_lookup_all($superUserID);
        $resultTotalFilter  = $this->doctor_model->count_lookup_filtered($superUserID,$searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['doctorID'];
            $row[] = $item['doctorName'];
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