<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the survey related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Mar 04, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Survey_Model extends CI_Model {

    private $table_name = 'questionnaire';

    /**
     * gets the list of surveys
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_surveys($status = 1, $condition = TRUE, $company_id = FALSE, $user_id = FALSE) {
        $this->db->select("q.company_id, l.id as language_id, l.name as language, q.name as name,q.id as id, q.status, q.created_date, q.template_owner");
        $this->db->from("questionnaire q");
        $this->db->join('language l', 'l.id = q.language_id', 'left');
        if ($company_id) {
            $this->db->where('company_id', $company_id);
        }

        if ($user_id) {

            $this->db->where('user_id', $user_id);
        }

        if ($condition) {
            $this->db->where('q.status', $status);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    /**
     * gets a single record of survey
     * @param int $id     
     * return object
     */
    public function get_survey($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
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
     * update record of survey
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * checks the existence of survey
     * @param int $survey_id     
     * return object
     */
    public function check_survey_exists($survey_id) {
        $this->db->where('id', $survey_id);
        $query = $this->db->get($this->table_name);
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid request in various stages of survey generation
     * @param int $survey_id     
     * return bool
     */
    public function is_valid_survey_reqest($survey_id) {
        $flag = TRUE;
        if (!$survey_id) {
            $flag = FALSE;
        }

        if (!is_numeric($survey_id)) {
            $flag = FALSE;
        }
        $survey_object = $this->survey_model->check_survey_exists($survey_id);
        if (!$survey_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * gets all the languages of particular translation set
     * @param int $survey_id     
     * @param boolean $include_original
     * return object
     */
    public function get_translation_sets($survey_id, $include_original = TRUE) {
        $this->db->select("q.translation_set_id as translation_set_id, q.id as questionnaire_id, q.name as name, l.id as language_id, l.name as language, l.short_name");
        $this->db->from("language l");
        $this->db->join('questionnaire q', 'l.id = q.language_id', 'left');
        $this->db->where('q.translation_set_id', $survey_id);
        if ($include_original) {
            $this->db->or_where('q.id', $survey_id);
        } else {
            $this->db->where('q.id <>', $survey_id);
        }
        $this->db->where('l.status', '1');
        //$this->db->order_by('order', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * gets the list of surveys of particular company
     * @param int $company_id
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_surveys_company($company_id, $status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->where('company_id', $company_id);
        $this->db->order_by('id', 'desc');
        $this->db->select("name, id");
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets the list of surveys of particular company to show in mobile app
     * @param int $user_id
     * @param int $language_id
     * return array
     */
    public function get_surveys_company_app($user_id, $language_id = "") {
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        $this->db->where('company_id', $company_id);
        $this->db->where('status', 1);
        if (!empty($language_id)) {
            $this->db->where('language_id', $language_id);
        }
        //$this->db->order_by('id', 'asc');
        $this->db->select("name, id, language_id, translation_set_id");
        $this->db->from($this->table_name);
        $query = $this->db->get();
        $results = $query->result();
        $final_result = array();
        $translated_surveys = array();
        $translated_surveys_ids = array();
        $translated_surveys_langs = array();

        //if the user is added in other surveys apart from his own company, that should be also displayed
        $added_in_others = $this->permission_model->get_cadmin_other_survey($user_id);
        $user_det = $this->user_model->get_user($user_id);
        $user_role = $this->user_model->get_role($user_det->role_id);

        if ($user_role == 'normal-company-user') {
            $results = array();
        }

        $results = array_merge($results, $added_in_others);

        foreach ($results as $k => $result) {
            //authorized users to donwload a particular survey
            $authorized_users = $this->permission_model->get_authorized_users($result->id, TRUE);

            if (in_array($user_id, $authorized_users)) {

                $original_survey = $this->get_survey($result->id);
                $lang_detail = $this->language_model->get_language($original_survey->language_id);
                $new_original_survey = new stdClass();
                $new_original_survey->translation_set_id = $original_survey->translation_set_id;
                $new_original_survey->questionnaire_id = $original_survey->id;
                $new_original_survey->language_id = $original_survey->language_id;
                $new_original_survey->short_name = $lang_detail->short_name;
                $new_original_survey->name = $original_survey->name;

                $translated_surveys_langs[] = $lang_detail->short_name;

                if ($this->get_original_survey($result->id, $result->translation_set_id)) {
                    $all_translations = $this->get_translation_sets($result->id, FALSE);

                    foreach ($all_translations as $translation) {
                        //authorized users to donwload a translation of particular survey
                        $authorized_users_translations = $this->permission_model->get_authorized_users($translation->questionnaire_id);
                        if (in_array($user_id, $authorized_users_translations)) {
                            $translated_surveys [$translation->short_name] = $translation;
                            $translated_surveys_ids [] = $translation->questionnaire_id;
                            $translated_surveys_langs[] = $translation->short_name;
                        }
                    }
                }
                $translated_surveys [$lang_detail->short_name] = $new_original_survey;

                //exclude the translated surveys from being displayed again
                if (!in_array($result->id, $translated_surveys_ids)) {
                    $final_result[] = array("id" => $result->id, 'translations' => $translated_surveys,'translated_langs'=> $translated_surveys_langs);
                    $translated_surveys = array();
                }
                $translated_surveys = array();
                $translated_surveys_langs = array();

            }
        }

        return $final_result;
    }

    /**
     * gets a single record of survey
     * @param int $id     
     * return object
     */
    public function get_survey_clone($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * gets a single record of survey having particular translation id and language id
     * @param int $language_id
     * @param int $translation_set_id     
     * return object
     */
    public function get_survey_with_translation($language_id, $translation_set_id) {
        $this->db->where('language_id', $language_id);
        $this->db->where('translation_set_id', $translation_set_id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * finds the original survey from which translations were made
     * @param int $survey_id
     * @param int $translation_set_id     
     * return boolean
     */
    public function get_original_survey($survey_id, $translation_set_id) {
        $this->db->where('id', $survey_id);
        $this->db->where('translation_set_id', $translation_set_id);
        $query = $this->db->get($this->table_name);
        if (is_object($query->row())) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * gets a single record of translated survey of particular language
     * @param int $translation_set_id
     * @param int $language_id
     * return object
     */
    public function get_translated_survey($translation_set_id, $language_id) {
        $this->db->select("name, id");
        $this->db->where('translation_set_id', $translation_set_id);
        $this->db->where('language_id', $language_id);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * gets the survey name including translation of particular language
     * @param int $survey_id 
     * @param int $language_id     
     * return array
     */
    public function get_survey_questionnaire($survey_id, $language_id) {
        $this->db->select("id,name");
        $this->db->from($this->table_name);
        $this->db->where('id', $survey_id);
        //$this->db->order_by('order', 'asc');
        $query = $this->db->get();
        $survey = $query->row();
        $translated_survey = $this->get_translated_survey($survey->id, $language_id);
        if (empty($translated_survey)) {
            $translated_survey = new stdClass;
            $translated_survey->id = '';
            $translated_survey->name = '';
        }
        $final_survey = array(
            'original_name' => $survey->name,
            'original_id' => $survey->id,
            'translate_name' => $translated_survey->name,
            'translate_id' => $translated_survey->id,
        );
        return $final_survey;
    }

    /* public function get_all_original_survey($survey_id, $company_id) {
      $sql = "SELECT * FROM " . $this->table_name . " where ";
      $sql .= "(id = $survey_id AND translation_set_id = $survey_id) OR (translation_set_id = 0) ";
      $sql .= "AND company_id = $company_id";
      $query = $this->db->query($sql);
      return $query->result();
      } */

    /**
     * gets a single record of user from the card 
     * @param string $card_image
     * return object
     */
    public function get_user_by_imagename($card_image) {
        $this->db->select("card_image, survey_user_id, user_id, questionnaire_id");
        $this->db->where('card_image', $card_image);
        $this->db->from('survey_other_info');
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * gets a email address of the survey user
     * @param string $field_id
     * @param string $user_id
     * return string
     */
    public function get_email_by_imagename($field_id, $user_id) {
        $this->db->select("value");
        $this->db->where('contact_form_field_id', $field_id);
        $this->db->where('survey_user_id', $user_id);
        $this->db->from('survey_contact_detail');
        $query = $this->db->get();
        $row = $query->row();
        return $row->value;
    }

    /**
     * update record of card image with the new card image
     * @param int $survey_user_id
     * @param associative array $data     
     * return object
     */
    public function update_card_image_details($survey_user_id, $data) {
        $this->db->where('survey_user_id', $survey_user_id);
        return $this->db->update('survey_other_info', $data);
    }

    /**
     * get the completed survey
     * @param object $survey
     * return bool
     */
    public function is_completed_survey($survey_id) {
        $survey_details = $this->survey_model->get_survey($survey_id);
        //completed survey can't be edited
        if ($survey_details->status == 2) {
            return true;
        } else {
            return false;
        }

        /*  $owner = $survey->user_id;
          $current_logged_user = $this->session->userdata(SESS_UID);
          $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));
          $company_super_admin = $this->user_model->get_super_admin_company($survey->company_id);

          //super admin, company super admin and the owner of the survey can see all the uploaded data
          if (!($owner == $current_logged_user || $current_role == "super-admin" || $current_logged_user == $company_super_admin)) {
          $this->session->set_flashdata('error', 'Permission denied');
          redirect(base_url() . 'survey/all/');
          } */
    }

    /**
     * gets the list of surveys of normal users
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_surveys_normal_user($user_id, $condition = FALSE, $status = 1) {
        $this->db->select("q.company_id, l.id as language_id, l.name as language, q.name as name,q.id as id, q.status, q.created_date, q.template_owner");
        $this->db->from("questionnaire q");
        $this->db->join('language l', 'l.id = q.language_id', 'left');

        if ($condition) {
            $this->db->where('q.status', $status);
        }
        $query = $this->db->get();
        $result = $query->result();

        $final_surveys = array();
        foreach ($result as $row) {
            $authorized_users = $this->permission_model->get_authorized_users($row->id);
            if (in_array($user_id, $authorized_users)) {
                $final_surveys [] = $row;
            }
        }
        return $final_surveys;
    }

    /**
     * gets the list of surveys of company admin
     * @param int $user_id
     * @param int $company_id     
     * return array
     */
    public function get_surveys_cadmin_user($user_id, $company_id, $status) {
        $result = $this->survey_model->get_surveys($status, TRUE, $company_id);
        $final_surveys = array();
        foreach ($result as $row) {
            $authorized_users = $this->permission_model->get_authorized_users($row->id);
            if (in_array($user_id, $authorized_users)) {
                $final_surveys [] = $row;
            }
        }
        $added_in_others = $this->permission_model->get_cadmin_other_survey($user_id);
        return array_merge($final_surveys, $added_in_others);
    }

    /**
     * gets the survey owner
     * @param int $survey_id     
     * return array
     */
    public function get_survey_owner($survey_id) {
        $this->db->select("user_id");
        $this->db->from("questionnaire");
        $query = $this->db->get();
        $result = $query->row();
        return $result->user_id;
    }

    /**
     * clone the whole survey
     * @param int $survey_id
     * @param int $cloner     
     * return  integer
     */
    public function clone_whole_survey($survey_id, $cloner = NULL) {

        $this->load->helper('randomnumber');
        //inserts into questionnaire table
        $survey_results = $this->survey_model->get_survey_clone($survey_id);
        $data_survey = get_object_vars($survey_results);
        unset($data_survey['id']);

        //copy the logo image
        $file_ext = substr($survey_results->logo, strrpos($survey_results->logo, '.') + 1);
        $new_file = get_random_string(16) . "." . $file_ext;
        @copy(UPLOADPATH . DS . 'logo' . DS . $survey_results->logo, UPLOADPATH . DS . 'logo' . DS . $new_file);
        $data_survey['logo'] = $new_file;
        $data_survey['created_date'] = time();
        //who clones the survey
        if (!is_null($cloner)) {
            unset($data_survey['user_id']);
            unset($data_survey['company_id']);
            $data_survey['user_id'] = $cloner;
            $data_survey['company_id'] = $this->user_model->get_company_id_by_user_id($cloner);
        }
        $new_survey_id = $this->survey_model->save($data_survey);
        $new_survey_details = $this->survey_model->get_survey($new_survey_id);


        //inserts into contact_form_field_questionnaire table
        $contact_field_results = $this->contact_model->get_contact_field_questionnaire_clone($survey_id);
        foreach ($contact_field_results as $contact_field_result) {
            $data_contact_field = get_object_vars($contact_field_result);
            unset($data_contact_field['id']);
            $data_contact_field['questionnaire_id'] = $new_survey_id;
            $this->contact_model->save_contact($data_contact_field);
        }

        //inserts into device_questionnaire table
        $device_results = $this->device_model->get_device_questionnaire_clone($survey_id);
        foreach ($device_results as $device_result) {
            $data_device = get_object_vars($device_result);
            unset($data_device['id']);
            $data_device['questionnaire_id'] = $new_survey_id;
            $this->device_model->save_device_questionnaire($data_device);
        }

        //inserts into question table
        $question_results = $this->question_model->get_question_clone($survey_id);
        foreach ($question_results as $question_result) {

            $data_question = get_object_vars($question_result);
            unset($data_question['id']);
            $data_question['questionnaire_id'] = $new_survey_id;
            $new_question_id = $this->question_model->save($data_question);

            //inserts into question file table
            $file_results = $this->file_model->get_resources_question($question_result->id);
            foreach ($file_results as $file_result) {
                $data_file = get_object_vars($file_result);
                $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
                unset($data_file['id']);
                //$new_file = get_random_string(16) . "." . $file_ext;
                $new_file = $this->file_model->get_duplicate_file($file_result->name);
                $data_file['question_id'] = $new_question_id;
                $data_file['name'] = $new_file;
                @copy(UPLOADPATH . DS . 'question' . DS . $file_result->name, UPLOADPATH . DS . 'question' . DS . $new_file);
                $this->file_model->save_question_file($data_file);
            }

            //inserts answer of particular survey
            $answer_results = $this->answer_model->get_answers($question_result->id);
            $data_answer = get_object_vars($answer_results);
            unset($data_answer['id']);
            $data_answer['question_id'] = $new_question_id;
            $this->answer_model->save($data_answer);

            //inserts into answer file table
            $file_results = $this->file_model->get_resources_answerall($question_result->id);
            foreach ($file_results as $file_result) {
                $data_file = get_object_vars($file_result);
                $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
                unset($data_file['id']);
                //$new_file = get_random_string(16) . "." . $file_ext;
                $new_file = $this->file_model->get_duplicate_file($file_result->name);
                $data_file['question_id'] = $new_question_id;
                $data_file['name'] = $new_file;
                $data_file['answer_id'] = $file_result->answer_id;
                @copy(UPLOADPATH . DS . 'answer' . DS . $file_result->name, UPLOADPATH . DS . 'answer' . DS . $new_file);
                $this->file_model->save_answer_file($data_file);
            }

            //inserts subanswer of particular questions of particular survey
            $subanswer_results = $this->answer_model->get_subanswer_clone($question_result->id);
            foreach ($subanswer_results as $subanswer_result) {
                $data_subanswer = get_object_vars($subanswer_result);
                unset($data_subanswer['id']);
                $data_subanswer['question_id'] = $new_question_id;
                $this->answer_model->save_subanswer($data_subanswer);
            }
        }

        //inserts into survey file table
        $file_results = $this->file_model->get_resources_survey($survey_id);
        foreach ($file_results as $file_result) {
            $data_file = get_object_vars($file_result);
            $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
            unset($data_file['id']);
            //$new_file = get_random_string(16) . "." . $file_ext;
            $new_file = $this->file_model->get_duplicate_file($file_result->name);
            $data_file['questionnaire_id'] = $new_survey_id;
            $data_file['name'] = $new_file;
            @copy(UPLOADPATH . DS . 'survey' . DS . $file_result->name, UPLOADPATH . DS . 'survey' . DS . $new_file);
            $this->file_model->save_survey_file($data_file);
        }

        //inserts into permission user table so that user can see in the survey list in the overview page
        $user_id = $survey_results->user_id;
        $previous_priveleges = $this->permission_model->get_survey_privelege($survey_id, $user_id);
        $data_previous_priveleges = get_object_vars($previous_priveleges[0]);
        unset($data_previous_priveleges['id']);
        unset($data_previous_priveleges['questionnaire_id']);
        $data_previous_priveleges['questionnaire_id'] = $new_survey_id;

        //cloning is triggered from the registration page (dummy questionnaire for every company)
        if (!is_null($cloner)) {
            unset($data_previous_priveleges['user_id']);
            $data_previous_priveleges['user_id'] = $cloner;
        }

        $this->permission_model->save_priveledge($data_previous_priveleges);
        return $new_survey_id;
    }

    /**
     * gets the list of salesperson of a particulal questionnaire
     * @param int $survey_id     
     * return array
     */
    public function get_salesperson_questionnaire($survey_id) {
        $this->db->select("s.id as id, s.name as name");
        $this->db->from("salesperson s");
        $this->db->join('salesperson_questionnaire sq', 's.id = sq.salesperson_id', 'left');
        $this->db->where('sq.questionnaire_id', $survey_id);
        $this->db->where('s.status', '1');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

}