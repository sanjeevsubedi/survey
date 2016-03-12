<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the log related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Mar 14, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Log_model extends CI_Model {

    private $table_name = 'log';

    /**
     * insert record of log
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of log
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * gets all the error records of log
     * @param int $id     
     * return array
     */
    public function get_error_logs() {
        $this->db->where('has_error', 1);
        $this->db->where('is_processed', 0);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * gets all the users of a particular file
     * @param int $file_name     
     * return array
     */
    public function get_users_by_filename($file_name) {
        $this->db->where('file_name', $file_name);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * gets all the questions of a particular user
     * @param string $survey_user_id     
     * return array
     */
    public function get_questions_by_user($survey_user_id) {
        $this->db->where('survey_user_id', $survey_user_id);
        $query = $this->db->get('survey_question');
        $result = $query->result();
        $question_ids = array();
        foreach ($result as $v) {
            $question_ids [] = $v->question_id;
        }
        return $question_ids;
    }

    /**
     * deletes all the incosistent data which was uploaded
     * @param int $survey_user_id     
     * return void
     */
    public function delete_inconsistent_data() {
        $total_logs = $this->log_model->get_error_logs();
        if (!empty($total_logs)) {
            foreach ($total_logs as $total_log) {
                $file_name = $total_log->file_name;
                $users = $this->get_users_by_filename($file_name);
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $survey_user_id = $user->survey_user_id;
                        $this->db->where('survey_user_id', $survey_user_id);
                        $this->db->delete('survey_answer');

                        $this->db->where('survey_user_id', $survey_user_id);
                        $this->db->delete('survey_contact_detail');

                        $this->db->where('survey_user_id', $survey_user_id);
                        $this->db->delete('survey_other_info');

                        $q_ids = $this->get_questions_by_user($survey_user_id);

                        if (!empty($q_ids)) {
                            $this->db->where_in('question_id', $q_ids);
                            $this->db->delete('survey_sub_answer');
                        }

                        $this->db->where('survey_user_id', $survey_user_id);
                        $this->db->delete('survey_question');
                    }
                }
                //update to indicate that all the records associated to this file is cleared
                $this->update($total_log->id, array('is_processed' => 1));
            }
        }
    }

}