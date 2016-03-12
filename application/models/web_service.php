<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This Model class handles web services.
 * 
 * @author Sanjeev Subedi
 * @version 1.0
 * @date feb 06, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 */
class Web_Service extends CI_Model {

    var $status = OK;

    function __construct() {
        parent::__construct();
        $this->load->model('device/device_model');
        $this->load->model('user/user_model');
        $this->load->model('survey/survey_model');
        $this->load->model('language/language_model');
        $this->load->model('contact/contact_model');
        $this->load->model('file/file_model');
        $this->load->model('permission/permission_model');
    }

    /**
     * list all the devices
     * return array
     * 
     */
    public function get_devices() {
        $result = $this->device_model->get_devices();

        if (is_string($result) && $result == DB_ERROR) {
            $this->status = ERROR;
            $result = NULL;
        }
        $response = array(
            'device' => $result,
            'status' => $this->status,
        );
        return $response;
    }

    public function login($username, $password) {
        $result = $this->user_model->validate_user_app($username, $password);
        if ($result) {
            $company_id = $this->user_model->get_company_id_by_user_id($result->id);
            $company = $this->user_model->get_company_name_by_id($company_id);
            $response = array(
                'valid_user' => TRUE,
                'email' => $result->email,
                'user_id' => $result->id,
                'company' => $company,
            );
        } else {
            $response = array(
                'valid_user' => FALSE,
            );
        }
        return $response;
    }

    public function forgot($email) {
        $result = $this->user_model->is_already_registered($email);

        $change_password = FALSE;
        $email_count = $this->user_model->check_user_email($email);
        if (count($email_count) > 0) {
            $data['first_name'] = $email_count->first_name;
            $change_password = TRUE;
        }
        if ($change_password === TRUE) {
            $this->load->helper('randomnumber');
            $this->load->helper('sendmail');
            /* Generating random Password */
            $rand_password = get_random_string(6);
            $data['password'] = $rand_password;
            $data['email'] = $email;
            $pass_data = array(
                'password' => md5($rand_password)
            );

            $result = $this->user_model->update_password($pass_data, $email);

            if ($result) {
                /* Prepareation for sending mail. */
                $subject = "Forget password";
                //creating mail body
                $message = $this->load->view('layout/sendmail_forget_password', $data, true);

                if (_sendmail($email, $subject, $message, "ExpoRM", SITE_EMAIL)) {
                    $response = array(
                        'valid_user' => TRUE,
                        'sent' => TRUE
                    );
                } else {
                    $response = array(
                        'valid_user' => TRUE,
                        'sent' => FALSE
                    );
                }
            } else {
                $response = array(
                    'valid_user' => TRUE,
                    'sent' => FALSE
                );
            }
        } else {
            $response = array(
                'valid_user' => FALSE,
                'sent' => FALSE
            );
        }

        return $response;
    }

       /**
     * function to insert push tokens
     * return 
     * 
     */

   public function insert_pushtoken($user_id,$token, $device) {

        $data = array('user_id'=>$user_id, 'token'=>$token,'device_type'=>$device, 'date'=>time());
        $result = $this->user_model->save_pushtoken($data);
        return $result;
   }


    /**
     * function to return survey related all paramanters
     * return xml
     * 
     */
    public function get_xml($survey_id, $type, $enc) {

        if (!$this->survey_model->is_valid_survey_reqest($survey_id) || !is_numeric($survey_id)) {
            $this->service_error_xml('No survey found');
        }

        $this->load->model('contact/contact_model');
        $this->load->model('question/question_model');
        $this->load->model('answer/answer_model');
        $this->load->model('template/template_model');
        $this->load->model('language/language_model');
        $this->load->model('user/company_model');
        $this->load->model('file/file_model');
        $this->load->model('logic/logic_model');

        //survey details
        $survey = $this->survey_model->get_survey($survey_id);


        //create xml
        $xml = new SimpleXMLElement("<survey/>");
        $xml->addAttribute('id', $survey_id);
        //childrens
        $title = $xml->addChild("title", $survey->name);
        $scan_only = $xml->addChild("scan_only", $survey->scan_only);
        $location = $xml->addChild("location", "");
        $salesmen = $xml->addChild("salesmen", "");
        $survey_common_resources = $xml->addChild("survey_resources", "");
        $badgescan = $xml->addChild("badgescan", "");
        
        /*contact collections*/
        $contactcoll = $xml->addChild("contactcoll", "");
        $bcard = $contactcoll->addChild("bcard", "");
        $bscan = $contactcoll->addChild("bscan", "");
        $lookup = $contactcoll->addChild("lookup", "");
        $lookupsearch = $lookup->addChild("search", "");
        $lookupdwnld = $lookup->addChild("download", "");
        //$lookupsearch->addChild("value", 1);
        //$lookupdwnld->addChild("value", 1);
        //prepare salesmen block
        $salesmen_results = $this->survey_model->get_salesperson_questionnaire($survey_id);
        foreach ($salesmen_results as $key => $result) {
            $salesman = $salesmen->addChild("salesman");
            $salesman->addAttribute('id', ++$key);
            $salesman->addChild("name", $result->name);
        }

        //prepare badgescan block
        $badgescan_results = $this->contact_model->get_badgescan($survey_id);
        if ($badgescan_results) {

            //language mapping with event monitor system (key=cms lang id, value = eventmonitor lang id)
            //$language_mapping = array(8 => 1, 5 => 3);

            $lang = $badgescan_results->language_id;
            if ($badgescan_results->language_id == 8) {
                $lang = 1;
            }
            if ($badgescan_results->language_id == 5) {
                $lang = 3;
            }

            $badgescan->addAttribute('id', $badgescan_results->id);
            $badgescan->addChild("server", $badgescan_results->server);
            //$badgescan->addChild("username", base64_encode($badgescan_results->username));
            //$badgescan->addChild("password", base64_encode($badgescan_results->password));

            $badgescan->addChild("username", $enc == "y" ? base64_encode($badgescan_results->username) : $badgescan_results->username);
            $badgescan->addChild("password", $enc == "y" ? base64_encode($badgescan_results->password) : $badgescan_results->password);


            $badgescan->addChild("idsection", $badgescan_results->idsection);
            $badgescan->addChild("idquestionsearch", $badgescan_results->idquestionsearch);
            $badgescan->addChild("idquestionoutput", $badgescan_results->idquestionoutput);
            $badgescan->addChild("language_id", $lang/* $badgescan_results->language_id */);
        }
        //prepare data collection block
        $data_collections = unserialize($survey->data_collection_method_id);
        if ($data_collections) {
            if (in_array(2, $data_collections)) { // checks whether is used by survey or not
                $xml->addChild("business_card", 1);
                $bcard->addChild("value",1);
            } else {
                $xml->addChild("business_card", 0);
                $bcard->addChild("value",0);
            }

            if (in_array(3, $data_collections)) { // checks whether badgescan is used by survey or not
                $badgescan->addChild("value", 1);
                $bscan->addChild("value",1);
            } else {
                $badgescan->addChild("value", 0);
                $bscan->addChild("value",0);
            }
        }

        $lookupsearch->addChild("value", $badgescan_results->lookup_search);
        $lookupdwnld->addChild("value", $badgescan_results->db_download);

        $contacts = $xml->addChild("contacts");
        $templates = $xml->addChild("templates");
        $languages = $xml->addChild("languages");
        $companys = $xml->addChild("companys");
        $devices = $xml->addChild("devices");
        $questions = $xml->addChild("questions");

        //prepare contacts block
        $contacts_results = $this->contact_model->get_contact_field_questionnaire($survey_id);

        foreach ($contacts_results as $key => $result) {
            $contact = $contacts->addChild("contact_field");
            $contact->addAttribute('id', $result->contact_form_field_id);
            $contact->addChild("name", $result->name);
            $contact->addChild("identifier", $result->identifier);
            $contact->addChild("type", $result->type);
            $contact->addChild("is_required", $result->is_required);
            $contact->addChild("is_visible", $result->is_visible);
            $contact->addChild("validation_rule", $result->validation_rule);
            $order = $contact->addChild("layout");
            $order->addChild("row", $result->row);
            $order->addChild("column", $result->order);

            if ($result->options != "") {
                $options = $contact->addChild("options");
                $options_val = $result->options;
                $options_arr = explode("\n", $options_val);
                //print_r($options_arr);
                foreach ($options_arr as $key => $value) {
                    $option = $options->addChild("option", trim($value));
                    $option->addAttribute('id', ++$key);
                }
            }
        }

        //prepare template block
        /* $template_results = $this->template_model->get_template_questionnaire($survey_id);
          foreach ($template_results as $key => $result) {
          $template = $templates->addChild("template-field");
          $template->addAttribute('id', ++$key);
          $template->addChild("name", $result->template);
          $template->addChild("font-family", $result->font_style);
          $template->addChild("font-color", '#' . $result->font_color);
          //$template->addChild("bg-color", $result->bg_color);
          $template->addChild("bg-image", DS . "uploads" . DS . "template" . DS . $result->bg_image);
          $logo_path = !empty($survey->logo) ? DS . "uploads" . DS . "logo" . DS . $survey->logo : "";
          $template->addChild("logo-image", $logo_path);
          } */

        if ($survey->template_owner == 's') {
            $scheme_details = $this->template_model->get_scheme($survey->template_id);
            $template_details = $this->template_model->get_template($scheme_details->template_id);
            $template = $templates->addChild("template_field");
            $template->addChild("name", $template_details->name);
            $template->addChild("font_family", $template_details->font_style);
            $template->addChild("font_color", '#' . $template_details->font_color);
            $template->addChild("bg_image", DS . "uploads" . DS . "template" . DS . $scheme_details->bg_image_ipad);
            $template->addChild("bg_color", "#64dbd5");
            $template->addChild("bg_color_hover", "#B3B3B3");
            //$template->addChild("type", $type);
            $logo_path = !empty($survey->logo) ? DS . "uploads" . DS . "logo" . DS . $survey->logo : "";
            $template->addChild("logo_image", $logo_path);
        } else {
            $template_details = $this->template_model->get_template($survey->template_id);
            $template = $templates->addChild("template_field");
            $template->addChild("name", $template_details->name);
            $template->addChild("font_family", $template_details->font_style);
            $template->addChild("font_color", '#' . $template_details->font_color);
            $template->addChild("bg_image", DS . "uploads" . DS . "template" . DS . $template_details->bg_image);

            $template->addChild("bg_color", $template_details->bg_color ? '#' . $template_details->bg_color : "no");
            $template->addChild("bg_color_hover", "#B3B3B3");

            $logo_path_mobile = !empty($template_details->mobile_logo) ? DS . "uploads" . DS . "logo" . DS . $template_details->mobile_logo : "";
            $template->addChild("logo_image_mobile", $logo_path_mobile);

            $template->addChild("type", $type);
            $logo_path = DS . "default_images" . DS . 'holder.png';
            $template->addChild("logo_image", $logo_path); // logo in attached in the template. only png placeholder is sent
        }
        $stitle_settings = unserialize($template_details->survey_title_settings);
        $survey_title_settings = $template->addChild("survey_title_settings");
        $survey_title_settings->addChild("color", '#' . $stitle_settings['color']);
        $survey_title_settings->addChild("size", $stitle_settings['size'] . 'px');

        $qtitle_settings = unserialize($template_details->question_title_settings);
        $question_title_settings = $template->addChild("question_title_settings");
        $question_title_settings->addChild("color", '#' . $qtitle_settings['color']);
        $question_title_settings->addChild("size", $qtitle_settings['size'] . 'px');


        $o_settings = unserialize($template_details->option_settings);
        $option_settings = $template->addChild("option_settings");
        $option_settings->addChild("color", '#' . $o_settings['color']);
        $option_settings->addChild("size", $o_settings['size'] . 'px');


        //prepare language block
        $language_results = $this->language_model->get_language_questionnaire($survey_id);
        foreach ($language_results as $key => $result) {
            $language = $languages->addChild("language_field");
            $language->addAttribute('id', ++$key);
            $language->addChild("name", $result->language);
            $language->addChild("short_name", $result->short_name);
        }

        //prepare company block
        $company_results = $this->company_model->get_company_questionnaire($survey_id);
        foreach ($company_results as $key => $result) {
            $company = $companys->addChild("company_field");
            $company->addAttribute('id', ++$key);
            $company->addChild("name", $result->company);
            $company->addChild("address_1", $result->address_1);
            $company->addChild("address_2", $result->address_2);
            $company->addChild("telephone", $result->telephone);
        }

        //prepare device block
        $device_results = $this->device_model->get_devices_questionnaire($survey_id);
        foreach ($device_results as $key => $result) {
            $device = $devices->addChild("device");
            //$device->addAttribute('id', ++$key);
            $device->addChild("id", $result->id);
            $device->addChild("name", $result->name);
        }

        //prepare questions block
        $questions_results = $this->question_model->get_quiestions_questionnaire($survey_id);
        foreach ($questions_results as $key => $result) {
            $question = $questions->addChild("question");
            $question->addAttribute("id", $result->question_id);
            if ($result->question_type == "singleSelect") {
                if ($result->data_entry_type == "selectbox") {
                    $question->addChild("type", "dropDown");
                } else {
                    $question->addChild("type", $result->question_type);
                }
            } elseif ($result->question_type == "pullDown") {
                $question->addChild("type", "dropDown");
            } else {
                $question->addChild("type", $result->question_type);
            }
            $question->addChild("text", $result->question);
            $question->addChild("is_compulsory", $result->is_compulsory);
            $question->addChild("order", $result->order);

            if ($result->data_entry_type == "none") {
                $scale_type = $this->get_scale_answers($question, $result);
                $input_type = $question->addChild("input_type", $scale_type);
            } else {
                if ($result->question_type == "contingency") {
                    if ($result->data_entry_type == "textbox") {
                        $input_type = $question->addChild("input_type", "text");
                    } else if ($result->data_entry_type == "radiobutton") {
                        $input_type = $question->addChild("input_type", "radio");
                    } else {
                        $question->addChild("input_type", $result->data_entry_type);
                    }
                } else {

                    $input_type = $question->addChild("input_type", $result->data_entry_type);
                }
            }

            //$input_type->addAttribute("id", $result->data_entry_type_id);
            //get answers if present
            $this->get_answers($question, $result);

            //prepare question logic
            $this->get_logics($question, $result);

            //prepare question's resource
            $question_resources = $this->file_model->get_resources_question($result->question_id);
            if (!empty($question_resources)) {
                $resources = $question->addChild("resources");
                foreach ($question_resources as $key => $value) {
                    $resource = $resources->addChild("resource");
                    $resource->addAttribute('id', $value->id);
                    $resource->addChild("name", $value->name);
                    $resource->addChild("title", preg_replace('/\.[^$]*/', '', $value->name));
                    $file_ext = substr($value->name, strrpos($value->name, '.') + 1);
                    $resource->addChild("type", $file_ext);
                    $resource->addChild("mime", $this->get_mime_type($file_ext));
                    $folder = "question";
                    $resource_path = !empty($value->name) ? DS . "uploads" . DS . $folder . DS . $value->name : "";
                    $resource->addChild("path", $resource_path);
                }
            }
        }

        //prepare common survey resources(docs)
        $survey_files = $this->file_model->get_resources_survey($survey_id);
        if (!empty($survey_files)) {
            foreach ($survey_files as $survey_file) {
                $res = $survey_common_resources->addChild("resource");
                $res->addAttribute('id', $survey_file->id);
                $res->addChild("name", $survey_file->name);
                $res->addChild("title", preg_replace('/\.[^$]*/', '', $survey_file->name));
                $file_ext = substr($survey_file->name, strrpos($survey_file->name, '.') + 1);
                $res->addChild("type", $file_ext);
                $res->addChild("mime", $this->get_mime_type($file_ext));
                $folder = "survey";
                $resource_path = !empty($survey_file->name) ? DS . "uploads" . DS . $folder . DS . $survey_file->name : "";
                $res->addChild("path", $resource_path);
            }
        }

        //ob_clean();
        $ob_get_status = ob_get_status();
        if (isset($ob_get_status['name']) && $ob_get_status['name'] != 'zlib output compression') {
            ob_clean();
        }
        Header('Content-type: application/xml');
        echo $xml->asXML();
//        echo "<pre>";
//        print_r($questions_results);die;
    }

    /**
     * Private function to return answers block of a question
     * 
     * @param object $question 
     * @param object $result 
     * return object
     */
    private function get_answers($question, $result) {
        $answers = $this->answer_model->get_answers($result->question_id);
        if (!empty($answers)) {
            $options = $question->addChild("options");
            $ans_options = @unserialize($answers->name);
            switch ($result->question_type_id) {
                case 1:
                case 3 :
                    $is_other = !empty($ans_options['is_other']) ? $ans_options['is_other'] : "";
                    $ans_options = $ans_options['options'];

                    foreach ($ans_options as $key => $value) {
                        $option_main = $options->addChild("option");
                        $option_main->addAttribute('id', $key);

                        if (!empty($is_other) && !empty($is_other[$key])) {
                            $option_main->addAttribute('type', 'other');
                        }
                        $option_main->addChild("text", htmlspecialchars($value));

                        //answer resources
                        $ans_resources = $option_main->addChild("resources");
                        $ans_resource_details = $this->file_model->get_resources_answer($result->question_id, $key);
                        if (!empty($ans_resource_details)) {
                            $ans_resource = $ans_resources->addChild("resource");
                            $ans_resource->addAttribute('id', $ans_resource_details->id);
                            $ans_resource->addChild("name", $ans_resource_details->name);
                            $ans_resource->addChild("title", preg_replace('/\.[^$]*/', '', $ans_resource_details->name));
                            $file_ext = substr($ans_resource_details->name, strrpos($ans_resource_details->name, '.') + 1);
                            $ans_resource->addChild("type", $file_ext);
                            $ans_resource->addChild("mime", $this->get_mime_type($file_ext));
                            $folder = "answer";
                            $ans_resource_path = !empty($ans_resource_details->name) ? DS . "uploads" . DS . $folder . DS . $ans_resource_details->name : "";
                            $ans_resource->addChild("path", $ans_resource_path);
                        }

                        $suboptions = $option_main->addChild("suboptions");

                        $suboptions_type = $this->answer_model->get_subanswer_dataentry_type($result->question_id, $key);

                        if (!empty($suboptions_type->data_entry_type_name)) {

                            $suboptiontype = $suboptions_type->data_entry_type_name == "radiobutton" ? "radio" : $suboptions_type->data_entry_type_name;
                            $suboptions->addAttribute('type', $suboptiontype);
                        }

                        $sub_answers = $this->answer_model->get_all_subanswer($result->question_id, $key);
                        foreach ($sub_answers as $key => $sub_answer) {
                            $suboption = $suboptions->addChild("suboption", htmlspecialchars($sub_answer->name));
                            //$suboption->addAttribute('id', $sub_answer->id);
                            $suboption->addAttribute('id', $sub_answer->order); //sanjeev
                        }
                    }
                    break;
                case 2:
                    $range_options = @$ans_options['range'];
                    $interval_options = @$ans_options['interval'];
                    if ($range_options) {
                        $scale = "range";
                        $ans_options = $range_options;
                    } else if ($interval_options) {
                        $scale = "interval";
                        $ans_options = $interval_options;
                    }
                    $ans_options = explode(",", $ans_options);
                    //$options->addChild("scale-type", $scale);
                    foreach ($ans_options as $key => $value) {
                        $option = $options->addChild("option", htmlspecialchars($value));
                        $option->addAttribute('id', $key);
                    }
                    break;
                case 4:
                    $h_answers = $ans_options['h_answer'];
                    $v_answers = $ans_options['v_answer'];
                    $is_other = !empty($ans_options['is_other']) ? $ans_options['is_other'] : "";
                    $options->addAttribute('type', "horizontal");
                    foreach ($h_answers as $key => $value) {
                        $option = $options->addChild("option", htmlspecialchars($value));
                        $option->addAttribute('id', $key);
                    }
                    $options = $question->addChild("options");
                    $options->addAttribute('type', "vertical");
                    foreach ($v_answers as $key => $value) {
                        $option = $options->addChild("option", htmlspecialchars($value));
                        $option->addAttribute('id', $key);

                        if (!empty($is_other) && !empty($is_other[$key])) {
                            $option->addAttribute('type', 'other');
                        }
                    }

                    //answer resources of each pair of contigency table
                    $contigency_resources = $question->addChild("option_resources");
                    $contigency_resources_details = $this->file_model->get_resources_answerall($result->question_id);
                    if (!empty($contigency_resources_details)) {
                        foreach ($contigency_resources_details as $k => $contigency_resources_detail) {
                            $contigency_resource = $contigency_resources->addChild("option_resource");
                            $contigency_resource->addAttribute('id', $contigency_resources_detail->id);
                            $contigency_resource->addChild("name", $contigency_resources_detail->name);
                            $pair = explode('_', $contigency_resources_detail->answer_id);
                            $contigency_resource->addChild("row", $pair[0]);
                            $contigency_resource->addChild("column", $pair[1]);
                            $contigency_resource->addChild("pair", $contigency_resources_detail->answer_id);
                            $contigency_resource->addChild("title", preg_replace('/\.[^$]*/', '', $contigency_resources_detail->name));
                            $file_ext = substr($contigency_resources_detail->name, strrpos($contigency_resources_detail->name, '.') + 1);
                            $contigency_resource->addChild("type", $file_ext);
                            $contigency_resource->addChild("mime", $this->get_mime_type($file_ext));
                            $folder = "answer";
                            $contigency_resource_path = !empty($contigency_resources_detail->name) ? DS . "uploads" . DS . $folder . DS . $contigency_resources_detail->name : "";
                            $contigency_resource->addChild("path", $contigency_resource_path);
                        }
                    }

                    //shown only if the dataentry type is selectbox
                    if (isset($ans_options['select_options']) && !empty($ans_options['select_options'])) {
                        $select_options = $ans_options['select_options'];
                        $select_parent = $question->addChild("select_options");

                        foreach ($select_options as $selk => $select_option) {
                            $select_child = $select_parent->addChild("select_option");
                            $row = substr($selk, 0, 1);
                            $col = substr($selk, 1, 1);
                            $select_child->addChild("row", $row);
                            $select_child->addChild("column", $col);

                            $option_lists = explode("\n", $select_option);

                            $optionlists = $select_child->addChild("option_list");

                            if (!empty($option_lists)) {
                                foreach ($option_lists as $olk => $option_list) {
                                    $opt = $optionlists->addChild('option', $option_list);
                                    $opt->addAttribute('id', $olk);
                                }
                            }
                        }
                    }

                    break;
                case 7:
                    $is_other = !empty($ans_options['is_other']) ? $ans_options['is_other'] : "";
                    $ans_options = $ans_options['options'];

                    foreach ($ans_options as $key => $value) {
                        $option_main = $options->addChild("option");
                        $option_main->addAttribute('id', $key);

                        if (!empty($is_other) && !empty($is_other[$key])) {
                            $option_main->addAttribute('type', 'other');
                        }
                        $option_main->addChild("text", htmlspecialchars($value));

                        //answer resources
                        $ans_resources = $option_main->addChild("resources");
                        $ans_resource_details = $this->file_model->get_resources_answer($result->question_id, $key);
                        if (!empty($ans_resource_details)) {
                            $ans_resource = $ans_resources->addChild("resource");
                            $ans_resource->addAttribute('id', $ans_resource_details->id);
                            $ans_resource->addChild("name", $ans_resource_details->name);
                            $ans_resource->addChild("title", preg_replace('/\.[^$]*/', '', $ans_resource_details->name));
                            $file_ext = substr($ans_resource_details->name, strrpos($ans_resource_details->name, '.') + 1);
                            $ans_resource->addChild("type", $file_ext);
                            $ans_resource->addChild("mime", $this->get_mime_type($file_ext));
                            $folder = "question";
                            $ans_resource_path = !empty($ans_resource_details->name) ? DS . "uploads" . DS . $folder . DS . $ans_resource_details->name : "";
                            $ans_resource->addChild("path", $ans_resource_path);
                        }
                    }

                    break;
            }
        }
        return $question;
    }

    /**
     * Private function which gives type of scale
     * 
     * @param object $question 
     * @param object $result 
     * return object
     */
    private function get_scale_answers($question, $result) {
        $answers = $this->answer_model->get_answers($result->question_id);
        $scale = "";
        if (!empty($answers)) {
            $options = $question->addChild("options");
            $ans_options = @unserialize($answers->name);
            switch ($result->question_type_id) {
                case 2:
                    $range_options = @$ans_options['range'];
                    $interval_options = @$ans_options['interval'];
                    if ($range_options) {
                        $scale = "range";
                    } else if ($interval_options) {
                        $scale = "interval";
                    }
                    break;
            }
        }
        return $scale;
    }

    /**
     * Private function to return logics block of a question
     * 
     * @param object $question 
     * @param object $result 
     * return object
     */
    private function get_logics($question, $result) {
        $ans_logics = $this->logic_model->get_jump_to_logics($result->question_id);
        if (!empty($ans_logics)) {
            $logics = $question->addChild("logics");
            switch ($result->question_type_id) {
                case 1:
                case 3 :
                    foreach ($ans_logics as $key => $value) {
                        $logic_main = $logics->addChild("logic");
                        $question_row = $this->question_model->get_question_row($value->jump_to);
                        $logic_main->addAttribute('jumpTo', ($question_row->order - 1));
                        $logic_conditions = $this->logic_model->get_conditions($result->question_id, $value->jump_to);
                        if (!empty($logic_conditions)) {
                            $conditions = $logic_main->addChild("conditions");
                            $condition = $conditions->addChild("condition");
                            foreach ($logic_conditions as $key => $value) {
                                //if ($value->group_logic)
                                $condition->addAttribute('groupLogic', $value->group_logic);
                                $condition->addAttribute('operator', $value->operator);
                                $condition->addAttribute('value', $value->value);
                            }
                        }
                    }
                    break;
                case 2:
                    foreach ($ans_logics as $key => $value) {
                        $logic_main = $logics->addChild("logic");
                        $logic_main->addAttribute('jumpTo', $value->jump_to);
                        $logic_conditions = $this->logic_model->get_conditions($result->question_id, $value->jump_to);
                        if (!empty($logic_conditions)) {
                            $conditions = $logic_main->addChild("conditions");
                            foreach ($logic_conditions as $key => $value) {
                                if ($value->group_logic)
                                    $conditions->addAttribute('groupLogic', $value->group_logic);

                                $conditions->addAttribute('operator', $value->operator);
                                $conditions->addAttribute('value', $value->value);
                            }
                        }
                    }
                    break;
                case 4:
                    foreach ($ans_logics as $key => $value) {
                        $logic_main = $logics->addChild("logic");
                        $logic_main->addAttribute('jumpTo', $value->jump_to);
                        $logic_conditions = $this->logic_model->get_conditions($result->question_id, $value->jump_to);
                        if (!empty($logic_conditions)) {
                            $conditions = $logic_main->addChild("conditions");
                            foreach ($logic_conditions as $key => $value) {
                                if ($value->group_logic)
                                    $conditions->addAttribute('groupLogic', $value->group_logic);

                                $conditions->addAttribute('operator', $value->operator);
                                $conditions->addAttribute('value', $value->value);
                            }
                        }
                    }
                    break;
                case 6:
                    foreach ($ans_logics as $key => $value) {
                        $logic_main = $logics->addChild("logic");
                        $logic_main->addAttribute('jumpTo', $value->jump_to);
                        $logic_conditions = $this->logic_model->get_conditions($result->question_id, $value->jump_to);
//                        echo "<pre>";
//                        print_r($logic_conditions);die;
                        if (!empty($logic_conditions)) {
                            $conditions = $logic_main->addChild("conditions");
                            foreach ($logic_conditions as $key => $value) {
                                $condition = $conditions->addChild("condition");
                                if ($value->group_logic)
                                    $condition->addAttribute('groupLogic', $value->group_logic);

                                $condition->addAttribute('operator', $value->operator);
                                $condition->addAttribute('value', $value->value);
                            }
                        }
                    }
                    break;
            }
        }
        return $question;
    }

    /**
     * list all the survey of a particular company
     * @param int $user_id
     * return array
     */
    public function list_survey($user_id, $lang) {
        $available_langs = $this->language_model->get_languages_app();

        $result = $this->survey_model->get_surveys_company_app($user_id, $lang);
        $response = array(
            'surveys' => $result,
            'langs' => $available_langs,
        );
        return $response;
    }

    /**
     * Uploads the survey response data to database
     * @param String $filename
     * return bool
     */
    public function upload_survey_data($filename) {

        //if the file is not uploaded in the tmp folder
        if (empty($filename)) {
            $response = array(
                'code' => ERROR,
                'message' => "File couldn't be uploaded",
            );
            return $response;
        }
        $this->load->model('contact/contact_model');
        $this->load->model('log/log_model');
        $this->load->helper(array('json_parser', 'randomnumber', 'contactdetail'));
        $filepath = ROOTPATH . "/tmp/" . $filename;
        $survey = json_parser($filepath);
        $status_details = array();

        // if the json can't be decoded
        if (is_null($survey)) {
            $response = array(
                'message' => "JSON Parsing error",
                'error' => ERROR,
            );
            return $response;
        }

        $this->load->helper('randomnumber');

        $this->load->library('encrypt');

        foreach ($survey as $value) {
            //Generate unique survey user id
            $survey_user_id = get_random_string(10); //get_uniqid(); //unique person who takes the full survey
            $survey_id = $value->surveyId;
            $user_id = $value->userId; // company user who downloads the survey. 
            $contact_info = $value->contactInfo;

            $device_id = @$value->deviceId;
            $responses = $value->responses;
            $commentinfo = @$value->commentInfo;
            $data_card_name = @$value->cardImageName;
	        $guid = "";
            $source = "";

            $surveyGeneratedDate = @$value->genDate;	
            $barcode =  @$value->barcode;

            //saving contacts info
            $inserted_contacts = 0;
            $total_contacts = 0;

            foreach ($contact_info as $key => $value) {
                $field_id_info = explode('_', $key);
                $contact_form_field_id = $field_id_info[1];

                if (is_numeric($contact_form_field_id)) {
                    $insert_data = array('contact_form_field_id' => $contact_form_field_id, 'questionnaire_id' => $survey_id, 'survey_user_id' => $survey_user_id, 'value' => $this->encrypt->encode($value));
                    $contact_insertion_status = $this->survey_model->save($insert_data, 'survey_contact_detail');
                    if (is_numeric($contact_insertion_status)) {
                        $inserted_contacts++;
                    }
                    $total_contacts++;
                }elseif($key == "guid-eventmonitor") {

             $guidValues = explode("_", $value);
             $guid = $guidValues[0] ? $guidValues[0] : "";
             $source = $guidValues[1] ? $guidValues[1] : "";
			//$guid = $value;
		}
            }

            //throw an error if all the needed contact fields couldn't be inserted to database
            if ($inserted_contacts != $total_contacts) {
                $status_details['contact'] = ERROR;
                $status_data = array('progress_data' => serialize($status_details),
                    'file_name' => $filename,
                    'survey_user_id' => $survey_user_id,
                    'has_error' => 1,
                    'is_processed' => 0,
                );
                $this->log_model->save($status_data);
                $response = array(
                    'code' => ERROR,
                    'message' => "Contact field  insertion error",
                );
                return $response;
            }

            //saving question / answers
            $inserted_questions = 0;
            $total_questions = 0;

            foreach ($responses as $value) {
                //saving questions
                $question_id = $value->questionId;
                $insert_data = array('questionnaire_id' => $survey_id, 'survey_user_id' => $survey_user_id, 'question_id' => $question_id, 'question_comment' => @$value->comment);
                $question_insertion_status = $this->survey_model->save($insert_data, 'survey_question');

                if (is_numeric($question_insertion_status)) {
                    $inserted_questions++;
                }

                //saving answers
                $inserted_answers = 0;
                $total_answers = 0;
                $answers = (isset($value->answers)) ? $value->answers : array();
                foreach ($answers as $value) {
                    $option_id = $value->optionId;//(@$value->optionId != "") ? $value->optionId : "";
                    $insert_data = array('questionnaire_id' => $survey_id, 'text' => $value->text, 'survey_user_id' => $survey_user_id, 'question_id' => $question_id, 'option_id' => $option_id);
                    $survey_answer_id = $this->survey_model->save($insert_data, 'survey_answer');

                    if (is_numeric($survey_answer_id)) {
                        $inserted_answers++;
                    }

                    $sub_answer = "";
                    if (isset($value->hOptions)) {
                        $sub_answer = serialize($value->hOptions);
                    } else if (!empty($value->subOptions)) {
                        $sub_answer = serialize($value->subOptions);
                    }

                    //saving sub answers
                    if ($sub_answer) {
                        $insert_data = array('answer' => $sub_answer, 'survey_answer_id' => $survey_answer_id, 'question_id' => $question_id);
                        $subanswer_insertion_status = $this->survey_model->save($insert_data, 'survey_sub_answer');

                        //throw an error if all the subanswers couldn't be inserted to database
                        if (!is_numeric($subanswer_insertion_status)) {
                            $status_details['subanswer'] = ERROR;
                            $status_data = array('progress_data' => serialize($status_details),
                                'file_name' => $filename,
                                'survey_user_id' => $survey_user_id,
                                'has_error' => 1,
                                'is_processed' => 0,
                            );
                            $this->log_model->save($status_data);

                            $response = array(
                                'code' => ERROR,
                                'message' => "Sub answer insertion error",
                            );
                            return $response;
                        }
                    }
                    $total_answers++;
                }
                $total_questions++;
            }

            //throw an error if all the questions couldn't be inserted to database
            if ($inserted_questions != $total_questions) {
                $status_details['question'] = ERROR;
                $status_data = array('progress_data' => serialize($status_details),
                    'file_name' => $filename,
                    'survey_user_id' => $survey_user_id,
                    'has_error' => 1,
                    'is_processed' => 0,
                );
                $this->log_model->save($status_data);

                $response = array(
                    'code' => ERROR,
                    'message' => "Question insertion error",
                );
                return $response;
            }

            //throw an error if all the answers couldn't be inserted to database
            if ($inserted_answers != $total_answers) {
                $status_details['answer'] = ERROR;
                $status_data = array('progress_data' => serialize($status_details),
                    'file_name' => $filename,
                    'survey_user_id' => $survey_user_id,
                    'has_error' => 1,
                    'is_processed' => 0,
                );
                $this->log_model->save($status_data);

                $response = array(
                    'code' => ERROR,
                    'message' => "Answer insertion error",
                );
                return $response;
            }

            //saving others info
            $survey_comment = $source . '\n'.$commentinfo->comment;
            $assign_to = @$commentinfo->assignTo;
            $receive_email = @$commentinfo->receiveEmail;
            $telemarketing_qualification = @$commentinfo->telemarketingQualification;

            
            //insert multiple images (5)
             $comment_imgs = @$commentinfo->commentImageName ? $commentinfo->commentImageName : "";
             $comment_imgs_arr  = array();
            if(isset($comment_imgs) && !empty($comment_imgs)){

                foreach($comment_imgs as $cimg){
                     $comment_imgs_arr [] =  base_url() . 'app_uploads' . DS . 'comment' . DS . $cimg->name;
                }

               
            }

            $comment_img = implode($comment_imgs_arr, '\n');
            //$comment_img = @$commentinfo->commentImageName ? base_url() . 'app_uploads' . DS . 'comment' . DS . $commentinfo->commentImageName : "";
            
            $comment_audio = @$commentinfo->audioName ? base_url() . 'app_uploads' . DS . 'comment' . DS . $commentinfo->audioName : "";

            $insert_data = array('questionnaire_id' => $survey_id, 'survey_user_id' => $survey_user_id, 'user_id' => $user_id, 'survey_comment' => $survey_comment, 'assign_to' => $assign_to, 'receive_email' => $receive_email, 'telemarketing_qualification' => $telemarketing_qualification, 'device_identifier' => $device_id, 'card_image' => $data_card_name, 'uploaded_date' => time(),'lead_generated_date'=>$surveyGeneratedDate,'comment_image' => $comment_img, 'comment_audio' => $comment_audio,'guid'=>$guid,'barcode_content'=>$barcode);
            $result = $this->survey_model->save($insert_data, 'survey_other_info');

            //throw an error if all the remaining fields couldn't be inserted to database
            if (!is_numeric($result)) {
                $status_details['misc'] = ERROR;
                $status_data = array('progress_data' => serialize($status_details),
                    'file_name' => $filename,
                    'survey_user_id' => $survey_user_id,
                    'has_error' => 1,
                    'is_processed' => 0,
                );
                $this->log_model->save($status_data);

                $response = array(
                    'code' => ERROR,
                    'message' => "Misc option insertion error",
                );
                return $response;
            }

            //save in the log file
            $status_details['contact'] = OK;
            $status_details['question'] = OK;
            $status_details['answer'] = OK;
            $status_details['subanswer'] = OK;
            $status_details['misc'] = OK;

            $status_data = array('progress_data' => serialize($status_details),
                'file_name' => $filename,
                'survey_user_id' => $survey_user_id,
                'has_error' => 0,
                'is_processed' => 1,
            );
            $this->log_model->save($status_data);
        }

        $response = array(
            'code' => OK,
            'message' => "Inserted Successfully",
        );
        return $response;
    }

    /**
     * update the card image
     * @param array $card_image_details
     * return array
     */
    public function upload_card_image($card_image_details) {

        if (!empty($card_image_details)) {

            if ($card_image_details['is_comment'] == "true") {
                $response = array(
                    'code' => OK,
                    'message' => "Inserted Successfully",
                );
            } else if ($card_image_details['is_waste'] == "true") {
                $response = array(
                    'code' => OK,
                    'message' => "Inserted Successfully",
                );
            } else {
                $new_card_image = $card_image_details['new_card_image'];
                $old_card_image = $card_image_details['old_card_image'];
                $survey_user_id = $card_image_details['survey_user_id'];

                $new_card_image_url = base_url() . 'app_uploads' . DS . $new_card_image;
                $this->survey_model->update_card_image_details($survey_user_id, array('card_image' => $new_card_image_url, 'original_image' => $old_card_image, 'card_img' => $new_card_image));

                $response = array(
                    'code' => OK,
                    'message' => "Inserted Successfully",
                );
            }
        } else {
            $response = array(
                'code' => ERROR,
                'message' => "Card Image couldn't be uploaded",
            );
        }
        return $response;
    }

    /**
     * get the mime type according to the type
     * @param string $type
     * return string
     */
    public function get_mime_type($type) {
        $mime = "";
        switch ($type) {
            /* case 'png':
              $mime = "image/png";
              break;
              case 'jpg':
              $mime = "image/jpeg";
              break; */
            case 'doc':
                $mime = "application/msword";
                break;
            case 'docx':
                $mime = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case 'pdf':
                $mime = "application/pdf";
                break;
            case 'ppt':
            case 'pptx':
                $mime = "application/vnd.ms-powerpoint";
                break;
            case 'xlsx':
            case 'xls':
                $mime = "application/vnd.ms-excel";
            case 'mp3':
            case 'mpeg':
                $mime = "video/mpeg";
            case 'mp4':
                $mime = "video/mp4";
        }

        return $mime;
    }

    /**
     * track the download history
     * @param int $survey_id
     * @param string $device_id
     * return array
     */
    public function download_status($survey_id, $device_id) {
        $this->load->model('statistic/statistic_model');
        $device_exists = $this->statistic_model->check_device_exists($survey_id, $device_id);
        if (!$device_exists) {
            $insertion_status = $this->statistic_model->save(array('questionnaire_id' => $survey_id,
                'device_identifier' => $device_id, 'total' => 1));
        } else {
            $stat_info = $this->statistic_model->get_device_stat($device_id, $survey_id);
            $prev_total = $stat_info->total;
            $total = $prev_total + 1;
            $update_status = $this->statistic_model->update($device_id, $survey_id, array('total' => $total));
        }
        if (is_numeric($insertion_status) || $update_status) {
            $response = array(
                'code' => OK,
                'message' => "Inserted Successfully",
            );
        } else {
            $response = array(
                'code' => ERROR,
                'message' => "Statistic could not be sent",
            );
        }
        return $response;
    }

    /**
     * throw the error message when method is not found in the service
     * @param string $msg
     * return array
     */
    public function service_error_json($msg) {
        $response = array(
            'code' => ERROR,
            'message' => $msg,
        );
        return $response;
    }

    /**
     * throw the error message when method is not found in the service
     * @param string $msg
     * return xml
     */
    public function service_error_xml($msg) {
        Header('Content-type: application/xml');
        $xml = new SimpleXMLElement("<errors/>");
        $xml->addChild("error", $msg);
        echo $xml->asXML();
        die;
    }

}
