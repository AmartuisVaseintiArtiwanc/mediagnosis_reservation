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
							'totalQueue' => $verifyReservation->totalQueue + 1,
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
							'noQueue' => $verifyReservation->totalQueue + 1,
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
	}
?>