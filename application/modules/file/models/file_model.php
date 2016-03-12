<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the file related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Feb 06, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class File_model extends CI_Model {

    /**
     * insert record of question file
     * @param associative array $data     
     * return integer
     */
    public function save_question_file($data) {
        $this->db->insert('question_file', $data);
        return $this->db->insert_id();
    }

    /**
     * insert record of answer file
     * @param associative array $data     
     * return integer
     */
    public function save_answer_file($data) {
        $this->db->insert('answer_file', $data);
        return $this->db->insert_id();
    }

    /**
     * insert record of survey file
     * @param associative array $data     
     * return integer
     */
    public function save_survey_file($data) {
        $this->db->insert('survey_file', $data);
        return $this->db->insert_id();
    }

    /**
     * delete record of answer file
     * @param int $file_id
     * return object
     */
    function delete_answer_file($file_id) {
        $this->db->where('id', $file_id);
        return $this->db->delete('answer_file');
    }

    /**
     * delete record of survey file
     * @param int $file_id
     * return object
     */
    function delete_survey_file($file_id) {
        $this->db->where('id', $file_id);
        return $this->db->delete('survey_file');
    }

    /**
     * delete record of question file
     * @param int $file_id
     * return object
     */
    function delete_question_file($file_id) {
        $this->db->where('id', $file_id);
        return $this->db->delete('question_file');
    }

    /**
     * get question files
     * @param int $question_id
     * return array
     */
    public function get_resources_question($question_id) {
        $this->db->where('question_id', $question_id);
        $query = $this->db->get('question_file');
        $result = $query->result();
        return $result;
    }

    /**
     * get answer files
     * @param int $question_id
     * @param int $answer_id
     * return object
     */
    public function get_resources_answer($question_id, $answer_id) {
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        $query = $this->db->get('answer_file');
        $result = $query->row();
        return $result;
    }

    /**
     * get answer resource
     * @param int $question_id
     * @param int $answer_id
     * return object
     */
    public function get_resourceid_answer($question_id, $answer_id) {
        $this->db->where('question_id', $question_id);
        $this->db->where('answer_id', $answer_id);
        $query = $this->db->get('answer_file');
        $result = $query->row();
        return $result;
    }

    /**
     * get all answer resources of particular question
     * @param int $question_id
     * return array
     */
    public function get_resources_answerall($question_id) {
        $this->db->where('question_id', $question_id);
        $query = $this->db->get('answer_file');
        $result = $query->result();
        return $result;
    }

    /**
     * get survey files
     * @param int $survey_id
     * return array
     */
    public function get_resources_survey($survey_id) {
        $this->db->where('questionnaire_id', $survey_id);
        $query = $this->db->get('survey_file');
        $result = $query->result();
        return $result;
    }

    /**
     * insert record of temporary file
     * @param associative array $data     
     * return integer
     */
    public function save_temporary_file($data) {
        $this->db->insert('temporary_file', $data);
        return $this->db->insert_id();
    }

    /**
     * delete record of temporary file
     * @param int $file_id
     * return object
     */
    function delete_temporary_file($file_id) {
        $this->db->where('id', $file_id);
        return $this->db->delete('temporary_file');
    }

    /**
     * update record of temporary file
     * @param int id
     * @param associative array $data     
     * return object
     */
    public function update_temporary_file($file_name, $data) {
        $this->db->where('name', $file_name);
        return $this->db->update('temporary_file', $data);
    }

    /**
     * get single question file
     * @param int $file_id
     * return array
     */
    public function get_question_resource($file_id) {
        $this->db->where('id', $file_id);
        $query = $this->db->get('question_file');
        $result = $query->row();
        return $result;
    }

    /**
     * get single answer file
     * @param int $file_id
     * return object
     */
    public function get_ans_resource($file_id) {
        $this->db->where('id', $file_id);
        $query = $this->db->get('answer_file');
        $result = $query->row();
        return $result;
    }

    /**
     * get single survey file
     * @param int $file_id
     * return object
     */
    public function get_survey_resource($file_id) {
        $this->db->where('id', $file_id);
        $query = $this->db->get('survey_file');
        $result = $query->row();
        return $result;
    }

    /**
     * check the duplicate file name and return a new file name
     * @param int $file_name
     * return String
     */
    public function get_duplicate_file($file_name) {
        $survey_file_folder = UPLOADPATH . DS . "survey" . DS . $file_name;
        $question_file_folder = UPLOADPATH . DS . "question" . DS . $file_name;
        $answer_file_folder = UPLOADPATH . DS . "answer" . DS . $file_name;
        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
        $path_parts = pathinfo($survey_file_folder);
        $only_file_name = $path_parts['filename'];

        if (file_exists($survey_file_folder) || file_exists($question_file_folder) || file_exists($answer_file_folder)) {
            /* $total_matched_files = glob(UPLOADPATH . DS . 'survey/' . $only_file_name . ' ([0-9]).' . $file_ext);
              $total_match_count = count($total_matched_files);
              //foreach ($total_matched_files as $total_matched_file) {
              $file_name = $this->getFilename($total_matched_file, '/', 1);
              $file = $this->getFilename($file_name, '.', 2);
              $file_count = $file = $this->getFilename($file, ' ', 1);
              $str_count = strlen($file_count);
              $file_count = substr($file_count, 1, $str_count - 2);
              //}

              if ($total_match_count > 0) {
              $file_name = $only_file_name . ' (' . $file_count++ . ').' . $file_ext;
              } else {
              $file_name = $only_file_name . ' (1).' . $file_ext;
              } */
            $this->load->helper('randomnumber');
            $rand = get_random_string(4);
            $file_name = $only_file_name . ' (' . $rand . ').' . $file_ext;
        }
        return $file_name;
    }

    /* public function getFilename($string, $delimiter, $length) {
      $exploded_info = explode($delimiter, $string);
      $count = count($exploded_info);
      $final_string = $exploded_info[$count - $length];
      return $final_string;
      } */

    /**
     * This function displays image according to extension
     * @param string $ext
     * return string
     */
    public function load_file_img($ext) {
        $img_base_path = base_url() . 'assets' . DS . 'img' . DS;
        $img_path = $img_base_path . 'docimg.png';
        switch ($ext) {
            case 'pdf':
                $img_path = $img_base_path . 'pdf.png';
                break;
            case 'ppt':
            case 'pptx':
                $img_path = $img_base_path . 'ppt.png';
                break;
            case 'doc':
            case 'docx':
                $img_path = $img_base_path . 'doc.png';
                break;
            case 'txt':
                $img_path = $img_base_path . 'txt.png';
                break;
            case 'xls':
            case 'xlsx':
                $img_path = $img_base_path . 'xlsx.png';
                break;
        }
        return $img_path;
    }

}