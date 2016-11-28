<?php  
	class ConsultationMobile extends CI_Controller{
		function __construct(){
	        parent::__construct();
	        $this->load->helper(array('form', 'url','security','date'));
	        $this->load->library("pagination");
	        //$this->is_logged_in();
	        $this->load->model('Topic_model',"topic_model");
	        $this->load->model('Patient_model',"patient_model");
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

	    function userList(){
	    	$patients = $this->patient_model->getPatientList();

	    	echo json_encode(array('data' => $patients));	
	    }
	}
?>