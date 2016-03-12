<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the survey related functions.
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Mar 04, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Survey_response_Model extends CI_Model {

    private $table_name = '';

    /**
     * gets the details of survey contact details
     * @param int $survey_id     
     * @param boolen $title
     * @param int $survey_user_id
     * return array
     */
    public function get_survey_contact_details($survey_id, $title = true, $survey_user_id = null) {
        if ($title)
            $this->db->select("distinct(scd.contact_form_field_id), cff.name");
        else
            $this->db->select("scd.survey_user_id, scd.value");
        $this->db->from("survey_contact_detail scd");
        $this->db->join('contact_form_field cff', 'cff.id = scd.contact_form_field_id', 'left');

        $this->db->where('questionnaire_id', $survey_id);
        if ($survey_user_id)
            $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get();
//print_r($this->db->last_query());die();
        return $query->result();
    }

    /**
     * gets the details of survey questions details
     * @param int $survey_id     
     * @param boolen $title
     * @param int $survey_user_id     
     * return array
     */
    public function get_survey_questions_details($survey_id, $title = true, $survey_user_id = null, $order = false) {
        if ($title)
            $this->db->select("distinct(sq.question_id), q.name, q.other,q.order");
        else
            $this->db->select("distinct(sq.question_id), sq.question_comment, sq.survey_user_id, q.other");
        $this->db->from("survey_question sq");
        $this->db->join('question q', 'q.id = sq.question_id', 'left');

        $this->db->where('sq.questionnaire_id', $survey_id);
        if ($survey_user_id)
            $this->db->where('sq.survey_user_id', $survey_user_id);
            
        
        if ($order)
            $this->db->order_by('q.order', 'asc');
        $query = $this->db->get();

//print_r($this->db->last_query());die();
        return $query->result();
    }

    /**
     * list all users of a servey
     * @param int $survey_id     
     * return object
     */
    public function get_survey_users($survey_id, $user_id = FALSE) {
        /* $this->db->select("distinct(survey_user_id), questionnaire_id");
          $this->db->where('questionnaire_id', $survey_id);
          $query = $this->db->get("survey_contact_detail");
          return $query->result(); */

        /* $survey_details = $this->survey_model->get_survey($survey_id);
          $creator = $survey_details->user_id;
          var_dump($survey_details);die; */

        $this->db->select("survey_user_id, questionnaire_id, card_img");
        $this->db->where('questionnaire_id', $survey_id);

        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }

        $query = $this->db->get("survey_other_info");
        return $query->result();
    }
    
        /**
     * count number of leads
     * @param int $survey_id    
     * @param int $user_id
     * return object
     */
    public function get_lead_count($survey_id, $user_id = FALSE) {

        $this->db->select("survey_user_id, questionnaire_id");
        $this->db->where('questionnaire_id', $survey_id);

        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }

        $this->db->from("survey_other_info");
        return $this->db->count_all_results();
    }
    
    

    /**
     * get survey other details
     * @param int $survey_id     
     * @param int $survey_user_id     
     * return object
     */
    public function get_survey_other_details($survey_id, $survey_user_id) {
        $this->db->select("*");
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get("survey_other_info");
        return $query->row();
    }

    /**
     * get answer of question
     * @param int $survey_id     
     * @param int $survey_user_id     
     * @param int $question_id     
     * return object
     */
    public function get_survey_question_answer($survey_id, $survey_user_id, $question_id, $type = "single") {
        $this->db->select("sqa.question_id, sqa.text as answer, sqa.option_id, sa.answer as sub_answer");
        $this->db->where('sqa.questionnaire_id', $survey_id);
        $this->db->where('sqa.survey_user_id', $survey_user_id);
        $this->db->where('sqa.question_id', $question_id);
        $this->db->join('survey_sub_answer sa', 'sqa.id = sa.survey_answer_id', 'left');
        $query = $this->db->get("survey_answer sqa");
//print_r($this->db->last_query());die();
        if ($type == "single")
            return $query->row();
        else
            return $query->result();
    }

    /**
     * get answer of question    
     * @param int $question_id     
     * return object
     */
    public function get_question_answer($question_id) {
        $this->db->select("q.id, a.name as answer, q.question_type_id, q.data_entry_type_id, sa.name as sub_answer");
        $this->db->where('q.id', $question_id);
        $this->db->join('answer a', 'q.id = a.question_id', 'left');
        $this->db->join('sub_answer sa', 'a.id = sa.answer_id and q.id = sa.question_id', 'left');
        $this->db->join('question_type qt', 'qt.id = q.question_type_id', 'left');
        $query = $this->db->get("question q");
//print_r($this->db->last_query());die();
        return $query->row();
    }

    /**
     * get sub answer of question    
     * @param array $sub_answer_ids     
     * @param int $question_id     
     * return array
     */
    /*public function get_sub_answers($sub_answer_ids, $question_id) {
        $this->db->select("sa.id, sa.name");
        $this->db->where('sa.question_id', $question_id);
        $query = $this->db->get("sub_answer sa");
//print_r($this->db->last_query());die();
        $results = $query->result();
        $entries = array();
        foreach ($results as $value) {
            if (in_array($value->id, $sub_answer_ids)) {
                $entries[] = $value->name;
            }
        }
        return $entries;
    }*/
        public function get_sub_answers($sub_answer_ids, $question_id, $answer_id) {
        $this->db->select("sa.id, sa.name,sa.order");
        $this->db->where('sa.question_id', $question_id);
        $this->db->where('sa.answer_id', $answer_id);
        $query = $this->db->get("sub_answer sa");
//print_r($this->db->last_query());die();
        $results = $query->result();
        $entries = array();
        foreach ($results as $value) {
            if (in_array($value->order, $sub_answer_ids)) {
                $entries[] = $value->name;
            }
        }
        return $entries;
    }

    /**
     * insert record of survey
     * @param associative array $data     
     * @param String  $table
     * return integer
     */
    public function save($data, $table = null) {
        $table = ($table) ? $table : $this->table_name;
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of survey
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * delete record of survey
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * update record of survey contact detail info
     * @param int $survey_user_id
     * @param int $contact_field_id
     * @param associative array $data     
     * return object
     */
    public function update_contact_detail($survey_user_id, $contact_field_id, $data) {
        $this->db->where('survey_user_id', $survey_user_id);
        $this->db->where('contact_form_field_id', $contact_field_id);
        return $this->db->update('survey_contact_detail', $data);
    }

    /**
     * gets the details of survey contact details of particular survey user
     * @param int $survey_user_id
     * @param int $survey_id     
     * return array
     */
    public function get_contact_details_report($survey_user_id, $survey_id) {
        $this->db->select("scd.contact_form_field_id,scd.value,cff.name");
        $this->db->from("survey_contact_detail scd");
        $this->db->join('contact_form_field cff', 'cff.id = scd.contact_form_field_id', 'left');
        $this->db->where('survey_user_id', $survey_user_id);
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * checks the existence of survey user (who takes survey)
     * @param int $survey_user_id     
     * return object
     */
    public function check_survey_user_exists($survey_user_id) {
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get('survey_other_info');
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid survey user request
     * @param int $survey_id     
     * return bool
     */
    public function is_valid_surveyuser_reqest($survey_user_id) {
        $flag = TRUE;
        if (!$survey_user_id) {
            $flag = FALSE;
        }
        $survey_user_object = $this->check_survey_user_exists($survey_user_id);
        if (!$survey_user_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * delete surveyreport of particuar survey of particular user
     * @param int $user_id
     * return object
     */
    function delete_surveyreport($user_id) {

//delete from survey sub answer survey_sub_answer table
        $all_answers = $this->get_answers_report_user($user_id);
        $this->db->where_in('survey_answer_id', $all_answers);
        $this->db->delete('survey_sub_answer');

        $this->db->where('survey_user_id', $user_id);
        $this->db->delete('survey_answer');

        $this->db->where('survey_user_id', $user_id);
        $this->db->delete('survey_contact_detail');

        $this->db->where('survey_user_id', $user_id);
        $this->db->delete('survey_other_info');

        $this->db->where('survey_user_id', $user_id);
        $this->db->delete('survey_question');
    }

    /**
     * delete surveyreport of particuar survey of particular survey
     * @param int $survey_id
     * return object
     */
    function delete_surveyreport_survey($survey_id) {

        $all_answers = $this->get_answers_report_survey($survey_id);
        $this->db->where_in('survey_answer_id', $all_answers);
        $this->db->delete('survey_sub_answer');

        $this->db->where('questionnaire_id', $survey_id);
        $this->db->delete('survey_answer');

        $this->db->where('questionnaire_id', $survey_id);
        $this->db->delete('survey_contact_detail');

        $this->db->where('questionnaire_id', $survey_id);
        $this->db->delete('survey_other_info');

        $this->db->where('questionnaire_id', $survey_id);
        $this->db->delete('survey_question');
    }

    /**
     * gets the answers given by a particular user
     * @param int $survey_user_id
     * return array
     */
    public function get_answers_report_user($survey_user_id) {
        $result = array();
        $this->db->from("survey_answer");
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get();
        $all_answers_results = $query->result();

        if (!empty($all_answers_results)) {
            foreach ($all_answers_results as $answer) {
                $result [] = $answer->id;
            }
        }
        return $result;
    }

    /**
     * gets the answers given for a particular survey
     * @param int $survey_id
     * return array
     */
    public function get_answers_report_survey($survey_id) {
        $result = array();
        $this->db->from("survey_answer");
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get();
        $all_answers_results = $query->result();

        if (!empty($all_answers_results)) {
            foreach ($all_answers_results as $answer) {
                $result [] = $answer->id;
            }
        }
        return $result;
    }

    /**
     * get the details of particular survey user(who participates in survey)
     * @param int $survey_id     
     * return object
     */
    public function get_survey_user_details($survey_id, $survey_user_id) {
        $this->db->select("survey_user_id, questionnaire_id, original_image, card_img");
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get("survey_other_info");
        return $query->row();
    }
    
        /**
     * get all the responses of particular survey
     * @param int $survey_id     
     * return array
     */
    public function get_surveyresponse_details($survey_id) {
        $this->db->select("original_image, card_img, survey_user_id");
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get("survey_other_info");
        return $query->result();
    }

    /**
     * This function takes the backup of the survey response
     * return integer
     */
    public function backup() {

        $user_id = $this->session->userdata(SESS_UID);
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        if ($company_id > 0) {
            //pull the company specific survey
            $company_surveys = $this->survey_model->get_surveys_company($company_id);
            $survey_answer = array();
            $survey_contact_detail = array();
            $survey_other_info = array();
            $survey_question = array();
            $survey_sub_answer = array();


            if (!empty($company_surveys)) {
                foreach ($company_surveys as $company_survey) {
                    $survey_answer_query = $this->db->where('questionnaire_id', $company_survey->id);
                    $survey_answer_query = $this->db->get('survey_answer');
                    //echo $this->db->last_query();
                    $survey_ans = $survey_answer_query->result();
                    $survey_answer [] = $survey_ans;

                    $survey_answer_ids = array();

                    //collect survey answer id to insert in the survey sub answer table
                    if (!empty($survey_ans)) {
                        foreach ($survey_ans as $row) {
                            $survey_answer_ids [] = $row->id;
                        }
                    }

                    $survey_contact_detail_query = $this->db->where('questionnaire_id', $company_survey->id);
                    $survey_contact_detail_query = $this->db->get('survey_contact_detail');
                    $survey_contact_detail [] = $survey_contact_detail_query->result();

                    $survey_other_info_query = $this->db->where('questionnaire_id', $company_survey->id);
                    $survey_other_info_query = $this->db->get('survey_other_info');
                    $survey_other_info [] = $survey_other_info_query->result();

                    $survey_question_query = $this->db->where('questionnaire_id', $company_survey->id);
                    $survey_question_query = $this->db->get('survey_question');
                    $survey_question [] = $survey_question_query->result();
                    if (!empty($survey_answer_ids)) {
                        $survey_sub_answer_query = $this->db->where_in('survey_answer_id', $survey_answer_ids);
                        $survey_sub_answer_query = $this->db->get('survey_sub_answer');
                        $survey_sub_answer [] = $survey_sub_answer_query->result();
                    }
                }


                //load the remote database
                $remote = $this->load->database('remote', TRUE);

                if (!is_null($remote->use_set_names)) {

                    //truncate the existing records (no merging)
                    $remote->truncate('survey_answer');
                    $remote->truncate('survey_contact_detail');
                    $remote->truncate('survey_other_info');
                    $remote->truncate('survey_question');
                    $remote->truncate('survey_sub_answer');


                    //insert the fresh data
                    if (!empty($survey_answer)) {
                        foreach ($survey_answer as $rows) {
                            foreach ($rows as $row) {
                                $remote->insert('survey_answer', $row);
                            }
                        }
                    }

                    if (!empty($survey_contact_detail)) {
                        foreach ($survey_contact_detail as $rows) {
                            foreach ($rows as $row) {
                                $remote->insert('survey_contact_detail', $row);
                            }
                        }
                    }

                    if (!empty($survey_other_info)) {
                        foreach ($survey_other_info as $rows) {
                            foreach ($rows as $row) {
                                $remote->insert('survey_other_info', $row);
                            }
                        }
                    }

                    if (!empty($survey_question)) {
                        foreach ($survey_question as $rows) {
                            foreach ($rows as $row) {
                                $remote->insert('survey_question', $row);
                            }
                        }
                    }

                    if (!empty($survey_sub_answer)) {
                        foreach ($survey_sub_answer as $rows) {
                            foreach ($rows as $row) {
                                $remote->insert('survey_sub_answer', $row);
                            }
                        }
                    }

                    //close the remote connection
                    $remote->close();
                    $this->db->close();
                    $this->load->database('default', TRUE);
                    return 1;
                } else {
                    return 2;
                }
            }
        } else {
            return 0;
        }
    }
    
    
   
    public function get_next_survey_response($survey_id, $survey_user_id) {
        
        $survey_details = $this->survey_model->get_survey($survey_id);
        $owner = $survey_details->user_id;
        $current_logged_user = $this->session->userdata(SESS_UID);
        $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));
        $company_super_admin = $this->user_model->get_super_admin_company($survey_details->company_id);
        
        $that = $this;
        $this->db->select("*");
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get("survey_other_info");
        $current = $query->row();
   
        //var_dump($current->id);die;
        
          //next record
        $that->db->select("*");
        $that->db->limit(1);
        $that->db->where('id >', $current->id);
        $that->db->where('questionnaire_id', $survey_id);
        
        
         if ($current_role == "super-admin" || $current_logged_user == $company_super_admin) {
           
        } else if($owner == $current_logged_user) {
           $that->db->where('user_id', $owner);
        }
        
      
        $query = $this->db->get("survey_other_info");
        return $query->row();
    }


    public function get_answered_question($survey_user_id) {
        $this->db->where('survey_user_id',$survey_user_id);
        $survey_question_query = $this->db->get('survey_question');
        $responses = $survey_question_query->result();
        $questions = array();
        if(!empty($responses)) {   
           foreach($responses as $response) {
                $questions [] = $response->question_id;
            }
        }
        return $questions;
    }

}