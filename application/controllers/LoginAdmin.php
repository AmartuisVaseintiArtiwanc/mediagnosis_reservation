<?php

class LoginAdmin extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->helper('date');

        $this->load->library('Hash');
        $this->load->library("Authentication");

        $this->load->model('Login_admin_model');
    }

	public function index(){
		$this->load->view('admin/template/loginAdmin');
	}

    public function dashboardAdmin(){
        $data['main_content'] = '';
        $this->load->view('admin/template/template', $data);
    }
	
	public function validateLogin(){
        $status = "";
        $msg="";

        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        $userVerifier = $this->Login_admin_model->getUserDataByUsername($username);
        //$user = $this->login_model->validate($username, $password);

        if(isset($userVerifier)){
            if($this->authentication->isAuthorizeAdminMediagnosis($userVerifier->userRole)){
                if($this->hash->verifyPass($password, $userVerifier->password)){
                    $data = array(
                        'userID' => $userVerifier->userID,
                        'userName' => $userVerifier->userName,
                        'superUserID' => $userVerifier->superUserID,
                        'role' => $userVerifier->userRole,
                        'is_logged_in' => true
                    );
                    $this->session->set_userdata($data);
                    $status = 'success';
                    $msg = "";
                }else{
                    $status = 'error';
                    $msg = "Username or Password is Wrong ! ";
                }
            }else{
                $status = 'error';
                $msg = "You can't access this page ! ";
            }
        }else{
            $status = 'error';
            $msg = "Username or Password is Wrong ! ";

        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
	}	
	
	public function signup()
	{
		$data['main_content'] = 'signup_form';
		$this->load->view('includes/template', $data);
	}
	
	public function create_member()
	{
		$this->load->library('form_validation');
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('first_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
		
		
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('signup_form');
		}
		
		else
		{			
			$this->load->model('membership_model');
			
			if($query = $this->membership_model->create_member())
			{
				$data['main_content'] = 'signup_successful';
				$this->load->view('includes/template', $data);
			}
			else
			{
				$this->load->view('signup_form');			
			}
		}
		
	}

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("LoginAdmin");
            redirect($url_login, 'refresh');
        }else{
            $data['main_content'] = '';
            $this->load->view('admin/template/template',$data);
        }
    }
	
	public function logout()
	{
		$this->session->sess_destroy();
		$this->index();
	}

}