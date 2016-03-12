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

if ( ! function_exists('json_parser'))
{
    function json_parser($filepath) {
        $survey= array();
        if(file_exists($filepath)){
            $data = file_get_contents($filepath); //read the file
            //decode json
            $survey = json_decode($data);
        }
        return $survey;
    }
}
?>
