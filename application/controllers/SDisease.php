<?php

class SDisease extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        //$this->is_logged_in();
        $this->load->model('Diseases_model',"disease_model");
		$this->load->model('Symptomps_model',"symptomp_model");
		$this->load->model('Sdisease_model',"sdisease_model");
    }
    
	function index(){
        $this->is_logged_in_admin();
        $data['main_content'] = 'admin/setting/setting_disease_list_view';
        $this->load->view('admin/template/template', $data);
	}

    function dataDiseaseListAjax(){

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

        $result = $this->disease_model->getDiseaseListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->disease_model->count_all();
        $resultTotalFilter  = $this->disease_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['diseaseID'];
            $row[] = $item['diseaseName'];
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
	
	function goToSettingDetailDisease($id){
        
        $data['main_content'] = 'admin/setting/setting_disease_detail_view';
        $data['data'] = null;
        $data['msg'] = null;
        
        //Data Selection
        $data['data_setting_header'] = $this->disease_model->getDiseaseByIdWithoutIsacitve($id);
        $data['data_setting_detail'] = $this->sdisease_model->getSettingDetailDisease($id);
        
		$this->load->view('admin/template/template', $data);
    }
	
	function saveDisease(){
        //$this->output->enable_profiler(TRUE);
        $status="";
        $msg="";
        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->input->post('data');
        $diseaseID= $data[0]['diseaseID'];
		
		$this->db->trans_begin();
		// ADD NEW DATA 
		if(isset($data[1])){
			foreach($data[1] as $row){
				$detail_setting = array(
					'diseaseID'=>$diseaseID,
					'symptompID'=>$row['symptompID'],
					'weight'=>$row['weight'],					
					"created"=>$datetime,
					"createdBy" => "sample",
					"lastUpdated"=>$datetime,
					"lastUpdatedBy"=>"sample"
				);

				$addDetil = $this->sdisease_model->createSettingDisease($detail_setting);			
			}
		}
		
		//DELETE DATA
		if(isset($data[2])){
			foreach($data[2] as $row){			
				$deteleDetil = $this->sdisease_model->deleteSettingDisease($diseaseID,$row['symptompID']);			
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
        $query = $this->disease_model->getDiseaseByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function deleteDisease(){
        $status = 'success';
        $msg = "Disease has been deleted successfully !";
        $id = $this->security->xss_clean($this->input->post("delID"));
        $this->disease_model->deleteDisease($id);

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in_admin(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        $role = $this->session->userdata('role');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("LoginAdmin");
            redirect($url_login, 'refresh');
        }else{
            if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
                $url_login = site_url("LoginAdmin");
                redirect($url_login, 'refresh');
            }
        }
    }
}