<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the statistic related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date May 15, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Statistic_Model extends CI_Model {

    /**
     * gets the information of survey download statistics by mobile apps
     * @param int $survey_id     
     * return int
     */
    public function get_download_statistic($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->from("download_statistic");
        return $this->db->count_all_results();
    }

    /**
     * gets a single record of download stat of particular device id
     * @param int $device_id
     * @param int $survey_id        
     * return object
     */
    public function get_device_stat($device_id, $survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('device_identifier', $device_id);
        $query = $this->db->get('download_statistic');
        return $query->row();
    }

    /**
     * insert record of statistic
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert('download_statistic', $data);
        return $this->db->insert_id();
    }

    /**
     * update record of statistic
     * @param int $device_id
     * @param int $survey_id
     * @param associative array $data     
     * return mixed
     */
    public function update($device_id, $survey_id, $data) {
        $this->db->where('device_identifier', $device_id);
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->update('download_statistic', $data);
    }

    /**
     * gets the total count of questions of particular survey
     * @param int $survey_id     
     * return int
     */
    public function get_total_question($survey_id) {
        $this->db->from("question");
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->count_all_results();
    }

    /**
     * checks the existence of device id
     * @param int $survey_id 
     * @param int $device_id     
     * return boolean
     */
    public function check_device_exists($survey_id, $device_id) {
        $this->db->where('device_identifier', $device_id);
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get('download_statistic');
        $result = is_object($query->row()) ? TRUE : FALSE;
        return $result;
    }

    /**
     * gets the total no of questionnaires donwload by different devices for a single survey
     * @param int $questionnaire_id     
     * return int
     */
    public function get_total_questionnaires($questionnaire_id) {
        $this->db->select_sum('total');
        $this->db->where('questionnaire_id', $questionnaire_id);
        $query = $this->db->get('download_statistic');
        $result = $query->row();
        return !empty($result->total) ? $result->total : 0;
    }

    /**
     * gets the total no of users who gives answer to particular question
     * @param int $question_id     
     * return int
     */
    public function get_total_survey_users($question_id) {
        $this->db->select('count(distinct(survey_user_id))as cnt');
        $this->db->where('question_id', $question_id);
        $query = $this->db->get('survey_answer');
        $result = $query->row();
        return $result->cnt;
    }

    /**
     * gets the list of users who gives answer to particular question
     * @param int $question_id     
     * return int
     */
    public function list_distinct_survey_users($question_id) {
        $this->db->select('distinct(survey_user_id)');
        $this->db->where('question_id', $question_id);
        $query = $this->db->get('survey_answer');
        $result = $query->result();
        return $result;
    }

    /**
     * gets the response of particular question given by a particular user
     * @param int $question_id
     * @param int $survey_user_id
     * return int
     */
    public function get_user_response($question_id, $survey_user_id) {
        $this->db->where('survey_user_id', $survey_user_id);
        $this->db->where('question_id', $question_id);
        $this->db->order_by("option_id", 'asc');
        $query = $this->db->get('survey_answer');
        $result = $query->result();
        return $result;
    }

    /**
     * gets the total no of users who took survey for a particular survey
     * @param int $question_id     
     * return int
     */
    public function get_question_stat($question_id) {

        $question_details = $this->question_model->get_question($question_id);
        $question_type_id = $question_details->question_type_id;
        $answer_arr = $this->answer_model->get_answers_unserialized($question_id, $question_type_id);
        $final_result = array();
        $answer_options = array();
        switch ($question_type_id) {
            //single choice and pull downt
            case 1:
            case 7:
                $this->db->select('count(option_id) as cnt, option_id');
                $this->db->group_by("option_id");
                $this->db->where('question_id', $question_id);
                $query = $this->db->get('survey_answer');
                //echo $this->db->last_query();
                $result = $query->result();
                foreach ($result as $row) {
                    if (key_exists($row->option_id, $answer_arr['options'])) {
                        $row->opt_name = $answer_arr['options'][$row->option_id];
                    }
                    $final_result [] = $row;
                }
                break;

            //multi choice    
            case 3 :

                $result = $this->statistic_model->list_distinct_survey_users($question_id);
                //var_dump($result);die;
                $main_response_holder = array();
                foreach ($result as $row) {
                    $responses = $this->statistic_model->get_user_response($question_id, $row->survey_user_id);
                    //var_dump($response);die;
                    $response_holder = array();
                    foreach ($responses as $response) {
                        $response_holder [] = $response->option_id;
                    }
                    $main_response_holder [] = implode('-', $response_holder);
                    $response_holder = array();
                }

                $final_responses = array_count_values($main_response_holder);
                //var_dump($final_responses);die;

                foreach ($final_responses as $final_response => $tot) {
                    $opt = explode('-', $final_response);
                    $cnt = count($opt);
                    $final_opt = "";
                    for ($i = 0; $i < $cnt; $i++) {
                        $final_opt .= $answer_arr['options'][$opt[$i]];
                        if ($i < $cnt - 1) {
                            $final_opt .= ", ";
                        }
                    }
                    $stat_obj = new stdClass;
                    $stat_obj->opt_name = $final_opt;
                    $stat_obj->cnt = $tot;
                    $final_result [] = $stat_obj;
                }

                break;

            //scale and text box answer 
            case 2 :
            case 6 :
                $this->db->select('count(text) as cnt, text');
                $this->db->group_by("text");
                $this->db->where('question_id', $question_id);
                $query = $this->db->get('survey_answer');
                $result = $query->result();

                foreach ($result as $row) {
                    $row->opt_name = $row->text;
                    $final_result [] = $row;
                }

                break;

            //contingency 
            case 4:
                $answer_arr = $this->answer_model->get_answers_unserialized($question_id, $question_details->question_type_id);
                $v_answers = $answer_arr['v_answer'];
                $h_answers = $answer_arr['h_answer'];


                $this->db->select('distinct(option_id)');
                $this->db->where('question_id', $question_id);
                $query = $this->db->get('survey_answer');
                $result = $query->result();

                foreach ($result as $row) {
                    $this->db->select('*');
                    $this->db->where('option_id', $row->option_id);
                    $this->db->where('question_id', $question_id);
                    $query = $this->db->get('survey_answer');

                    $result_inner = $query->result();

                    foreach ($result_inner as $row) {
                        $this->db->select('*');
                        $this->db->where('survey_answer_id', $row->id);
                        $query = $this->db->get('survey_sub_answer');
                        $final_inner_result = $query->row();
                        $unserialized_ans = unserialize($final_inner_result->answer);

                        //answers
                        $answers = array();
                        if ($question_details->data_entry_type_id == 2 || $question_details->data_entry_type_id == 3) {
                            foreach ($unserialized_ans as $answer_obj) {
                                $answers [] = $h_answers[$answer_obj->id];
                            }

                            $option = $v_answers[$row->option_id] . '(' . implode(',', $answers) . ')';
                            $answer_options [] = $option;
                        } else {
                            foreach ($unserialized_ans as $answer_obj) {
                                $answers [] = $h_answers[$answer_obj->id] . '-' . $answer_obj->text;
                            }

                            $option = $v_answers[$row->option_id] . '(' . implode(',', $answers) . ')';
                            $answer_options [] = $option;
                        }
                        //empty previous data
                        $answers = array();
                    }
                }
                $answer_stats = array_count_values($answer_options);

                foreach ($answer_stats as $k => $answer_stat) {
                    $answer_obj = new stdClass;
                    $answer_obj->opt_name = $k;
                    $answer_obj->cnt = $answer_stat;
                    $final_result [] = $answer_obj;
                }

                break;
        }

        return $final_result;
    }

}