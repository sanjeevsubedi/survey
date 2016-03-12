<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('language/language_model');
        $this->load->model('survey/survey_model');
    }

    
  public function index(){
  }


   

   

}
