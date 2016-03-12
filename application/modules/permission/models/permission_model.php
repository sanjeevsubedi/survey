<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the Permission related functions.
 * 
 * @author Sushil Gupta
 * @version 1.0
 * @date June 26, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Permission_model extends CI_Model {

    private $table_name = 'permission';

    /**
     * gets the list of permissions
     * @param int $status     
     * @param boolen $condition
     * return array
     */
    public function get_permissions($status = 1, $condition = TRUE) {
        if ($condition) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of permission
     * @param int $id     
     * return object
     */
    public function get_permission($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of permission
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * update record of permission
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of permission
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * checks the existence of permission
     * @param int $permission_id     
     * return object
     */
    public function check_permission_exists($permission_id) {
        $this->db->where('id', $permission_id);
        $query = $this->db->get($this->table_name);
        $result = $query->row();
        return $result;
    }

    /**
     * checks the privilege
     * @param int $survey_id     
     * return boolean
     */
    public function check_privelege($survey_id = NULL) {
        $current_logged_user = $this->session->userdata(SESS_UID);
        $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));

        if (!is_null($survey_id)) {
            $survey_details = $this->survey_model->get_survey($survey_id);
            $owner = $survey_details->user_id;
            $company_super_admin = $this->user_model->get_super_admin_company($survey_details->company_id);
            $privelege_name = "";

            $user_privelege = $this->get_survey_privelege($survey_id, $current_logged_user);
            if (!empty($user_privelege)) {
                $privelege = $this->permission_model->get_permission($user_privelege[0]->permission_id);
                $privelege_name = !empty($privelege->slug) ? $privelege->slug : "";
            }

            //super admin, company super admin, owner and user having questionnaire admin previlege can edit the survey
            if ($owner == $current_logged_user || $current_role == "super-admin" || $current_logged_user == $company_super_admin || $privelege_name == 'questionnaire-admin') {
                return true;
            } else {
                return false;
            }
        } else {

            if ($current_role == "super-admin") {
                return true;
            }
            $company_id = $this->user_model->get_company_id_by_user_id($current_logged_user);
            $company_super_admin = $this->user_model->get_super_admin_company($company_id);

            //super admin, company super admin can create the survey
            if ($current_logged_user == $company_super_admin) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * insert record of priveledge assigned to a particular user
     * @param associative array $data     
     * return integer
     */
    public function save_priveledge($data) {
        $this->db->insert('permission_user', $data);
        return $this->db->insert_id();
    }

    /**
     * update record of priveledge assigned to a particular user
     * @param int $survey_id
     * @param int $user_id
     * @param associative array $data     
     * return object
     */
    public function update_priveledge($survey_id, $user_id, $data) {
        $this->db->where('questionnaire_id', $survey_id);
        $this->db->where('user_id', $user_id);
        return $this->db->update('permission_user', $data);
    }

    /**
     * delete record of all priveledge assigned to a particular user for a particular survey
     * @param int $user_id
     * @param int $survey_id
     * return object
     */
    function delete_priveledge($survey_id, $user_id = NULL) {
        $this->db->where('questionnaire_id', $survey_id);
        if (!is_null($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        return $this->db->delete('permission_user');
    }

    /**
     * gets a privelege of a of a user of a particular survey 
     * @param int $survey_id
     * @param int $user_id     
     * return object
     */
    public function get_survey_privelege($survey_id, $user_id = NULL) {
        $this->db->from("permission_user");
        $this->db->where('questionnaire_id', $survey_id);

        if (!is_null($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * gets all the list of users who are authorized to download a particular survey
     * @param int $survey_id
     * @param boolean $app 
     * return array
     */
    public function get_authorized_users($survey_id, $app = FALSE) {
        $result = $this->get_survey_privelege($survey_id);
        $users = array();

        //$survey_details = $this->survey_model->get_survey($survey_id);
        //$owner = $survey_details->user_id;
        //if (!in_array($owner, $users)) {
        //array_push($users, $owner);
        //}

        if (!empty($result)) {
            foreach ($result as $row) {
                $perm_detail = $this->permission_model->get_permission($row->permission_id);

                if (is_object($perm_detail)) {
                    $perm_name = $perm_detail->slug;

                    if ($app) {
                        if ($perm_name == 'questionnaire-user' || $perm_name == 'questionnaire-admin') {
                            $users [] = $row->user_id;
                        }
                    } else {
                        $users [] = $row->user_id;
                    }
                }
            }
        }
        return $users;
    }

    /**
     * gets list of survey owned by other but company super admin added in them 
     * @param int $user_id     
     * return array
     */
    public function get_cadmin_other_survey($user_id) {
        $this->db->from("permission_user");
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result();

        $surveys = array();
        foreach ($result as $row) {
            $survey_id = $row->questionnaire_id;
            $survey_details = $this->survey_model->get_survey($survey_id);
            $company_super_admin = $this->user_model->get_super_admin_company($survey_details->company_id);

            if ($company_super_admin != $user_id && $user_id != 1) {
                $surveys [] = $survey_details;
            }
        }
        return $surveys;
    }

}