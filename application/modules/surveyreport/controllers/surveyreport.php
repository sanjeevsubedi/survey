<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Surveyreport extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('surveyreport/survey_response_model');
        $this->load->model('permission/permission_model');
        $this->load->helper(array('generatereport', 'get_alphabet'));

        $this->template->set_template('blank');
    }

    /**
     * This function displays the survey setup form (step 1)
     */
    public function index() {

        $survey_id = $this->uri->segment(2);

        //this step verifies the valid survey and prevents from url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        $data['contact_details_heading'] = $this->survey_response_model->get_survey_contact_details($survey_id, TRUE);
        $data['questions_heading'] = $this->survey_response_model->get_survey_questions_details($survey_id, TRUE,NULL,TRUE);

        $survey_details = $this->survey_model->get_survey($survey_id);
        $owner = $survey_details->user_id;
        $current_logged_user = $this->session->userdata(SESS_UID);
        $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));
        $company_super_admin = $this->user_model->get_super_admin_company($survey_details->company_id);


        $user_perm = $this->permission_model->get_survey_privelege($survey_id, $current_logged_user);
        $user_perm = $user_perm[0];
        $user_det = $this->user_model->get_user($current_logged_user);
        

        //$backoffice user can view the stats
        $backOfficeUser = FALSE;
        $data['backOfficeUser'] = FALSE;
        if($user_perm->permission_id == 1 && $user_det->company_id == $survey_details->company_id) {

            $backOfficeUser = TRUE;
            $data['backOfficeUser'] = TRUE;
        }
        

        //super admin, company super admin and the owner of the survey can see all the uploaded data
        if ($owner == $current_logged_user || $current_role == "super-admin" || $current_logged_user == $company_super_admin || $backOfficeUser) {
           
            $data['survey_users'] = $this->survey_response_model->get_survey_users($survey_id);
            $data['leadcount'] = $this->survey_response_model->get_lead_count($survey_id);
        } else {
            $data['survey_users'] = $this->survey_response_model->get_survey_users($survey_id, $current_logged_user);
            $data['leadcount'] = $this->survey_response_model->get_lead_count($survey_id, $current_logged_user);
        }

        $data['survey_id'] = $survey_id;
        $data['survey'] = $survey_details;
        $data['cur_user'] = $current_logged_user;

        //Generate XLS
        require_once(APPPATH . "libraries/PHPExcel-1.7.7/Classes/PHPExcel.php");
        require_once(APPPATH . 'libraries/PHPExcel-1.7.7/Classes/PHPExcel/IOFactory.php');
        $xls = new PHPExcel();
        $xls->getProperties()->setCreator("Cisco")
                ->setTitle("surveyreport-" . $data['survey_id'] . '-' . $data['cur_user'])
                ->setSubject("surveyreport-" . $data['survey_id'] . '-' . $data['cur_user']);

        //Select the first worksheet
        $sheet = $xls->setActiveSheetIndex(0);
        $data['sheet'] = $sheet;
        $data['xls'] = $xls;
        $this->template->write_view('content', 'index', $data);
        $this->template->render();
    }

    public function generatexls($survey_id = null, $user_id) {

        if ($survey_id) {
            $filename = "surveyreport-" . $survey_id . '-' . $user_id . ".xls";
            //header("Content-type: application/octet-stream");
            //header("Content-Disposition: attachment; filename=" . $filename);
            if (file_exists(ROOTPATH . "/xls/" . $filename)) {
                redirect(base_url() . "/xls/" . $filename);
                //readfile("xls/" . $filename);
            } else {
                redirect('surveyreport');
            }
        } else {
            redirect('surveyreport');
        }
    }

    /**
     * This function edit the survey report data of particular user
     */
    public function edit() {
        $survey_id = $this->uri->segment(3);
        $survey_user_id = $this->uri->segment(4);

        //this step verifies the valid survey and prevents from url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //this step verifies the valid survey user and prevents from url tampering
        if (!$this->survey_response_model->is_valid_surveyuser_reqest($survey_user_id)) {
            redirect(base_url() . 'survey/all');
        }
        $this->load->library('encrypt');
        $contact_fields = $this->survey_response_model->get_contact_details_report($survey_user_id, $survey_id);
        $cf_array_coll = array();
        foreach ($contact_fields as $cf) {
            $cf->value = $this->encrypt->decode($cf->value);
            $cf_array_coll [] = $cf;
        }
        $data['contact_fields'] = $cf_array_coll;
        $data['user_id'] = $survey_user_id;
        $data['survey_id'] = $survey_id;
        $other_details = $this->survey_response_model->get_survey_other_details($survey_id, $survey_user_id);
        $data['bcard'] = $other_details->card_image;
        $next_response = $this->survey_response_model->get_next_survey_response($survey_id, $survey_user_id);
        $data['next_response'] = $next_response;



        if ($_POST) {



            $contact_field_ids = $this->input->post('cids');

            foreach ($contact_field_ids as $contact_field_id => $value) {
                $data = array('value' => $this->encrypt->encode($value));
                $this->survey_response_model->update_contact_detail($survey_user_id, $contact_field_id, $data);
            }

            if ($next_response) {
                $survey_user_id = $next_response->survey_user_id;
            }

            $this->session->set_flashdata('success', 'contact field edited successfully');
            redirect(base_url() . 'surveyreport/edit/' . $survey_id . '/' . $survey_user_id);
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function delete the survey report data of particular user
     */
    public function delete() {
        $survey_id = $this->uri->segment(3);
        $survey_user_id = $this->uri->segment(4);

        //this step verifies the valid survey and prevents from url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //this step verifies the valid survey user and prevents from url tampering
        if (!$this->survey_response_model->is_valid_surveyuser_reqest($survey_user_id)) {
            redirect(base_url() . 'survey/all');
        }

        $this->deleteResponses($survey_id, $survey_user_id);

        $this->session->set_flashdata('success', 'survey data deleted successfully');
        redirect(base_url() . 'surveyreport/' . $survey_id);
    }

    public function multidelete() {

        $responses = $this->input->post("child");

        $survey_id = $this->input->post("sid");

        foreach ($responses as $survey_user_id) {
            $this->deleteResponses($survey_id, $survey_user_id);
        }
        $this->session->set_flashdata('success', 'survey data deleted successfully');
        redirect(base_url() . 'surveyreport/' . $survey_id);
    }

    function deleteResponses($survey_id, $survey_user_id) {
        //delete the business card associated with a survey user
        $survey_user_info = $this->survey_response_model->get_survey_user_details($survey_id, $survey_user_id);
        $orignal_card = $survey_user_info->original_image;
        $new_card_image = $survey_user_info->card_img;
        @unlink(APP_UPLOADPATH . DS . 'original' . DS . $orignal_card);
        @unlink(APP_UPLOADPATH . DS . $new_card_image);
        $this->survey_response_model->delete_surveyreport($survey_user_id);
    }

    function zip($survey_id = null) {

        if ($survey_id) {
            $survey_details = $this->survey_model->get_survey($survey_id);
            $this->load->library('zip');
            $folder = APP_UPLOADPATH . DS;
            //$this->zip->read_dir($folder, false);

            $handle = opendir($folder);
            while (false !== $f = readdir($handle)) {
                if ($f != '.' && $f != '..') {
                    $filePath = "$folder/$f";
                    if (is_file($filePath)) {

                        $cards = $this->get_business_cards($survey_id);

                        if (in_array($f, $cards)) {
                            $this->zip->read_file($filePath, false);
                        }
                    }
                }
            }

            $current_logged_user = $this->session->userdata(SESS_UID);
            $filename = "surveyreport-" . $survey_id . '-' . $current_logged_user . ".xls";
            //xls file
            if (file_exists(ROOTPATH . "/xls/" . $filename)) {
                $filePath = ROOTPATH . "/xls/" . $filename;
                $this->zip->read_file($filePath, false);
            }

            $this->zip->download('package_' . $survey_details->name . '_' . $survey_details->id . '.zip');
        } else {
            redirect(base_url() . 'surveyreport/' . $survey_id);
        }
    }

    function get_business_cards($survey_id) {
        $cards = array();
        $survey_details = $this->survey_model->get_survey($survey_id);
        $owner = $survey_details->user_id;
        $current_logged_user = $this->session->userdata(SESS_UID);
        $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));
        $company_super_admin = $this->user_model->get_super_admin_company($survey_details->company_id);

        //super admin, company super admin and the owner of the survey can see all the uploaded data
        if ($owner == $current_logged_user || $current_role == "super-admin" || $current_logged_user == $company_super_admin) {
            $survey_users = $this->survey_response_model->get_survey_users($survey_id);
        } else {
            $survey_users = $this->survey_response_model->get_survey_users($survey_id, $current_logged_user);
        }

        if (!empty($survey_users)) {
            foreach ($survey_users as $survey_user) {
                $cards [] = $survey_user->card_img;
            }
        }
        return $cards;
    }

    public function send_message() {
        $survey_id = $this->uri->segment(3);
        $this->load->model('survey/survey_model');
        $this->load->model('permission/permission_model');
        $this->load->model('user/user_model');
             //this step verifies the valid survey and prevents from url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //var_dump($survey_id);die;
        $data = array();
        $survey_user_ids = $this->permission_model->get_authorized_users($survey_id);

        $users= array();
        //$loggedInusers = array();
        $user_tokens = array();

        $individual_tokens = array();

        if(!empty($survey_user_ids)) {
            foreach($survey_user_ids as $survey_user_id) {

                $tokens = $this->user_model->get_pushtokens($survey_user_id);
                if(!empty($tokens)) {

                    $user_info = $this->user_model->get_user($survey_user_id);
                    //$loggedInusers [] = $this->user_model->get_user($survey_user_id);

                    $individual_tokens [] = array("user"=>$user_info, "devices"=>$tokens, "count"=>count($tokens));

                    foreach($tokens as $token) {
                        $user_tokens [] = $token;                        
                    }
                }

                $users [] = $this->user_model->get_user($survey_user_id);
            }
        }
        

        $data['individual_tokens'] = $individual_tokens;
        //$data['loggedInusers'] = $loggedInusers;
        $data['survey_users'] = $users;
        $data['tokens'] = json_encode($user_tokens);

        $this->template->write_view('content', 'broadcast', $data);
        $this->template->render();
    }

}
