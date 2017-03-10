<?php

class SuperAdminClinic extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
		$this->load->library("hash");
        $this->is_logged_in();
        $this->load->model('User_model',"user_model");
		$this->load->model('Login_model',"login_model");
		$this->load->helper("language");
		$this->load->language("main", "bahasa");
    }

    function index(){
        $data['main_content'] = 'admin/master/super_admin_clinic_list_view';
        $this->load->view('admin/template/template', $data);
    }

    function dataSuperAdminClinicListAjax(){

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

        $result = $this->user_model->getSuperAdminClinicListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->user_model->countSuperAdminClinicAll();
        $resultTotalFilter  = $this->user_model->countSuperAdminClinicFiltered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['userID'];
            $row[] = $item['userName'];
            $row[] = $item['email'];
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

        //$this->output->enable_profiler(TRUE);
        //output to json format
        echo json_encode($output);
    }
	
	function editAccountSuperAdminClinic(){
        $status = "error";
        $msg="";
        $flag = 0;

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email = $this->security->xss_clean($this->input->post('email'));   
		$isActive = $this->security->xss_clean($this->input->post('isActive'));   		

        $data = [];
        // Check Duplicate Username
        if(isset($username) && $username != ""){
            if(!($this->checkDuplicateUsername($username))){
                $msg = $this->lang->line("007");//"Username already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['username'] = $username;
            }
        }
        // Check Duplicate Email
        if(isset($email) && $email != ""){
            if(!($this->checkDuplicateEmail($email))){
                $msg = $this->lang->line("008");//"Email already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['email'] = $email;
            }
        }

        if(isset($password)){
            $data['password'] = $this->hash->hashPass($password);
        }

        $data['isActive'] = $isActive;
		$data['lastUpdated'] = $datetime;
        $data['lastUpdatedBy'] = $this->session->userdata('userID');

        if($flag == 0){
            $this->db->trans_begin();
            $query = $this->login_model->updateUser($id,$data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("002");//"Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = $this->lang->line("004");//"Master Clinic has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = $this->lang->line("002");//"Failed to save data Master ! ";
                }
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
	
	 function deleteSuperAdminClinic(){

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post("delID"));

        $data=array(
            'isActive'=>0,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        $query = $this->login_model->updateUser($id, $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = $this->lang->line("006");//"Cannot delete master in Database";
        } else {
            if ($query == 1) {
                $this->db->trans_commit();
                $status = "success";
                $msg = $this->lang->line("005");//"Clinic has been deleted successfully !";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("006");//"Failed to delete data Master ! ";
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
	
	private function checkDuplicateEmail($email){
        $result = false;
        if($email != ""){
            $check = $this->login_model->checkEmailExists($email);
            if($check == 0){
                $result = true;
            }
        }
        return $result;
    }

    private function checkDuplicateUsername($username){
        $result = false;
        if($username != ""){
            $check = $this->login_model->checkUsernameExists($username);
            if($check == 0){
                $result = true;
            }
        }
        return $result;
    }
	
    function is_logged_in(){
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