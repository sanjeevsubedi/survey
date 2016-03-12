<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('load_js')) {

    function load_js($params) {
        $CI = & get_instance();
        if (!is_array($params)) {
            $jsStack = array();
            $jsStack[0] = $params;
        }
        foreach ($jsStack as $k => $jslib) {
            switch ($jslib) {
                case 'jquery':
                    break;

                case 'colorpicker':
                    $CI->template->add_js('assets/colorpicker/js/colorpicker.js');
                    $CI->template->add_js('assets/colorpicker/js/eye.js');
                    $CI->template->add_js('assets/colorpicker/js/utils.js');
                    $CI->template->add_js('assets/colorpicker/js/layout.js?ver=1.0.2');
                    $CI->template->add_css('assets/colorpicker/css/colorpicker.css');
                    break;

                case 'fancybox':
                    $CI->template->add_js('assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');
                    $CI->template->add_js('assets/fancybox/source/jquery.fancybox.js?v=2.1.4');
                    $CI->template->add_css('assets/fancybox/source/jquery.fancybox.css?v=2.1.4');
                    $CI->template->add_js('$(function() {$("#exisiting_preview").fancybox();$("a.dropdown-options").fancybox({"closeBtn" : true, "wrapCSS": "contigency-select-options" });})', 'embed');
                    break;

                case 'quicksearch':
                    $CI->template->add_js('assets/js/jquery.quicksearch.js');
                    break;

                case 'tablesorter':
                    $CI->template->add_js('assets/js/jquery.tablesorter-2.0.4.js');
                    break;

                case 'highcharts':
                    $CI->template->add_js('assets/js/highcharts.js');
                    break;

                case 'sticky':
                    $CI->template->add_js('assets/js/jquery.stickytableheaders.js');
                    break;

            }
        }
    }

}


/* End of file jscss_helper.php */
/* Location: ./application/helpers/jscss_helper.php */