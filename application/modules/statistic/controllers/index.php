<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * This function displays the response statistics of a particuar question
     */
    public function question_stat() {
        //$this->load->helper('jscss');
        //load_js('highcharts');
        $this->template->set_template('blank');
        $this->load->model('statistic/statistic_model');
        $this->load->model('question/question_model');
        $this->load->model('answer/answer_model');
        $this->load->model('surveyreport/survey_response_model');
        $question_id = $this->uri->segment(3);

        //verifies the valid question type and prevents the url tampering
        if (!$this->question_model->is_valid_question_reqest($question_id)) {
            redirect(base_url() . 'survey/all');
        }

        $stat = $this->statistic_model->get_question_stat($question_id);
        $question_details = $this->question_model->get_question($question_id);
        $data['q_type'] = $question_details->question_type_id;
        $data['stats'] = $stat;
        $data['total_users'] = $this->statistic_model->get_total_survey_users($question_id);
        $data['question'] = $question_details->question_name;

        $this->template->write_view('content', 'question_stat', $data);
        $this->template->render();
    }

}

