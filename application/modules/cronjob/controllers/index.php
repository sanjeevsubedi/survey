<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cronjob/cronjob_model');
    }

    /**
     * This function manages cleaning of unused files/images
     */
    public function clean_temp_files() {
        $unused_files = $this->cronjob_model->get_temp_files();
        if (!empty($unused_files)) {
            foreach ($unused_files as $unused_file) {
                if ($unused_file->type == 'logo') {
                    @unlink(UPLOADPATH . DS . 'logo' . DS . $unused_file->name);
                } else {
                    @unlink(UPLOADPATH . DS . 'template' . DS . $unused_file->name);
                }
                $this->cronjob_model->delete($unused_file->id);
            }
        }
    }

}

