<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the salesperson related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Salesperson_model extends CI_Model {

    private $table_name = 'salesperson';

    /**
     * gets the list of salespersons
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_salesperson($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        $salespersons = array();
        foreach ($results as $row) {
            $salespersons[] = $row;
        }
        return $salespersons;
    }

    /**
     * gets a single record of salesperson
     * @param int $id     
     * return object
     */
    public function get_salperson($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        $rowcount = $query->num_rows();
        if ($rowcount > 0) {
            return $query->row();
        }
    }

    /**
     * insert record of salesperson
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of salesperson
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of salesperson
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * gets the list of questionnaire used by a particular salesperson
     * @param int $salesperson_id     
     * return array
     */
    public function get_salesperson_questionnaire($salesperson_id) {
        $this->db->select("s.id as id, s.name as name,sq.questionnaire_id as sqid");
        $this->db->from("salesperson s");
        $this->db->join('salesperson_questionnaire sq', 's.id = sq.salesperson_id', 'left');
        $this->db->where('sq.salesperson_id', $salesperson_id);
        $this->db->where('s.status', '1');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    /**
     * delete all record of questionnaires used by particular salesperson
     * @param int $salesperson_id
     * return object
     */
    function delete_salesperson_questionnaire($salesperson_id) {
        $this->db->where('salesperson_id', $salesperson_id);
        return $this->db->delete('salesperson_questionnaire');
    }

    /**
     * insert record of one or more questionnaires used by particular salesperson 
     * @param associative array $data     
     * return integer
     */
    public function save_salesperson_questionnaire($data) {
        $this->db->insert('salesperson_questionnaire', $data);
        return $this->db->insert_id();
    }

}