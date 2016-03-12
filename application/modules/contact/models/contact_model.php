<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the contact field related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Contact_model extends CI_Model {

    private $table_name = 'contact_form_field';

    /**
     * gets the list of contacts field
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_contacts($status = 1, $condition = TRUE, $start = NULL, $end = NULL) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $this->db->where('language_id', 8);
        $query = $this->db->get($this->table_name, $start, $end);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of contact
     * @param int $id     
     * return object
     */
    public function get_contact($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of contact
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of contact
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of contact
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * delete all the translations of a particular contact field
     * @param int id
     * return object
     */
    function delete_ctranslations($id) {
        $this->db->where('translation_set_id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * insert record of contact field of paricular questionnaire
     * @param associative array $data     
     * return integer
     */
    public function save_contact($data) {
        $this->db->insert('contact_form_field_questionnaire', $data);
        return $this->db->insert_id();
    }

    /**
     * delete record of contact field of paricular questionnaire
     * @param int id
     * return object
     */
    public function delete_contact($id) {
        $this->db->where('questionnaire_id', $id);
        return $this->db->delete('contact_form_field_questionnaire');
    }

    /**
     * update record of contact field of paricular required field
     * @param int id
     * @param associative array $data 
     * return object
     */
    public function update_required_fields($id, $data) {
        $this->db->where('is_required', $id);
        return $this->db->update('contact_form_field_questionnaire', $data);
    }
    
       public function update_required_fields_survey($id,$survey_id, $data) {
        $this->db->where('is_required', $id);
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->update('contact_form_field_questionnaire', $data);
    }

    public function update_visible_fields_survey($id,$survey_id, $data) {
        $this->db->where('is_visible', $id);
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->update('contact_form_field_questionnaire', $data);
    }

    /**
     * update record of contact field of paricular contact form field
     * @param int id
     * @param associative array $data 
     * return object
     */
    public function update_contact($id, $data) {
        $this->db->where('contact_form_field_id', $id);
        return $this->db->update('contact_form_field_questionnaire', $data);
    }
    
    
    
      public function update_required_contact_fields($id,$survey_id, $data) {
        $this->db->where('contact_form_field_id', $id);
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->update('contact_form_field_questionnaire', $data);
    }
    

    /**
     * gets the list of contact fields of particular questionnaire (survey)
     * @param int $survey_id     
     * return array
     */
    public function get_contact_field_questionnaire($survey_id) {
        $this->db->where('contact_form_field_questionnaire.questionnaire_id', $survey_id);
        $this->db->join('contact_form_field', 'contact_form_field.id = contact_form_field_questionnaire.contact_form_field_id', 'left');
        $this->db->join('field_config', 'field_config.id = contact_form_field.field_config_id', 'left');
        //$this->db->order_by('order', 'asc');
        $query = $this->db->get('contact_form_field_questionnaire');
        //echo $this->db->last_query();die;
        $results = $query->result();
        return $results;
    }

    /**
     * gets the list of contact fields of particular questionnaire (survey)
     * @param int $survey_id     
     * return array
     */
    public function get_contact_fields_for_survey($survey_id) {
        $this->db->select('contact_form_field.id, contact_form_field.name');
        $this->db->where('contact_form_field_questionnaire.questionnaire_id', $survey_id);
        $this->db->join('contact_form_field', 'contact_form_field.id = contact_form_field_questionnaire.contact_form_field_id', 'left');
        $this->db->join('field_config', 'field_config.id = contact_form_field.field_config_id', 'left');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('contact_form_field_questionnaire');
        //echo $this->db->last_query();die;
        $results = $query->result();
        return $results;
    }

    /**
     * gets the count of total contact fields of particular questionnaire (survey)
     * @param int $survey_id     
     * return int
     */
    public function get_count_contact_field_questionnaire($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->from('contact_form_field_questionnaire');
        return $this->db->count_all_results();
    }

    /**
     * gets the highest order number of contact field of particular questionnaire
     * @param int $survey_id 
     * return int
     */
    public function get_highest_contact_field_order($survey_id) {
        $this->db->select_max('order', 'max_order');
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get('contact_form_field_questionnaire');
        $result = $query->row();
        return $result->max_order;
    }

    /**
     * gets the following contact field of particular questionnaire
     * @param int $survey_id 
     * @param int $contact_field_id 
     * @param string $type
     * return array
     */
    public function get_contact_field_neighbour($survey_id, $contact_field_id, $type) {
        $this->db->where('questionnaire_id', $survey_id);
        if ($type == 'following') {
            $this->db->order_by('order', 'asc');
        } else {
            $this->db->order_by('order', 'desc');
        }
        $query = $this->db->get('contact_form_field_questionnaire');
        $results = $query->result();

        $contact_field_array = array();
        $current_contact_field = NULL;

        foreach ($results as $row) {

            if ($contact_field_id == $row->contact_form_field_id) {
                $current_contact_field = 1;
            }
            if ($current_contact_field == 2) {
                $contact_field_array[$row->contact_form_field_id] = $row->order;

                return $contact_field_array;
            }

            if (!is_null($current_contact_field)) {
                $current_contact_field++;
            }
        }
    }

    /**
     * gets a single record of particulare contact form field
     * @param int $contact_form_field_id     
     * return object
     */
    public function get_contact_form_field($contact_form_field_id) {
        $this->db->where('contact_form_field_id', $contact_form_field_id);
        $query = $this->db->get('contact_form_field_questionnaire');
        return $query->row();
    }

    /**
     * gets the list of required contacts field of particular questionnaire
     * @param int $survey_id     
     * return array
     */
    public function get_requried_contact_field($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('is_required', 1);
        $query = $this->db->get('contact_form_field_questionnaire');
        $results = $query->result();
        $required_fields = array();
        foreach ($results as $row) {
            $required_fields[] = $row;
        }
        return $required_fields;
    }

        /**
     * gets the list of visible contacts field of particular questionnaire
     * @param int $survey_id     
     * return array
     */
    public function get_visible_contact_field($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('is_visible', 1);
        $query = $this->db->get('contact_form_field_questionnaire');
        $results = $query->result();
        $visible_fields = array();
        foreach ($results as $row) {
            $visible_fields[] = $row;
        }
        return $visible_fields;
    }

    /**
     * gets all the records of contact field questionnaire table of particular survey
     * @param int $survey_id     
     * return array
     */
    public function get_contact_field_questionnaire_clone($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get('contact_form_field_questionnaire');
        $results = $query->result();
        return $results;
    }

    /**
     * gets the list of contacts field with permission
     * @param int $status     
     * @param boolen $condition
     * @param boolen $company_id
     * return array
     */
    public function get_contacts_with_permission($status = 1, $condition = TRUE, $company_id) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->where('company_id', $company_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of translated contact field of particular language
     * @param int $translation_set_id
     * @param int $language_id
     * return object
     */
    public function get_translated_contact_field($translation_set_id, $language_id) {
        $this->db->select("c.id as contact_field_id, c.name as contact_field");
        $this->db->where('c.translation_set_id', $translation_set_id);
        $this->db->where('c.language_id', $language_id);
        $this->db->from("contact_form_field_questionnaire cq");
        $this->db->join('contact_form_field c', 'c.id = cq.contact_form_field_id', 'left');
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * gets the list of questions of particular survey including translation of particular language
     * @param int $survey_id     
     * return array
     */
    public function get_all_contact_field_questionnaire($survey_id, $language_id) {
        $this->db->select("c.id as contact_field_id, c.name as contact_field");
        $this->db->from("contact_form_field_questionnaire cq");
        $this->db->join('contact_form_field c', 'c.id = cq.contact_form_field_id', 'left');
        $this->db->where('cq.questionnaire_id', $survey_id);
        //$this->db->order_by('order', 'asc');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $all_contact_fields = $query->result();
        $final_array = array();
        foreach ($all_contact_fields as $contact_field) {
            $translated_contact_field = $this->get_translated_contact_field($contact_field->contact_field_id, $language_id);

            if (empty($translated_contact_field)) {
                $translated_contact_field = new stdClass;
                $translated_contact_field->contact_field = '';
                $translated_contact_field->contact_field_id = '';
            }
            $final_array[] = array(
                'original_name' => $contact_field->contact_field,
                'original_id' => $contact_field->contact_field_id,
                'translate_name' => $translated_contact_field->contact_field,
                'translate_id' => $translated_contact_field->contact_field_id,
            );
        }

        return $final_array;
    }

    /**
     * gets the list of contacts field of specific language
     * @param int $language_id     
     * return array
     */
    public function get_contacts_by_language($language_id, $company = FALSE) {
        $this->db->where('status', 1);
        if ($company) {
            $this->db->where('company_id', 0);
        }
        $this->db->where('language_id', $language_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of contact field according to the name of the identifier
     * @param string $identifier     
     * return int
     */
    public function get_email_contact_field($identifier) {
        $this->db->where('identifier', $identifier);
        $query = $this->db->get($this->table_name);
        $row = $query->row();
        return $row->id;
    }

    /**
     * gets the list of contact fields of particular row of particular questionnaire (survey)
     * @param int $survey_id
     * @param int $row     
     * return array
     */
    public function get_row_fields($survey_id, $row) {
        $this->db->where('contact_form_field_questionnaire.questionnaire_id', $survey_id);
        $this->db->where('contact_form_field_questionnaire.row', $row);
        $this->db->join('contact_form_field', 'contact_form_field.id = contact_form_field_questionnaire.contact_form_field_id', 'left');
        $this->db->join('field_config', 'field_config.id = contact_form_field.field_config_id', 'left');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('contact_form_field_questionnaire');
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of translated contact field of particular language
     * @param int $translation_set_id
     * @param int $language_id
     * return object
     */
    public function get_single_translated_contact_field($translation_set_id, $language_id) {
        $this->db->select("c.id as contact_field_id, c.name as contact_field");
        $this->db->where('c.translation_set_id', $translation_set_id);
        $this->db->where('c.language_id', $language_id);
        $query = $this->db->get('contact_form_field c');
        return $query->row();
    }

    /**
     * checks the existence of contact form field
     * @param int $contact_field_id     
     * return object
     */
    public function check_contact_field_exists($contact_field_id) {
        $this->db->where('id', $contact_field_id);
        $query = $this->db->get($this->table_name);
        $result = $query->row();
        return $result;
    }

    /**
     * gets the list of default contacts field of specific language
     * @param int $language_id     
     * return array
     */
    public function get_default_contacts_by_language($language_id, $get_ids = TRUE) {
        $ids = array();
        switch ($language_id) {
            case 8: //english
                $default_contact_fields = unserialize(DEFAULT_EN_CONTACT_FIELDS);
                break;
            case 7: //french
                $default_contact_fields = unserialize(DEFAULT_FR_CONTACT_FIELDS);
                break;
            case 6: //spanish
                $default_contact_fields = unserialize(DEFAULT_ES_CONTACT_FIELDS);
                break;
            case 5: //german
                $default_contact_fields = unserialize(DEFAULT_DE_CONTACT_FIELDS);
                break;
        }

        $this->db->where_in('id', $default_contact_fields);
        $this->db->where('status', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();

        if ($get_ids) {
            foreach ($results as $result) {
                $ids [] = $result->id;
            }
            return $ids;
        }

        return $results;
    }

    /**
     * gets the list of contact collection types
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_contact_types($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('order', 'asc');
        $query = $this->db->get('data_collection_method');
        $results = $query->result();
        return $results;
    }

    /**
     * checks the existence of contact collection type
     * @param int $contact_type_id     
     * return object
     */
    public function check_contact_type_exists($contact_type_id) {
        $this->db->where('id', $contact_type_id);
        $query = $this->db->get('data_collection_method');
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid contact collection type
     * @param int $contact_type_id     
     * return bool
     */
    public function is_valid_contact_request($contact_type_id) {
        $flag = TRUE;
        if (!$contact_type_id) {
            $flag = FALSE;
        }

        if (!is_numeric($contact_type_id)) {
            $flag = FALSE;
        }
        $contact_type_object = $this->check_contact_type_exists($contact_type_id);
        if (!$contact_type_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * This function takes field name from the XML and searches their original ID
     * @returns feilds original id in english
     */
    public function get_original_field_id($field) {
        $this->db->where('identifier', $field);
        $query = $this->db->get('contact_form_field');
        $result = $query->row();
        return $result;
    }

    /**
     * This function takes original field_id and searches their translated id in given language
     * @returns feilds original id in english
     */
    public function get_translated_field_id($original_id, $language_id) {
        $this->db->where('translation_set_id', $original_id);
        $this->db->where('language_id', $language_id);

        $query = $this->db->get('contact_form_field');

        $result = $query->row();
        return $result;
    }

    /**
     * insert record of badgescan
     * @param associative array $data     
     * return integer
     */
    public function save_badgescan($data) {
        $this->db->insert('badgescan', $data);
        return $this->db->insert_id();
    }

    /**
     * update record of badgescan
     * @param int id
     * return object
     */
    function delete_badgescan($questionnaire_id) {
        $this->db->where('questionnaire_id', $questionnaire_id);
        return $this->db->delete('badgescan');
    }

    /**
     * gets a single record of particulare badgescan
     * @param int $questionnaire_id     
     * return object
     */
    public function get_badgescan($questionnaire_id) {
        $this->db->where('questionnaire_id', $questionnaire_id);
        $query = $this->db->get('badgescan');
        return $query->row();
    }

}