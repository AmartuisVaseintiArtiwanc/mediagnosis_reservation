<?php
class Adapter extends CI_Controller
{
    function __construct(){
            parent::__construct();

            $this->load->helper(array('form', 'url'));
            $this->load->helper('date');
            $this->load->helper('html');
            $this->load->library("pagination");
            $this->load->library('form_validation');
            $this->load->library('upload');
     }
    function index()
    {        
        echo "<a href='".base_url()."adapter/symptomp'>Symptomp</a>";
        echo "<br/>";
        echo "<a href='".base_url()."adapter/disease'>Disease</a>";
        echo "<br />";
        echo "<a href='".base_url()."adapter/medicine'>Medicine</a>";
    }
	function symptomp()
	{	        
        $this->load->model('Symptomps_model');

		$data['data'] = $this->Symptomps_model->getSymptompList();        

		$this->load->view('adapter_view', $data);
	}

    function disease()
    {
        $this->load->model('Diseases_model');

        $data['data'] = $this->Diseases_model->getDiseaseList();

        $this->load->view('adapter_view', $data);
    }

    function medicine()
    {
        $this->load->model('Medicines_model');

        $data['data'] = $this->Medicines_model->getMedicineList();

        $this->load->view('adapter_view', $data);   
    }

    function diseaseDetail($id)
    {
        $this->load->model('Diseases_model');

        $disease = $this->Diseases_model->getDiseaseDetail($id);
        $diseaseSymptomp = $this->Diseases_model->getDiseaseSymptomp($id);

        echo json_encode(array('disease' => $disease, 'diseaseSymptomp' => $diseaseSymptomp));
    }

    function diagnosisResult($stringSymptomp)
    {
        //$numOFSymptomp = substr_count($stringSymptomp, "_");
        $arraySymptomp = explode("_", $stringSymptomp);

        $this->load->model('Diseases_model');
        $diagnoseResult = $this->Diseases_model->getDiagnosisResult($arraySymptomp);

        echo json_encode($diagnoseResult);
        //$this->output->enable_profiler(true);
    }    
}
?>
