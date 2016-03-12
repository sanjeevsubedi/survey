<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  |--------------------------------------------------------------------------
  | Active template
  |--------------------------------------------------------------------------
  |
  | The $template['active_template'] setting lets you choose which template
  | group to make active.  By default there is only one group (the
  | "default" group).
  |
 */
$template['active_template'] = 'default';

/*
  |--------------------------------------------------------------------------
  | Explaination of template group variables
  |--------------------------------------------------------------------------
  |
  | ['template'] The filename of your master template file in the Views folder.
  |   Typically this file will contain a full XHTML skeleton that outputs your
  |   full template or region per region. Include the file extension if other
  |   than ".php"
  | ['regions'] Places within the template where your content may land.
  |   You may also include default markup, wrappers and attributes here
  |   (though not recommended). Region keys must be translatable into variables
  |   (no spaces or dashes, etc)
  | ['parser'] The parser class/library to use for the parse_view() method
  |   NOTE: See http://codeigniter.com/forums/viewthread/60050/P0/ for a good
  |   Smarty Parser that works perfectly with Template
  | ['parse_template'] FALSE (default) to treat master template as a View. TRUE
  |   to user parser (see above) on the master template
  |
  | Region information can be extended by setting the following variables:
  | ['content'] Must be an array! Use to set default region content
  | ['name'] A string to identify the region beyond what it is defined by its key.
  | ['wrapper'] An HTML element to wrap the region contents in. (We
  |   recommend doing this in your template file.)
  | ['attributes'] Multidimensional array defining HTML attributes of the
  |   wrapper. (We recommend doing this in your template file.)
  |
  | Example:
  | $template['default']['regions'] = array(
  |    'header' => array(
  |       'content' => array('<h1>Welcome</h1>','<p>Hello World</p>'),
  |       'name' => 'Page Header',
  |       'wrapper' => '<div>',
  |       'attributes' => array('id' => 'header', 'class' => 'clearfix')
  |    )
  | );
  |
 */

/*
  |--------------------------------------------------------------------------
  | Template Configurations
  |--------------------------------------------------------------------------
 */


//Default
$template['default']['template'] = 'layout/default.php';
$template['default']['regions'] = array(
    'menu',
    'header',
    'content',
    'sidebar',
    'footer',
    'accordion_disable_flag',
);

$template['default']['parser'] = 'parser';
$template['default']['parser_method'] = 'parse';
$template['default']['parse_template'] = FALSE;

/* $template['default']['regions'] = array(
  'menusteps' => array(
  'content' => array('<h1>Welcome</h1>','<p>Hello World</p>'),
  'name' => 'Page Header',
  'wrapper' => '<div>',
  'attributes' => array('id' => 'header', 'class' => 'clearfix')
  )
  ); */


//login layout
$template['login']['template'] = 'layout/login-layout.php';
$template['login']['regions'] = array(
    'menu',
    'header',
    'content',
    'sidebar',
    'footer',
    'accordion_disable_flag',
);

$template['login']['parser'] = 'parser';
$template['login']['parser_method'] = 'parse';
$template['login']['parse_template'] = FALSE;

//public layout
$template['public']['template'] = 'layout/public-layout.php';
$template['public']['regions'] = array(
    'menu',
    'header',
    'content',
    'footer',
);

$template['public']['parser'] = 'parser';
$template['public']['parser_method'] = 'parse';
$template['public']['parse_template'] = FALSE;

//blank layout
$template['blank']['template'] = 'layout/blank-layout.php';
$template['blank']['regions'] = array(
    'content'
);

$template['blank']['parser'] = 'parser';
$template['blank']['parser_method'] = 'parse';
$template['blank']['parse_template'] = FALSE;






/* End of file template.php */
/* Location: ./system/application/config/template.php */