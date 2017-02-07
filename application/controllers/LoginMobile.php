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
}
?>