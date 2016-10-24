<?php

class Doctor extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->is_logged_in();
        $this->load->model('doctor_model',"doctor_model");
    }
    
	function index(){
        $data['main_content'] = 'master/doctor_list_view';
        $this->load->view('template/template', $data);
	}

    function dataDoctorListAjax(){

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

        $result = $this->doctor_model->getDoctorListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->doctor_model->count_all();
        $resultTotalFilter  = $this->doctor_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['doctorID'];
            $row[] = $item['doctorName'];
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
        //output to json format
        echo json_encode($output);
    }
	
	function createDoctor(){
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'isActive'=>1,
            'doctorName'=>$name,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('userID'),
			"lastUpdated"=>$datetime,
			"lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->doctor_model->createDoctor($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Master Doctor has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Doctor already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editDoctor(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $isActive = $this->security->xss_clean($this->input->post('isActive'));
        // OLD DATA
        $old_data = $this->doctor_model->getDoctorByID($id);

        $data=array(
            'doctorName'=>$name,
            'isActive'=>$isActive,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name, true, $old_data->doctorName)) {
            $this->db->trans_begin();
            $query = $this->doctor_model->updateDoctor($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = "Master Doctor has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Doctor already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function checkDuplicateMaster($name, $isEdit, $old_data){
        $query = $this->doctor_model->getDoctorByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function deleteDoctor(){
        $status = 'success';
        $msg = "Doctor has been deleted successfully !";
        $id = $this->security->xss_clean($this->input->post("delID"));
        $this->doctor_model->deleteDoctor($id);

        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');
        }
    }
}