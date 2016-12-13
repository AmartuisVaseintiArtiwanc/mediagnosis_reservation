<?php  
	class Register extends CI_Controller{

		function __construct(){
			parent::__construct();

			$this->load->helper(array('form', 'url','security','date'));
			$this->load->helper('date');
			$this->load->helper('html');
            $this->load->library('Hash');
		    $this->load->library("pagination");
        	$this->is_logged_in();
		    $this->load->library('form_validation');
		    $this->load->library('email');

		    $this->load->model("Login_model");
		    $this->load->model("Patient_model", "patient_model");
		    $this->load->model("Clinic_model", "clinic_model");
		}

		function registerOfflinePatient(){
			$data['main_content'] = 'registration/patient_offline_registration_view';
        	$this->load->view('template/template', $data);
		}

		function dataPatientListAjax(){
			//$this->output->enable_profiler(TRUE);
			$clinic = $this->clinic_model->getClinicByUserID($this->session->userdata('userID'));

	        $searchText = $this->security->xss_clean($_POST['search']['value']);
	        $limit = $_POST['length'];
	        $start = $_POST['start'];

	        // here order processing
	        if(isset($_POST['order'])){
	            $orderByColumnIndex = $_POST['order']['0']['column'];
	            $orderDir =  $_POST['order']['0']['dir'];
	        }
	        else {
	            $orderByColumnIndex = 5;
	            $orderDir = "DESC";
	        }

	        $result = $this->patient_model->getPatientListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit ,$clinic->clinicID);
	        $resultTotalAll = $this->patient_model->count_all($clinic->clinicID);
	        $resultTotalFilter  = $this->patient_model->count_filtered($searchText, $clinic->clinicID);

	        $data = array();
	        $no = $_POST['start'];
	        foreach ($result as $item) {
	            $no++;
	            $date_created=date_create($item['created']);
	            $date_lastModified=date_create($item['lastUpdated']);
	            $row = array();
	            $row[] = $no;
	            $row[] = $item['patientID'];
	            $row[] = $item['patientName'];
	            $row[] = $item['ktpID'];
	            $row[] = $item['bpjsID'];
	            $row[] = $item['isActive'];
	            $row[] = $item['lastUpdated'];
	            $data[] = $row;
	        }

	        $output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => $resultTotalAll,
	            "recordsFiltered" => $resultTotalFilter,
	            "data" => $data,
	        );
	        //output to json format
	        echo json_encode($output);
	    }

	    function doInsertPatientOffline(){
	        $status = "";
	        $msg="";

	        $userID = 33;
	        $patientName = $this->security->xss_clean($this->input->post('patientName'));
	        $ktpID = $this->security->xss_clean($this->input->post('ktpID'));
	        $bpjsID = $this->security->xss_clean($this->input->post('bpjsID'));
	        $gender = $this->security->xss_clean($this->input->post('gender'));
	        $participantStatus = $this->security->xss_clean($this->input->post('participantStatus'));
	        $participantType = $this->security->xss_clean($this->input->post('participantType'));
	        $clinic = $this->clinic_model->getClinicByUserID($this->session->userdata('userID')); 

	        $checkUniqueBPJS = $this->patient_model->checkBPJSIDExists($bpjsID);
	        $checkUniqueKTP = $this->patient_model->checkIDNumberExists($ktpID);

	        if($checkUniqueKTP == 1){
	        	$status = "error";
				$msg="Maaf, No. KTP sudah terpakai";
	        }else if($checkUniqueBPJS == 1){
	        	$status = "error";
				$msg="Maaf, No. BPJS sudah terpakai";
	        }else{
	        	$datetime = date('Y-m-d H:i:s', time());
		        $data=array(
		        	"userID"=>$userID,
		        	"patientName"=>$patientName,
		        	"ktpID"=>$ktpID,
		        	"bpjsID"=>$bpjsID,
		            "gender"=>$gender,
		            "participantStatus"=>$participantStatus,
		            "participantType"=>$participantType,
		            "isTemp"=>1,
		            'isActive'=>1,
		            'created'=>$datetime,
		            "createdBy" => $this->session->userdata('superUserID'),
					"lastUpdated"=>$datetime,
					"lastUpdatedBy"=>$this->session->userdata('userID'),
					"clinicID"=>$clinic->clinicID
		        );	

		        $this->db->trans_begin();
		        $query = $this->Login_model->insertPatient($data);

		        if ($this->db->trans_status() === FALSE) {
	                // Failed to save Data to DB
	                $this->db->trans_rollback();
	                $status = 'error';
					$msg = "Maaf, Terjadi kesalahan saat registrasi pasien";
	            }
	            else{
	            	$this->db->trans_commit();
    				$status = 'success';
					$msg = "Proses Registrasi berhasil";
	            }
	        }

	        echo json_encode(array('status' => $status, 'msg' => $msg));
	        //echo print_r($data);
		}

		function is_logged_in(){
	        $is_logged_in = $this->session->userdata('is_logged_in');
	        if(!isset($is_logged_in) || $is_logged_in != true) {
	            $url_login = site_url("Login");
	            redirect($url_login, 'refresh');
	        }
	    }
	}
?>