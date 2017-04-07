<?php

class Profile extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->helper('date');

        $this->load->library('Hash');
        $this->load->library("Authentication");
        $this->load->helper("language");
        $this->load->language("main", "bahasa");

        $this->load->model('Login_model','login_model');
        $this->load->model('Clinic_model','clinic_model');

        $this->is_logged_in();
    }

    public function updateProfile(){

        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('userID');

        $account = $this->login_model->getUserDataByUserID($userID);
        $data['account'] = $account;

        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            $data['main_content'] = 'profile/update_admin_mediagnosis_view';
            $this->load->view('admin/template/template', $data);

        }else if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'profile/update_super_admin_view';
            $this->load->view('template/template', $data);

        }else if($this->authentication->isAuthorizeAdmin($role)){
            $data['clinic'] = $this->clinic_model->getClinicByUserID($userID);
            $data['main_content'] = 'profile/update_admin_clinic_view';
            $this->load->view('template/template', $data);
        }

    }

    function editAccount(){
        $status = "error";
        $msg="";
        $flag = 0;

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        $email = $this->security->xss_clean($this->input->post('email'));

        $data = [];
        // Check Duplicate Username
        if(isset($username) && $username != ""){
            if(!($this->checkUpdateDuplicateUsername($id,$username))){
                $msg = $this->lang->line("007");//"Username already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['username'] = $username;
            }
        }
        // Check Duplicate Email
        if(isset($email) && $email != ""){
            if(!($this->checkUpdateDuplicateEmail($id,$email))){
                $msg = $this->lang->line("008");//"Email already exist !";
                $status = "error";
                $flag++;
            }else{
                $data['email'] = $email;
            }
        }

        if(isset($password) && $password != ""){
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


    function editClinic(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $address = $this->security->xss_clean($this->input->post('address'));
        $lng = $this->security->xss_clean($this->input->post('lng'));
        $lat = $this->security->xss_clean($this->input->post('lat'));
        $superUserID = $this->session->userdata('superUserID');

        $data=array(
            'clinicName'=>$name,
            'clinicAddress'=>$address,
            'latitude'=>$lat,
            'longitude'=>$lng,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name, true, $id,$superUserID)) {
            $this->db->trans_begin();
            $query = $this->clinic_model->updateClinic($data, $id);

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
        }else{
            $status = "error";
            $msg=$name." ".$this->lang->line("003");//"This ".$name." Clinic already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function checkDuplicateMaster($name, $isEdit, $clinicID, $superUserID=""){
        //Check Super Admin Clinic
        $role = $this->session->userdata('role');
        $superUserID = $this->session->userdata('superUserID');
        $query = $this->clinic_model->checkDupicateClinicName($name, $isEdit, $clinicID, $superUserID);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    private function checkUpdateDuplicateEmail($userID,$email){
        $result = false;
        if($email != ""){
            $check = $this->login_model->checkUpdatedEmailExists($userID,$email);
            $result = $check;
        }
        return $result;
    }

    private function checkUpdateDuplicateUsername($userID,$username){
        $result = false;
        if($username != ""){
            $check = $this->login_model->checkUpdatedUsernameExists($userID,$username);
            $result = $check;
        }
        return $result;
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            echo 'You don\'t have permission to access this page. <a href="'.$url_login.'"">Login</a>';
            die();
            $this->load->view('login_form');
        }
    }

}