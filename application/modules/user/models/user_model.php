<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the user related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class user_model extends CI_Model {

    private $table_name = 'user';

    /**
     * checks the valid user
     * @param string $email     
     * @param string $password
     * return boolean
     */
    public function validate_user($email, $password) {
        $password = md5($password);
        $this->db->select("id, email, role_id");
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->where('status', 1);
        $query = $this->db->get($this->table_name);
        $row = $query->row();
        if ($row) {
            $this->set_session_variables($row);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * checks the valid user
     * @param string $email     
     * @param string $password
     * return object
     */
    public function validate_user_app($email, $password) {
        $password = md5($password);
        $this->db->select("id,email");
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * checks the session of the user
     * return boolean
     */
    function check_session() {
        $sname = $this->session->userdata(SESS_UNAME);
        $sid = $this->session->userdata(SESS_UID);
        if ($sid || $sname) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * insert record of user
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }


        /**
     * insert record of pushtoken
     * @param associative array $data     
     * return integer
     */
    public function save_pushtoken($data) {
        $this->db->insert('pushtoken', $data);
        return $this->db->insert_id();
    }

    /**
     * gets a record of specific user
     * @param int id
     * return object
     */
    public function get_user($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * update record of user
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * checks if user is already registered with provided email address
     * return boolean
     */
    public function is_already_registered($email) {
        $this->db->select('email');
        $this->db->from('user');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * checks if user is already registered with provided email address in update function
     * return boolean
     */
    public function is_already_registered_edit($email, $user_id) {
        $this->db->select('email');
        $this->db->from('user');
        $this->db->where('email', $email);
        $this->db->where('id <>', $user_id);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * function to change the password
     * @param type $pass_data
     * @param type $email
     * @return type 
     */
    function update_password($pass_data, $email) {
        $this->db->where('email', $email);
        return $this->db->update($this->table_name, $pass_data);
    }

    /**
     * check whether the email exist
     * @param type $email
     * @return type 
     */
    function check_user_email($email) {
        $this->db->select('first_name', FALSE);
//        $this->db->select('COUNT(*) AS count', FALSE); 
        if ($email != "") {
            $this->db->where('email', $email);
        }
        $query = $this->db->get($this->table_name);
        return $query->row();
//        return $count->count;
    }

    /**
     * function that returns all the user of the cms.
     * @param bool company_id
     * @return type 
     */
    function get_all_user($company_id = NULL, $status = NULL) {
        $this->db->select('*');
        $this->db->from($this->table_name);
        //$this->db->where('role_id !=', 1);
        if (is_numeric($company_id)) {
            $this->db->where('company_id', $company_id);
        }
        if (is_numeric($status)) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * gets a name of role of specific user
     * @param int $id
     * return string
     */
    public function get_role($id) {
        $this->db->select("name");
        $this->db->where('id', $id);
        $query = $this->db->get('role');
        $role = $query->row();
        return $role->name;
    }

    /**
     * gets a name of company of specific user
     * @param int $id
     * return string
     */
    public function get_company_name_by_id($id) {
        $this->db->select("name");
        $this->db->where('id', $id);
        $query = $this->db->get('company');
        $company = $query->row();
        return $company->name;
    }

    /**
     * gets a id of company of specific user
     * @param int $id
     * return string
     */
    public function get_company_id_by_user_id($id) {
        $this->db->select("company_id");
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        $company = $query->row();
        return $company->company_id;
    }

    /**
     * update record of survey
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of survey
     * @param int id
     * return object
     */
    function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * gets the super admin of particular company
     * @param int $company_id
     * return int
     */
    public function get_super_admin_company($company_id) {
        $this->db->select("id");
        $this->db->where('company_id', $company_id);
        $this->db->where('role_id', 2);
        $query = $this->db->get('user');
        $result = $query->row();
        return !empty($result) ? $result->id : "";
    }

    /**
     * gets the user detail from token
     * @param string $token
     * return object
     */
    public function get_user_token($token) {
        $this->db->select("u.role_id, u.id as id, u.token, u.salutation, u.first_name, u.last_name, u.email, c.name as company");
        $this->db->where('token', $token);
        $this->db->join('company c', 'u.company_id = c.id', 'left');
        $query = $this->db->get('user u');
        return $query->row();
    }

    /**
     * checks if token is valid
     * @param string token
     * return boolean
     */
    public function is_valid_token($token) {
        $this->db->select('token');
        $this->db->from('user');
        $this->db->where('token', $token);
        $this->db->where('status', 0);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * gets a record of specific user
     * @param int id
     * return object
     */
    public function get($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * sets the session variables
     * @param object token
     * return void
     */
    public function set_session_variables($user) {
        $this->session->set_userdata(SESS_UNAME, $user->email);
        $this->session->set_userdata(SESS_UID, $user->id);
        $this->session->set_userdata(SESS_ROLE_ID, $user->role_id);
    }

    public function get_pushtokens($user_id) {

        $this->db->select('token,device_type');
        $this->db->from('pushtoken');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();

        $result = $query->result();
        //var_dump($result);die;
        return $result;
        /*$tokens = array();
        
        if(!empty($result)) {
            foreach($result as $token) {
                $tokens [] = $token->token;
             }
         }
         return $tokens;*/
    }

}