<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the template related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Template_model extends CI_Model {

    private $table_name = 'template';

    /**
     * gets the list of templates
     * @param int $company_id     
     * @param boolen $is_company
     * return array
     */
    public function get_templates($is_company = FALSE, $company_id = NULL) {
        $this->db->where('status', 1);
        $this->db->order_by('id', 'desc');
        if ($is_company) {
            $this->db->where('company_id', $company_id);
        }
        $query = $this->db->get($this->table_name);
        $results = $query->result();
        return $results;
    }

    /**
     * gets a single record of template
     * @param int $id     
     * return object
     */
    public function get_template($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * insert record of template
     * @param associative array $data     
     * return integer
     */
    public function save($data) {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * insert record of template scheme
     * @param associative array $data     
     * return integer
     */
    public function save_scheme($data) {
        $this->db->insert('template_scheme', $data);
        return $this->db->insert_id();
    }

    /**
     * gets a list of template schemes of particular template
     * @param int $tempate_id     
     * return array
     */
    public function get_schemes($tempate_id) {
        $this->db->where('template_id', $tempate_id);
        $query = $this->db->get("template_scheme");
        return $query->result();
    }

    /**
     * gets a single scheme
     * @param int $id     
     * return array
     */
    public function get_scheme($id) {
        $this->db->where('id', $id);
        $query = $this->db->get("template_scheme");
        return $query->row();
    }

    /**
     * update record of template
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * update record of template scheme
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update_scheme($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update("template_scheme", $data);
    }

    /**
     * delete record of template
     * @param int id
     * return object
     */
    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    /**
     * delete record of template scheme
     * @param int id
     * return object
     */
    function delete_scheme($id) {
        $this->db->where('id', $id);
        return $this->db->delete("template_scheme");
    }

    /**
     * gets the details of template of particular questionnaire (survey)
     * @param int $survey_id     
     * return array
     */
    public function get_template_questionnaire($survey_id) {
        $this->db->select("t.id as template_id, t.name as template, t.font_style, t.font_color, t.bg_color, t.bg_image");
        //$this->db->select("*");
        $this->db->from("template t");
        $this->db->join('questionnaire q', 't.id = q.template_id', 'left');
        $this->db->where('q.id', $survey_id);
        $this->db->where('t.status', '1');

        //$this->db->order_by('order', 'desc');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        return $query->result();
    }

    /**
     * gets a template scheme record of particular template
     * @param string $scheme_name
     * @param int $tpl_id     
     * return array
     */
    public function get_scheme_by_name($scheme_name, $tpl_id) {
        $this->db->where('name', $scheme_name);
        $this->db->where('template_id', $tpl_id);
        $query = $this->db->get("template_scheme");
        return $query->row();
    }

    /**
     * checks the existence of template
     * @param int $tpl_id     
     * return object
     */
    public function check_template_exists($tpl_id) {
        $this->db->where('id', $tpl_id);
        $query = $this->db->get($this->table_name);
        $result = $query->row();
        return $result;
    }

    /**
     * checks the valid request of template
     * @param int $tpl_id     
     * return bool
     */
    public function is_valid_tpl_request($tpl_id) {
        $flag = TRUE;
        if (!$tpl_id) {
            $flag = FALSE;
        }
        if (!is_numeric($tpl_id)) {
            $flag = FALSE;
        }
        $tpl_object = $this->template_model->check_template_exists($tpl_id);
        if (!$tpl_object) {
            $flag = FALSE;
        }
        return $flag;
    }

}