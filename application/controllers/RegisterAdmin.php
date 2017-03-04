<?php

    class RegisterAdmin extends CI_Controller{
        function __construct(){
            parent::__construct();

            $this->load->helper(array('form', 'url','security','date'));
            $this->load->helper('date');
            $this->load->helper('html');
            $this->load->library('Hash');
            $this->load->library("Authentication");
            $this->load->model('Login_admin_model',"login_admin_model");
            $this->load->model('Login_model',"login_model");
            $this->load->model('Clinic_model',"clinic_model");
            $this->load->model('Doctor_model',"doctor_model");
            $this->is_logged_in_admin();
        }

        function goToAddAdminForm(){
            $data['main_content'] = 'admin/register/register_admin_add_view';
            $this->load->view('admin/template/template', $data);
        }

        function goToAddClinicForm(){
            $data['main_content'] = 'admin/register/register_clinic_add_view';
            $this->load->view('admin/template/template', $data);
        }

        function goToAddDoctorForm(){
            $data['main_content'] = 'admin/register/register_doctor_add_view';
            $this->load->view('admin/template/template', $data);
        }

        function getLookupAdminList(){

            $result = $this->login_admin_model->getUserDataByUserRole("super_admin");

            echo json_encode(array('data' => $result));
        }

        function createAdmin(){
            $status = "error";
            $msg="";

            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $email = $this->security->xss_clean($this->input->post('email'));

            if(!($this->checkDuplicateUsername($username))){
                $msg="Username already exist !";
            }else if(!($this->checkDuplicateEmail($email))){
                $msg="Email already exist !";
            }else{
                $datetime = date('Y-m-d H:i:s', time());
                $data=array(
                    'isActive'=>1,
                    'userName'=>$username,
                    'password'=>$this->hash->hashPass($password),
                    'email'=>$email,
                    'userRole'=>"super_admin",
                    'superUserID'=>0,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );

                $this->db->trans_begin();
                $query = $this->login_model->insertUser($data);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Cannot save to Database";
                }
                else {
                    if(isset($query)){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg="User has been added successfully.";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Failed to save User ! ";
                    }
                }
            }
            echo json_encode(array('status' => $status, 'msg' => $msg));
        }

        function createClinic(){
            $status = "error";
            $msg="";

            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $superUser = $this->security->xss_clean($this->input->post('superUser'));
            $clinic_name = $this->security->xss_clean($this->input->post('clinic'));

            if(!($this->checkDuplicateUsername($username))){
                $msg="Username already exist !";
            }else if(!($this->checkDuplicateEmail($email))){
                $msg="Email already exist !";
            }else{
                $datetime = date('Y-m-d H:i:s', time());
                $data=array(
                    'isActive'=>1,
                    'userName'=>$username,
                    'password'=>$this->hash->hashPass($password),
                    'email'=>$email,
                    'userRole'=>"admin",
                    'superUserID'=>$superUser,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );

                $this->db->trans_begin();
                $account_id = $this->login_model->insertUser($data);

                $data_clinic=array(
                    'isActive'=>1,
                    'clinicName'=>$clinic_name,
                    'userID'=>$account_id,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $superUser,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$superUser
                );

                $clinic_query = $this->clinic_model->createClinic($data_clinic);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Cannot save to Database";
                }
                else {
                    if(isset($account_id) && isset($clinic_query)){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg="User has been added successfully.";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Failed to save User ! ";
                    }
                }
            }
            echo json_encode(array('status' => $status, 'msg' => $msg));
        }

        function createDoctor(){
            $status = "error";
            $msg="";

            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean($this->input->post('password'));
            $email = $this->security->xss_clean($this->input->post('email'));
            $superUser = $this->security->xss_clean($this->input->post('superUser'));
            $doctor_name = $this->security->xss_clean($this->input->post('doctor'));

            if(!($this->checkDuplicateUsername($username))){
                $msg="Username already exist !";
            }else if(!($this->checkDuplicateEmail($email))){
                $msg="Email already exist !";
            }else{
                $datetime = date('Y-m-d H:i:s', time());
                $data=array(
                    'isActive'=>1,
                    'userName'=>$username,
                    'password'=>$this->hash->hashPass($password),
                    'email'=>$email,
                    'userRole'=>"doctor",
                    'superUserID'=>$superUser,
                    'created'=>$datetime,
                    "createdBy" => $this->session->userdata('userID'),
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );

                $this->db->trans_begin();
                $account_id = $this->login_model->insertUser($data);

                $data_doctor=array(
                    'isActive'=>1,
                    'doctorName'=>$doctor_name,
                    'userID'=>$account_id,
                    'isActive'=>1,
                    'created'=>$datetime,
                    "createdBy" => $superUser,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$superUser
                );

                $doctor_query = $this->doctor_model->createDoctor($data_doctor);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Cannot save to Database";
                }
                else {
                    if(isset($account_id) && isset($doctor_query)){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg="User has been added successfully.";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Failed to save User ! ";
                    }
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

?>