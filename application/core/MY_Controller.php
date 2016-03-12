<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This is Base controller, all the controllers should extend this
 * controller. 
 *
 * @author Sanjeev Subedi
 * @version 1.0
 * @date March 1, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class MY_Controller extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user/user_model');
        $this->load->model('menu/menu_model');
        $this->load->model('survey/survey_model');

        //check if the user is not authenticated        
        if ($this->user_model->check_session() == FALSE) {
            redirect(base_url() . 'user/auth/login');
        }

        //preparing necessary template variables
        $survey_id = $this->uri->segment(3);
        $data['survey_id'] = $survey_id;
        $data['menusteps'] = $this->uri->segment(2);
        $module = $this->uri->segment(1);

        $data['translation_set_id'] = "";
        $data['is_original_survey'] = FALSE;

        $survey_det = $this->survey_model->get_survey($survey_id);
        $scan_only = $survey_det->scan_only ? TRUE : FALSE;
        $data['scan_only'] = $scan_only;

        $deny = array('all', 'edit');

        if (!in_array($this->uri->segment(2), $deny)) {
            if (!in_array($module, array('template', 'device','permission','cfield','salesperson','statistic'))) {
                if (isset($survey_id) && !empty($survey_id) && is_numeric($survey_id)) {
                    $survey_detail = $this->survey_model->get_survey($survey_id);
                    //prepare data to find the original and translated block
                    $data['translation_set_id'] = $survey_detail->translation_set_id;
                    $data['is_original_survey'] = $this->survey_model->get_original_survey($survey_id, $survey_id);
                }
                //prepare the navigation block
                $this->template->write_view('menu', 'menu/navigation', $data);
            }
        }

        $this->clear_cache();
    }

    function clear_cache() {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

}
