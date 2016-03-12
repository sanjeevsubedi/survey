<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the question related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Question_model extends CI_Model {

    private $table_name = 'question';

    /**
     * insert record of question
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of question
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * delete record of question
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * gets a single record of question
     * @param int $id     
     * return object
     */
    public function get_question_row($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * gets a single record of question type
     * @param int $id     
     * return object
     */
    public function get_questiontype_row($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('question_type');
        return $query->row();
    }

    /**
     * update record of question of particular survey
     * @param int survey_id
     * @param associative array $data
     * return object
     */
    function update_question_survey($survey_id, $data) {
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * gets the list of data entry type of question
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_data_entry_type($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('data_entry_type');
        $results = $query->result();
        return $results;
    }

    /**
     * gets the list of question types
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_question_types($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('question_type');
        $results = $query->result();
        return $results;
    }

    /**
     * checks the existence of question type
     * @param int $question_type_id     
     * return object
     */
    public function check_question_type_exists($question_type_id) {
        $this->db->where('id', $question_type_id);
        $query = $this->db->get('question_type');
        $result = $query->row();
        return $result;
    }

    /**
     * checks the existence of question
     * @param int $question_id     
     * return object
     */
    public function check_question_exists($question_id) {
        $this->db->where('id', $question_id);
        $query = $this->db->get('question');
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid question type in various stages of survey generation
     * @param int $question_type_id     
     * return bool
     */
    public function is_valid_question_type_reqest($question_type_id) {
        $flag = TRUE;
        if (!$question_type_id) {
            $flag = FALSE;
        }

        if (!is_numeric($question_type_id)) {
            $flag = FALSE;
        }
        $question_type_object = $this->question_model->check_question_type_exists($question_type_id);
        if (!$question_type_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * checks the valid question in various stages of survey generation
     * @param int $question_id     
     * return bool
     */
    public function is_valid_question_reqest($question_id) {
        $flag = TRUE;
        if (!$question_id) {
            $flag = FALSE;
        }

        if (!is_numeric($question_id)) {
            $flag = FALSE;
        }
        $question_object = $this->question_model->check_question_exists($question_id);
        if (!$question_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * This function displays question view according to its type
     * @param int $type
     * @param string $action
     * return string
     */
    public function load_question($type, $action = "add") {
        switch ($type) {
            case 1:
            case 3:
                $view_page = ($action == "add") ? 'new_single' : "edit_single";
                break;
            case 2:
                $view_page = ($action == "add") ? 'new_scale' : "edit_scale";
                break;
            /* case 3:
              $view_page = ($action == "add") ? 'new_multichoice' : "edit_multichoice";
              break; */
            case 4:
                $view_page = ($action == "add") ? 'new_contingencytable' : "edit_contingencytable";
                break;
            case 6:
                $view_page = ($action == "add") ? 'new_textanswer' : "edit_textanswer";
                break;
            case 7:
                $view_page = ($action == "add") ? 'new_dropdown' : "edit_dropdown";
                break;
        }
        return $view_page;
    }

    /**
     * gets the list of questions of particular questionnaire (survey)
     * @param int $survey_id     
     * @param array $question_ids     
     * return array
     */
    public function get_quiestions_questionnaire($survey_id, $question_ids = null) {
        $this->db->select("q.id as question_id, q.name as question, q.question_type_id,q.is_compulsory,q.order ,q.other,q.data_entry_type_id, qt.name as question_type, det.name as data_entry_type");
        //$this->db->select("*");
        $this->db->from("question q");
        $this->db->where('q.questionnaire_id', $survey_id);
        if ($question_ids)
            $this->db->where_not_in('q.id', $question_ids);
        $this->db->join('question_type qt', 'qt.id = q.question_type_id', 'left');
        $this->db->join('data_entry_type det', 'det.id = q.data_entry_type_id', 'left');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    /**
     * gets the list of compulsory questions of particular questionnaire
     * @param int $survey_id     
     * return array
     */
    public function get_compulsory_question($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('is_compulsory', 1);
        $query = $this->db->get('question');
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of question
     * @param int $id     
     * return object
     */
    public function get_question($id) {

        $this->db->select("q.id as question_id,q.is_compulsory as is_compulsory, q.name as question_name,q.question_type_id, det.id as data_entry_type_id, q.other");
        $this->db->from("question q");
        $this->db->join('data_entry_type det', 'q.data_entry_type_id = det.id', 'left');
        $this->db->where('q.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * gets all the records of question table of particular survey
     * @param int $survey_id     
     * return array
     */
    public function get_question_clone($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of translated question of particular language
     * @param int $translation_set_id
     * @param int $language_id
     * return object
     */
    public function get_translated_question($translation_set_id, $language_id) {
        $this->db->where('translation_set_id', $translation_set_id);
        $this->db->where('language_id', $language_id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * gets the list of questions of particular survey including translation of particular language
     * @param int $survey_id     
     * return array
     */
    public function get_all_questions_questionnaire($survey_id, $language_id) {
        $this->db->select("q.id as question_id, q.name as question,q.question_type_id");
        $this->db->from("question q");
        $this->db->where('q.questionnaire_id', $survey_id);
        //$this->db->order_by('order', 'asc');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $all_questions = $query->result();
        $final_array = array();
        $questions = array();
        foreach ($all_questions as $question) {

            $translated_question = $this->get_translated_question($question->question_id, $language_id);
            if (empty($translated_question)) {
                $translated_question = new stdClass();
                $translated_question->id = '';
                $translated_question->name = '';
            }
            $questions ['original'][] = array(
                'id' => $question->question_id, 'name' => $question->question, 'type' => $question->question_type_id);
            $questions ['translate'][] = array(
                'id' => $translated_question->id, 'name' => $translated_question->name);


            $answers = $this->get_answers_question($question->question_id, $question->question_type_id, $translated_question->id);
            $translated_answer_arr = $this->answer_model->get_answers_unserialized($translated_question->id, $question->question_type_id);

            switch ($question->question_type_id) {
                case 1:
                case 3:
                    $whole_translated_answer = array();
                    $translated_answer = $translated_answer_arr['options'];
                    $sub_answer = array();
                    if (!empty($translated_answer)) {
                        foreach ($translated_answer as $key => $answer) {
                            $translated_sub_ans = $this->answer_model->get_all_subanswer($translated_question->id, $key);
                            if (!empty($translated_sub_ans)) {
                                foreach ($translated_sub_ans as $k => $sub_an) {
                                    $sub_answer[$sub_an->id] = $sub_an->name;
                                }
                            }
                            $whole_translated_answer[] = array($key => $sub_answer);
                            //empty the previous sub answer
                            $sub_answer = array();
                        }
                    }
                    break;
                case 4:
                    break;
            }

            if (empty($translated_question)) {
                $translated_question = new stdClass;
                $translated_question->name = '';
                $translated_question->id = '';
            }
            $final_array[] = array(
                'questions' => $questions,
                'answers' => $answers,
            );
        }
        return $final_array;
    }

    public function get_answers_question($question_id, $question_type_id, $translated_ques_id = '') {
        $answers = $this->answer_model->get_answers($question_id);
        $translated_answers = $this->answer_model->get_answers($translated_ques_id);
        $main_answer = array();
        $ans = array();
        $sub_answer = array();
        if (!empty($answers)) {
            $ans_options = @unserialize($answers->name);
            $translated_ans_options = @unserialize($translated_answers->name);
            switch ($question_type_id) {
                case 1:
                case 3 :
                    $ans_options = $ans_options['options'];

                    $translated_ans_options = $translated_ans_options['options'];

                    foreach ($ans_options as $key => $value) {
                        $ans['original'][] = array(
                            'id' => $key, 'name' => $value);

                        $ans['translate'][] = array(
                            'id' => $key, 'name' => isset($translated_ans_options[$key]) ? $translated_ans_options[$key] : '');

                        $sub_ans = $this->answer_model->get_all_subanswer($question_id, $key);
                        $translated_sub_ans = $this->answer_model->get_all_subanswer($translated_ques_id, $key);

                        if (!empty($sub_ans)) {
                            foreach ($sub_ans as $k => $sub_an) {
                                $sub_answer['original'][] = array(
                                    'id' => $sub_an->id, 'name' => $sub_an->name);

                                if (isset($translated_sub_ans[$k]) && is_object($translated_sub_ans[$k])) {
                                    $translated_subans_obj = $translated_sub_ans[$k];
                                } else {
                                    $translated_subans_obj = new stdClass();
                                    $translated_subans_obj->id = '';
                                    $translated_subans_obj->name = '';
                                }

                                $sub_answer['translate'][] = array(
                                    'id' => $translated_subans_obj->id, 'name' => $translated_subans_obj->name);
                            }
                        }

                        $main_answer[] = array('main_answer' => $ans, 'sub_answer' => $sub_answer);
                        //empty the previous answers
                        $ans = array();
                        //empty the previous subanswers
                        $sub_answer = array();
                    }
                    break;
                case 4:
                    $h_answers = $ans_options['h_answer'];
                    $v_answers = $ans_options['v_answer'];
                    $translated_hans_options = $translated_ans_options['h_answer'];
                    $translated_vans_options = $translated_ans_options['v_answer'];
                    foreach ($h_answers as $key => $value) {
                        $ans['v_original'][] = array(
                            'id' => $key, 'name' => $value);

                        $ans['v_translate'][] = array(
                            'id' => $key, 'name' => $translated_hans_options[$key]);
                        $main_answer[] = array('main_answer' => $ans);
                        $ans = array();
                    }

                    foreach ($v_answers as $key => $value) {
                        $ans['h_original'][] = array(
                            'id' => $key, 'name' => $value);

                        $ans['h_translate'][] = array(
                            'id' => $key, 'name' => $translated_vans_options[$key]);
                        $main_answer[] = array('main_answer' => $ans);
                        $ans = array();
                    }
                    break;
            }
        }
        return $main_answer;
    }

    /**
     * This function displays dynamic class based on question type
     * @param int $type
     * return string
     */
    public function load_class($type) {
        $class = "";
        switch ($type) {
            case 1:
                $class = "qt-single-select";
                break;
            case 3:
                $class = "qt-mutli-select";
                break;
            case 2:
                $class = "qt-scale";
                break;
            case 4:
                $class = "qt-contingency";
                break;
            case 6:
                $class = "qt-text-answer";
                break;
            case 7:
                $class = "qt-drop-down";
                break;
        }
        return $class;
    }

}