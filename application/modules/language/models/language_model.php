<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the language related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Language_model extends CI_Model {

    private $table_name = 'language';

    /**
     * gets the list of languages
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_languages($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of language
     * @param int $id     
     * return object
     */
    public function get_language($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of language
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of language
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of language
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * gets the details of language of particular questionnaire (survey)
     * @param int $survey_id     
     * return array
     */
    public function get_language_questionnaire($survey_id) {
        $this->db->select("l.id as language_id, l.name as language, l.short_name");
        $this->db->from("language l");
        $this->db->join('questionnaire q', 'l.id = q.language_id', 'left');
        $this->db->where('q.id', $survey_id);
        $this->db->where('l.status', '1');
        //$this->db->order_by('order', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * checks the existence of language
     * @param int $language_id     
     * return object
     */
    public function check_language_exists($language_id) {
        $this->db->where('id', $language_id);
        $query = $this->db->get('language');
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid language in various stages of survey generation
     * @param int $language_id     
     * return bool
     */
    public function is_valid_language_reqest($language_id) {
        $flag = TRUE;
        if (!$language_id) {
            $flag = FALSE;
        }

        if (!is_numeric($language_id)) {
            $flag = FALSE;
        }
        $language_object = $this->language_model->check_language_exists($language_id);
        if (!$language_object) {
            $flag = FALSE;
        }
        return $flag;
    }

    /**
     * gets the list of languages to show in app
     * return array
     */
    public function get_languages_app() {
        $this->db->select('name, short_name, id');
        $this->db->where('status', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

}