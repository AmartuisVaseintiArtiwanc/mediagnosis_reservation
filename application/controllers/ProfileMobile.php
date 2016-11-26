<?php

class ProfileMobile extends CI_Controller{

    public function getUserProfile(){
        $this->load->model('Login_model');
        $userID = $this->security->xss_clean($this->input->post('userID'));
        $status="error";
        $msg="";
        $data="";
        $user="";

        $check_role = $this->Login_model->getUserRoleByUserID($userID);
        // Check Existing Account and Get User Role
        if(isset($check_role)){
            // Set User Data
            $data_user = new stdClass();
            $data_user->userID = $check_role->userID;
            $data_user->userRole = $check_role->userRole;
            $data_user->userName = $check_role->userName;
            $data_user->userImage = $check_role->userImage;
            $data_user->email = $check_role->email;
            $data_user->isGoogle = $check_role->isGoogle;

            // Check User Role
            $role = $check_role->userRole;
            if($role == "patient"){
                // Get Patient Data
                $data = $this->getPatientData($userID);
                if($data){
                    $status="success";
                    $user = $data_user;
                }else{
                    $data="";
                    $status="error";
                }
            }else if($role == "doctor"){
                // Get Doctor Data
                $data = $this->getDoctorData($userID);
                if($data){
                    $status="success";
                    $user = $data_user;
                }else{
                    $data="";
                    $status="error";
                }
            }
        }else{
            $status="error";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg, 'data' => $data,'user' => $user));
    }

    private function getPatientData($userID){
        $patient = $this->Login_model->getDetailPatientData($userID);

        if(isset($patient)){
            return $patient;
        }else{
            return false;
        }

    }

    private function getDoctorData($userID){
        $doctor = $this->Login_model->getDetailDoctorData($userID);

        if(isset($doctor)){
            return $doctor;
        }else{
            return false;
        }
    }

    public function saveDataProfile(){

    }
}
?>