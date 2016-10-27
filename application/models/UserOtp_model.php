<?php 
	class UserOtp_model extends CI_Model {


		function createOtp($data){
		    $this->db->insert('tbl_cyberits_t_user_otp',$data);
			$result=$this->db->affected_rows();
			return $result;
		}

		function updateOtp($patientID,$data){
			$this->db->where('patientID',$patientID);
			$this->db->update('tbl_cyberits_t_user_otp',$data);
			$result=$this->db->affected_rows();
			return $result;
		}

		function checkOtpByPatientID($patientID){
			$this->db->select('*');
		    $this->db->from('tbl_cyberits_t_user_otp a');
		    $this->db->where('a.patientID',$patientID);
		    
		    $query = $this->db->get();
	        if($query->num_rows()>0){
	            return 1; // allready exist
	        }else{
	            return 0; //blom ada
	        }
		}

        function checkOtpByPatientDoctor($doctorID, $patientID){
            $this->db->select('*');
            $this->db->from('tbl_cyberits_t_user_otp a');
            $this->db->where('a.patientID',$patientID);
            $this->db->where('a.doctorID',$doctorID);

            $query = $this->db->get();
            if($query->num_rows()>0){
                return 1; // allready exist
            }else{
                return 0; //blom ada
            }
        }

        function validateOTP($patientID, $otpID){
            $datetime = date('Y-m-d H:i:s', time());
            $this->db->select('*');
            $this->db->from('tbl_cyberits_t_user_otp a');

            $this->db->where('a.patientID',$patientID);
            $this->db->where('a.otpID',$otpID);
            $this->db->where('a.isActive',1);
            $this->db->where('a.expireTime >',$datetime);

            $query = $this->db->get();
            return $query->row();
        }
	}
?>