<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */

$route['default_controller'] = "user/auth/login";
$route['device/(:any)'] = "device/index/$1";
$route['salesperson/(:any)'] = "salesperson/index/$1";
$route['permission/(:any)'] = "permission/index/$1";
$route['template/(:any)'] = "template/index/$1";
$route['language/(:any)'] = "language/index/$1";
$route['survey/(:any)'] = "survey/index/$1";
$route['contact/(:any)'] = "contact/index/$1";
$route['cfield/(:any)'] = "contact/index/$1";
$route['question/(:any)'] = "question/index/$1";
$route['preview/(:any)'] = "preview/index/$1";
$route['member/(:any)'] = "user/index/$1";
$route['file/(:any)'] = "file/index/$1";
$route['surveyreport/(:num)'] = "surveyreport/index/$1";
$route['bcard/(:any)'] = "bcard/index/$1";
$route['cronjob/(:any)'] = "cronjob/index/$1";
$route['statistic/(:any)'] = "statistic/index/$1";

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */