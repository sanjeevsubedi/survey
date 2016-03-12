<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the device related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Device_model extends CI_Model {

    private $table_name = 'device';

    /**
     * gets the list of devices
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_devices($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        $devices = array();
        foreach ($results as $row) {
            $devices[] = $row;
        }
        return $devices;
    }
	
    /**
     * gets a single record of device
     * @param int $id     
     * return object
     */
    public function get_device($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of device
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of device
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of device
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }
	

    /**
     * insert record of one or more devices used by particular questionnaire 
     * @param associative array $data     
     * return integer
     */
    public function save_device_questionnaire($data) {
        $this->db->insert('device_questionnaire', $data);
        return $this->db->insert_id();
    }
	
    /**
     * gets the list of devices used by particular questionnaire
     * @param int $survey_id     
     * return array
     */
    public function get_devices_questionnaire($survey_id) {
        $this->db->select("d.id as id, d.name as name");
        $this->db->from("device d");
        $this->db->join('device_questionnaire dq', 'd.id = dq.device_id', 'left');
        $this->db->where('dq.questionnaire_id', $survey_id);
        $this->db->where('d.status', '1');
        $query = $this->db->get();
        $results = $query->result();
        return $results;
    }

    /**
     * delete all record of devices used by particular questionnaire
     * @param int $survey_id
     * return object
     */
    function delete_devices_questionnaire($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        return $this->db->delete('device_questionnaire');
    }

    /**
     * gets all the records of device questionnaire table of particular survey
     * @param int $survey_id     
     * return array
     */
	 
    public function get_device_questionnaire_clone($survey_id) 
	{
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get('device_questionnaire');
        $results = $query->result();
        return $results;
    }

}