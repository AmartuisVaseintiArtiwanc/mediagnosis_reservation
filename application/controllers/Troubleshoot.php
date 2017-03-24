<?php 
	class Troubleshoot extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->helper(array('form', 'url','security','date'));
			$this->load->library("pagination");
			$this->load->library("Authentication");
			$this->is_logged_in_admin();
			$this->load->model('SRoom_model',"sroom_model");
		}
		
		function reportedChat(){
			$data['main_content'] = 'admin/transaction/chat_problems_list_view';
			$this->load->view('admin/template/template', $data);
			
		}
		
		function dataReportedChatListAjax(){
			
			$searchText = $this->security->xss_clean($_POST['search']['value']);
			$limit = $_POST['length'];
			$start = $_POST['start'];

			// here order processing
			if(isset($_POST['order'])){
				$orderByColumnIndex = $_POST['order']['0']['column'];
				$orderDir =  $_POST['order']['0']['dir'];
			}
			else {
				$orderByColumnIndex = 7;
				$orderDir = "DESC";
			}

			$result = $this->sroom_model->getReportedChatListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
			$resultTotalAll = $this->sroom_model->countReportedChatAll();
			$resultTotalFilter  = $this->sroom_model->countReportedChatFiltered($searchText);
			
			$data = array();
			$no = $_POST['start'];
			foreach ($result as $item) {
				$no++;
				$date_created=date_create($item['created']);
				$date_lastModified=date_create($item['lastUpdated']);
				$row = array();
				$row[] = $no;
				$row[] = $item['sRoomID'];
				$row[] = $item['patientName'];
				$row[] = $item['doctorName'];
				$row[] = $item['topicName'];
				$row[] = $item['recentChat'];
				$row[] = $item['sRoomID'];
				$row[] = $item['isActive'];
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
		
		function is_logged_in_admin(){
			$is_logged_in = $this->session->userdata('is_logged_in');
			$role = $this->session->userdata('role');
			if(!isset($is_logged_in) || $is_logged_in != true) {
				$url_login = site_url("LoginAdmin");
				redirect($url_login, 'refresh');
			}else{
				if(!$this->authentication->isAuthorizeAdminMediagnosis($role)){
					$url_login = site_url("LoginAdmin");
					redirect($url_login, 'refresh');
				}
			}
		}
	}
?>