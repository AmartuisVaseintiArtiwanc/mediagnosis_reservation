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
		}

		function doReserve(){
			$datetime = date('Y-m-d H:i:s', time());
			$clinicID = $this->input->post('clinicID');
			$poliID = $this->input->post('poliID');
			$userID = $this->input->post('userID');
			$verifyReservation = $this->HReservation_model->checkReservationToday($clinicID, $poliID);

			if($userID!=null){
				//belom ada reservasi hari itu
				if($verifyReservation == 0){
					//insert baru
					$data_reservasi = array(
							'clinicID' => $clinicID,
							'poliID' => $poliID,
							'currentQueue' => 0,
							'totalQueue' => 1,
							'isActive' => 1,
							'created' => $datetime,
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
						$msg = "Maaf, Terjadi kesalahan saat melakukan reservasi";
		            }
		            else{
		            	$data_reservasi = array(
							'reservationID' => $query,
							'noQueue' => 1,
							'patientID' => $userID,
							'status' => 'waiting',
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
							$msg = "Mohon Maaf,Terjadi kesalahan saat melakukan reservasi";
			            }
			            else{
			            	$this->db->trans_commit();
	            			$status = 'success';
							$msg = "Proses Reservasi berhasil";
			            }
		            }
				}
				else{
					//update tambah satu total 

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
						$msg = "Maaf, Terjadi kesalahan saat melakukan reservasi";
		            }
		            else{
		            	$data_reservasi = array(
							'reservationID' => $existingReservation->reservationID,
							'noQueue' => $existingReservation->totalQueue + 1,
							'patientID' => $userID,
							'status' => 'waiting',
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
							$msg = "Mohon Maaf,Terjadi kesalahan saat melakukan reservasi";
			            }
			            else{
			            	$this->db->trans_commit();
	            			$status = 'success';
							$msg = "Proses Reservasi berhasil";
			            }

		            }

				}
				echo json_encode(array('status' => $status, 'msg' => $msg));
			}else{
				echo json_encode("empty");
			}

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

        function checkTodayReservationByClinicID(){
            $clinicID = $this->input->post("clinicID");

            $reservation = $this->test_model->getClinicCurrentQueue($clinicID );

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
            }
            echo json_encode(array('status' => $status, 'msg' => $msg));
        }
	}
?>