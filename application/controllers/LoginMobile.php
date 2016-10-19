<?php  

class LoginMobile extends CI_Controller{

	public function validateLogin(){
        $status = "";
        $msg="";
        $userID = "";

		$this->load->model('login_model');
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));

		$user = $this->login_model->validateByEmail($email, $password);
		
		if($user != "" || $user != null) // if the user's credentials validated...
		{

			$userID = $user->userID;
            $status = 'success';
            $msg = "";
		}
		else // incorrect username or password
		{
			$userID = 0;
            $status = 'error';
            $msg = "Username or Password is Wrong ! ";
		}
        echo json_encode(array('userID' => $userID ,'status' => $status, 'msg' => $msg));
	}
}
?>