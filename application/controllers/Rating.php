<?php
class Rating extends CI_Controller{
    function __construct(){
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->helper('date');
        $this->load->helper('html');
        $this->load->library("Authentication");
        $this->load->model('Clinic_model',"clinic_model");
        $this->load->model('Doctor_model',"doctor_model");
        $this->load->model('Report_model',"report_model");
        $this->is_logged_in_admin();
    }

    function ratingUpdate(){
        $data['main_content'] = 'admin/transaction/update_rating_view';
        $this->load->view('admin/template/template', $data);
    }

    function doUpdateClinic(){
        $status = "error";
        $msg="";

        $today = date('Y-m-d', time());
        //$yesterday =  strtotime ( '-1 day' , strtotime ( $today ) );
        //$yesterday = date ( 'Y-m-d' , $yesterday );

        $clinic_list = $this->clinic_model->getClinicList(null,null);

        foreach($clinic_list as $row){
            $clinic_id = $row['clinicID'];
            $last_updated_rating = $row['lastUpdatedRating'];
            $start_date = "";
            // check Last Rating date for Update
            // NULL is new data to rating
            if($last_updated_rating != null){
                // Compare Last Rating date with Yesterday date,
                // Match date is already rating
                if($last_updated_rating != $today){
                    $start_date = strtotime ( '1 day' , strtotime ( $last_updated_rating ) );
                    $clinic_report = $this->report_model->getAdminReportClinicVisit($clinic_id,$start_date,$today);

                    if(isset($clinic_report)){
                        $rating = $clinic_report->rating;
                        $old_rating = $row['rating'];
                        $new_rating = ($rating + $old_rating)/2;

                        $data=array(
                            "rating"=>round($new_rating,1),
                            "lastUpdated"=>$today,
                            "lastUpdatedBy"=>$this->session->userdata('userID'),
                            "lastUpdatedRating"=>$today
                        );
                        $this->clinic_model->updateClinic($data,$clinic_id);
                    }
                }
                $status = "success";
                $msg="Update Rating Clinic successfully!";
            }else{
                // New Updated Rating
                $clinic_report = $this->report_model->getAdminReportClinicVisit($clinic_id,null,$today);
                if(isset($clinic_report)){
                    $new_rating = $clinic_report->rating;
                    $data=array(
                        "rating"=>round($new_rating,1),
                        "lastUpdated"=>$today,
                        "lastUpdatedBy"=>$this->session->userdata('userID'),
                        "lastUpdatedRating"=>$today
                    );
                    $this->clinic_model->updateClinic($data,$clinic_id);
                }
                $status = "success";
                $msg="Update Rating Clinic successfully!";
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    function ratingDoctorUpdate(){
        $data['main_content'] = 'admin/register/register_admin_add_view';
        $this->load->view('admin/template/template', $data);
    }

    function doUpdateDoctor(){
        $status = "error";
        $msg="";

        $today = date('Y-m-d', time());
        //$yesterday =  strtotime ( '-1 day' , strtotime ( $today ) );
        //$yesterday = date ( 'Y-m-d' , $yesterday );

        $clinic_list = $this->doctor_model->getDoctorList(null,null);

        foreach($clinic_list as $row){
            $doctor_id = $row['doctorID'];
            $last_updated_rating = $row['lastUpdatedRating'];
            $start_date = "";
            // check Last Rating date for Update
            // NULL is new data to rating
            if($last_updated_rating != null){
                // Compare Last Rating date with Yesterday date,
                // Match date is already rating
                if($last_updated_rating != $today){
                    $start_date = strtotime ( '1 day' , strtotime ( $last_updated_rating ) );
                    $clinic_report = $this->report_model->getAdminReportDoctorVisit($doctor_id,$start_date,$today);

                    if(isset($clinic_report)){
                        $rating = $clinic_report->rating;
                        $old_rating = $row['rating'];
                        $new_rating = ($rating + $old_rating)/2;

                        $data=array(
                            "rating"=>round($new_rating,1),
                            "lastUpdated"=>$today,
                            "lastUpdatedBy"=>$this->session->userdata('userID'),
                            "lastUpdatedRating"=>$today
                        );
                        $this->doctor_model->updateDoctor($data,$doctor_id);
                    }
                }
                $status = "success";
                $msg="Update Rating Doctor successfully!";
            }else{
                // New Updated Rating
                $clinic_report = $this->report_model->getAdminReportDoctorVisit($doctor_id,null,$today);
                if(isset($clinic_report)){
                    $new_rating = $clinic_report->rating;
                    $data=array(
                        "rating"=>round($new_rating,1),
                        "lastUpdated"=>$today,
                        "lastUpdatedBy"=>$this->session->userdata('userID'),
                        "lastUpdatedRating"=>$today
                    );
                    $this->doctor_model->updateDoctor($data,$doctor_id);

                    $status = "success";
                    $msg="Update Rating Doctor successfully!";
                }
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
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