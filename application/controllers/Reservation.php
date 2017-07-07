<?php

class Reservation extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('Patient_model',"patient_model");
        $this->load->model('Clinic_model',"clinic_model");
        $this->load->model('Poli_model',"poli_model");
        $this->load->model('Medical_record_detail_model',"medical_record_detail_model");
        $this->load->model("HReservation_model");
        $this->load->model("DReservation_model");
        $this->load->model('Test_model',"test_model");
        $this->load->model("SClinic_model","sclinic_model");
		$this->load->model('Notification_model');
		$this->load->model('Company_model');
		$this->load->model('Company_reservation_model');
		$this->load->model('SPoli_model');
		$this->load->helper("language");
		$this->load->language("main", "bahasa");
    }

    function index(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'reservation/reservation_clinic_list_view';
            $this->load->view('template/template', $data);
        }else if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            $this->goToListReservationQueue($clinic->clinicID);
        }
    }

    /*Reservasi Antrian Tiap Clinic Pada HARI INI*/
    function goToListReservationQueue($clinicID){
        $clinicPoliList = $this->sclinic_model->getSettingDetailClinic($clinicID);

        // CREATE & CHECK RESERVATION CLINIC EACH POLI
        $this->createHeaderReservationDummy($clinicPoliList,$clinicID );

        $data['reversation_clinic_data']  = $this->test_model->getHeaderReservationData($clinicID);
        $data['reservation_latest_queue'] = $this->test_model->getReservationNextQueue($clinicID);
        $data['poli_list']  = $this->sclinic_model->getClinicListByID($clinicID);
		$data['doctor_poli_list'] = $this->SPoli_model->getDoctorPoliByClinicID($clinicID);

        $data['main_content'] = 'reservation/reservation_home_view';
        $this->load->view('template/template', $data);
    }

    function getReservationListForPatient(){
        $role = $this->session->userdata('role');

        $userID =  $this->session->userdata('userID');
        $clinic = $this->clinic_model->getClinicByUserID($userID);

        $clinicPoliList = $this->sclinic_model->getSettingDetailClinic($clinic->clinicID);

        // CREATE & CHECK RESERVATION CLINIC EACH POLI
        $this->createHeaderReservationDummy($clinicPoliList,$clinic->clinicID );

        $data['reversation_clinic_data']  = $this->test_model->getHeaderReservationData($clinic->clinicID);
        $data['reservation_latest_queue'] = $this->test_model->getReservationNextQueue($clinic->clinicID);
        $data['poli_list']  = $this->sclinic_model->getClinicListByID($clinic->clinicID);

        
        $this->load->view('reservation/reservation_patient_view', $data);
        
    }

    function checkReservationAfterExamine(){
        $detailID = $this->input->post("detailID");

        $result = $this->DReservation_model->checkReservationAfterExamine($detailID);

        if($result){
            $status = "success";
        }
        else{
            $status = "error";
        }

        echo json_encode(array('status' => $status));
    }

    function getSumPatientToday(){
        $sum = $this->HReservation_model->getSumPatientToday();

        echo json_encode(array('sum' => $sum->sumQueue));
    }

    /*Create Header Reservasi untuk HARI INI*/
    private function createHeaderReservationDummy($clinicPoliList,$clinicID){
        $datetime = date('Y-m-d H:i:s', time());
        $userID = $this->session->userdata('userID');
        //$userID = $this->session->userdata('userID');

        foreach($clinicPoliList as $row){
            $poliID = $row['poliID'];
            $verifyReservation = $this->test_model->checkReservationToday($clinicID,$poliID);
            if($verifyReservation == 0) {
                //insert baru
                $data_reservasi = array(
                    'clinicID' => $clinicID,
                    'poliID' => $poliID,
                    'currentQueue' => 0,
                    'totalQueue' => 0,
                    'isActive' => 1,
                    'created' => $datetime,
                    'createdBy' => $userID,
                    'lastUpdated' => $datetime,
                    'lastUpdatedBy' => $userID
                );

                $query = $this->test_model->insertReservation($data_reservasi);

                if ($this->db->trans_status() === FALSE) {
                    // Failed to save Data to DB
                    $this->db->trans_rollback();
                }
                else{
                    $this->db->trans_commit();
                }
            }
        }
    }

    /* Get Antrian Sekarang, Per Clinic*/
    function getQueueCurrent(){
        $clinicID = $this->security->xss_clean($this->input->post('clinic'));
        $poliID = $this->security->xss_clean($this->input->post('poli'));
        $data = $this->test_model->getCurrentQueue($clinicID,$poliID);

        $output="";
        $status="error";
        if(isset($data)){
            $output = array(
                "headerID"=>$data->reservationID,
                "detailID"=>$data->detailReservationID,
                "noQueue"=>$data->noQueue,
                "poliID"=>$data->poliID,
                "poliName" => strtoupper($data->poliName),
                "doctorName" => $data->doctorName,
                "patientName" => $data->patientName
            );
            $status="success";
        }
        echo json_encode(array('status' => $status, 'output' => $output));
    }

    function getQueueNext(){
        $clinicID = $this->security->xss_clean($this->input->post('clinic'));

        $data= $this->test_model->getReservationNextQueue($clinicID);
        $output="";
        $status="error";
        if(isset($data)){
            $output =$data;
            $status="success";
        }
        echo json_encode(array('status' => $status, 'output' => $output));
    }

    function saveCurrentQueue(){

        $datetime = date('Y-m-d H:i:s', time());
        $status_rev = $this->security->xss_clean($this->input->post('status'));
        $detailID = $this->security->xss_clean($this->input->post('detailID'));

        $data=array(
            'status'=>$status_rev,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        $query = $this->test_model->updateReservationDetail($data,$detailID);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = $this->lang->line("002");//"Cannot save to Database !";
        } else {
            if ($query) {
                $this->db->trans_commit();
                $status = "success";
                $msg = $this->lang->line("001");//"Save data successfully !";
				
				if($status_rev == "late"){
					$token_wrapper = $this->test_model->getTokenByReservationID($detailID);
					$token = $token_wrapper->token;
					$this->sendNotification("Reservasi anda terlewatkan","Maaf, reservasi anda terlewatkan",$token);
					
					/*$data = array(
						'userID'=>$userID,
						'header'=>"Reservasi anda terlewatkan",
						'message'=>"Maaf, reservasi anda terlewatkan",
						'isActive'=>1,
						'created'=>$datetime,
						'createdBy'=>$userID,
						'lastUpdated'=>$datetime,
						'lastUpdatedBy'=>$userID
					);
					$this->Notification_model->createNotification($data);*/
				}
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = $this->lang->line("002");//"Failed to save data !";
            }
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }


    function goToReservationReportClinicList(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeSuperAdmin($role)){
            $data['main_content'] = 'reservation/reservation_report_list_view';
            $this->load->view('template/template', $data);
        }else if($this->authentication->isAuthorizeAdmin($role)){
            $userID =  $this->session->userdata('userID');
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            $this->goToReservationReportPoliList($clinic->clinicID);
        }
    }

    function goToReservationReportPoliList($clinicID){
        $data['data_clinic'] = $this->clinic_model->getClinicByID($clinicID);
        $data['data_poli']  = $this->sclinic_model->getSettingDetailClinic($clinicID);
        $data['main_content'] = 'reservation/reservation_report_poli_list_view';
        $this->load->view('template/template', $data);
    }

    function dataReservationClinicPoliListAjax(){

        $searchText = $this->security->xss_clean($_POST['search']['value']);
        $limit = $_POST['length'];
        $start = $_POST['start'];

        // here order processing
        if(isset($_POST['order'])){
            $orderByColumnIndex = $_POST['order']['0']['column'];
            $orderDir =  $_POST['order']['0']['dir'];
        }
        else {
            $orderByColumnIndex = 1;
            $orderDir = "ASC";
        }

        $result = $this->clinic_model->getClinicListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->clinic_model->count_all();
        $resultTotalFilter  = $this->clinic_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['clinicID'];
            $row[] = $item['clinicName'];
            $row[] = date_format($date_created,"d M Y")." by ".$item['createdBy'];
            $row[] = date_format($date_lastModified,"d M Y")." by ".$item['lastUpdatedBy'];
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $resultTotalAll,
            "recordsFiltered" => $resultTotalFilter,
            "data" => $data,
        );

        //$this->output->enable_profiler(TRUE);
        //output to json format
        echo json_encode($output);
    }

    function goToPhysicalExamination($detailReservation){
        $role = $this->session->userdata('role');
        $userID = $this->session->userdata('userID');
        if($this->authentication->isAuthorizeAdmin($role)){
            $clinic = $this->clinic_model->getClinicByUserID($userID);
            if(isset($clinic)){
                $header_data = $this->test_model->checkReservationClinicAdminRole($detailReservation,$clinic->clinicID);
                if(isset($header_data)){
                    $status = $header_data->status;
                    if($status == "waiting" || $status == "examine" ){
                        $this->goToExamineForm($detailReservation,$header_data);
                    }else{
                        echo $this->lang->line("015");//"Pasien ini tidak terdapat dalam proses reservasi ..";
                    }
                }else{
                    echo $this->lang->line("014");
                }
            }else{
                echo $this->lang->line("014");
            }
        }else{
            echo $this->lang->line("014");
        }
    }

    private function goToExamineForm($detailReservation,$header_data){
        $datetime = date('Y-m-d H:i:s', time());

        /*$reservationData=array(
            'status'=>"examine",
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $this->db->trans_begin();
        // Update Reservation Status to Examine
        $query = $this->test_model->updateReservationDetail($reservationData,$detailReservation);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = $this->lang->line("002");//"Cannot save to Database !";
        } else {
            $this->db->trans_commit();
            $status = "success";
            $msg = $this->lang->line("001");//"Save data successfully !";
        }*/

        $doctor_data = $this->doctor_model->getDoctorByID($header_data->doctorID);
        $patient_data = $this->patient_model->getPatientByID($header_data->patientID);

        $data['header_data'] = $header_data;
        $data['doctor_data'] = $doctor_data;
        $data['patient_data']  = $patient_data;
        $data['detailReservation'] = $detailReservation;
        $this->load->view('reservation/physical_examination_view', $data);
    }

    function savePhysicalExamination(){

        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->security->xss_clean($this->input->post('data'));
        $detailID = $this->security->xss_clean($this->input->post('detail_reservation'));

        // EXAMINATION / PEMERIKSAAN
        $conscious = $data['conscious'];
        $blood_low = $data['blood_low'];
        $blood_high = $data['blood_high'];
        $pulse = $data['pulse'];
        $respiration = $data['respiration'];
        $temperature = $data['temperature'];
        $height = $data['height'];
        $weight = $data['weight'];

        $physical_examination_data=array(
            'detailReservationID'=>$detailID,
            'conscious'=>$conscious,
            'bloodPreasureLow'=>$blood_low,
            'bloodPreasureHigh'=>$blood_high,
            'pulse'=>$pulse,
            'respirationRate'=>$respiration,
            'temperature'=>$temperature,
            'weight'=>$weight,
            'height'=>$height,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        $reservationData=array(
            'status'=>"examine",
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->db->trans_begin();
        // Update Reservation Status to Confirm
        $query = $this->test_model->updateReservationDetail($reservationData,$detailID);
        // Save Physical Examination
        $this->medical_record_detail_model->createMedicalRecordDetailPhysicalExamination($physical_examination_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = $this->lang->line("002");//"Cannot save to Database !";
        } else {
            $this->db->trans_commit();
            $status = "success";
            $msg = $this->lang->line("001");//"Save data successfully !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    private function validateSavePhysicalExamination($data){

    }

    function reserveOfflinePatient(){

            $userID =  $this->session->userdata('userID');
            $clinicData = $this->clinic_model->getClinicByUserID($userID);

            if(isset($clinicData)){
                $poli_data = $this->sclinic_model->getClinicListByID($clinicData->clinicID);
				$company_data = $this->company_model->getAllCompany($clinicData->clinicID);
                if(isset($poli_data)){
                    $data['clinic_data'] = $clinicData;
                    $data['poli_data'] = $poli_data;
					$data['company_data'] = $company_data;
                    $data['main_content'] = 'registration/patient_offline_reservation_view';
                    $this->load->view('template/template', $data);
                }else{
                    $data['err_msg'] = "Maaf Pengaturan Poli pada Klinik Anda belum di atur..";
                    $data['main_content'] = 'template/error';
                    $this->load->view('template/template', $data);
                }
            }else{
                $data['err_msg'] = "Maaf Anda tidak dapat mengakses halaman ini..";
                $data['main_content'] = 'template/error';
                $this->load->view('template/template', $data);
            }
        }

	function doReservePatientOffline(){
		$status = "error";
		$msg=$this->lang->line("002");//"Maaf Data Anda tidak dapat tersimpan, cobalah beberapa saat lagi..";
		$queueNo=0;

		$datetime = date('Y-m-d H:i:s', time());
		$userID = $this->session->userdata('userID');

		$clinic = $this->security->xss_clean($this->input->post('clinic'));
		$poli = $this->security->xss_clean($this->input->post('poli'));
		$company = $this->security->xss_clean($this->input->post('company'));
		$reserveDate = date('Y-m-d', time());
		$reserveType = $this->security->xss_clean($this->input->post('reserve_type'));
		$patient = $this->security->xss_clean($this->input->post('patient'));

		$resrvationAvailability = $this->DReservation_model->checkReservationAvailability($patient);

		if(!empty($clinic) && !empty($poli) && !empty($company) && !empty($reserveDate) && !empty($patient) &&!empty($reserveType) ){

			//validasi multiple reservation
			$resrvationAvailability = $this->DReservation_model->checkReservationAvailability($patient);
			if($resrvationAvailability != 0){
				$msg = $this->lang->line("013");//"Maaf, pasien telah melakukan reservasi sebelumnya";
			}else{

			   // CREATE AND CHECK Header Reservation
				$header = $this->createHeaderReservationOffline($clinic,$poli,$reserveDate);
				if($header != ""){
					$data_reservasi = array(
						'reservationID' => $header["headerID"],
						'noQueue' => $header["nextQueue"],
						'patientID' => $patient,
						'status' => 'waiting',
						'reservationType' => $reserveType,
						'isOnline' => 1,
						'isActive' => 1,
						'created' => $reserveDate,
						'createdBy' => $userID,
						'lastUpdated' => $datetime,
						'lastUpdatedBy' => $userID
					);

					// Create Detail Reservation
					$detailReservation = $this->DReservation_model->insertReservation($data_reservasi);
					if(isset($detailReservation)){
						$status = "success";
						$msg= $this->lang->line("012");//"Success";
						$queueNo=$header["nextQueue"];
						
						if($reserveType=="perusahaan"){
						
							$data_company_reservation = array(
								'companyID' => $company,
								'detailReservationID' => $detailReservation,
								'isActive' => 1,
								'created' => $datetime,
								'createdBy' => $userID,
								'lastUpdated' => $datetime,
								'lastUpdatedBy' => $userID
								
							);
							
							 // Create company reservation transaction info
							$companyReservation = $this->Company_reservation_model->insertCompanyReservation($data_company_reservation);
							if(isset($companyReservation)){
								$status = "success";
								//$msg= $this->lang->line("012");//"Success";
								$queueNo=$header["nextQueue"];
							}else{
								$status = "error";
							}
						
						}
					}

				}else{
					$status = "error";
				}
			}
		}
		echo json_encode(array('status' => $status, 'msg' => $msg, 'queueNo' => $queueNo));
	}

	/*Create Header Reservasi untuk HARI INI*/
	private function createHeaderReservationOffline($clinicID, $poliID, $reserveDate){
		$datetime = date('Y-m-d H:i:s', time());
		$result;
		$userID = $this->session->userdata('userID');
		$headerID = "";

		$verifyReservation = $this->test_model->checkReservationByDate($clinicID,$poliID,$reserveDate);
		if(!isset($verifyReservation)) {
			//insert baru
			$data_reservasi = array(
				'clinicID' => $clinicID,
				'poliID' => $poliID,
				'currentQueue' => 0,
				'totalQueue' => 1,
				'isActive' => 1,
				'created' => $reserveDate,
				'createdBy' => $userID,
				'lastUpdated' => $datetime,
				'lastUpdatedBy' => $userID
			);
			$this->db->trans_begin();
			$query = $this->test_model->insertReservation($data_reservasi);

			if ($this->db->trans_status() === FALSE) {
				// Failed to save Data to DB
				$this->db->trans_rollback();
			}
			else{
				$headerID = $query;
				$result["headerID"] = $headerID;
				$result["nextQueue"] = 1;
				$this->db->trans_commit();
			}
		}else{
			$headerID = $verifyReservation->reservationID;
			$data_reservasi = array(
					'totalQueue' => $verifyReservation->totalQueue + 1,
					'lastUpdated' => $datetime,
					'lastUpdatedBy' => $userID
				);

			$this->db->trans_begin();
			$query = $this->HReservation_model->updateReservation($data_reservasi, $clinicID, $poliID);
			if ($this->db->trans_status() === FALSE) {
				// Failed to save Data to DB
				$this->db->trans_rollback();
			}else{

				$result["headerID"] = $headerID;
				$result["nextQueue"] = $verifyReservation->totalQueue + 1;
				$this->db->trans_commit();
			}
		}

		return $result;
	}
	
	function doAssignPatients(){
		$error = 0;
		$status = "";
		$msg = "";
		$datetime = date('Y-m-d H:i:s', time());
		$patients = $this->security->xss_clean($this->input->post('patients'));
        $doctorID = $this->security->xss_clean($this->input->post('doctorID'));
		
		$data=array(
            'doctorID'=>$doctorID,
			'status'=>"confirm",
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
		
		foreach($patients as $p){
			//echo $p."\n";
			if($error == 0){
				$this->db->trans_begin();
				$query = $this->test_model->updateReservationDetail($data,$p);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$status = "error";
					$msg = $this->lang->line("002");//"Cannot save to Database !";
					$error++;
				}else{
					$this->db->trans_commit();
					$status = "success";
					$msg = $this->lang->line("001");//"Save data successfully !";
				}
			}
		}
		
		echo json_encode(array('status' => $status, 'msg' => $msg));
		
	}

    function sendNotification($title, $message, $token){
		$path = 'https://fcm.googleapis.com/fcm/send';
		$server_key = "AAAAa0DykfY:APA91bGVDIV31q6GpXzcbpo_Tlr_L1BkqtuVio_OwvV2Ov7zTzIXrkPaRpcgLNxZ7XEy33gX356Q9TeRstFxqQo5V-rImTvvrFEG7EvLTwecAWncZ72xQvy63Waux3Xu7Pcv07WsxTPY8t8_DbtyqohE06ZdV0RSug";
		
		$headers = array(
			'Authorization:key='.$server_key,
			'Content-Type:application/json'
		);
		
		$data = array('title'=>$title, 'body'=>$message, 'open_drawer'=>"OPEN");
		$fields = array('to'=>$token,
						//'notification'=>array('title'=>$title, 'body'=>$message),
						'data'=>$data
		);
		
		$payload= json_encode($fields);
		
		$curl_session = curl_init();
		curl_setopt($curl_session, CURLOPT_URL, $path);
		curl_setopt($curl_session, CURLOPT_POST, true);
		curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
		
		$result = curl_exec($curl_session);
		curl_close($curl_session);
		
	}
	
	private function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}