<?php  
	class ReservationMobile extends CI_Controller{
		function __construct(){
			parent::__construct();

			$this->load->helper(array('form', 'url'));
			$this->load->helper('date');
			$this->load->helper('html');
		    $this->load->library("pagination");
		    $this->load->library('form_validation');
		    $this->load->library('email');

		    $this->load->model("HReservation_model");
		    $this->load->model("DReservation_model");
            $this->load->model("Patient_model");
            $this->load->model('test_model',"test_model");
            $this->load->model('Clinic_model');
            $this->load->model('Place_model');
			$this->load->model('Notification_model');
		}

		function doReserve(){
			//$this->output->enable_profiler(true);
			$today = date('Y-m-d', time());
			$clinicID = $this->input->post('clinicID');
			$poliID = $this->input->post('poliID');
			$userID = $this->input->post('userID');
			$reserveType = $this->input->post('reserveType');
			$reserveWhen = $this->input->post('reserveWhen');
			$token = $this->input->post('token');

            // Check type Reservation, today or later
			if($reserveWhen == "later"){
				$postDateString = $this->input->post('reserveDate');

				$reserveDate = date('Y-m-d', strtotime($postDateString));
			}else{
				$reserveDate = $today;
			}
			
			$patient_data = $this->Patient_model->getPatientByUserID($userID);
            $patientID = $patient_data->patientID;

            // Check Today or Reserve Date Reservation
			$verifyReservationOverall = $this->test_model->checkReservationByDate($clinicID, $poliID, $reserveDate);
            // Check User has been reserve on today or Not
			$resrvationAvailability = $this->DReservation_model->checkReservationAvailability($patientID);

            // Check User has been reserve on today or Not
			if($resrvationAvailability != 0){
				echo json_encode(array('status' => 'error', 'msg' => 'Maaf, anda tidak bisa melakukan reservasi lagi'));
			}else if($userID!=null){
				if(!isset($verifyReservationOverall)){
                    //belom ada reservasi hari itu
                    $this->newHeaderReservation($clinicID, $poliID, $reserveType, $reserveDate, $userID, $patientID, $token);
				}
				else{
					//update tambah satu total
                    $this->existHeaderReservation($clinicID, $poliID, $reserveType, $reserveDate, $userID, $patientID, $token);
				}
			}else{
				echo json_encode("empty");
			}
		}

        private function newHeaderReservation($clinicID, $poliID, $reserveType, $reserveDate, $userID, $patientID, $token ){
            $datetime = date('Y-m-d H:i:s', time());
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
            $query = $this->HReservation_model->insertReservation($data_reservasi);

            if ($this->db->trans_status() === FALSE) {
                // Failed to save Data to DB
                $this->db->trans_rollback();
                $status = 'error';
                $msg = "Maaf, Terjadi kesalahan saat melakukan reservasi 1";
            }
            else{
                $data_reservasi = array(
                    'reservationID' => $query,
                    'noQueue' => 1,
                    'patientID' => $patientID,
                    'status' => 'waiting',
                    'reservationType' => $reserveType,
                    'isOnline' => 1,
                    'isActive' => 1,
                    'created' => $datetime,
                    'createdBy' => $userID,
                    'lastUpdated' => $datetime,
                    'lastUpdatedBy' => $userID
                );

                $query2 = $this->DReservation_model->insertReservation($data_reservasi);

                if ($this->db->trans_status() === FALSE) {
                    // Failed to save Data to DB
                    $this->db->trans_rollback();
                    $status = 'error';
                    $msg = "Mohon Maaf,Terjadi kesalahan saat melakukan reservasi 2";
                }
                else{
                    $this->db->trans_commit();
                    $status = 'success';
                    $msg = "Proses Reservasi berhasil";

                    $this->sendNotification("Proses reservasi berhasil","Nomor antrian anda ".$data_reservasi["noQueue"],$token);
					
					/*$data = array(
						'userID'=>$userID,
						'header'=>"Proses reservasi berhasil",
						'message'=>"Nomor antrian anda ".$data_reservasi["noQueue"],
						'isActive'=>1,
						'created'=>$datetime,
						'createdBy'=>$userID,
						'lastUpdated'=>$datetime,
						'lastUpdatedBy'=>$userID
					);
					$this->Notification_model->createNotification($data);*/
                }
            }
            echo json_encode(array('status' => $status, 'msg' => $msg));
        }

        private function existHeaderReservation($clinicID, $poliID, $reserveType, $reserveDate, $userID, $patientID, $token){
            $datetime = date('Y-m-d H:i:s', time());
            $existingReservation = $this->HReservation_model->getReservationTodayID($clinicID, $poliID);

            $data_reservasi = array(
                'totalQueue' => $existingReservation->totalQueue + 1,
                'lastUpdated' => $datetime,
                'lastUpdatedBy' => $userID
            );

            $this->db->trans_begin();
            $query = $this->HReservation_model->updateReservation($data_reservasi, $clinicID, $poliID);

            if ($this->db->trans_status() === FALSE) {
                // Failed to save Data to DB
                $this->db->trans_rollback();
                $status = 'error';
                $msg = "Maaf, Terjadi kesalahan saat melakukan reservasi 3";
            }
            else{
                $data_reservasi = array(
                    'reservationID' => $existingReservation->reservationID,
                    'noQueue' => $existingReservation->totalQueue + 1,
                    'patientID' => $patientID,
                    'status' => 'waiting',
                    'reservationType' => $reserveType,
                    'isOnline' => 1,
                    'isActive' => 1,
                    'created' => $datetime,
                    'createdBy' => $userID,
                    'lastUpdated' => $datetime,
                    'lastUpdatedBy' => $userID
                );

                $query2 = $this->DReservation_model->insertReservation($data_reservasi);

                if ($this->db->trans_status() === FALSE) {
                    // Failed to save Data to DB
                    $this->db->trans_rollback();
                    $status = 'error';
                    $msg = "Mohon Maaf,Terjadi kesalahan saat melakukan reservasi 4";
                }
                else{
                    $this->db->trans_commit();
                    $status = 'success';
                    $msg = "Proses Reservasi berhasil";

                    $this->sendNotification("Proses reservasi berhasil","Nomor antrian anda ".$data_reservasi["noQueue"],$token);
					
					/*$data = array(
						'userID'=>$userID,
						'header'=>"Proses reservasi berhasil",
						'message'=>"Nomor antrian anda ".$data_reservasi["noQueue"],
						'isActive'=>1,
						'created'=>$datetime,
						'createdBy'=>$userID,
						'lastUpdated'=>$datetime,
						'lastUpdatedBy'=>$userID
					);
					$this->Notification_model->createNotification($data);*/
                }

            }
            echo json_encode(array('status' => $status, 'msg' => $msg));

        }

        function getClinicBpjsDetail(){
            $userID = $this->security->xss_clean($this->input->post("userID"));
            $data = "";

            $patient_data = $this->Patient_model->getPatientByUserID($userID);
            if(isset($patient_data) && isset($patient_data->clinicID)){
                // GET Clinic Detail
                $clinic = $this->Clinic_model->getClinicByUserID_Mobile($patient_data->clinicID);

                if(isset($clinic)){
                    $status="success";
                    $msg="Success";
                    $data = $clinic;
                }else{
                    $status="error";
                    $msg="Maaf Anda belum terdaftar di BPJS kami !";
                }
            }else{
                $status="error";
                $msg="Maaf terjadi kesalahan silahkan coba beberapa saat lagi !";
            }

            echo json_encode(array('status' => $status, 'msg' => $msg, 'data' => $data));
        }

        function getClinicDetail(){
            $status="";
            $data="";
            $datetime = date('Y-m-d H:i:s', time());
            $userID = $this->input->post("userID");
            $placeID = $this->input->post("placeID");

            $clinic = $this->Clinic_model->getClinicByPlaceID($placeID);
            if(isset($clinic)){
                $status="success";
                $data=$clinic;
            }else{
                // Check Clinic Suggestion
                $place = $this->Place_model->getPlaceByID($placeID);
                if(isset($place)){
                    // Adding Counter
                    $data=array(
                        'counter'=>$place->counter+1,
                        "lastUpdated"=>$datetime,
                        "lastUpdatedBy"=>$userID
                    );

                    //Update Counter
                    $query = $this->Place_model->updatePlace($data,$placeID);

                    $data=$place;
                    $status="suggestion";
                }else{
                    $status="empty";
                }
            }
            echo json_encode(array('status' => $status, 'data' => $data));
        }

        function getLocationDetail(){
            $status="";
            $data="";
            $placeID = $this->input->post("placeID");

            $clinic = $this->Clinic_model->getClinicByPlaceID($placeID);
            if(isset($clinic)){
                $status="success";
                $data=$clinic;
            }else{
                // Check Clinic Suggestion
                $place = $this->Place_model->getPlaceByID($placeID);
                if(isset($place)){
                    $status="suggestion";
                    $data=$place;
                }else{
                    $status="empty";
                }
            }
            echo json_encode(array('status' => $status, 'data' => $data));
        }

        function saveClinicSuggestion(){
            $status="";
            $msg="";
            $datetime = date('Y-m-d H:i:s', time());
            $userID = $this->input->post("userID");
            $placeID = $this->input->post("placeID");
            $placeName = $this->input->post("placeName");
            $placeAddress = $this->input->post("placeAddress");
            $latitude = $this->input->post("latitude");
            $longitude = $this->input->post("longitude");
            $phone="";
			
            if($this->input->post('placePhoneNumber')){
                $phone = $this->input->post("placePhoneNumber");
            }
			
			if($this->input->post('userID') && $this->input->post('placeID') ){
				// SAVE New Suggestion Place
				$data=array(
					'placeID'=>$placeID,
					'placeName'=>$placeName,
					'placeAddress'=>$placeAddress,
					'latitude'=>$latitude,
					'longitude'=>$longitude,
					'placePhone'=>$phone,
					'counter'=>1,
					'isActive'=>1,
					'created'=>$datetime,
					"createdBy" => $userID,
					"lastUpdated"=>$datetime,
					"lastUpdatedBy"=>$userID
				);
				$query = $this->Place_model->createPlace($data);
			}
            
            echo json_encode(array('status' => $status, 'msg' => $msg));
        }

        function checkTodayReservationByUserID(){
            $userID = $this->input->post("userID");
            $patient_data = $this->Patient_model->getPatientByUserID($userID);
            $patientID = $patient_data->patientID;
            $reservation = $this->test_model->getPatientCurrentQueue($patientID);

            $currQueue="";
            $totalQueue="";
            $yourQueue="";
            $detailID="";
            $isQueue=false;
            if(isset($reservation->reservationID)){
                $currQueue = $reservation->currentQueue;
                $totalQueue = $reservation->totalQueue;
                $yourQueue = $reservation->noQueue;
                $detailID = $reservation->detailReservationID;
                $isQueue=true;
            }

            echo json_encode(array('isQueue' => $isQueue, 'currentQueue' => $currQueue,'totalQueue'=>$totalQueue,'yourQueue'=>$yourQueue,'detailReservationID'=>$detailID));
        }

        function checkTodayReservationClinicPoli(){
            $clinicID = $this->input->post("clinicID");
			$poliID = $this->input->post("poliID");

            $reservation = $this->test_model->getClinicCurrentQueue($clinicID,$poliID);

            $currQueue="";
            $totalQueue="";
            $isQueue=false;
            if(isset($reservation->reservationID)){
                $currQueue = $reservation->currentQueue;
                $totalQueue = $reservation->totalQueue;
                $isQueue=true;
            }

            echo json_encode(array('isQueue' => $isQueue, 'currentQueue' => $currQueue,'totalQueue'=>$totalQueue));
        }

        function getReservationQueue(){
            $status = "error";
            $detailReservationID = $this->security->xss_clean($this->input->post('detailReservationID'));
            $userID = $this->security->xss_clean($this->input->post('userID'));
            $patient_data = $this->Patient_model->getPatientByUserID($userID);

            if(isset($patient_data)){
                $patientID = $patient_data->patientID;
                $reservation = $this->test_model->getPatientCurrentQueueByReservation($detailReservationID, $patientID);

                if(isset($reservation)){
                    if($reservation->status == "done"){
                        $header_reservation = $this->test_model->getHeaderMedicalRecordByDetail($detailReservationID);
						$isRating = false;
						if($header_reservation->isRating == 1){ $isRating = true;}
						
                        echo json_encode(array('status' => "success",'currentQueue' => $reservation->currentQueue,
                            'yourQueue' => $reservation->noQueue,'totalQueue'=>$reservation->totalQueue, 
							'reservation_status'=>$reservation->status, 'doctorName'=>$header_reservation->doctorName, 
							'clinicName'=>$header_reservation->clinicName,'isRating'=>$isRating,
							'detailReservation'=>$detailReservationID));
                    }else{
                        echo json_encode(array('status' => "success",'currentQueue' => $reservation->currentQueue,
							'totalQueue'=>$reservation->totalQueue, 'yourQueue' => $reservation->noQueue,
							'reservation_status'=>$reservation->status,'detailReservation'=>$detailReservationID));
                    }

                }else{
                    echo json_encode(array('status' => $status));
                }
            }else{
                echo json_encode(array('status' => $status));
            }
        }

        function checkRatingReservation(){
            $userID = $this->security->xss_clean($this->input->post('userID'));
            $patient_data = $this->Patient_model->getPatientByUserID($userID);

            if(isset($patient_data)){
                $rating_data = $this->test_model->getRatingReservationByPatient($patient_data->patientID);
                if(isset($rating_data)){
                    echo json_encode(array('status' => "success",'clinicName' => $rating_data->clinicName,
                        'doctorName'=>$rating_data->doctorName, 'detailReservation'=> $rating_data->detailReservationID));
                }else{
                    echo json_encode(array('status' => "empty"));
                }
            }else{
                echo json_encode(array('status' => "error"));
            }
        }

        function saveRatingComment(){
            $datetime = date('Y-m-d H:i:s', time());
            $detailReservationID = $this->security->xss_clean($this->input->post('detailReservation'));
            $userID = $this->security->xss_clean($this->input->post('userID'));

            $rating_doctor = $this->security->xss_clean($this->input->post('ratingDoctor'));
            $comment_doctor = $this->security->xss_clean($this->input->post('commentDoctor'));
            $rating_clinic = $this->security->xss_clean($this->input->post('ratingClinic'));
            $comment_clinic = $this->security->xss_clean($this->input->post('commentClinic'));

            if(!empty($detailReservationID) && !empty($userID)){
                $data=array(
                    'isRating'=>"1",
					'ratingDoctor'=>$rating_doctor,
                    'commentDoctor'=>$comment_doctor,
                    'ratingClinic'=>$rating_clinic,
                    'commentClinic'=>$comment_clinic,
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$userID
                );

                $this->db->trans_begin();
                $this->test_model->updateReservationDetail($data, $detailReservationID);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Cannot save to Database !";
                } else {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = "Tanggapan Anda berhasil disimpan !";
                }

                echo json_encode(array('status' => $status, 'msg' => $msg));
            }
        }

        function cancelReservation(){
            $datetime = date('Y-m-d H:i:s', time());
            $detailReservationID = $this->input->post("detailReservationID");
            $userID = $this->input->post("userID");

            $data=array(
                'status'=>'reject',
                "lastUpdated"=>$datetime,
                "lastUpdatedBy"=>$userID
            );

            $this->db->trans_begin();
            $saveData = $this->test_model->updateReservationDetail($data,$detailReservationID);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save to Database !";
            } else {
                $this->db->trans_commit();
                $status = "success";
                $msg = "Reservasi berhasil di batalkan !";
				
				$token_wrapper = $this->test_model->getTokenByReservationID($detailReservationID);
				$token = $token_wrapper->token;
				$this->sendNotification("Reservasi dibatalkan","Anda berhasil membatalkan reservasi anda!",$token);
				
				/*$data = array(
					'userID'=>$userID,
					'header'=>"Reservasi dibatalkan",
					'message'=>"Anda berhasil membatalkan reservasi anda!",
					'isActive'=>1,
					'created'=>$datetime,
					'createdBy'=>$userID,
					'lastUpdated'=>$datetime,
					'lastUpdatedBy'=>$userID
				);
				$this->Notification_model->createNotification($data);*/
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
			
			$fields = array('to'=>$token,
							'notification'=>array('title'=>$title, 'body'=>$message)
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
			curl_close($curl_session
			
		}
	}
?>