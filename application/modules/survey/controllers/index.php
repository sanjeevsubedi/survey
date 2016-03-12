<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('survey/survey_model');
        $this->load->model('device/device_model');
        $this->load->model('language/language_model');
        $this->load->model('template/template_model');
        $this->load->model('contact/contact_model');
        $this->load->model('question/question_model');
        $this->load->model('user/user_model');
        $this->load->model('file/file_model');
        $this->load->model('answer/answer_model');
        $this->load->helper('permission');
        $this->load->model('permission/permission_model');
        $this->load->library('form_validation');
    }

    /**
     * This function displays the survey setup form (step 1)
     */
    public function step_1() {
        load_js('fancybox');
        load_js('colorpicker');
        $survey_id = $this->uri->segment(3);
        //check the necessary permission
        if (!$survey_id) {
            permission_helper();
        }
        $user_id = $this->session->userdata(SESS_UID);
        $company_id = $this->user_model->get_company_id_by_user_id($user_id);
        $file_name = "";
        $new_upload = FALSE;
        $user_details = $this->user_model->get($user_id);
        $role = $this->user_model->get_role($user_details->role_id);

        $data['devices'] = $this->device_model->get_devices();
        //$data['languages'] = $this->language_model->get_languages();
        //system templates created by super admin
        $tpls = $this->template_model->get_templates(TRUE, 0);

        $final_tps = array();
        $collection_schemes = array();
        foreach ($tpls as $tpl) {
            $schemes = $this->template_model->get_schemes($tpl->id);
            foreach ($schemes as $scheme) {
                $collection_schemes [] = $scheme;
            }
            $tpl->schemes = $collection_schemes;
            $collection_schemes = array();
            $final_tps [] = $tpl;
        }

        $data['template_schemes'] = $final_tps;
        $data['templates'] = $this->template_model->get_templates(TRUE, $company_id);

        $data['survey'] = '';
        $data['survey_devices'] = '';
        $data['eid'] = $survey_id;
        //sets the accordion flag to true
        $this->template->write('accordion_disable_flag', TRUE);
        if ($survey_id) {
            //this step verifies the valid survey and prevents from url tampering
            if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
                redirect(base_url() . 'survey/all');
            }

            $survey = $this->survey_model->get_survey($survey_id);
            //check the necessary permission
            permission_helper($survey_id);

            $file_name = $survey->logo;
            $data['survey'] = $survey;
            $user_id = $survey->user_id;
            $company_id = $this->user_model->get_company_id_by_user_id($user_id);
            $device_objs = $this->device_model->get_devices_questionnaire($survey_id);
            $device_ids = array();
            foreach ($device_objs as $device_obj) {
                $device_ids [] = $device_obj->id;
            }
            $data['survey_devices'] = $device_ids;

            //sets the accordion flag to false
            $this->template->write('accordion_disable_flag', FALSE, $overwrite = TRUE);
        }

        $data['company_id'] = $this->user_model->get_company_id_by_user_id($this->session->userdata(SESS_UID));
        if ($_POST) {
            $data = array();
            $this->form_validation->set_rules('survey', 'Survey', 'required');
            $this->form_validation->set_rules('device', 'Device', 'required');
            //$this->form_validation->set_rules('language', 'Language', 'required');
            //$this->form_validation->set_rules('template', 'Template', 'required');

            if ($this->form_validation->run() == FALSE) {

                $this->template->write_view('content', 'step_1', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('survey');
                $devices = $this->input->post('device');
                //$language = $this->input->post('language');

                $type = $this->input->post('template_type');
                $scan_only = $this->input->post('scan_only');

                $ipad_template = '';
                $galaxy_template = '';
                $mobile_template = '';
                $language = '';
                $template = '';
                if ($type == 'own-existing') {
                    $template = $this->input->post('template');
                    $template_owner = 'o'; // owner
                    $logo = ''; //logo in on the same template bg on the existing template
                } elseif ($type == 'system') {
                    $template = $this->input->post('tpl'); // this is the scheme id not the template id
                    $logo = $this->input->post('logo');
                    $template_owner = 's'; // system
                } else {
                    $template_name = $this->input->post('new_template');
                    $template_owner = 'o'; // owner

                    if (!empty($template_name)) {

                        //mobile device stuffs
                        $mobile_theme_color = $this->input->post('bg_color_mobile');
                        $mobile_logo = $this->input->post('mobile_logo');

                        $logo = ''; //new template doesn't have image as image is attached with the template
                        $font_style = $this->input->post('font_style');
                        $font_color = $this->input->post('font_color');
                        $ipad_template = $this->input->post('ipad_img');
                        $galaxy_template = $this->input->post('galaxy_img');
                        $mobile_template = $this->input->post('mobile_img');


                        //survey title and question title settings
                        $survey_title_color = $this->input->post('survey_title_color');
                        $survey_font_size = $this->input->post('survey_font_size');
                        $question_font_size = $this->input->post('question_font_size');
                        $question_title_color = $this->input->post('question_title_color');
                        $option_font_size = $this->input->post('option_font_size');
                        $option_title_color = $this->input->post('option_title_color');

                        $option_settings = serialize(array('color' => $option_title_color, 'size' => $option_font_size));
                        $survey_title_settings = serialize(array('color' => $survey_title_color, 'size' => $survey_font_size));
                        $question_title_settings = serialize(array('color' => $question_title_color, 'size' => $question_font_size));


                        //finding company id from user id.
                        $user_id = $this->session->userdata('sess_cms_uid');
                        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

                        $template_data = array(
                            'name' => $template_name,
                            'font_style' => $font_style,
                            'font_color' => $font_color,
                            'bg_color' => $mobile_theme_color,
                            'mobile_logo' => $mobile_logo,
                            'bg_image' => $file_name,
                            'status' => 1,
                            'company_id' => $company_id,
                            'bg_image_galaxy' => $galaxy_template,
                            'bg_image' => $ipad_template,
                            'bg_image_mobile' => $mobile_template,
                            'survey_title_settings' => $survey_title_settings,
                            'question_title_settings' => $question_title_settings,
                            'option_settings' => $option_settings,
                        );
                        $template = $this->template_model->save($template_data);

                        //update the temporary file table indicating the image file is referenced 
                        // so the image is not deleted on he cron job task schedule
                        if ($ipad_template) {
                            $this->file_model->update_temporary_file($ipad_template, array('status' => 1));
                        }
                        if ($galaxy_template) {
                            $this->file_model->update_temporary_file($galaxy_template, array('status' => 1));
                        }
                        if ($mobile_template) {
                            $this->file_model->update_temporary_file($mobile_template, array('status' => 1));
                        }
                    } else {
                        //user can upload the template later
                        $template_owner = ''; // nobody is owner
                        $template = 0;  // no id is referenced
                        //delete the previous logo
                        if ($survey_id) {
                            @unlink(UPLOADPATH . DS . 'logo' . DS . $survey->logo);
                            $data['logo'] = '';
                        }
                    }
                }

                //upload logo
                if (isset($logo) && !empty($logo)) {
                    $new_upload = TRUE;
                }

                $data['name'] = $name;
                $data['language_id'] = $language;
                $data['company_id'] = $company_id;
                $data['template_id'] = $template;
                $data['template_owner'] = $template_owner;
                $data['status'] = 1;
                $data['data_collection_method_id'] = '';
                $data['translation_set_id'] = 0;
                $data['user_id'] = $user_id;
                $data['created_date'] = time();
                $data['scan_only'] = $scan_only;

                //delete past data of devices and update the survey
                if ($survey_id) {
                    //delete the previous logo image
                    if ($new_upload) {
                        @unlink(UPLOADPATH . DS . 'logo' . DS . $survey->logo);

                        //update the temporary file table indicating the image file is referenced 
                        // so the image is not deleted on he cron job task schedule
                        $this->file_model->update_temporary_file($logo, array('status' => 1));
                        $data['logo'] = $logo;
                    }

                    //delete the logo when sytem template replaced with own template. Own template has logo within template
                    if ($type == 'own-existing') {
                        @unlink(UPLOADPATH . DS . 'logo' . DS . $survey->logo);
                        $data['logo'] = '';
                    }
                    //preserve past language and data collection method
                    $data['language_id'] = $survey->language_id;
                    $data['data_collection_method_id'] = $survey->data_collection_method_id;
                    $this->device_model->delete_devices_questionnaire($survey_id);
                    $this->survey_model->update($survey_id, $data);
                } else {
                    //update the temporary file table indicating the image file is referenced 
                    // so the image is not deleted on he cron job task schedule
                    if ($type == 'system') {
                        $this->file_model->update_temporary_file($logo, array('status' => 1));
                        $data['logo'] = $logo;
                    }

                    $survey_id = $this->survey_model->save($data);

                    //contact form is mandatory
                    $existing_contacts = array('1');
                    $data_contact = array(
                        'data_collection_method_id' => serialize($existing_contacts),
                    );
                    $this->survey_model->update($survey_id, $data_contact);

                    //make the entry of the owner as questionnaire admin in the permission table
                    $data = array('permission_id' => 3, 'questionnaire_id' => $survey_id,
                        'user_id' => $user_id);
                    $this->permission_model->save_priveledge($data);
                }
                foreach ($devices as $device) {
                    $this->device_model->save_device_questionnaire(array(
                        'device_id' => $device, 'questionnaire_id' => $survey_id)
                    );
                }
                //$this->session->set_flashdata('success', 'Go to Step 2');
                

                if(!$scan_only) {
                    redirect(base_url() . 'survey/step_5/' . $survey_id);
                }else {
                    redirect(base_url() . 'language/survey_lang/' . $survey_id);
                }


            }
        } else {

            $this->template->write_view('content', 'step_1', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays the fifth step of survey
     */
    public function step_5() {
        $survey_id = $this->uri->segment(3);

        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }
        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        //load fancybox
        load_js('fancybox');

        $data['eid'] = $survey_id;
        $data['survey'] = $this->survey_model->get_survey($survey_id);
        $data['question_types'] = $this->question_model->get_question_types();

        $data['translation_set_id'] = $survey_detail->translation_set_id;
        $data['is_original_survey'] = $this->survey_model->get_original_survey($survey_id, $survey_id);
        $questions = $this->question_model->get_quiestions_questionnaire($survey_id);
        $data['has_questions'] = empty($questions) ? FALSE : TRUE;
        if ($_POST) {
            $required_fields = $this->input->post('required');
            //delete previous data
            $this->contact_model->update_required_fields(1, array('is_required' => 0));
            if ($required_fields) {
                foreach ($required_fields as $required_field) {
                    $data = array(
                        'is_required' => 1,
                    );
                    $this->contact_model->update_contact($required_field, $data);
                }
            }
            $this->session->set_flashdata('success', 'Go to Step 5');
            redirect(base_url() . 'survey/step_5/' . $survey_id);
        } else {
            $this->template->write_view('content', 'question/type', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the surveys
     */
    public function all() {
        load_js('fancybox');
        $this->load->model('statistic/statistic_model');
        $this->load->model('permission/permission_model');
        $this->load->model('bcard/bcard_model');
        $role_id = $this->session->userdata(SESS_ROLE_ID);
        $user_id = $this->session->userdata(SESS_UID);
        $user_role = $this->user_model->get_role($role_id);

        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        if ($user_role == 'super-admin') {
            $active_surveys = $this->survey_model->get_surveys(1, TRUE);
        } else if ($user_role == 'company-super-admin') {
            $active_surveys = $this->survey_model->get_surveys_cadmin_user($user_id, $company_id, 1);
        } else if ($user_role == 'normal-company-user') {
            //$active_surveys = $this->survey_model->get_surveys(1, FALSE, FALSE, $user_id);
            $active_surveys = $this->survey_model->get_surveys_normal_user($user_id, TRUE, 1);
        }

        $final_active_surveys = array();
        foreach ($active_surveys as $survey) {
            $survey->download = $this->statistic_model->get_download_statistic($survey->id);
            $survey->question = $this->statistic_model->get_total_question($survey->id);
            $survey->questionnnaires = $this->statistic_model->get_total_questionnaires($survey->id);
            $survey->created_date = date('d.M Y ', $survey->created_date);
            $survey->processed_bcards = $this->bcard_model->get_bcards_processed($survey->id);
            $survey->uploaded_bcards = $this->bcard_model->get_bcards_uploaded($survey->id);
            $survey->company_id = $survey->company_id;
            $final_active_surveys [] = $survey;
        }

        if ($user_role == 'super-admin') {
            $completed_surveys = $this->survey_model->get_surveys(2, TRUE);
        } else if ($user_role == 'company-super-admin') {
            $completed_surveys = $this->survey_model->get_surveys_cadmin_user($user_id, $company_id, 2);
        } else if ($user_role == 'normal-company-user') {
            $completed_surveys = $this->survey_model->get_surveys_normal_user($user_id, TRUE, 2);
        }

        $final_completed_surveys = array();
        foreach ($completed_surveys as $c_survey) {
            $c_survey->download = $this->statistic_model->get_download_statistic($c_survey->id);
            $c_survey->question = $this->statistic_model->get_total_question($c_survey->id);
            $c_survey->questionnnaires = $this->statistic_model->get_total_questionnaires($c_survey->id);
            $c_survey->created_date = date('d.M Y ', $c_survey->created_date);
            $final_completed_surveys [] = $c_survey;
        }

        $data['active_surveys'] = $final_active_surveys;
        $data['completed_surveys'] = $final_completed_surveys;

        //popup is shown when the user logins for the first time
        $data['first_access'] = FALSE;
        $user_details = $this->user_model->get_user($user_id);
        if ($user_details->visit == 0) {
            $u_data['visit'] = 1;
            $this->user_model->update($user_id, $u_data);
            $data['first_access'] = TRUE;
        }

        $data['role'] = $user_role;
        $data['user_company_id'] = $company_id;
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function manage the survey
     */
    public function manage() {
        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'survey/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        // deletes all the used resources
                        $this->delete_survey_resources($id);
                        $this->survey_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->survey_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->survey_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'survey updated successfully');
            redirect(base_url() . 'survey/all');
        }
    }

    /**
     * This function deletes all the files related to particular survey
     */
    public function delete_survey_resources($survey_id) {

        $survey = $this->survey_model->get_survey_clone($survey_id);
        //removes images from the logo folder
        @unlink(UPLOADPATH . DS . 'logo' . DS . $survey->logo);

        //deletes all the files of questions
        $question_results = $this->question_model->get_question_clone($survey_id);
        foreach ($question_results as $question_result) {
            $file_results = $this->file_model->get_resources_question($question_result->id);
            foreach ($file_results as $file_result) {
                @unlink(UPLOADPATH . DS . 'question' . DS . $file_result->name);
            }

            //deletes all the answer files
            $file_results = $this->file_model->get_resources_answerall($question_result->id);
            foreach ($file_results as $file_result) {
                @unlink(UPLOADPATH . DS . 'answer' . DS . $file_result->name);
            }
        }

        //deletes all the survey files
        $file_results = $this->file_model->get_resources_survey($survey_id);
        foreach ($file_results as $file_result) {
            @unlink(UPLOADPATH . DS . 'survey' . DS . $file_result->name);
        }
    }

    /**
     * This function deletes all the responses related to particular survey
     */
    public function delete_survey_responses($survey_id) {

        //delete all the business card associated with a survey
        $this->load->model('surveyreport/survey_response_model');
        $file_results = $this->survey_response_model->get_surveyresponse_details($survey_id);

        foreach ($file_results as $file_result) {
            $orignal_card = $file_result->original_image;
            $new_card_image = $file_result->card_img;
            @unlink(APP_UPLOADPATH . DS . 'original' . DS . $orignal_card);
            @unlink(APP_UPLOADPATH . DS . $new_card_image);
        }
        $this->survey_response_model->delete_surveyreport_survey($survey_id);
    }

    /**
     * This function clones the whole survey
     */
    public function copy() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //check the necessary permission
        permission_helper($survey_id);
        $this->survey_model->clone_whole_survey($survey_id);

        /* $this->load->helper('randomnumber');
          //inserts into questionnaire table
          $survey_results = $this->survey_model->get_survey_clone($survey_id);
          $data_survey = get_object_vars($survey_results);
          unset($data_survey['id']);

          //copy the logo image
          $file_ext = substr($survey_results->logo, strrpos($survey_results->logo, '.') + 1);
          $new_file = get_random_string(16) . "." . $file_ext;
          @copy(UPLOADPATH . DS . 'logo' . DS . $survey_results->logo, UPLOADPATH . DS . 'logo' . DS . $new_file);
          $data_survey['logo'] = $new_file;
          $data_survey['created_date'] = time();
          $new_survey_id = $this->survey_model->save($data_survey);


          //inserts into contact_form_field_questionnaire table
          $contact_field_results = $this->contact_model->get_contact_field_questionnaire_clone($survey_id);
          foreach ($contact_field_results as $contact_field_result) {
          $data_contact_field = get_object_vars($contact_field_result);
          unset($data_contact_field['id']);
          $data_contact_field['questionnaire_id'] = $new_survey_id;
          $this->contact_model->save_contact($data_contact_field);
          }

          //inserts into device_questionnaire table
          $device_results = $this->device_model->get_device_questionnaire_clone($survey_id);
          foreach ($device_results as $device_result) {
          $data_device = get_object_vars($device_result);
          unset($data_device['id']);
          $data_device['questionnaire_id'] = $new_survey_id;
          $this->device_model->save_device_questionnaire($data_device);
          }

          //inserts into question table
          $question_results = $this->question_model->get_question_clone($survey_id);
          foreach ($question_results as $question_result) {

          $data_question = get_object_vars($question_result);
          unset($data_question['id']);
          $data_question['questionnaire_id'] = $new_survey_id;
          $new_question_id = $this->question_model->save($data_question);

          //inserts into question file table
          $file_results = $this->file_model->get_resources_question($question_result->id);
          foreach ($file_results as $file_result) {
          $data_file = get_object_vars($file_result);
          $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
          unset($data_file['id']);
          //$new_file = get_random_string(16) . "." . $file_ext;
          $new_file = $this->file_model->get_duplicate_file($file_result->name);
          $data_file['question_id'] = $new_question_id;
          $data_file['name'] = $new_file;
          @copy(UPLOADPATH . DS . 'question' . DS . $file_result->name, UPLOADPATH . DS . 'question' . DS . $new_file);
          $this->file_model->save_question_file($data_file);
          }

          //inserts answer of particular survey
          $answer_results = $this->answer_model->get_answers($question_result->id);
          $data_answer = get_object_vars($answer_results);
          unset($data_answer['id']);
          $data_answer['question_id'] = $new_question_id;
          $this->answer_model->save($data_answer);

          //inserts into answer file table
          $file_results = $this->file_model->get_resources_answerall($question_result->id);
          foreach ($file_results as $file_result) {
          $data_file = get_object_vars($file_result);
          $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
          unset($data_file['id']);
          //$new_file = get_random_string(16) . "." . $file_ext;
          $new_file = $this->file_model->get_duplicate_file($file_result->name);
          $data_file['question_id'] = $new_question_id;
          $data_file['name'] = $new_file;
          $data_file['answer_id'] = $file_result->answer_id;
          @copy(UPLOADPATH . DS . 'answer' . DS . $file_result->name, UPLOADPATH . DS . 'answer' . DS . $new_file);
          $this->file_model->save_answer_file($data_file);
          }

          //inserts subanswer of particular questions of particular survey
          $subanswer_results = $this->answer_model->get_subanswer_clone($question_result->id);
          foreach ($subanswer_results as $subanswer_result) {
          $data_subanswer = get_object_vars($subanswer_result);
          unset($data_subanswer['id']);
          $data_subanswer['question_id'] = $new_question_id;
          $this->answer_model->save_subanswer($data_subanswer);
          }
          }

          //inserts into survey file table
          $file_results = $this->file_model->get_resources_survey($survey_id);
          foreach ($file_results as $file_result) {
          $data_file = get_object_vars($file_result);
          $file_ext = substr($file_result->name, strrpos($file_result->name, '.') + 1);
          unset($data_file['id']);
          //$new_file = get_random_string(16) . "." . $file_ext;
          $new_file = $this->file_model->get_duplicate_file($file_result->name);
          $data_file['questionnaire_id'] = $new_survey_id;
          $data_file['name'] = $new_file;
          @copy(UPLOADPATH . DS . 'survey' . DS . $file_result->name, UPLOADPATH . DS . 'survey' . DS . $new_file);
          $this->file_model->save_survey_file($data_file);
          } */

        $this->session->set_flashdata('success', 'survey copied successfully');
        redirect(base_url() . 'survey/all');
    }

    /**
     * This function clones the concluded survey
     */
    public function copy_concluded_survey() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //check the necessary permission
        $cloned_survey_id = $this->survey_model->clone_whole_survey($survey_id);


        //change the status
        $this->survey_model->update($cloned_survey_id, array('status' => 1));

        $this->session->set_flashdata('success', 'survey copied successfully');
        redirect(base_url() . 'survey/all');
    }

    /**
     * This function deletes the individual survey
     */
    public function delete() {

        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey and prevents url tampering
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        // deletes all the used resources
        $this->delete_survey_responses($survey_id);
        $this->delete_survey_resources($survey_id);

        $this->survey_model->delete($survey_id);
        $this->session->set_flashdata('success', 'survey deleted successfully');
        redirect(base_url() . 'survey/all');
    }

    /**
     * This function displays interface of choosing contact fields via ajax request
     */
    public function ajax_choosen_contact_field() {
        $survey_id = $this->uri->segment(3);

        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //gets the required contact form fields
        $required_field_objs = $this->contact_model->get_requried_contact_field($survey_id);
        $required_field_ids = array();
        if (!empty($required_field_objs)) {
            foreach ($required_field_objs as $required_field_obj) {
                $required_field_ids [] = $required_field_obj->contact_form_field_id;
            }
        }

        //gets the visible contact form fields
        $visible_field_objs = $this->contact_model->get_visible_contact_field($survey_id);
        //var_dump($visible_field_objs);die;
        $visible_field_ids = array();
        if (!empty($visible_field_objs)) {
            foreach ($visible_field_objs as $visible_field_obj) {
                $visible_field_ids [] = $visible_field_obj->contact_form_field_id;
            }
        }

        $contacts = $this->contact_model->get_contact_field_questionnaire($survey_id);
        //$data['contacts'] = $this->contact_model->get_contact_field_questionnaire($survey_id);
        $contact_row_data = array();
        $temp_row = array();
        foreach ($contacts as $contact) {
            if (!in_array($contact->row, $temp_row)) {
                $row_data = $this->contact_model->get_row_fields($contact->questionnaire_id, $contact->row);
            } else {
                $row_data = array();
            }
            $temp_row [] = $contact->row;
            $contact_row_data [] = $row_data;
        }

        $empty_data = array();

        foreach ($contact_row_data as $k => $data) {
            if (empty($data)) {
                $empty_data [] = $data;
                unset($contact_row_data[$k]);
            }
        }

        $data['contacts'] = array_merge($contact_row_data, $empty_data);
        $data['required_form_fields'] = $required_field_ids;
        $data['visible_form_fields'] = $visible_field_ids;
        $data['eid'] = $survey_id;

        $this->load->view('sel_contact_field_list', $data);
    }

// remove it - bharat
    public function survey_overview() {
        $this->load->helper('xml');
        $xml = xml_parser('test.xml');
        $fields = $xml->aitem->fields;
        $company = (String) $fields->company;

        $contact_form_field_id = ""; //should be fixed in the xml file and we need to give them
        $survey_user_id = ""; //need to get from xml
        $survey_id = ""; //need to get from xml
        $value = ""; //from xml
        $insert_data = array('contact_form_field_id' => $contact_form_field_id, 'questionnaire_id' => $survey_id, 'survey_user_id' => $survey_user_id, 'value' => $value);
        //$this->survey_model->save($insert_data, 'survey_contact_detail');

        $this->template->write_view('content', 'overview');
        $this->template->render();
    }

    /**
     * This function concludes the survey
     */
    public function conclude() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        $current_role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID));

        if ($current_role == "company-super-admin" || $current_role == "super-admin") {
            $this->survey_model->update($survey_id, array('status' => 2));
            $this->session->set_flashdata('success', 'survey concluded successfully');
            redirect(base_url() . 'survey/all');
        } else {
            $this->session->set_flashdata('error', 'Permission denied');
            redirect(base_url() . 'survey/all');
        }
    }

    /**
     * This function shows the contact collection types
     */
    function contacts() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }
        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        //questions are mandatory for survey
        $survey_questions = $this->question_model->get_quiestions_questionnaire($survey_id);
        if (empty($survey_questions) && !$survey_detail->scan_only) {
            $this->session->set_flashdata('error', 'At least one question is required');
            redirect(base_url() . 'survey/step_5/' . $survey_id);
        }

        //check whether user has selected a language or not
        if ($survey_detail->language_id == 0) {
            $this->session->set_flashdata('error', 'Please select at least one language');
            redirect(base_url() . 'language/survey_lang/' . $survey_id);
        }

        $data['eid'] = $survey_id;
        $data['survey'] = $this->survey_model->get_survey($survey_id);
        $data['badge'] = $this->contact_model->get_badgescan($survey_id);

        $contacts = unserialize($survey_detail->data_collection_method_id);
        $contacts_final = $contacts ? $contacts : array();
        $data['contact_collections'] = $contacts_final;
        $data['contact_types'] = $this->contact_model->get_contact_types();
        $this->template->write_view('content', 'contact_types', $data);
        $this->template->render();
    }

    public function upload_backup() {
        $this->load->model('surveyreport/survey_response_model');
        $status = $this->survey_response_model->backup();
        if ($status == 2) {
            $this->session->set_flashdata('error', 'Remote host not found');
            redirect(base_url() . 'survey/all');
        } else if ($status) {
            $this->session->set_flashdata('success', 'Backup to remote server taken successfully');
            redirect(base_url() . 'survey/all');
        } else {
            redirect(base_url() . 'survey/all');
        }
    }

}
