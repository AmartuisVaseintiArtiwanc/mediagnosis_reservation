<?php  

class LoginMobile extends CI_Controller{

	public function validateLogin(){
        $status = "";
        $msg="";
        $userID = "";
        $username = "";
        $patientName = "";

		$this->load->model('login_model');
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));

		$user = $this->login_model->validateByEmail($email, $password);
		
		if($user != "" || $user != null) // if the user's credentials validated...
		{

			$userID = $user->userID;
			$username = $user->userName;
			$patientname = $user->patientName;
            $status = 'success';
            $msg = "Proses Login Berhasil";
		}
		else // incorrect username or password
		{
			$userID = 0;
			$username = "";
			$patientName = "";
            $status = 'error';
            $msg = "Username atau Password kurang tepat ";
		}
        echo json_encode(array('userID' => $userID , 'username' => $username, 'patientName' => $patientname ,'status' => $status, 'msg' => $msg));
	}
}
?>