<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the logic related functions.
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date April 04, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Logic_model extends CI_Model {

    private $table_name = 'logic';

    /**
     * insert record of logic
     * @param associative array $data     
     * @param string $table     
     * return integer
     */
    public function save($data, $table = null) {
        $table = ($table) ? $table : $this->table_name;
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of logic
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }
    /**
     * update record of logic
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update_condition($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('condition', $data);
    }

    /**
     * delete record of logic
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }
    
    /**
     * delete record of logic
     * @param int id
     * return object
     */
    function delete_condition($id) {
        $this->db->where('id', $id);
        return $this->db->delete('condition');
    }

    /**
     * gets a single record of logic
     * @param int $id     
     * return object
     */
    public function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }
    /**
     * gets a lost of record of conditions
     * @param int $id     
     * return object
     */
    public function get_condition_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('condition');
        return $query->row();
    }
    /**
     * gets  records of logic condition
     * @param int $question_id     
     * @param int $jump_to     
     * return object
     */
    public function get_conditions($question_id, $jump_to) {
        $this->db->select('distinct(jump_to),logic.group_logic as group_logic, operator.name as operator, logic.value as value');
        $this->db->where('question_id', $question_id);
        $this->db->where('jump_to', $jump_to);
        $this->db->order_by('group_logic', 'asc');
        $this->db->join('operator','logic.operator_id=operator.id');
        $query = $this->db->get('logic');
        //print_r($this->db->last_query());die();
        return $query->result();
    }
    
    /**
     * gets a single record of logic
     * @param int $logic_id     
     * return object
     */
    public function get_logic($logic_id) {
        $this->db->select('distinct(logic.question_id),logic.id as id, logic.operator_id,logic.group_logic as group_logic, logic.jump_to as jump_to, operator.name as name, logic.value as value');
        $this->db->where('logic.id', $logic_id);
        $this->db->join('operator','logic.operator_id=operator.id','left');
        $query = $this->db->get($this->table_name);
        return $query->row();
    }
    
    /**
     * gets  records of logic
     * @param int $question_id     
     * return object
     */
    public function get_logics($question_id) {
        $this->db->select('distinct(logic.question_id),logic.id as id, logic.group_logic as group_logic, logic.jump_to as jump_to, operator.name as name, logic.value as value');
        $this->db->where('question_id', $question_id);
        $this->db->join('operator','logic.operator_id=operator.id','left');
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * gets  records of logic
     * @param int $question_id     
     * return object
     */
    public function get_jump_to_logics($question_id) {
        $this->db->select('distinct(logic.jump_to)');
        $this->db->where('question_id', $question_id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * list of operators
     * 
     * return object array
     */
    public function list_operators() {
        $query = $this->db->get('operator');
        return $query->result();
    }
}