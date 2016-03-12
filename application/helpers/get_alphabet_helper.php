<?php

/**
 * Helper to return albhabets
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Mar 27, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */

if ( ! function_exists('get_alphabet'))
{
    function get_alphabet($i) {
        $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
        return $alphabet[$i];
    }
}
?>
