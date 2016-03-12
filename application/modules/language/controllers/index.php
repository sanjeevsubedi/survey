<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('language/language_model');
        $this->load->model('survey/survey_model');
        $this->load->model('question/question_model');
        $this->load->model('language/language_model');
        $this->load->model('device/device_model');
        $this->load->model('contact/contact_model');
        $this->load->model('answer/answer_model');
        $this->load->helper('permission');
        $this->load->model('permission/permission_model');
    }

    /**
     * This function displays and manages the language add form
     */
    public function add() {

        if ($_POST) {
            $this->form_validation->set_rules('language', 'language', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new');
                $this->template->render();
            } else {
                $name = $this->input->post('language');
                $data = array('name' => $name, 'status' => 1);
                $this->language_model->save($data);
                $this->session->set_flashdata('success', 'language added successfully');
                redirect(base_url() . 'language/all');
            }
        } else {
            $data = '';
            //$data['language'] = $this->survey_model->get_languages();
            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the language edit form
     */
    public function edit() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'language/all');
        }
        $data['language'] = $this->language_model->get_language($id);
        $data['eid'] = $id;
        if ($_POST) {
            $this->form_validation->set_rules('language', 'language', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('language');
                $data = array('name' => $name);
                $this->language_model->update($id, $data);
                $this->session->set_flashdata('success', 'language updated successfully');
                redirect(base_url() . 'language/all');
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the languages
     */
    public function all() {
        $data['languages'] = $this->language_model->get_languages(NULL, FALSE);
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the language
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'language/all');
        }
        $this->language_model->delete($id);
        $this->session->set_flashdata('success', 'language deleted successfully');
        redirect(base_url() . 'language/all');
    }

    /**
     * This function manage the language
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'language/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->language_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->language_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->language_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'language updated successfully');
            redirect(base_url() . 'language/all');
        }
    }

    /**
     * This function deletes the language
     */
    public function survey_lang() {
        $survey_id = $this->uri->segment(3);
        $more = $this->uri->segment(4); //when back from the translate page this variable is set
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

        //load fancybox to show the popup 
        load_js('fancybox');

        $data['eid'] = $survey_id;
        $data['more'] = $more ? TRUE : FALSE;

        $suvey_languages = $this->survey_model->get_translation_sets($survey_id);
        $final_survey_languages = array();
        foreach ($suvey_languages as $survey) {
            $final_survey_languages[$survey->language_id] = array(
                'language' => $survey->language,
                'translation_set_id' => $survey->translation_set_id,
                'is_original' => $this->survey_model->get_original_survey($survey->questionnaire_id, $survey->questionnaire_id)
            );
        }

        $data['survey_languages'] = $final_survey_languages;
        $available_languages = $this->language_model->get_languages();
        $survey_lang_ids = array();
        $available_lang_ids = array();

        if (!empty($suvey_languages)) {
            foreach ($suvey_languages as $lang) {
                $survey_lang_ids [$lang->language_id] = $lang->language;
            }
        }

        if (!empty($available_languages)) {
            foreach ($available_languages as $lang) {
                $available_lang_ids [$lang->id] = $lang->name;
            }
        }

        $data['available_languages'] = array_diff($available_lang_ids, $survey_lang_ids);
        $data['survey_languages_ids'] = $survey_lang_ids;
        $data['languages'] = $this->language_model->get_languages();
        $data['survey'] = $survey_detail;
        $data['has_lang'] = $survey_detail->language_id == 0 ? FALSE : TRUE;

        if ($_POST) {
            $this->form_validation->set_rules('language', 'language', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'survey_lang_list', $data);
                $this->template->render();
            } else {
                $language = $this->input->post('upper-language');
                $next = $this->input->post('next');
                if ($survey_detail->language_id == 0) {
                    $data_lang = array(
                        'language_id' => $language,
                    );
                    $this->survey_model->update($survey_id, $data_lang);
                    $this->session->set_flashdata('success', 'Language added successfully');
                    redirect(base_url() . 'member/auth_survey_user/' . $survey_id);
                } else if ($survey_detail->language_id > 0 && !$next) {
                    redirect(base_url() . 'member/auth_survey_user/' . $survey_id);
                } else {
                    //if the original language is same as the new language then we will not allow
                    if ($survey_detail->language_id == $language) {
                        $this->session->set_flashdata('error', 'Please select different language');
                        redirect(base_url() . 'language/survey_lang/' . $survey_id);
                    }
                    $language = $this->input->post('language');
                    $template = $survey_detail->template_id;
                    $template_owner = $survey_detail->template_owner;
                    $user_id = $this->session->userdata(SESS_UID);
                    $company_id = $this->user_model->get_company_id_by_user_id($user_id);

                    $lang_detail = $this->language_model->get_language($language);

                    $this->load->helper('randomnumber');
                    $survey_details = $this->survey_model->get_survey($survey_id);
                    //copy the logo image
                    $file_ext = substr($survey_details->logo, strrpos($survey_details->logo, '.') + 1);
                    $new_file = get_random_string(16) . "." . $file_ext;
                    @copy(UPLOADPATH . DS . 'logo' . DS . $survey_details->logo, UPLOADPATH . DS . 'logo' . DS . $new_file);

                    //inserts into questionnaire table
                    $data = array(
                        'name' => $lang_detail->short_name . " " . $survey_detail->name,
                        'language_id' => $language,
                        'company_id' => $company_id,
                        'user_id' => $user_id,
                        'template_id' => $template,
                        'template_owner' => $template_owner,
                        'status' => 1,
                        'data_collection_method_id' => $survey_details->data_collection_method_id,
                        'translation_set_id' => $survey_id,
                        'logo' => $new_file,
                        'created_date' => time(),
                    );
                    //saves new language
                    $new_survey_id = $this->survey_model->save($data);

                    //updates the translation set id of the existing survey which was used for translation
                    $data_existing = array(
                        'translation_set_id' => $survey_id,
                    );
                    $this->survey_model->update($survey_id, $data_existing);

                    //inserts into device_questionnaire table as device names are same for all languages
                    $device_results = $this->device_model->get_device_questionnaire_clone($survey_id);
                    foreach ($device_results as $device_result) {
                        $data_device = get_object_vars($device_result);
                        unset($data_device['id']);
                        $data_device['questionnaire_id'] = $new_survey_id;
                        $this->device_model->save_device_questionnaire($data_device);
                    }

                    //insert the owner of the original survey in the permission table
                    //make the entry of the owner as questionnaire admin in the permission table
                    $data_perm = array('permission_id' => 3, 'questionnaire_id' => $new_survey_id,
                        'user_id' => $survey_details->user_id);
                    $this->permission_model->save_priveledge($data_perm);

                    //insert the translated contact field entry
                    $original_lang_cfields = $this->contact_model->get_contact_field_questionnaire($survey_id);
                    if (!empty($original_lang_cfields)) {
                        foreach ($original_lang_cfields as $original_lang_cfield) {
                            $translated_row = $this->contact_model->get_single_translated_contact_field($original_lang_cfield->contact_form_field_id, $language);
                            $data_cfield = array('questionnaire_id' => $new_survey_id,
                                'contact_form_field_id' => $translated_row->contact_field_id,
                                'is_required' => $original_lang_cfield->is_required,
                                'order' => $original_lang_cfield->order,
                                'row' => $original_lang_cfield->row
                            );
                            $this->contact_model->save_contact($data_cfield);
                        }
                    }

                    $this->session->set_flashdata('success', 'Language added successfully');
                    redirect(base_url() . 'language/translate/' . $survey_id . '/' . $language);
                }
            }
        } else {
            $this->template->write_view('content', 'language/survey_lang_list', $data);
            $this->template->render();
        }
    }

    /**
     * This function shows the language translation interface
     */
    public function translate() {
        $survey_id = $this->uri->segment(3);
        $language_id = $this->uri->segment(4);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //this step verifies the valid language and prevents the url tampering
        if (!$this->language_model->is_valid_language_reqest($language_id)) {
            redirect(base_url() . 'survey/all');
        }

        //check the necessary permission
        permission_helper($survey_id);

        //questions are mandatory for survey
        $survey_questions = $this->question_model->get_quiestions_questionnaire($survey_id);
        if (empty($survey_questions)) {
            $this->session->set_flashdata('error', 'At least one question is required');
            redirect(base_url() . 'survey/step_5/' . $survey_id);
        }

        //accordion upper section only
        $survey_detail = $this->survey_model->get_survey($survey_id);
        $data['languages'] = $this->language_model->get_languages();
        $data['survey_obj'] = $survey_detail;

        //load fancybox to show the popup 
        load_js('fancybox');
        $original_survey = $this->survey_model->get_survey($survey_id);
        $translated_survey = $this->survey_model->get_survey_with_translation($language_id, $survey_id);
        $original_language = $this->language_model->get_language($original_survey->language_id);
        $translated_language = $this->language_model->get_language($language_id);
        $data['original_lang'] = $original_language->name;
        $data['translated_lang'] = $translated_language->name;

        $data['eid'] = $survey_id;
        $data['lang'] = $language_id;

        $data['quest_ans'] = $this->question_model->get_all_questions_questionnaire($survey_id, $language_id);
        $data['contact_fields'] = array(); //$this->contact_model->get_all_contact_field_questionnaire($survey_id, $language_id);
        $data['survey'] = $this->survey_model->get_survey_questionnaire($survey_id, $language_id);

        $original_question = $this->input->post('original_question');
        $translate_question_ids = $this->input->post('translate_question_ids');


        $original_contact_field = $this->input->post('original_contact_field');
        $translate_contact_field_ids = $this->input->post('translate_contact_field_ids');

        $original_survey = $this->input->post('original_survey');
        $translate_survey_id = $this->input->post('translate_survey_id');

        //$answers = $this->answer_model->get_answers($result->question_id);
        //translates survey
        /* if ($original_survey) {
          //contact form is mandatory
          $existing_contacts = array('1');
          $this->survey_model->update($translate_survey_id[key($translate_survey_id)], array("name" =>
          $original_survey[key($original_survey)], "data_collection_method_id" => serialize($existing_contacts))
          );
          } */

        //translates all the contact form fields
        if ($original_contact_field) {
            foreach ($original_contact_field as $contact_field_id => $translated_contact_field) {
                $contact_results = $this->contact_model->get_contact($contact_field_id);
                $data_contact = get_object_vars($contact_results);
                unset($data_contact['id']);
                $data_contact['name'] = $translated_contact_field;
                //$data_question['questionnaire_id'] = $translated_survey->id;
                $data_contact['language_id'] = $translated_language->id;
                $data_contact['translation_set_id'] = $contact_field_id;

                //if the contact field is already translated then we need to update the contacts accordingly
                if (!empty($translate_contact_field_ids[$contact_field_id])) {
                    //update translated question
                    $this->contact_model->update(
                            $translate_contact_field_ids[$contact_field_id], array('name' => $translated_contact_field)
                    );
                } else {
                    //saves translated contact field
                    $data_contact['identifier'] = preg_replace('/\s+/', '-', $translated_contact_field);
                    $new_contact_field_id = $this->contact_model->save($data_contact);
                    //update the translation_set_id of the original contact field
                    $this->contact_model->update($contact_field_id, array('translation_set_id' => $contact_field_id));

                    //saves the contact field information in contact_form_field_questionnaire table
                    $contact_questionnaire_results = $this->contact_model->get_contact_form_field($contact_field_id);
                    $data_contact_questionnaire = get_object_vars($contact_questionnaire_results);
                    unset($data_contact_questionnaire['id']);
                    $data_contact_questionnaire['questionnaire_id'] = $translated_survey->id;
                    $data_contact_questionnaire['contact_form_field_id'] = $new_contact_field_id;
                    $this->contact_model->save_contact($data_contact_questionnaire);
                }
            }
        }

        //translates all the questions
        if ($original_question) {

            foreach ($original_question as $question_id => $translated_question) {
                $question_results = $this->question_model->get_question_row($question_id);
                $data_question = get_object_vars($question_results);
                unset($data_question['id']);
                $data_question['name'] = $translated_question;
                $data_question['questionnaire_id'] = $translated_survey->id;
                $data_question['language_id'] = $translated_language->id;
                $data_question['translation_set_id'] = $question_id;

                //if the question is already translated then we need to update the questions accordingly
                if (!empty($translate_question_ids[$question_id])) {

                    $update_ques_id = $translate_question_ids[$question_id];
                    //update translated question
                    $this->question_model->update(
                            $translate_question_ids[$question_id], array('name' => $translated_question)
                    );

                    $original_main_answer = $this->input->post('original_main_answer' . $question_id);
                    $original_vmain_answer = $this->input->post('original_vmain_answer' . $question_id);
                    $original_hmain_answer = $this->input->post('original_hmain_answer' . $question_id);

                    //updates answer
                    if (!empty($original_vmain_answer) || !empty($original_hmain_answer)) {
                        $final_answer = array("h_answer" => $original_vmain_answer, "v_answer" => $original_hmain_answer);
                        $this->answer_model->update_answer($update_ques_id, array(
                            'name' => serialize($final_answer),
                        ));
                    }

                    //updates answer
                    if (!empty($original_main_answer)) {
                        $final_answer = array('options' => $original_main_answer);
                        $this->answer_model->update_answer($update_ques_id, array(
                            'name' => serialize($final_answer),
                        ));
                        //update subanswers
                        foreach ($original_main_answer as $k => $ans) {
                            $original_sub_answer = $this->input->post('original_sub_answer' . $question_id . '_' . $k);
                            $translated_sub_answer_ids = $this->input->post('translate_subans_ids' . $question_id . '_' . $k);
                            if (!empty($original_sub_answer)) {
                                foreach ($original_sub_answer as $key => $subans) {
                                    $data_subans = array('name' => $subans);
                                    $this->answer_model->update_sub_answer($translated_sub_answer_ids[$key], $data_subans);
                                }
                            }
                        }
                    }
                } else {
                    //saves translated question
                    $new_ques_id = $this->question_model->save($data_question);

                    $original_main_answer = $this->input->post('original_main_answer' . $question_id);
                    $original_vmain_answer = $this->input->post('original_vmain_answer' . $question_id);
                    $original_hmain_answer = $this->input->post('original_hmain_answer' . $question_id);


                    if (!empty($original_vmain_answer) || !empty($original_hmain_answer)) {
                        $final_answer = array("h_answer" => $original_vmain_answer, "v_answer" => $original_hmain_answer);
                        $this->answer_model->save(array(
                            'question_id' => $new_ques_id,
                            'name' => serialize($final_answer),
                        ));
                    }

                    if (!empty($original_main_answer)) {
                        $final_answer = array('options' => $original_main_answer);
                        $this->answer_model->save(array(
                            'question_id' => $new_ques_id,
                            'name' => serialize($final_answer),
                        ));

                        foreach ($original_main_answer as $k => $ans) {
                            $original_sub_answer = $this->input->post('original_sub_answer' . $question_id . '_' . $k);
                            if (!empty($original_sub_answer)) {
                                foreach ($original_sub_answer as $key => $subans) {
                                    $data_subans = array('answer_id' => $k, 'question_id' => $new_ques_id, 'name' => $subans);
                                    $this->answer_model->save_subanswer($data_subans);
                                }
                            }
                        }
                    }


                    //update the translation_set_id of the original question
                    $this->question_model->update($question_id, array('translation_set_id' => $question_id));
                }
            }

            $track_btn = $this->input->post('track_btn');
            if ($track_btn == 'another') {
                redirect(base_url() . 'language/survey_lang/' . $survey_id . '/more');
            }
            $this->session->set_flashdata('success', 'Question translated successfully');
            redirect(base_url() . 'member/auth_survey_user/' . $survey_id);
        } else {
            $this->template->write_view('content', 'language/translate_interface', $data);
            $this->template->render();
        }
    }

}

