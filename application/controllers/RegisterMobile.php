<?php  
	class RegisterMobile extends CI_Controller{

		function __construct(){
			parent::__construct();

			$this->load->helper(array('form', 'url'));
			$this->load->helper('date');
			$this->load->helper('html');
		    $this->load->library("pagination");
		    $this->load->library('form_validation');
		    $this->load->library('email');

		    $this->load->model("Login_model");
		}

		public function createPatient(){
			$datetime = date('Y-m-d H:i:s', time());

			$status = "";
			$msg="";
			$userName = $this->input->post('username');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			$name = $this->input->post('name');

			$isExistsUsername = $this->Login_model->checkUsernameExists($userName);
			$isExistsEmail = $this->Login_model->checkEmailExists($email);

			if($isExistsUsername == true){
				$status = "error";
				$msg="Maaf, username sudah terpakai";
			}
			else if($isExistsEmail == true){
				$status = "error";
				$msg="Maaf, email sudah terpakai";
			}
			else{
				$data_patient = array(
						'userName' => $userName,
						'password' => md5($password),
						'email' => $email,
						'userRole' => 'patient',
						'isGoogle' => 0,
						'isActive'=> 1,
						'created' => $datetime,
						'createdBy' => $userName,
						'lastUpdated' => $datetime,
						'lastUpdatedBy' => $userName
					);

				$this->db->trans_begin();
				$query = $this->Login_model->insertUser($data_patient);

				if ($this->db->trans_status() === FALSE) {
	                // Failed to save Data to DB
	                $this->db->trans_rollback();
	                $status = 'error';
					$msg = "Maaf, Terjadi kesalahan saat melakukan registrasi user";
	            }
	            else{
	            	$data_patient = array(
						'userID' => $query,
						'patientName' => $name,
						'isActive'=> 1,
						'created' => $datetime,
						'createdBy' => $query,
						'lastUpdated' => $datetime,
						'lastUpdatedBy' => $query,
						'clinicID' => 1 // asumsi semntara masih di klinik omega
					);

					$query2 = $this->Login_model->insertPatient($data_patient);
					if ($this->db->trans_status() === FALSE) {
		                // Failed to save Data to DB
		                $this->db->trans_rollback();
		                $status = 'error';
						$msg = "Maaf, Terjadi kesalahan saat melakukan registrasi user";
		            }
		            else{
		            	$this->db->trans_commit();
        				$status = 'success';
						$msg = "Proses Registrasi berhasil";	
		            }
	            	
	            }
			}

			echo json_encode(array('status' => $status, 'msg' => $msg));
		}
	}
?>