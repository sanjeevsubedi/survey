<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
define('SESS_UNAME', 'sess_cms_name');
define('SESS_UID', 'sess_cms_uid');
define('SESS_ROLE_ID', 'sess_cms_rid');
define('DB_ERROR', 'Error occured in Database queries');
define('OK', 200);
define('ERROR', 404);
define('ROOTPATH', dirname(BASEPATH));
define('UPLOADPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'uploads');
define('APP_UPLOADPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'app_uploads');
define('CERT_PATH', ROOTPATH . DIRECTORY_SEPARATOR . 'cert');
define('DS', '/');
define('SITE_EMAIL', 'admin@exporm.com');

define('FONT_FAMILY', serialize(array(
            'Arial',
            'Times Roman',
            'Georgia',
            'Tahoma',
            'Verdana',
            'Trebhuchet',
        )));
define('ACCEPT_FILE', 'pdf|ppt|pptx|mp3|mp4|mpeg|doc|docx|xls|xlsx');


define('DEFAULT_EN_CONTACT_FIELDS', serialize(array('1', '5', '9')));
//define('DEFAULT_EN_CONTACT_FIELDS', serialize(array('1', '5', '9', '13', '17', '21', '25', '29', '33')));
define('DEFAULT_FR_CONTACT_FIELDS', serialize(array('2', '6', '10', '14', '18', '22', '26', '30', '34')));
define('DEFAULT_ES_CONTACT_FIELDS', serialize(array('3', '7', '11', '15', '19', '23', '27', '31', '35')));
define('DEFAULT_DE_CONTACT_FIELDS', serialize(array('4', '8', '12', '16', '20', '24', '28', '32', '36')));

define('SALUTATION', serialize(
                array('Mr', 'Mrs', 'Ms', 'Dr')
        ));




/* End of file constants.php */
/* Location: ./application/config/constants.php */