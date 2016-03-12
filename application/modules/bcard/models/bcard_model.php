<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the business cards xml processing related functions.
 * 
 * @author Sushil Gupta
 * @version 1.0
 * @date October 22, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Bcard_model extends CI_Model {

    private $table_name = 'bcard_xml_stat';

    /**
     * gets the list of processed xmls
     * return array
     */
    public function get_processed_xmls() {
        $query = $this->db->get($this->table_name); //bcard_xml_stat
        $results = $query->result();
        return $results;

        /* $processed_xmls = array();
          foreach ($results as $row) {
          $processed_xmls[] = $row;
          }

          return $processed_xmls; */
    }

    /**
     * insert record of newly scanned xml file in the stats table
     * @param associative array $data     
     * return integer
     */
    public function update_xml_stats($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * insert new records scanned from the xml bcard into the proper table
     * @param associative array $data
     * if the contact_field_id is already added in the table, then update that one
     * return integer
     */
    public function save_xml_data($data, $table) {
        //check if the data is already inserted
        $already_present = $this->check_xml_data_present($data, $table);

        if ($already_present) { //then update it instead of inserting it
            $this->db->where('id', $already_present[0]->id);
            $this->db->update($table, $data);
            return $this->db->affected_rows();
        } else { //else insert this new data in the table
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }
    }

    /**
     * check if the provided xml field has already been added
     * for that survey_id, user_id, contact_field_id
     * @param $data, associative array, contains all the things we need in itself
     * return boolean
     */
    public function check_xml_data_present($data, $table) {
        $this->db->where('contact_form_field_id', $data['contact_form_field_id']);
        $this->db->where('questionnaire_id', $data['questionnaire_id']);
        $this->db->where('survey_user_id', $data['survey_user_id']);

        $query = $this->db->get($table);
        $result = $query->result();

        if (!empty($result))
            return $result;
        else
            return false;
    }

    /**
     * get the total business cards processed (xml processed) of a particular survey
     * @param int $survey_id     
     * return int
     */
    public function get_bcards_processed($survey_id) {
        $this->db->from($this->table_name);
        $this->db->where('survey_id', $survey_id);
        return $this->db->count_all_results();
    }

    /**
     * get the total business cards uploaded of a particular survey
     * @param int $survey_id     
     * return int
     */
    public function get_bcards_uploaded($survey_id) {
        $this->db->from('survey_other_info');
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->count_all_results();
    }

}