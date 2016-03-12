<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logic extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('question/question_model');
        $this->load->model('survey/survey_model');
        $this->load->model('logic/logic_model');
        $this->load->model('answer/answer_model');
        $this->load->helper('answers');
    }

    /**
     * This function displays and manages the logics for a question
     */
    public function index() {
        $survey_id = $this->uri->segment(3);
        $question_id = $this->uri->segment(4);
        $logic_id = $this->uri->segment(5);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        $data['question_id'] = $question_id;
        $question_detail = $this->question_model->get_question($question_id);
        $data['question'] = $question_detail;
        $questions_escaped = array($question_id);
        $data['jump_questions'] = $this->question_model->get_quiestions_questionnaire($survey_id, $questions_escaped);

        $logics = $this->logic_model->get_logics($question_id);

        $answers = $this->answer_model->get_answers($question_id);
        $ans_options = @unserialize($answers->name);
        $ans_options = $ans_options['options'];

        $final_logics = array();
        foreach ($logics as $logic) {
            //var_dump($logic);die;
            foreach ($ans_options as $key => $val) {
                if ($key == $logic->value) {
                    $logic->opt_name = $val;
                }
            }

            $q_detail = $this->question_model->get_question($logic->jump_to);
            $logic->q_name = $q_detail->question_name;
            $final_logics [] = $logic;
        }
        $data['logics'] = $final_logics;

        $data['operators'] = $this->logic_model->list_operators();
        if ($logic_id) {
            $data['logic'] = $this->logic_model->get_logic($logic_id);
        }
        $data['survey_id'] = $survey_id;
        $this->template->write_view('content', 'logic', $data);
        $this->template->render();
    }

    /**
     * Save/ update the logic, conditions
     */
    public function addedit() {
        $survey_id = $this->uri->segment(3);
        $question_id = $this->uri->segment(4);
        if ($_POST) {
            $data['question_id'] = $this->input->post('question_id');
            $data['jump_to'] = $this->input->post('jump_to');
            $logic_id = $this->input->post('logic_id');
            $survey_id = $this->input->post('survey_id');
            $question_id = $this->input->post('question_id');
            $data['operator_id'] = $this->input->post('operator_id');
            $data['value'] = $this->input->post('value');
            $data['group_logic'] = $this->input->post('group_logic');

            if ($logic_id) {
                $this->logic_model->update($logic_id, $data); // update
            } else {
                $logic_id = $this->logic_model->save($data); // save
            }
        }
        redirect('logic/index/' . $survey_id . '/' . $question_id);
    }

}

