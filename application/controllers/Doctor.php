<?php

class Doctor extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Login_model',"login_model");
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('SPoli_model',"spoli_model");
    }
    
	function index($superUserID=""){
        $role = $this->session->userdata('role');

        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            $data['main_content'] = 'admin/master/doctor_list_view';
            $data['superUserID'] = $superUserID;
            $data['data_account'] = $this->login_model->getUserDataByUserID($superUserID, "none");
            $this->load->view('admin/template/template', $data);

        }else if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'master/doctor_list_view';
            $data['superUserID'] = $superUserID;
            $this->load->view('template/template', $data);

        }
	}

    function indexAdmin(){
        $data['main_content'] = 'admin/master/home_super_admin_clinic_list_view';
        $data['master'] = 'Doctor';
        $this->load->view('admin/template/template', $data);
    }

    function dataDoctorListAjax($superUserID=""){

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

        $result = $this->doctor_model->getDoctorListData($superUserID,$searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->doctor_model->count_all($superUserID);
        $resultTotalFilter  = $this->doctor_model->count_filtered($superUserID,$searchText);

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
            $row[] = $item['isActive'];
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
	
	function createDoctor(){
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'isActive'=>1,
            'doctorName'=>$name,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->doctor_model->createDoctor($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Master Doctor has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Doctor already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editDoctor(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $isActive = $this->security->xss_clean($this->input->post('isActive'));
        // OLD DATA
        $old_data = $this->doctor_model->getDoctorByID($id);

        $data=array(
            'doctorName'=>$name,
            'isActive'=>$isActive,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name, true, $old_data->doctorName)) {
            $this->db->trans_begin();
            $query = $this->doctor_model->updateDoctor($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = "Master Doctor has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Doctor already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function checkDuplicateMaster($name, $isEdit, $old_data){
        $query = $this->doctor_model->getDoctorByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function getLookupDoctorList(){
        $status = 'error';
        $msg = "Maaf Data Dokter kosong, silahkan cek menu Setting Poli Anda ..";

        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeAdmin($role)){
            $id = $this->security->xss_clean($this->input->post('sClinic'));
            $query = $this->spoli_model->getSettingDetailPoli($id);
            if(isset($query) && count($query)!=0){
                $status = 'success';
                $msg = "Success";
            }
        }

        echo json_encode(array('data' => $query, 'status' => $status, 'msg' => $msg));
    }

    function deleteDoctor(){
        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post("delID"));
        $data=array(
            'isActive'=>0,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        $query = $this->doctor_model->updateDoctor($data, $id);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save master to Database";
        } else {
            if ($query == 1) {
                $this->db->trans_commit();
                $status = "success";
                $msg = "Master Doctor has been updated successfully.";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Failed to save data Master ! ";
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        $role = $this->session->userdata('role');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }else{
            if(!$this->authentication->isAuthorizeAdminMediagnosis($role) &&
                !$this->authentication->isAuthorizeSuperAdmin($role)){
                $url_login = site_url("Login");
                redirect($url_login, 'refresh');

            }
        }
    }
}