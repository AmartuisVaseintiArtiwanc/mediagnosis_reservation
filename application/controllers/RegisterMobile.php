<?php  
	class RegisterMobile extends CI_Controller{

		function __construct(){
			parent::__construct();

			$this->load->helper(array('form', 'url'));
			$this->load->helper('date');
			$this->load->helper('html');
            $this->load->library('Hash');
		    $this->load->library("pagination");
		    $this->load->library('form_validation');
		    $this->load->library('email');

		    $this->load->model("Login_model");
		    $this->load->model("Patient_model");
		}

		public function createPatient(){
			$datetime = date('Y-m-d H:i:s', time());

			$status = "";
			$msg="";
			$ID = 0;
			
			$userName = $this->input->post('username');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			$name = $this->input->post('name');
			$idNumber = $this->input->post('idNumber');

			$isExistsUsername = $this->Login_model->checkUsernameExists($userName);
			$isExistsEmail = $this->Login_model->checkEmailExists($email);
			$isExistsIDNumber = $this->Patient_model->checkIDNumberExists($idNumber);
			$isTemp = $this->Patient_model->checkTemporaryPatient($idNumber);

			if($isExistsUsername == true){
				$status = "error";
				$msg="Maaf, username sudah terpakai";
				$ID = 0;
			}
			else if($isExistsEmail == true){
				$status = "error";
				$msg="Maaf, email sudah terpakai";
				$ID = 0;
			}
			else if($isExistsIDNumber == true){
				$status = "error";
				$msg="Maaf, No. KTP sudah terpakai";
				$ID = 0;	
			}
			else{
				$data_patient = array(
						'userName' => $userName,
						'password' => $this->hash->hashPass($password),
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
	            	if($isTemp == true){
						$tempPatient = $this->Patient_model->getTemporaryPatientID($idNumber);
						$data_patient = array(
							'userID' => $query,
							'isTemp' => 0,
							'lastUpdated' => $datetime,
							'lastUpdatedBy' => $query
						);

						$query2 = $this->Patient_model->updatePatientByPatientID($tempPatient->patientID, $data_patient);
						if ($this->db->trans_status() === FALSE) {
			                // Failed to save Data to DB
			                $this->db->trans_rollback();
			                $status = 'error';
							$msg = "Maaf, Terjadi kesalahan saat justifikasi registrasi user offline";
			            }
			            else{
			            	$this->db->trans_commit();
	        				$status = 'success';
							$msg = "Proses Registrasi berhasil";	
							$ID = $query;
			            }
					}else{

		            	$data_patient = array(
							'userID' => $query,
							'patientName' => $name,
							'ktpID' => $idNumber,
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
							$ID = $query;
			            }
		        	}
	            	
	            }
			}

			echo json_encode(array('status' => $status, 'msg' => $msg, 'ID' => $ID));
		}
		
		public function createPatientByGoogle(){
			$datetime = date('Y-m-d H:i:s', time());

			$status = "";
			$msg="";
			$ID=0;
			$email = $this->input->post('email');
			$name = $this->input->post('name');

			$isExistsEmail = $this->Login_model->checkEmailExists($email);

			if($isExistsEmail == true){
				$status = "error";
				$msg="Maaf, email sudah terpakai";
				$ID=0;
			}
			else{
				$data_patient = array(
						'email' => $email,
						'userRole' => 'patient',
						'isGoogle' => 1,
						'isActive'=> 1,
						'created' => $datetime,
						'createdBy' => $email,
						'lastUpdated' => $datetime,
						'lastUpdatedBy' => $email
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
						$ID = $query;
		            }
	            	
	            }
			}

			echo json_encode(array('status' => $status, 'msg' => $msg, 'ID' => $ID));
		}
		
		public function checkEmailAlreadyExists(){
			$email = $this->input->post("email");

			$result = $this->Login_model->checkEmailExists($email);
			
			if($result == 1){
				$user = $this->Login_model->getIDByEmail($email);
				$ID = $user->userID;
			}
			else{
				$ID = 0;
			}

			echo json_encode(array("result" => $result, "ID" => $ID));	
		}
	}
?>