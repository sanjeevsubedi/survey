<?php

/**
 * Helper to parse xml
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date June 25, 2013
 * @copyright Copyright (c) 2013, neolinx.com.np
 * 
 */
if (!function_exists('parse_xml')) {

    function xml_parser($file) {
        $xml = simplexml_load_file($file);
        return $xml;
    }

}
?>
