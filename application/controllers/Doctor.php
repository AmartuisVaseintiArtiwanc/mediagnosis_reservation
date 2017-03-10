<?php

class Doctor extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        $this->load->library('Hash');
        $this->is_logged_in();
        $this->load->model('Login_model',"login_model");
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('SPoli_model',"spoli_model");
		$this->load->helper("language");
		$this->load->language("main", "bahasa");
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
        $data['master_title'] = 'Master Doctor';
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
            $row[] = $item['userID'];
            $row[] = $item['userName'];
            $row[] = $item['email'];
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
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email = $this->security->xss_clean($this->input->post('email'));
        $superUserID = $this->security->xss_clean($this->input->post('superUserID'));
        $datetime = date('Y-m-d H:i:s', time());

        $role = $this->session->userdata('role');
        if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
            $superUserID = $this->session->userdata('superUserID');
        }

        //Start
        $this->db->trans_begin();
        $save_account = $this->saveAccountDoctor($username, $password, $email, $superUserID);

        if($save_account['status'] == "success"){
            $data=array(
                'userID'=>$save_account['userID'],
                'doctorName'=>$name,
                'isActive'=>1,
                'created'=>$datetime,
                "createdBy" =>$superUserID,
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );

            if($this->checkDuplicateMaster($name,false,null,$superUserID)){
                $query = $this->doctor_model->createDoctor($data);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg=$this->lang->line("002");//"Cannot save master to Database";
                }
                else {
                    if($query==1){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg=$this->lang->line("001");//"Master Doctor has been added successfully.";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg=$this->lang->line("002");//"Failed to save data Master ! ";
                    }
                }
            }else{
                $status = "error";
                $msg=$name." ".$this->lang->line("003");//"This ".$name." Doctor already exist !";
            }
        }else{
            $status = "error";
            $msg = $save_account['msg'];
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
        $superUserID = $this->security->xss_clean($this->input->post('superUserID'));
        // OLD DATA
        //$old_data = $this->doctor_model->getDoctorByID($id);

        $data=array(
            'doctorName'=>$name,
            'isActive'=>$isActive,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name, true, $id,$superUserID)) {
            $this->db->trans_begin();
            $query = $this->doctor_model->updateDoctor($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("002");//"Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = $this->lang->line("004");//"Master Doctor has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = $this->lang->line("002");//"Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg=$name." ".$this->lang->line("003");//"This ".$name." Doctor already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function editAccountDoctor(){
        $status = "error";
        $msg="";
        $flag = 0;

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email = $this->security->xss_clean($this->input->post('email'));
        $superUserID = $this->security->xss_clean($this->input->post('superUserID'));

        $data = [];
        // Check Duplicate Username
        if(isset($username) && $username != ""){
            if(!($this->checkDuplicateUsername($username))){
                $msg = "Username already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['username'] = $username;
            }
        }
        // Check Duplicate Email
        if(isset($email) && $email != ""){
            if(!($this->checkDuplicateEmail($email))){
                $msg = "Email already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['email'] = $email;
            }
        }

        if(isset($password)){
            $data['password'] = $this->hash->hashPass($password);
        }

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

    private function saveAccountDoctor($username, $password, $email, $superUserID){
        $data['msg'] = '';
        $data['status'] = 'error';

        if(!($this->checkDuplicateUsername($username))){
            $data['msg'] = $this->lang->line("007");//"Username already exist !";

        }else if(!($this->checkDuplicateEmail($email))){
            $data['msg'] = $this->lang->line("008");//"Email already exist !";

        }else {
            $datetime = date('Y-m-d H:i:s', time());
            $data=array(
                'isActive'=>1,
                'userName'=>$username,
                'password'=>$this->hash->hashPass($password),
                'email'=>$email,
                'userRole'=>"doctor",
                'superUserID'=>$superUserID,
                'created'=>$datetime,
                "createdBy" => $this->session->userdata('userID'),
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $query = $this->login_model->insertUser($data);
            $data['status'] = 'success';
            // set Inserted ID
            $data['userID'] = $query;
        }
        return $data;
    }

    function checkDuplicateMaster($name,$isEdit,$doctorID,$superUserID=""){
        //Check Super Admin Clinic
        $role = $this->session->userdata('role');
        if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
            $superUserID = $this->session->userdata('superUserID');
        }

        $query = $this->doctor_model->getDoctorByName($name, $isEdit, $doctorID,$superUserID);

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
            $msg = $this->lang->line("006");//"Cannot save master to Database";
        } else {
            if ($query == 1) {
                $this->db->trans_commit();
                $status = "success";
                $msg = $this->lang->line("005");//"Master Doctor has been updated successfully.";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("006");//"Failed to save data Master ! ";
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