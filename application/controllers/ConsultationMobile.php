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

	    		$roomID = $this->sroom_model->insertRoom($room_data);
	    	}else{
	    		$roomID = $rooms->sRoomID;
	    	}

	    	echo json_encode(array('roomID' => $roomID));		
	    }
	}
?>