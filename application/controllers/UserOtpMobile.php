<?php  
	class UserOtpMobile extends CI_Controller{
		function __construct(){
			parent::__construct();

			$this->load->helper(array('form', 'url'));
			$this->load->helper('date');
			$this->load->helper('html');
		    $this->load->library("pagination");
		    $this->load->library('form_validation');
		    $this->load->library('email');

		    $this->load->model("Patient_model");
		    $this->load->model("UserOtp_model");
		}

		function generateUserOtp(){
			$datetime = date("Y-m-d h:i:s", strtotime("now"));
			$expireTime = date("Y-m-d h:i:s", strtotime("+30 minutes"));
			$OtpCode = $this->input->post("otpCode"); 
			$userID = $this->input->post("userID");
            $patient_data = $this->Patient_model->getPatientByUserID($userID);
            $patientID = $patient_data->patientID;
            $exisitingOtp_data = $this->UserOtp_model->checkOtpByPatientID($patientID);

            if($userID == null || $userID == ""){
            	echo json_encode("empty");
            }
            else{
                if($exisitingOtp_data == 1){
                    //udah ada
                    $data_otp = array(
                            'otpCode' => $OtpCode,
                            'expireTime' => $expireTime,
                            'lastUpdated' => $datetime,
                            'lastUpdatedBy' => $userID
                        );

                    $this->db->trans_begin();
                    $query = $this->UserOtp_model->updateOtp($patientID ,$data_otp);
                    if ($this->db->trans_status() === FALSE) {
                        // Failed to save Data to DB
                        $this->db->trans_rollback();
                        $status = 'error';
                        $msg = "Maaf, Terjadi kesalahan saat melakukan generasi otp";
                    }
                    else{
                        $this->db->trans_commit();
                        $status = 'success';
                        $msg = "Proses generasi otp berhasil";
                    }

                }else{
                    //belom ada
                    $data_otp = array(
                            'patientID' => $patientID,
                            'otpCode' => $OtpCode,
                            'expireTime' => $expireTime,
                            'isActive' => 1,
                            'created' => $datetime,
                            'createdBy' => $userID,
                            'lastUpdated' => $datetime,
                            'lastUpdatedBy' => $userID
                        );

                    $this->db->trans_begin();
                    $query = $this->UserOtp_model->createOtp($data_otp);
                    if ($this->db->trans_status() === FALSE) {
                        // Failed to save Data to DB
                        $this->db->trans_rollback();
                        $status = 'error';
                        $msg = "Maaf, Terjadi kesalahan saat melakukan generasi otp";
                    }
                    else{
                        $this->db->trans_commit();
                        $status = 'success';
                        $msg = "Proses generasi otp berhasil";
                    }
                }
		        echo json_encode(array('status' => $status, 'msg' => $msg));
		    }
		}
	}

?>