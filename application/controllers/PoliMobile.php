<?php  
	class PoliMobile extends CI_Controller{

		function __construct(){
	        parent::__construct();
	        $this->load->helper(array('form', 'url','security','date'));
	        $this->load->library("pagination");
	    }

	    function getPoliData(){
	    	$clinicID = $this->input->post('clinicID');
	    	$userID = $this->input->post('userID');

	    	if($userID != null){
	    		$this->load->model(array('SClinic_model'));
	        	$data = $this->SClinic_model->getClinicListByID($clinicID);

	        	echo json_encode(array('poliList' => $data));
	    	}
	    	else{
	    		echo json_encode("empty");
	    	}
	    }
	}
?>