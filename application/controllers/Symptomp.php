<?php

class Symptomp extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        //$this->is_logged_in();
        $this->load->model('Symptomps_model',"symptomp_model");
		$this->load->model('SDisease_model',"sdisease_model");
		$this->load->helper("language");
		$this->load->language("main", "bahasa");
    }
    
	function index(){
        $data['main_content'] = 'admin/master/symptomp_list_view';
        $this->load->view('admin/template/template', $data);
	}

    function dataSymptompListAjax(){
        $this->is_logged_in_admin();

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

        $result = $this->symptomp_model->getSymptompListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->symptomp_model->count_all();
        $resultTotalFilter  = $this->symptomp_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['symptompID'];
            $row[] = $item['symptompName'];
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
    
    function getSymptompData(){
        $data = $this->Symptomp_Model->getSymptompList();
        echo json_encode($data);
    }	
	
	function createSymptomp(){
		
		
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'symptompName'=>$name,
            "createdBy" => "sample",
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>"sample"
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->symptomp_model->createSymptomp($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg= $this->lang->line("002");//"Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg= $this->lang->line("001"); //"Master Symptomp has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg=$this->lang->line("002"); //"Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg= $name." ".$this->lang->line("003"); //"This ".$name." Symptomp already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editSymptomp(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        // OLD DATA
        $old_data = $this->symptomp_model->getSymptompByID($id);

        $data=array(
            'symptompName'=>$name,
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>"sample"
        );

        if($this->checkDuplicateMaster($name, true, $old_data->symptompName)) {
            $this->db->trans_begin();
            $query = $this->symptomp_model->updateSymptomp($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("002");//"Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = $this->lang->line("004");//"Master Symptomp has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = $this->lang->line("002");//"Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg= $name." ".$this->lang->line("003");//"This ".$name." Symptomp already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function checkDuplicateMaster($name, $isEdit, $old_data){
        $query = $this->symptomp_model->getSymptompByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function deleteSymptomp(){
        $status = 'error';
		$id = $this->security->xss_clean($this->input->post("delID"));
		
		if($this->checkSettingUsage($id)){
			$status = "success";
			$msg = $this->lang->line("005"); //"Disease has been deleted successfully !";
			$this->symptomp_model->deleteSymptomp($id);
			
		}else{
			$msg = $this->lang->line("006"); // Master masi dipakai di setting
		}

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
	
	private function checkSettingUsage($symptompID){
		$data = $this->sdisease_model->getSettingDetailDiseaseBySymptomp($symptompID);
		
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