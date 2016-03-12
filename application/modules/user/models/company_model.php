<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handels the Company related functions
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date March 5, 2013
 * @email bidurbs@gmail.com
 * 
 */
class Company_model extends CI_Model {

    private $table_name = 'company';

    /*     * ************** FIELDS LIST *************** */
    var $fields = array();

    /*     * ************** FIELDS END *************** */

    function __construct() {
        parent::__construct();
    }

    public function get_fields() {
        return $fields;
    }

    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    public function update($company_id, $data) {
        $this->db->where('id', $company_id);
        return $this->db->update($this->table_name, $data);
    }

    function delete($company_id) {
        $this->db->where('id', $company_id);
        $this->db->delete($this->table_name);
        return true;
    }

    public function get($company_id) {
        $this->db->where('id', $company_id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    public function list_companys($offset = false, $limit = false) {
        //$this->db->where('is_deleted', '0');	
        if ($offset != "" || $limit != "")
            $this->db->limit($limit, $offset);
        $query = $this->db->get($this->table_name);
        //echo $this->db->last_query();die;
        return $results = $query->result();
    }

    public function get_by_name($name) {
        $company_id = "";
        $this->db->where('LOWER(name)', strtolower($name));
        $query = $this->db->get($this->table_name);
        $row = $query->row();
        //echo $this->db->last_query();die;
        if (!empty($row))
            $company_id = $row->id;
        return $company_id;
    }

    public function get_autocomplete() {
        $this->db->select('id, name');
        //$this->db->like('name',$this->input->post('queryString'));
        $query = $this->db->get($this->table_name);
        return $results = $query->result();
    }

    /**
     * gets the details of company of particular questionnaire (survey)
     * @param int $survey_id     
     * return array
     */
    public function get_company_questionnaire($survey_id) {
        $this->db->select("c.id as company_id, c.name as company, c.address_1, c.address_2, c.telephone");
        //$this->db->select("*");
        $this->db->from("company c");
        $this->db->join('questionnaire q', 'c.id = q.company_id', 'left');
        $this->db->where('q.id', $survey_id);
        $this->db->where('c.status', '1');

        //$this->db->order_by('order', 'desc');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    /**
     * checks if company is already registered
     *  @param string $company     
     * return boolean
     */
    public function is_company_registered($company) {
        $this->db->select('name');
        $this->db->from('company');
        $this->db->where('name', $company);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}