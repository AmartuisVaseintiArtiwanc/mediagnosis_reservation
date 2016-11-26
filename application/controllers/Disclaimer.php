<?php
class Disclaimer extends CI_Controller{
    function __construct(){
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->helper('date');
        $this->load->helper('html');
    }

    function getDisclaimer(){
        $this->load->view("template/disclaimer");
    }

    function getFaq(){
        $this->load->view("template/faq");
    }
}
?>