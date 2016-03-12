<?php

/**
 * Helper to parse json data
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Mar 27, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!function_exists('get_contact_form_field')) {

    function get_contact_form_field($survey_id) {
        $ci = &get_instance();
        $contactdetails = array();
        $contacts = $ci->contact_model->get_contact_fields_for_survey($survey_id);
        foreach ($contacts as $row) {
            $contactdetails[$row->name] = $row->id;
        }
        return $contactdetails;
    }

}
?>
