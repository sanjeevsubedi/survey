<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Preview extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('question/question_model');
        $this->load->model('survey/survey_model');
        $this->load->model('answer/answer_model');
        $this->load->helper('answers');
    }

    /**
     * This function displays preview of survey
     */
    public function index() {
        $survey_id = $this->uri->segment(2);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }
        $data['questions'] = $this->question_model->get_quiestions_questionnaire($survey_id);
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }
}
