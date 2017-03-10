<?php

class Disease extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        //$this->is_logged_in();
        $this->load->model('Diseases_model',"disease_model");
		$this->load->model('SDisease_model',"sdisease_model");
		$this->load->helper("language");
		$this->load->language("main", "bahasa");
    }
    
	function index(){
        $this->is_logged_in_admin();
        $data['main_content'] = 'admin/master/disease_list_view';
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
            $row[] = $item['description'];
            $row[] = $item['cause'];
            $row[] = $item['treatment'];
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
    
    function getDiseaseData($start=1){
        $data = $this->Disease_Model->getDiseaseList(null,null);
        //$this->output->set_content_type('application/json')->set_output(json_encode($data));
        
        print_r(json_encode($data));
        exit();
    }	
	
	function createDisease(){
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'diseaseName'=>$name,
            "createdBy" => "sample",
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>"sample"
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->disease_model->createDisease($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg=$this->lang->line("002"); //"Cannot save master to Database";
            }
            else {
                if($query){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg=$this->lang->line("001");//"Master Disease has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg=$this->lang->line("002"); //"Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg=$name." ".$this->lang->line("003");//"This ".$name." Disease already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editDisease(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $desc = $this->security->xss_clean($this->input->post('desc'));
        $caused = $this->security->xss_clean($this->input->post('caused'));
        $treatment = $this->security->xss_clean($this->input->post('treatment'));

        // OLD DATA
        $old_data = $this->disease_model->getDiseaseByIdWithoutIsacitve($id);

        $data['diseaseName'] = $name;
        $data['lastUpdated'] = $datetime;
        $data['lastUpdatedBy'] = "bsd";


        if(isset($desc)){
            $data['description'] = $desc;
        }
        if(isset($desc)){
            $data['cause'] = $caused;
        }
        if(isset($desc)){
            $data['treatment'] = $treatment;
        }

        if($this->checkDuplicateMaster($name, true, $old_data->diseaseName)) {
            $this->db->trans_begin();
            $query = $this->disease_model->updateDisease($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg =$this->lang->line("002");  //"Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg =$this->lang->line("004");  //"Master Disease has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg =$this->lang->line("002");  //"Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg=$name." ".$this->lang->line("003");//"This ".$name." Disease already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function checkDuplicateMaster($name, $isEdit, $old_data)
    {
        $query = $this->disease_model->getDiseaseByName($name, $isEdit, $old_data);

        if (empty($query)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteDisease(){
        $status = 'error';
		$id = $this->security->xss_clean($this->input->post("delID"));
		
		if($this->checkSettingUsage($id)){
			$status = "success";
			$msg = $this->lang->line("005"); //"Disease has been deleted successfully !";
			$this->disease_model->deleteDisease($id);
			
		}else{
			$msg = $this->lang->line("006"); // Master masi dipakai di setting
		}
        
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
	
	private function checkSettingUsage($diseaseID){
		$data = $this->sdisease_model->getSettingDetailDisease($diseaseID);
		
		if(count($data) == 0){
			return true;
		}else{
			return false;
		}
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