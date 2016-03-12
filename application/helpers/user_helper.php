<?php

/**
 * Helper to generate registration url for normal users
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date August 05, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 * 
 */
if (!function_exists('register_url')) {

    function register_url($user_id) {
        $ci = &get_instance();
        $user = $ci->user_model->get_user($user_id);
        $token = $user->token;
        $register_url = base_url() . "user" . DS . "auth" . DS . "register" . DS . $token;
        return $register_url;
    }

}
?>
