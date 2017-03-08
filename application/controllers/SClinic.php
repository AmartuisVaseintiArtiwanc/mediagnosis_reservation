<?php

class SClinic extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Login_model',"login_model");
        $this->load->model('clinic_model',"clinic_model");
		$this->load->model('poli_model',"poli_model");
		$this->load->model('sClinic_model',"sclinic_model");
        $this->load->model('sSchedule_model',"sschedule_model");
    }
    
	function index($superUserID=""){
        $role = $this->session->userdata('role');

        if($this->authentication->isAuthorizeSuperAdmin($role)){
            // Super Admin Clinic
            $data['main_content'] = 'setting/setting_clinic_list_view';
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
            $data['main_content'] = 'admin/setting/setting_clinic_list_view';
            $data['superUserID'] = $superUserID;
            $data['data_account'] = $this->login_model->getUserDataByUserID($superUserID, "none");
            $this->load->view('admin/template/template', $data);

        }
	}

    function indexAdmin(){
        $data['main_content'] = 'admin/master/home_super_admin_clinic_list_view';
        $data['master'] = 'SClinic';
        $this->load->view('admin/template/template', $data);
    }

    function dataClinicListAjax($superUserID=""){

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

        $result = $this->clinic_model->getClinicListData($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->clinic_model->count_all($superUserID);
        $resultTotalFilter  = $this->clinic_model->count_filtered($superUserID,$searchText);

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
        //output to json format
        echo json_encode($output);
    }
	
	function goToSettingDetailClinic($id,$superUserID=""){
        //Data Selection
        $data['data_setting_header'] = $this->clinic_model->getClinicByID($id,$superUserID);
        $data['data_setting_detail'] = $this->sclinic_model->getSettingDetailClinic($id,$superUserID);

        $data['data'] = null;
        $data['msg'] = null;

        //Check Super Admin Mediagnosis
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            //Super Admin Mediagnosis
            $data['main_content'] = 'admin/setting/setting_clinic_detail_view';
            $data['superUserID'] = $superUserID;
            $this->load->view('admin/template/template', $data);

        }else{
            $data['main_content'] = 'setting/setting_clinic_detail_view';
            $this->load->view('template/template', $data);

        }
    }
	
	function saveClinic(){
        //$this->output->enable_profiler(TRUE);
        $status="";
        $msg="";
        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->input->post('data');
        $clinicID= $data[0]['clinicID'];

        if(!isset($data[0]['superUserID'])){
            $superUserID = $this->session->userdata('superUserID');
        }else{
            $superUserID= $data[0]['superUserID'];
        }

		$this->db->trans_begin();
		// ADD NEW DATA 
		if(isset($data[1])){
            // Save Detail Clinic - Poli
			foreach($data[1] as $row){
				$detail_setting = array(
					'clinicID'=>$clinicID,
					'poliID'=>$row['poliID'],
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $superUserID,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
				);

				$addDetil = $this->sclinic_model->createSettingClinic($detail_setting);

                //Save Schedule Per Clinic-Poli
                for($i=0;$i<7;$i++){
                    $dayName = $this->getDayName($i);
                    $detail_schedule = array(
                        'clinicID'=>$clinicID,
                        'poliID'=>$row['poliID'],
                        'scheduleDay'=>$dayName,
                        'openTime'=>'00:00:00',
                        'closeTime'=>'24:00:00',
                        'isOpen'=>0,
                        'isActive'=>1,
                        'created'=>$datetime,
                        "createdBy" => $superUserID,
                        "lastUpdated"=>$datetime,
                        "lastUpdatedBy"=>$this->session->userdata('userID')
                    );
                    $this->sschedule_model->createSettingSchedule($detail_schedule);
                }
			}
		}
		
		//DELETE DATA
		if(isset($data[2])){
			foreach($data[2] as $row){			
				$deteleDetil = $this->sclinic_model->deleteSettingClinic($clinicID,$row['poliID']);
                $this->sschedule_model->deleteSettingSchedule($clinicID,$row['poliID']);
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

    function getDayName($i){
        $dayName = "";
        switch ($i) {
            case "0":
                $dayName="Senin";
                break;
            case "1":
                $dayName="Selasa";
                break;
            case "2":
                $dayName="Rabu";
                break;
            case "3":
                $dayName="Kamis";
                break;
            case "4":
                $dayName="Jumat";
                break;
            case "5":
                $dayName="Sabtu";
                break;
            case "6":
                $dayName="Minggu";
                break;
        }

        return $dayName;
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}