<?php

class ReservationDoctor extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("authentication");
        $this->is_logged_in();
        $this->load->model('clinic_model',"clinic_model");
        $this->load->model('doctor_model',"doctor_model");
        $this->load->model('poli_model',"poli_model");
        $this->load->model('test_model',"test_model");
        $this->load->model('patient_model',"patient_model");
        $this->load->model('Medical_record_detail_model',"medical_record_detail_model");
		$this->load->model('Notification_model');
    }

    function index(){
        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeDoctor($role)){
            $userID =  $this->session->userdata('userID');
            $doctor_data = $this->doctor_model->getClinicPoliDoctorByUserID($userID);

            $check_reservation = $this->test_model->checkUnfinishReservation($doctor_data->doctorID);
            if(isset($check_reservation->detailReservationID)){
                $this->goToMedicalRecord($check_reservation->detailReservationID);
            }else{
                $check_reservation = $this->test_model->checkWaitingConfirmReservation($doctor_data->doctorID);
                if(isset($check_reservation->detailReservationID)){
                    $status = "waiting";
                    $data['detailID']  = $check_reservation->detailReservationID;
                }else{
                    $status = "clear";
                    $data['detailID']  = "";
                }
                // CREATE & CHECK RESERVATION CLINIC POLI
                $this->createHeaderReservation($doctor_data->clinicID,$doctor_data->poliID );
                $headerData = $this->test_model->getHeaderReservationDataByDoctor($doctor_data->clinicID,$doctor_data->poliID);
                $data['reversation_clinic_data']  = $headerData;
                $data['doctor_data']  = $doctor_data;
                $data['status']  = $status;

                //$data['main_content'] = 'reservation/doctor/home_view';
                $this->load->view('reservation/doctor/reservation_doctor_view', $data);
                //$this->output->enable_profiler(TRUE);
            }
        }
    }

    /*Create Header Reservasi untuk HARI INI*/
    private function createHeaderReservation($clinicID, $poliID){
        $datetime = date('Y-m-d H:i:s', time());
        $userID = $this->session->userdata('userID');

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

    /* Get Antrian Sekarang, Per Clinic*/
    function getQueueCurrent(){
        $reservationID = $this->security->xss_clean($this->input->post('reservation'));
        $data = $this->test_model->getCurrentQueueDoctor($reservationID);

        $output="";
        $status="error";
        if(isset($data)){
            $output = array(
                "headerID"=>$data->reservationID,
                "detailID"=>$data->detailReservationID,
                "noQueue"=>$data->noQueue,
                "poliName" => strtoupper($data->poliName),
                "patientName" => strtoupper($data->patientName)
            );
            $status="success";
        }

        echo json_encode(array('status' => $status, 'output' => $output));
    }

    /* Get Antrian Yang sudah di confirm andmin*/
    function getStartQueueCurrent(){
        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getClinicPoliDoctorByUserID($userID);

        $detailID = $this->security->xss_clean($this->input->post('detailReservation'));
        $data = $this->test_model->checkReservationByDoctorDetailID($detailID,$doctor_data->doctorID);

        $output = "";
        $status = "error";
        if(isset($data)){
            if($data->status == "late" || $data->status == "reject"){
                $status = "late";
                $output = "Maaf pasien tidak datang, silahkan ambil pasien selanjutnya ...";
            }else if($data->status == "confirm"){
                $status="success";
            }

        }
        echo json_encode(array('status' => $status, 'msg' => $output));
    }

    function saveCurrentQueue(){

        $datetime = date('Y-m-d H:i:s', time());
        $headerID = $this->security->xss_clean($this->input->post('headerID'));
        $detailID = $this->security->xss_clean($this->input->post('detailID'));

        $checkReservation = $this->test_model->checkReservationDetail($detailID);
        if($checkReservation){
            $doctor = $this->doctor_model->getDoctorByUserID($this->session->userdata('userID'));
            $detail_data = $this->test_model->getReservationDetailByIDStatus($detailID,"waiting");

            //UPDATE HEADER CURRENT QUEUE
            $data_header=array(
                'currentQueue'=>$detail_data->noQueue,
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );
            $query_header = $this->test_model->updateReservationHeader($data_header,$headerID);

            //UPDATE RESERVATION DETAIL
            $data=array(
                'doctorID'=>$doctor->doctorID,
                'status'=>'check',
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$this->session->userdata('userID')
            );

            $this->db->trans_begin();
            $query_detail = $this->test_model->updateReservationDetail($data,$detailID);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save to Database !";
            } else {
                if ($query_detail && $query_header) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = "Save data successfully !";
					
					
					$token_wrapper = $this->test_model->getTokenByReservationID($detailID);
					$token = $token_wrapper->token;
					$this->sendNotification("No. ".$token_wrapper->noQueue." sedang dipanggil","Silahkan ke ruang dokter ".$doctor->doctorName,$token);
					
					$data = array(
						'userID'=>$token_wrapper->userID,
						'header'=>"No. ".$token_wrapper->noQueue." sedang dipanggil",
						'message'=>"Silahkan ke ruang dokter ",
						'isActive'=>1,
						'created'=>$datetime,
						'createdBy'=>$this->session->userdata('userID'),
						'lastUpdated'=>$datetime,
						'lastUpdatedBy'=>$this->session->userdata('userID')
					);
					$this->Notification_model->createNotification($data);
					
					$this->countdownThreeNotificationReminder($detail_data->noQueue);
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Failed to save data !";
                }
            }
        }else{
            $status = "taken";
            $msg = "This Patient has been taken by Other Doctor!";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function goToMedicalRecord($detailReservation){
        $userID =  $this->session->userdata('userID');
        $doctor_data = $this->doctor_model->getClinicPoliDoctorByUserID($userID);
        $check_medical_record = $this->test_model->getReservationDetailDoctor($detailReservation,$doctor_data->doctorID,'confirm');

        if(isset($check_medical_record)){
            $data['pe_data'] = $this->medical_record_detail_model->getPhysicalExaminationByDetailReservation($detailReservation);
            $data['header_data'] = $this->test_model->getHeaderMedicalRecordByDetail($detailReservation);
            $data['doctor_data'] = $doctor_data;
            $data['patient_data']  = $this->patient_model->getPatientByID($check_medical_record->patientID);
            $data['reservation_data']  = $check_medical_record;
            $this->load->view('reservation/doctor/medical_record_view', $data);
        }else{
            $index = site_url("ReservationDoctor");
            redirect($index, 'refresh');
        }
    }
	
	function countdownThreeNotificationReminder($currQueue){
		$token_wrapper = $this->test_model->getTokenForNextThreeQueue($currQueue);
		if(isset($token_wrapper->token)){
			$this->sendNotification("Pengingat antrian","Antrian anda 3 nomor lagi dipanggil",$token_wrapper->token);
			
			/*$data = array(
				'userID'=>$token_wrapper->userID,
				'header'=>"Pengingat antrian",
				'message'=>"Antrian anda 3 nomor lagi dipanggil",
				'isActive'=>1,
				'created'=>$datetime,
				'createdBy'=>$userID,
				'lastUpdated'=>$datetime,
				'lastUpdatedBy'=>$userID
			);
			$this->Notification_model->createNotification($data);*/
		}
	}
	
	function sendNotification($title, $message, $token){
		$path = 'https://fcm.googleapis.com/fcm/send';
		$server_key = "AAAAa0DykfY:APA91bGVDIV31q6GpXzcbpo_Tlr_L1BkqtuVio_OwvV2Ov7zTzIXrkPaRpcgLNxZ7XEy33gX356Q9TeRstFxqQo5V-rImTvvrFEG7EvLTwecAWncZ72xQvy63Waux3Xu7Pcv07WsxTPY8t8_DbtyqohE06ZdV0RSug";
		
		$headers = array(
			'Authorization:key='.$server_key,
			'Content-Type:application/json'
		);
		
		$fields = array('to'=>$token,
						'notification'=>array('title'=>$title, 'body'=>$message),
						'sound'=>"default"
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

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}