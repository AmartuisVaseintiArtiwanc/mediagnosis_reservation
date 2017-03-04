<?php

class Poli extends CI_Controller {
	
    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url','security','date'));
        $this->load->library("pagination");
        $this->load->library("Authentication");
        $this->is_logged_in();
        $this->load->model('poli_model',"poli_model");
    }
    
	function index(){
        $data['main_content'] = 'master/poli_list_view';

        $role = $this->session->userdata('role');
        if($this->authentication->isAuthorizeAdminMediagnosis($role)){
            $this->load->view('admin/template/template', $data);
        }else if($this->authentication->isAuthorizeSuperAdmin($role)){
            $this->load->view('template/template', $data);
        }
	}

    function dataPoliListAjax(){

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

        $result = $this->poli_model->getPoliListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->poli_model->count_all();
        $resultTotalFilter  = $this->poli_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $date_created=date_create($item['created']);
            $date_lastModified=date_create($item['lastUpdated']);
            $row = array();
            $row[] = $no;
            $row[] = $item['poliID'];
            $row[] = $item['poliName'];
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
	
	function createPoli(){
        $status = "";
        $msg="";

        $name = $this->security->xss_clean($this->input->post('name'));

        $datetime = date('Y-m-d H:i:s', time());
        $data=array(
            'poliName'=>$name,
            'isActive'=>1,
            'created'=>$datetime,
            "createdBy" => $this->session->userdata('superUserID'),
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name,false,null)){
            $this->db->trans_begin();
            $query = $this->poli_model->createPoli($data);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg="Cannot save master to Database";
            }
            else {
                if($query==1){
                    $this->db->trans_commit();
                    $status = "success";
                    $msg="Master Poli has been added successfully.";
                }else{
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg="Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Poli already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}
    
   	function editPoli(){
        $status = "";
        $msg="";

        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post('id'));
        $name = $this->security->xss_clean($this->input->post('name'));
        $isActive = $this->security->xss_clean($this->input->post('isActive'));
        // OLD DATA
        $old_data = $this->poli_model->getPoliByID($id);

        $data=array(
            'poliName'=>$name,
            'isActive'=>$isActive,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );

        if($this->checkDuplicateMaster($name, true, $old_data->poliName)) {
            $this->db->trans_begin();
            $query = $this->poli_model->updatePoli($data, $id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Cannot save master to Database";
            } else {
                if ($query == 1) {
                    $this->db->trans_commit();
                    $status = "success";
                    $msg = "Master Poli has been updated successfully.";
                } else {
                    $this->db->trans_rollback();
                    $status = "error";
                    $msg = "Failed to save data Master ! ";
                }
            }
        }else{
            $status = "error";
            $msg="This ".$name." Poli already exist !";
        }

        echo json_encode(array('status' => $status, 'msg' => $msg));
	}

    function checkDuplicateMaster($name, $isEdit, $old_data){
        $query = $this->poli_model->getPoliByName($name, $isEdit, $old_data);

        if(empty($query)) {
            return true;
        }else{
            return false;
        }
    }

    function deletePoli(){
        $datetime = date('Y-m-d H:i:s', time());
        $id = $this->security->xss_clean($this->input->post("delID"));

        $data=array(
            'isActive'=>0,
            "lastUpdated"=>$datetime,
            "lastUpdatedBy"=>$this->session->userdata('userID')
        );
        $this->db->trans_begin();
        $query = $this->poli_model->updatePoli($data, $id);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = "error";
            $msg = "Cannot save master to Database";
        } else {
            if ($query == 1) {
                $this->db->trans_commit();
                $status = "success";
                $msg = "Master Poli has been updated successfully.";
            } else {
                $this->db->trans_rollback();
                $status = "error";
                $msg = "Failed to save data Master ! ";
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function is_logged_in(){
        $is_logged_in = $this->session->userdata('is_logged_in');
        $role = $this->session->userdata('role');
        if(!isset($is_logged_in) || $is_logged_in != true) {
            $url_login = site_url("Login");
            redirect($url_login, 'refresh');

        }else{
            if(!$this->authentication->isAuthorizeAdminMediagnosis($role) &&
                !$this->authentication->isAuthorizeSuperAdmin($role)){
                $url_login = site_url("Login");
                redirect($url_login, 'refresh');
            }
        }
    }
}