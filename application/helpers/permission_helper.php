<?php

/**
 * Helper to check if the current user role has sufficient permission
 * 
 * @author Sushil Gupta
 * @version 1.0
 * @date June 28, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('permission_helper')) {

    function permission_helper($survey_id = NULL) {

        $ci = &get_instance();
        $is_allowed = $ci->permission_model->check_privelege($survey_id);

        if (!is_null($survey_id)) {
            $completed_survey = $ci->survey_model->is_completed_survey($survey_id);

            if ($completed_survey) {
                $ci->session->set_flashdata('error', 'Completed survey cannot be edited');
                redirect(base_url() . 'survey/all/');
            }
        }
        if (!$is_allowed) {
            $ci->session->set_flashdata('error', 'Permission denied');
            redirect(base_url() . 'survey/all');
        }
    }

}