<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles the survey related functions.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date Mar 04, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 */
class Menu_Model extends CI_Model {

    /**
     * This function is used for making selected menu active
     * @param string $steps (steps  in array)
     * @param string $stepuri(step info from url)
     * return string 
     */
    public function set_active_menu($steps, $stepuri) {

        $active = "passive";

        if (in_array($stepuri, $steps)) {
            $active = "active";
        }
        return $active;
    }

}