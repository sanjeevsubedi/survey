<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the answer related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Answer_model extends CI_Model {

    private $table_name = 'answer';

    /**
     * insert record of answer
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of answer
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of answer of specific question
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update_answer($id, $data) {
        $this->db->where('question_id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of sub answer of specific answer of specific question
     * @param int sub_answer_id
     * @param associative array $data     
     * return object
     */
    public function update_sub_answer($sub_answer_id, $data) {
        $this->db->where('id', $sub_answer_id);
        return $this->db->update('sub_answer', $data);
    }

    /**
     * update record of answer
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * get serialized answer options of particular question
     * @param int $question_id     
     * return array
     */
    public function get_answers($question_id) {
        $this->db->where('question_id', $question_id);
        $query = $this->db->get($this->table_name);
        $result = $query->row();
        return $result;
    }

    /**
     * get unserialized answer options of particular question
     * @param int $question_id 
     * @param int $question_type_id     
     * return array
     */
    public function get_answers_unserialized($question_id, $question_type_id) {
        $answer = $this->get_answers($question_id);
        $ans_options = @unserialize($answer->name);
        return $ans_options;
    }

    /**
     * insert record of subanswer
     * @param associative array $data     
     * return integer
     */
    public function save_subanswer($data) {
        $this->db->insert("sub_answer", $data);
        return $this->db->insert_id();
    }

    /**
     * delete record of sub answer of specific question
     * @param int id
     * return object
     */
    function delete_subanswer($id) {
        $this->db->where('question_id', $id);
        return $this->db->delete("sub_answer");
    }

    /**
     * delete record of sub answer of specific question and specific answer
     * @param int $question_id
     * @param int $answer_id 
     * return object
     */
    function delete_subanswer_answer($question_id, $answer_id) {
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        return $this->db->delete("sub_answer");
    }

    /**
     * get sub answer name of particular question and particular answer
     * @param int $question_id
     * @param int $answer_id     
     * return array
     */
    public function get_subanswer($question_id, $answer_id) {
        $this->db->select("name");
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        $query = $this->db->get("sub_answer");
        $result = $query->result();
        $result_arr = array();
        foreach ($result as $k => $v) {
            $result_arr [] = $v->name;
        }
        return $result_arr;
    }

    /**
     * get sub answer of particular question
     * @param int $question_id
     * return array
     */
    public function get_subanswer_clone($question_id) {
        $this->db->where('question_id', $question_id);
        $query = $this->db->get("sub_answer");
        $result = $query->result();
        return $result;
    }

    /**
     * get sub answer of particular question and particular answer
     * @param int $question_id
     * @param int $answer_id     
     * return array
     */
    public function get_all_subanswer($question_id, $answer_id) {
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        $query = $this->db->get("sub_answer");
        $result = $query->result();
        return $result;
    }

    /**
     * insert record of data entry type of a particular sub answer
     * @param associative array $data     
     * return integer
     */
    public function save_subans_data_entry_type($data) {
        $this->db->insert('data_entry_type_sub_answer', $data);
        return $this->db->insert_id();
    }

    /**
     * delete all the records of data entry type of a particular question
     * @param int question_id
     * return object
     */
    function delete_subanswer_data_entry_type($question_id) {
        $this->db->where('question_id', $question_id);
        return $this->db->delete("data_entry_type_sub_answer");
    }

    /**
     * delete record of data entry type of a particular answer
     * @param int question_id
     * @param int answer_id
     * return object
     */
    function delete_single_subanswer_data_entry($question_id, $answer_id) {
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        return $this->db->delete("data_entry_type_sub_answer");
    }

    /**
     * get the data entry type of particular subanswer
     * @param int $question_id
     * @param int $answer_id     
     * return object
     */
    public function get_subanswer_dataentry_type($question_id, $answer_id) {
        $this->db->select("sa.data_entry_type_id as data_entry_type_id, det.name as data_entry_type_name");
        $this->db->from("data_entry_type_sub_answer sa");
        $this->db->join('data_entry_type det', 'det.id = sa.data_entry_type_id', 'left');
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

}