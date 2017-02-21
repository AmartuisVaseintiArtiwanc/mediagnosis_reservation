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
        echo json_encode(array('userID' => $userID, 'username' => $username,'role' => $role, 'name' => $name,'status' => $status, 'msg' => $msg));
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

			}else{
				$this->db->trans_rollback();
			}
		}
		
	}
	
	public function resetPassword(){
        $status="error";
        $msg="Error";
        $datetime = date('Y-m-d H:i:s', time());
		$this->load->model('Login_model');
		
		$email = $this->security->xss_clean($this->input->post('email'));
        $random_string = $this->security->xss_clean($this->input->post('random_string'));
		
		if(empty($email) || empty($random_string)){
			$status="error";
			$msg="Maaf terdapat input yang kosong, silahkan diperiksa kembali..";
		}
		else{
			$isExistsEmail = $this->Login_model->checkEmailExists($email);
			if($isExistsEmail == 0){
				$status="error";
				$msg="Maaf email yang anda masukkan belum terdaftar ..";
			}
			else{
				$data = array(
					"password"=>$this->hash->hashPass($random_string),
					"lastUpdated"=>$datetime,
					"lastUpdatedBy"=>"the_forgotter"
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
						
						$this->sendEmail($email, $random_string);
                    }else{
                        $this->db->trans_rollback();
                        $status = "error";
                        $msg="Maaf password Anda tidak dapat tereset, cobalah beberapa saat lagi !";
                    }
                }
			}
		}
		echo json_encode(array('status' => $status, 'msg' => $msg));
	}
	
	public function sendEmail($email, $random_string){
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
	}
}
?>