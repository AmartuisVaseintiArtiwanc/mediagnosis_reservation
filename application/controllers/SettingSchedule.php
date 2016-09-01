<?php


class SettingSchedule extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security', 'date'));
        $this->load->library("pagination");
        $this->is_logged_in();
        $this->load->model('clinic_model', "clinic_model");
        $this->load->model('poli_model', "poli_model");
        $this->load->model('sschedule_model', "sschedule_model");
    }

    function index(){
        $data['main_content'] = 'setting/setting_schedule_list_view';
        $this->load->view('template/template', $data);
    }

    function dataScheduleListAjax(){

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

        $result = $this->sschedule_model->getScheduleListData($searchText,$orderByColumnIndex,$orderDir, $start,$limit);
        $resultTotalAll = $this->sschedule_model->count_all();
        $resultTotalFilter  = $this->sschedule_model->count_filtered($searchText);

        $data = array();
        $no = $_POST['start'];
        foreach ($result as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item['sClinicID'];
            $row[] = $item['clinicName'];
            $row[] = $item['poliName'];
            $row[] = $item['clinicID'];
            $row[] = $item['poliID'];
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

    function goToSettingDetailSchedule($clinicID, $poliID){
        $data['main_content'] = 'setting/setting_schedule_detail_view';
        $data['data'] = null;
        $data['msg'] = null;

        //Data Selection
        $data['data_setting_header'] = $this->sschedule_model->getHeaderData($clinicID, $poliID);
        $data['data_setting_detail'] = $this->sschedule_model->getSettingDetailSchedule($clinicID, $poliID);

        $this->load->view('template/template', $data);
    }

    function saveSchedule(){
        //$this->output->enable_profiler(TRUE);
        $status="";
        $msg="";
        $datetime = date('Y-m-d H:i:s', time());
        $data = $this->input->post('data');
        $poliID= $data[0]['poliID'];
        $clinicID= $data[0]['clinicID'];

        $this->db->trans_begin();
        // ADD NEW DATA
        if(isset($data[1])){
            foreach($data[1] as $row){
                $detail_setting = array(
                    'openTime'=>$row['openTime'],
                    'closeTime'=>$row['closeTime'],
                    'isOpen'=>$row['active'],
                    "lastUpdated"=>$datetime,
                    "lastUpdatedBy"=>$this->session->userdata('userID')
                );

                $addDetil = $this->sschedule_model->updateSettingSchedule($row['scheduleID'],$detail_setting);
            }
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $status="error";
            $msg="Error while saved data!";
        }
        else{
            $this->db->trans_commit();
            $status="success";
            $msg="Setting berhasil disimpan!";
        }

        // return message to AJAX
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
?>