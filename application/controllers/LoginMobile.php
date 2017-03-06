<?php  

class LoginMobile extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->library('Hash');
    }

	public function validateLogin(){
        $status = "";
        $msg="";
        $userID = "";
        $username = "";
        $role = "";
        $name = "";

		$this->load->model('login_model');
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));

        $userVerifier = $this->login_model->validateByEmail($email);
        if(isset($userVerifier)){
            // Check Hash Password
            if($this->hash->verifyPass($password, $userVerifier->password)){

                $userID = $userVerifier->userID;
                $username = $userVerifier->userName;
                $role = $userVerifier->userRole;
                $userImage = $userVerifier->userImage;
                if($role == "patient"){
                    $detail = $this->login_model->getDetailPatientData($userID);
                    $name = $detail->patientName;
                    $status = 'success';
                    $msg = "Proses Login Berhasil";
                }
                else if($role == "doctor"){
                    $detail = $this->login_model->getDetailDoctorData($userID);
                    $name = $detail->doctorName;
                    $status = 'success';
                    $msg = "Proses Login Berhasil";
                }
                else{
                    $userID = 0;
                    $username = "";
                    $role="";
                    $patientName = "";
                    $status = 'error';
                    $msg = "Role username tidak cocok ";
                }
            }else{
                $userID = 0;
                $username = "";
                $role="";
                $patientName = "";
                $status = 'error';
                $msg = "Username atau Password kurang tepat ";
            }
		}
		else // incorrect username or password
		{
			$userID = 0;
			$username = "";
			$role="";
			$patientName = "";
            $status = 'error';
            $msg = "Username atau Password kurang tepat ";
		}
        echo json_encode(array('userID' => $userID, 'username' => $username,'role' => $role, 'name' => $name, 'image'=>$userImage,
            'status' => $status, 'msg' => $msg));
	}
	
	public function updateToken(){
		$this->load->model('Doctor_model');
		$this->load->model('Patient_model');
		
		$token = $this->input->post("token");
		$role = $this->input->post("role");
		$userID = $this->input->post("userID");
		
		$datetime = date('Y-m-d H:i:s', time());
		
		$data_token = array(
			"token" => $token,
			"lastUpdated" => $datetime,
			"lastUpdatedBy" => $userID
		);
		
		if($role == "patient"){
			//update token tabel pasien dengan id user tertentu
			$this->db->trans_begin();
			$res = $this->Patient_model->updatePatient($userID,$data_token);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$status = "error";
				$msg="Maaf terjadi kesalahan token !";
			}
			else{
				if($res==1){
					$this->db->trans_commit();
					$status = "success";
					$msg="Proses reroll token berhasil";
					
					$this->updateIsOnline(1, $userID);
				}else{
					$this->db->trans_rollback();
					$status = "error";
					$msg="Maaf terjadi kesalahan token !";
				}
			}
				
		}else if($role == "doctor"){
			//update token tabel dokter dengan id user tertentu
			$this->db->trans_begin();
			$res = $this->Doctor_model->updateDoctorByUserID($data_token,$userID);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$status = "error";
				$msg="Maaf terjadi kesalahan token !";
			}
			else{
				if($res==1){
					$this->db->trans_commit();
					$status = "success";
					$msg="Proses reroll token berhasil";
					
					$this->updateIsOnline(1, $userID);
				}else{
					$this->db->trans_rollback();
					$status = "error";
					$msg="Maaf terjadi kesalahan token !";
				}
			}
		}else{
			$status = "error";
			$msg="Maaf terjadi kesalahan token !";
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	
	public function updateIsOnline($isOnline, $userID){
		$this->load->model('Login_model');
		$data_online = array(
			"isOnline" => $isOnline
		);
		
		$this->db->trans_begin();
		$res = $this->Login_model->updateUser($userID,$data_online);
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		}
		else{
			if($res==1){
				$this->db->trans_commit();
				if($isOnline == 0){
					$this->clearToken($userID);
				}

			}else{
				$this->db->trans_rollback();
			}
		}
		
	}
	
	public function clearToken($userID){
		$this->load->model('Login_model');
		$this->load->model('Doctor_model');
		$this->load->model('Patient_model');
		
		$datetime = date('Y-m-d H:i:s', time());
		$userData = $this->Login_model->getUserDataByUserID($userID);
		
		$data_token = array(
			"token" => "",
			"lastUpdated" => $datetime,
			"lastUpdatedBy" => $userID
		);
		if($userData->userRole == "patient"){
			//update token tabel pasien dengan id user tertentu
			$this->db->trans_begin();
			$res = $this->Patient_model->updatePatient($userID,$data_token);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			}
			else{
				if($res==1){
					$this->db->trans_commit();
				}else{
					$this->db->trans_rollback();
				}
			}
				
		}else if($userData->userRole == "doctor"){
			//update token tabel dokter dengan id user tertentu
			$this->db->trans_begin();
			$res = $this->Doctor_model->updateDoctorByUserID($data_token,$userID);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			}
			else{
				if($res==1){
					$this->db->trans_commit();
				}else{
					$this->db->trans_rollback();
				}
			}
		}
		
	}
	
	public function emailHandler(){
		$mode = $this->input->get("mode");
		if($mode == "resetPassword"){
			$this->load->view("registration/reset_password_view");
		}else if($mode == "verifyEmail"){
			$this->load->view("registration/verify_email_view");
		}else if ($mode == "recoverEmail"){
			$this->load->view("registration/recover_email_view");
		}
	}
	
	public function doResetPassword(){
        $status="error";
        $msg="Error";
        $datetime = date('Y-m-d H:i:s', time());
		$this->load->model('Login_model');
		
		$email = $this->security->xss_clean($this->input->post('email'));
        $new_password = $this->security->xss_clean($this->input->post('new_password'));
		
		if(empty($email) || empty($new_password)){
			$status="error";
			$msg="Maaf terdapat input yang kosong, silahkan diperiksa kembali..";
		}
		else{
			$data = array(
				"password"=>$this->hash->hashPass($new_password),
				"lastUpdated"=>$datetime,
				"lastUpdatedBy"=>$email
			);
			
			$this->db->trans_begin();
			$res = $this->Login_model->updateUserByEmail($data,$email);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$status = "error";
				$msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";

			}else{
				if($res==1){
					$this->db->trans_commit();
					$status = "success";
					$msg="Password anda berhasil tereset. Silahkan cek email anda ..";
				}else{
					$this->db->trans_rollback();
					$status = "error";
					$msg="Maaf password Anda tidak dapat tereset, cobalah beberapa saat lagi !";
				}
			}
			
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	
	public function doRevertEmail(){
		$status="error";
        $msg="Error";
        $datetime = date('Y-m-d H:i:s', time());
		$this->load->model('Login_model');
		
		$newEmail = $this->security->xss_clean($this->input->post('newEmail'));
		$oldEmail = $this->security->xss_clean($this->input->post('oldEmail'));
		
		if(empty($newEmail) || empty($oldEmail)){
			$status="error";
			$msg="Maaf terdapat input yang kosong, silahkan diperiksa kembali..";
		}else{
			$data = array(
				"email"=>$oldEmail,
				"lastUpdated"=>$datetime,
				"lastUpdatedBy"=>$oldEmail
			);
			
			$this->db->trans_begin();
			$res = $this->Login_model->updateUserByEmail($data,$newEmail);
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$status = "error";
				$msg="Maaf data Anda tidak dapat tersimpan, cobalah beberapa saat lagi !";

			}else{
				if($res==1){
					$this->db->trans_commit();
					$status = "success";
					$msg="Email anda berhasil di kembalikan ke semula";
				}else{
					$this->db->trans_rollback();
					$status = "error";
					$msg="Maaf email Anda tidak dapat di revert, cobalah beberapa saat lagi !";
				}
			}
			
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	
	/*public function sendEmail($email, $random_string){
		// kirim email
		$this->load->library('email');
		$config = Array(
                'protocol' => 'mail',
                'smtp_host' => 'cyberits.co.id',
                'smtp_port' => 25,
                'smtp_user' => 'no-reply@cyberits.co.id',
                'smtp_pass' => 'Pass@word1',
                'mailtype'  => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
		$message =  'New Password = '.$random_string;
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
        $this->email->from('no-reply@cyberits.co.id', 'Feedback System'); // nanti diganti dengan email mediagnosis
        $this->email->to($email); 
        $this->email->subject('[MEDIGNOSIS] RESET PASSWORD');
		$this->email->message($message);
		if(!$this->email->send()){
            show_error($this->email->print_debugger());
        }
	}*/
}
?>