<?php  

class LoginMobile extends CI_Controller{

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

		$user = $this->login_model->validateByEmail($email, $password);
		
		if($user != "" || $user != null) // if the user's credentials validated...
		{

			$userID = $user->userID;
			$username = $user->userName;
			$role = $user->userRole;
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
			//$patientname = $user->patientName;
            
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
}
?>