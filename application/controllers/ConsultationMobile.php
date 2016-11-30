<?php  
	class ConsultationMobile extends CI_Controller{
		function __construct(){
	        parent::__construct();
	        $this->load->helper(array('form', 'url','security','date'));
	        $this->load->library("pagination");
	        //$this->is_logged_in();
	        $this->load->model('Topic_model',"topic_model");
	        $this->load->model('Patient_model',"patient_model");
	        $this->load->model('Doctor_model',"doctor_model");
	        $this->load->model('SRoom_model',"sroom_model");
	    }

	    function topicList(){
	    	$userID = $this->input->post("userID");
	    	$topics = $this->topic_model->getTopicList();

	    	echo json_encode(array('data' => $topics));
	    }

	    function expertList($topicID){
	    	$userID = $this->input->post("userID");
	    	
	    	$experts = $this->topic_model->getExpertList($topicID);

	    	echo json_encode(array('data' => $experts));	
	    }

	    function patientList($expertUserID){
	    	$experts = $this->doctor_model->getDoctorIDByUserID($expertUserID);
	    	$expertID = $experts->doctorID;

	    	$patients = $this->sroom_model->getUserListByDoctorID($expertID);

	    	echo json_encode(array('data' => $patients));	
	    }

	    function generateRoomID(){
	    	$topicID = $this->input->post("topicID");
	    	$patientID = $this->input->post("patientID");
	    	$expertID = $this->input->post("expertID");
	    	$role = $this->input->post("role");

	    	if($role == "patient"){
	    		$patients = $this->patient_model->getPatientIDByUserID($patientID);
	    		$patientID = $patients->patientID;
	    	}
	    	else{
	    		$experts = $this->doctor_model->getDoctorIDByUserID($expertID);
	    		$expertID = $experts->doctorID;
	    	}

	    	$rooms = $this->sroom_model->getRoomID($topicID, $patientID, $expertID);

	    	if($rooms == null || $rooms == ""){

	    		$datetime = date('Y-m-d H:i:s', time());
		        $room_data=array(
		        	'topicID'=>$topicID,
		            'patientID'=>$patientID,
		            'doctorID'=>$expertID,
		            'isActive'=>1,
		            'created'=>$datetime,
		            "createdBy" => "patient",
					"lastUpdated"=>$datetime,
					"lastUpdatedBy"=>"patient"
		        );

		        $this->db->trans_begin();
	    		$roomID = $this->sroom_model->insertRoom($room_data);
	    		if ($this->db->trans_status() === FALSE) {
		            // Failed to save Data to DB
		            $this->db->trans_rollback();
		            $status = 'error';
					$msg = "Maaf, Terjadi kesalahan saat melakukan konsultasi";
		        }else{
		        	$this->db->trans_commit();
					$status = 'success';
					$msg = "Proses konsultasi berhasil";
		        }

	    	}else{
	    		$roomID = $rooms->sRoomID;
    			$status = 'success';
				$msg = "Proses konsultasi berhasil";
	    	}

	    	echo json_encode(array('status' => $status, 'msg' => $msg, 'roomID' => $roomID));
	    }

	    function updateRecentChat(){
	    	$userID = $this->input->post("userID");
	    	$sRoomID = $this->input->post("sRoomID");
	    	$recentChat = $this->input->post("recentChat");

    		$datetime = date('Y-m-d H:i:s', time());
	        $recent_chat_data=array(
	        	"recentChat"=> $recentChat,
				"lastUpdated"=>$datetime,
				"lastUpdatedBy"=>$userID
	        );

	        $this->db->trans_begin();
	        $query = $this->sroom_model->updateRoom($recent_chat_data, $sRoomID);

	        if ($this->db->trans_status() === FALSE) {
	            // Failed to save Data to DB
	            $this->db->trans_rollback();
	            $status = 'error';
				$msg = "Maaf, Terjadi kesalahan saat melakukan konsultasi";
	        }
	        else{
	        	$this->db->trans_commit();
    			$status = 'success';
				$msg = "Proses konsultasi berhasil";
	        }

	     	echo json_encode(array("status" => $status, "msg" => $msg));
	    }

	    function recentExpertList($userID){
	    	$patients = $this->patient_model->getPatientIDByUserID($userID);
	    	$patientID = $patients->patientID;

	    	$experts = $this->sroom_model->getUserListByPatientID($patientID);

	    	echo json_encode(array('data' => $experts));		
	    }
	}
?>