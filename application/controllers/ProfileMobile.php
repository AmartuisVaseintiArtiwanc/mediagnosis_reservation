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
                $patient_data = $this->getPatientData($userID);
                if($patient_data){
                    $status="success";
                    $data = $patient_data;
                    $user = $data_user;
                }else{
                    $data="";
                    $status="error";
                }
            }else if($role == "doctor"){
                // Get Doctor Data
                $doctor_data = $this->getDoctorData($userID);
                if($doctor_data){
                    $status="success";
                    $data = $doctor_data;
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

    public function saveDataAccount(){
        $this->load->model('Login_model');
        $datetime = date('Y-m-d H:i:s', time());

        $status="error";
        $msg="Error";

        $err_flag=0;

        $userID = $this->security->xss_clean($this->input->post('userID'));
        $username = $this->security->xss_clean($this->input->post('profile_username'));
        $email = $this->security->xss_clean($this->input->post('profile_email'));

        $updated_data = array();
        $updated_data['lastUpdated'] = $datetime;
        $updated_data['lastUpdatedBy'] = $userID;

        if($userID != null || $userID != ""){

            if(!empty($username)){
                // Check Duplicate Username
                if($this->checkUpdatedUsername($userID,$username)){
                    // Set Data to Update
                    $status="success";
                    $updated_data['userName'] = $username;
                }else{
                    $err_flag=1;
                    $status="error";
                    $msg="Username ini sudah terdaftar, coba gunakan username yang lain !";
                }
            }
            if(!empty($email)){
                // Check Duplicate Email
                if($this->checkUpdatedEmail($userID,$email)){
                    // Set Data to Update
                    $status="success";
                    $updated_data['email'] = $email;
                }else{
                    $err_flag=1;
                    $status="error";
                    $msg="Email ini sudah terdaftar, coba gunakan email yang lain !";
                }
            }

            // Check if no error flag
            if($err_flag == 0){
                if(isset($_POST['profile_image'])){
                    $this->updateProfilePicture();
                    $updated_data['userImage'] = $_POST['filename'];
                    $status="success";
                }
            }

            $this->db->trans_begin();
            $res = $this->Login_model->updateUser($userID,$updated_data);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";

            }else{
                if($res==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Akun Anda berhasil di perbaharui..";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";
                }
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }


    private function updateProfilePicture(){
        //header('Content-Type: bitmap; charset=utf-8');
        if($_SERVER['REQUEST_METHOD']=='POST'){

            $dir = "./user_profile/";
            $image = $_POST['profile_image'];
            $filename = $_POST['filename'];
            $binary=base64_decode($image);

            $file = fopen($dir.$filename, 'wa+');
            // Create File
            fwrite($file, $binary);
            fclose($file);

            //mkdir("./user_profile");
            //file_put_contents($dir.$filename, $binary);
        }
    }

    public function saveDataProfile(){
        $err_flag=0;
        $status="error";
        $msg="Error";
        $datetime = date('Y-m-d H:i:s', time());
        $this->load->model('Login_model');

        $userID = $this->security->xss_clean($this->input->post('userID'));
        $name = $this->security->xss_clean($this->input->post('profile_name'));
        $ktp = $this->security->xss_clean($this->input->post('profile_ktp'));
        $bpjs = $this->security->xss_clean($this->input->post('profile_bpjs'));
        $dob = $this->security->xss_clean($this->input->post('profile_dob'));
        $gender = $this->security->xss_clean($this->input->post('profile_gender'));
        $address = $this->security->xss_clean($this->input->post('profile_address'));
        $phone_number = $this->security->xss_clean($this->input->post('profile_phone_number'));

        if(!empty($userID)){
            // Check Kosong
            if(empty($name)||empty($ktp)||empty($bpjs)||empty($dob)||empty($gender)||empty($address)||empty($phone_number)){
                $err_flag = 1;
                $status="error";
                $msg="Maaf terdapat input yang kosong, silahkan diperiksa kembali..";
            }else{
                $dob = date('Y-m-d', strtotime ($dob));
                $data=array(
                    'patientName'=>$name,
                    'ktpID'=>$ktp,
                    'bpjsID'=>$bpjs,
                    'gender'=>$gender,
                    'phoneNumber'=>$phone_number,
                    'address'=>$address,
                    'dob'=>$dob,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$userID
                );

                $this->db->trans_begin();
                $res = $this->Login_model->updatePatient($userID,$data);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";

                }else{
                    if($res==1){
                        $this->db->trans_commit();
                        $status = "success";
                        $msg="Profil Anda berhasil di perbaharui..";
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";
                    }
                }
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    private function checkUpdatedUsername($userID, $username){
        $valid = $this->Login_model->checkUpdatedUsernameExists($userID,$username);
        return $valid;
    }
    private function checkUpdatedEmail($userID, $email){
        $valid = $this->Login_model->checkUpdatedEmailExists($userID,$email);
        return $valid;
    }

    public function changePasswordAccount(){
        $datetime = date('Y-m-d H:i:s', time());
        $status="error";
        $msg="Error";

        $userID = $this->security->xss_clean($this->input->post('userID'));
        $password = $this->security->xss_clean($this->input->post('userPassword'));

        $this->load->model('Login_model');
        $valid = $this->Login_model->checkPassowrdByUserID($userID,$password);
        if($valid){
            $data=array(
                'password'=>$password,
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$userID
            );
            $this->db->trans_begin();
            $res = $this->Login_model->updateUser($userID,$data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";

            }else{
                if($res==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Password Anda berhasil di perbaharui..";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";
                }
            }
        }else{
            $status = "error";
            $msg="Maaf Password lama Anda tidak valid !";
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
}
?>