<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles garbage collection functions related to unused files/images.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Nov 08, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Cronjob_model extends CI_Model {

    /**
     * gets the list of temporary files to be deleted
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_temp_files($status = 0) {
        $this->db->where('status', $status);
        $query = $this->db->get('temporary_file');
        $results = $query->result();
        return $results;
    }

    /**
     * delete record of temporary files
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('temporary_file');
    }

}