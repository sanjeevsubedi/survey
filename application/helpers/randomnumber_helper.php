<?php

/**
 * Helper to generate random number
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Dec 12, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!function_exists('get_random_string')) {

    function get_random_string($len) {
        $pwd = md5(microtime());
        $count = 1;
        $randomString = "";
        while ($count <= $len) {
            $startChar = rand(0, strlen($pwd) - 1);

            $tempChar = substr($pwd, $startChar, 1);
            $randomString = $randomString . $tempChar;
            $count++;
        }
        return strtoupper($randomString);
    }

}

if (!function_exists('get_uniqid')) {

    function get_uniqid() {
        //return  uniqid();
        return uniqid(php_uname('n'), true);
    }

}
?>
