<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the field related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Field_model extends CI_Model {

    private $table_name = 'field_config';

    /**
     * gets the list of fields
     * return array
     */
    public function get_fields() {

        $this->db->order_by('id', 'asc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        $fields = array();
        foreach ($results as $row) {
            $fields[] = $row;
        }
        return $fields;
    }

    /**
     * gets a single record of field
     * @param int $id     
     * return object
     */
    public function get_field($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of field
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of field
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of field
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

}