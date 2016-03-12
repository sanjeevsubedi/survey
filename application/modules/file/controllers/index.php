<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('file/file_model');
        $this->load->model('question/question_model');
        $this->load->model('answer/answer_model');
        $this->load->model('survey/survey_model');
        $this->load->helper('randomnumber');
        $this->load->helper('permission');
        $this->load->model('permission/permission_model');
    }

    /**
     * This function shows list of all files of particular survey
     */
    public function show_docs() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        $questions = $this->question_model->get_quiestions_questionnaire($survey_id);

        //questions are mandatory for survey
        if (empty($questions)) {
            $this->session->set_flashdata('error', 'At least one question is required');
            redirect(base_url() . 'survey/step_5/' . $survey_id);
        }

        $q_files = array();
        $a_files = array();
        $s_files = array();
        $final_array = array();

        foreach ($questions as $question) {
            $question_files = $this->file_model->get_resources_question($question->question_id);
            $answer_files = $this->file_model->get_resources_answerall($question->question_id);

            //all the answer files of a particular question
            if ($answer_files) {
                foreach ($answer_files as $k => $answer_file) {
                    $answerobj = new stdClass;
                    $file_ext = substr($answer_file->name, strrpos($answer_file->name, '.') + 1);
                    $answerobj->name = $answer_file->name;
                    $answerobj->id = $answer_file->id;
                    $answerobj->img_path = $this->file_model->load_file_img($file_ext);
                    $a_files [] = $answerobj;
                }
            }

            //question and its multiple files
            if (!empty($question_files)) {
                foreach ($question_files as $question_file) {
                    $questionobj = new stdClass;
                    $file_ext = substr($question_file->name, strrpos($question_file->name, '.') + 1);
                    $questionobj->name = $question_file->name;
                    $questionobj->id = $question_file->id;
                    $questionobj->img_path = $this->file_model->load_file_img($file_ext);
                    $q_files [] = $questionobj;
                }
            }

            $final_array [] = array('q' => $question->question, 'qf' => $q_files, 'af' => $a_files);
            $q_files = array();
            $a_files = array();
        }

        $survey_files = $this->file_model->get_resources_survey($survey_id);

        //survey and its multiple files
        if (!empty($survey_files)) {
            foreach ($survey_files as $survey_file) {
                $surveyobj = new stdClass;
                $file_ext = substr($survey_file->name, strrpos($survey_file->name, '.') + 1);
                $surveyobj->name = $survey_file->name;
                $surveyobj->id = $survey_file->id;
                $surveyobj->img_path = $this->file_model->load_file_img($file_ext);
                $s_files [] = $surveyobj;
            }
        }
        $data['s_docs'] = $s_files;
        $data['questions'] = $final_array;
        $data['eid'] = $survey_id;
        $data['survey'] = $this->survey_model->get_survey($survey_id);
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function insert the files for particular survey
     */
    public function save_doc() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        if (!empty($_FILES['survey_documents']['name'][0])) {
            foreach ($_FILES as $field_name => $value) {
                $upload_dir_name = 'survey';
                foreach ($value['name'] as $k => $file_name) {
                    $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
                    $temp_file = $value['tmp_name'][$k];
                    if (@is_uploaded_file($temp_file)) {
                        $upload_dir = UPLOADPATH . DS . $upload_dir_name . DS;
                        //$rand_file_name = get_random_string(16) . "." . $file_ext;
                        $rand_file_name = $this->file_model->get_duplicate_file($file_name);
                        $rand_file_name = str_replace(' ', '_', $rand_file_name);
                        @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);
                        $file_data_s = array('questionnaire_id' => $survey_id, 'name' => $rand_file_name);
                        $this->file_model->save_survey_file($file_data_s);
                    }
                }
                //$this->session->set_flashdata('success', 'Document added successfully');
                redirect(base_url() . 'language/survey_lang/' . $survey_id);
            }
        } else {
            //$this->session->set_flashdata('error', 'No Document uploaded');
            redirect(base_url() . 'language/survey_lang/' . $survey_id);
        }
    }

    /**
     * This function deletes the document from the library
     */
    public function doc_del() {
        $survey_id = $this->uri->segment(3);
        $doc_type = $this->uri->segment(4);
        $doc_id = $this->uri->segment(5);

        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        switch ($doc_type) {
            case 'q':
                $folder = 'question';
                $file_details = $this->file_model->get_question_resource($doc_id);
                $file = $file_details->name;
                //removes the file
                @unlink(UPLOADPATH . DS . $folder . DS . $file);
                $this->file_model->delete_question_file($doc_id);
                break;
            case 'a':
                $folder = 'answer';
                $file_details = $this->file_model->get_ans_resource($doc_id);
                $file = $file_details->name;
                //removes the file
                @unlink(UPLOADPATH . DS . $folder . DS . $file);
                $this->file_model->delete_answer_file($doc_id);
                break;
            case 's':
                $folder = 'survey';
                $file_details = $this->file_model->get_survey_resource($doc_id);
                $file = $file_details->name;
                //removes the file
                @unlink(UPLOADPATH . DS . $folder . DS . $file);
                $this->file_model->delete_survey_file($doc_id);

                break;
            default:
                redirect(base_url() . 'file/show_docs/' . $survey_id);
                break;
        }
        $this->session->set_flashdata('success', 'Document deleted successfully');
        redirect(base_url() . 'file/show_docs/' . $survey_id);
    }

}

