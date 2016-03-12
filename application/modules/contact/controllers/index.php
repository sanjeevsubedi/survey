<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('contact/contact_model');
        $this->load->model('field/field_model');
        $this->load->model('user/user_model');
        $this->load->helper('permission');
        $this->load->model('permission/permission_model');
    }

    /**
     * This function displays and manages the contact add form
     */
    public function add() {
        $return_case = $this->uri->segment(3);
        $return_id = $this->uri->segment(4);

        $data['types'] = $this->field_model->get_fields();
        if ($_POST) {
            $this->form_validation->set_rules('contact', 'contact', 'required');
            $this->form_validation->set_rules('type', 'type', 'required');
            $this->form_validation->set_rules('options', 'options', 'trim');


            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new', $data);
                $this->template->render();
            } else {

                //finding company id from user id.
                $user_id = $this->session->userdata('sess_cms_uid');
                $company_id = $this->user_model->get_company_id_by_user_id($user_id);

                $name = $this->input->post('contact');
                $identifier = $this->input->post('identifier');
                $validation_rule = $this->input->post('validation_rule');

                $type_details = explode('_', $this->input->post('type'));


                $type = $type_details[0];
                $type_name = $type_details[1];
                $options = '';
                if ($this->input->post('options')) {
                    $options = $this->input->post('options');
                }
                $data = array(
                    'name' => $name,
                    'identifier' => $identifier,
                    'validation_rule' => $validation_rule,
                    'field_config_id' => $type,
                    'options' => $type_name == 'select' ? $options : '',
                    'status' => 1,
                    'company_id' => $company_id,
                    'language_id' => 8, // english language
                    'translation_set_id' => 0,
                );
                $this->contact_model->save($data);
                $this->session->set_flashdata('success', 'contact field added successfully');
                if ($return_case && $return_id) {
                    redirect(base_url() . 'survey/step_2/' . $return_id);
                } else {

                    redirect(base_url() . 'cfield/all');
                }
            }
        } else {

            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the contact edit form
     */
    public function edit() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'cfield/all');
        }

        //default fields can't be edited
        if (in_array($id, unserialize(DEFAULT_EN_CONTACT_FIELDS))) {
            redirect(base_url() . 'cfield/all');
        }

        $data['types'] = $this->field_model->get_fields();
        $contact = $this->contact_model->get_contact($id);
        $data['contact'] = $contact;
        $data['type'] = $this->field_model->get_field($contact->field_config_id);

        $data['eid'] = $id;
        $this->load->model('language/language_model');


        $langs = array();
        $translation_detail = array();
        $available_langs = $this->language_model->get_languages();
        foreach ($available_langs as $lang) {
            $langs [] = $lang;
            $translation_detail[] = $this->contact_model->get_single_translated_contact_field($id, $lang->id);
        }

        $data['languages'] = array('langs' => $langs, 'translation' => $translation_detail);
        // $ctranslations = $this->contact_model->get_all_contact_fields($id);
        if ($_POST) {
            $this->form_validation->set_rules('contact', 'contact', 'required');
            $this->form_validation->set_rules('type', 'type', 'required');
            $this->form_validation->set_rules('options', 'options', 'trim');
            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('contact');
                $identifier = $this->input->post('identifier');
                $validation_rule = $this->input->post('validation_rule');
                $type = $this->input->post('type');
                $translations = $this->input->post('clang');
                $cfield_ids = $this->input->post('cfield_ids');

                $options = '';
                if ($this->input->post('options')) {
                    $options = $this->input->post('options');
                }
                $data = array(
                    'name' => $name,
                    'identifier' => $identifier,
                    'validation_rule' => $validation_rule,
                    'field_config_id' => $type,
                    'options' => $options,
                );
                $this->contact_model->update($id, $data);

                //save the translations
                foreach ($cfield_ids as $lang_id => $cfield_id) {
                    if (!empty($translations[$lang_id])) {
                        $contact_field = $this->contact_model->check_contact_field_exists($cfield_id);
                        if (!is_object($contact_field)) {
                            $data_translated = array(
                                'name' => $translations[$lang_id],
                                'identifier' => str_replace(' ', '_', $translations[$lang_id]),
                                'validation_rule' => $contact->validation_rule,
                                'field_config_id' => $contact->field_config_id,
                                'options' => '',
                                'status' => 1,
                                'company_id' => $contact->company_id,
                                'language_id' => $lang_id,
                                'translation_set_id' => $id,
                            );
                            $this->contact_model->save($data_translated);
                            //update the original contact field translation id also to indicate the field has already been translated
                            $this->contact_model->update($id, array('translation_set_id' => $id));
                        } else {
                            $data_translated = array(
                                'name' => $translations[$lang_id],
                                'identifier' => str_replace(' ', '_', $translations[$lang_id]),
                            );
                            $this->contact_model->update($cfield_id, $data_translated);
                        }
                    }
                }


                $this->session->set_flashdata('success', 'contact updated successfully');
                redirect(base_url() . 'cfield/all');
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the contact fields
     */
    public function all() {
        //for permission to view contacts
        $role_id = $this->session->userdata(SESS_ROLE_ID);
        $user_id = $this->session->userdata('sess_cms_uid');
        $user_role = $this->user_model->get_role($role_id);

        $company_id = $this->user_model->get_company_id_by_user_id($user_id);

        //for super admin
        if ($user_role == 'super-admin') {
            $this->load->library('pagination');
            $config['base_url'] = base_url() . 'cfield/all';
            $config['per_page'] = 20;
            $pagination_no = $this->uri->segment(3);
            $contacts = $this->contact_model->get_contacts(NULL, FALSE, $config['per_page'], $pagination_no);
            $contacts_tot = $this->contact_model->get_contacts(NULL, FALSE);
            $config['total_rows'] = count($contacts_tot);

            $config['first_link'] = "First";
            $config['next_link'] = "Next";
            $config['prev_link'] = "Prev";
            $config['last_link'] = "Last";

            $config['anchor_class'] = 'class = "page-links"';
            $config['full_tag_open'] = '<div class= "pagination-container"><ul>';
            $config['full_tag_close'] = '</ul></div>';

            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';

            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';

            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li><span>';
            $config['cur_tag_close'] = '</span></li>';

            $this->pagination->initialize($config);

            $data['pagination'] = $this->pagination->create_links();
        } else {
            $contacts = $this->contact_model->get_contacts_with_permission(NULL, FALSE, $company_id);
        }

        $final_contacts = array();
        foreach ($contacts as $contact) {
            $field_config = $this->field_model->get_field($contact->field_config_id);
            $contact->field_name = $field_config->type;
            $final_contacts [] = $contact;
        }
        $data['contacts'] = $final_contacts;
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the contact field
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'cfield/all');
        }
        //default fields can't be deleted
        if (in_array($id, unserialize(DEFAULT_CONTACT_FIELDS))) {
            redirect(base_url() . 'cfield/all');
        }


        $this->contact_model->delete($id);
        //delete all the associated translated fields as well
        $this->contact_model->delete_ctranslations($id);
        $this->session->set_flashdata('success', 'contact deleted successfully');
        redirect(base_url() . 'cfield/all');
    }

    /**
     * This function manage the contact field
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'contact/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $this->contact_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->contact_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->contact_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'contact updated successfully');
            redirect(base_url() . 'cfield/all');
        }
    }

    /**
     * This function displays a particular contact collection method
     */
    public function collection() {

        $survey_id = $this->uri->segment(3);
        $contact_type_id = $this->uri->segment(4);

        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid contact collection type
        if (!$this->contact_model->is_valid_contact_request($contact_type_id)) {
            redirect(base_url() . 'survey/contacts/' . $survey_id);
        }

        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        //questions are mandatory for survey
        $this->load->model("question/question_model");
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

        $existing_contacts = unserialize($survey_detail->data_collection_method_id);

        if (!$existing_contacts) {
            $existing_contacts = array();
        }


        if ($contact_type_id != 3) {
            if (!in_array($contact_type_id, $existing_contacts)) {
                //add the collection method to repective survey
                $existing_contacts [] = $contact_type_id;
                $data = array(
                    'data_collection_method_id' => serialize($existing_contacts),
                );
                $this->survey_model->update($survey_id, $data);
            }
        }

        $contact_view = "";
        switch ($contact_type_id) {
            case 1:
                $contact_view = "contactform";
                break;
            case 2:
                $contact_view = "businesscard";
                break;
            case 3:
                //$data['badgescan_active'] = in_array(3, $existing_contacts) ? TRUE : FALSE;
                //$data['badgescan_deactive'] = !in_array(3, $existing_contacts) ? TRUE : FALSE;
                $contact_view = "badgescan";
                $data['badge'] = $this->contact_model->get_badgescan($survey_id);
                $data['badgescan_status'] = in_array(3, $existing_contacts) ? TRUE : FALSE;
                break;
            case 4:
                $contact_view = "crm";
                break;
        }

        $data['eid'] = $survey_id;
        $data['contact_type'] = $contact_type_id;
        $data['default_contacts'] = $this->contact_model->get_default_contacts_by_language($survey_detail->language_id);
        $data['default_contacts_objs'] = $this->contact_model->get_default_contacts_by_language($survey_detail->language_id, FALSE);

        $data['contacts'] = $this->contact_model->get_contacts_by_language($survey_detail->language_id, TRUE);

        $contact_field_objs = $this->contact_model->get_contact_field_questionnaire($survey_id);
        $data['contact_form_fields_objs'] = $contact_field_objs;
        $contact_fields_ids = array();
        foreach ($contact_field_objs as $contact_field_obj) {
            $contact_fields_ids [] = $contact_field_obj->contact_form_field_id;
        }
        $data['contact_form_fields'] = $contact_fields_ids;

        $this->template->write_view('content', $contact_view, $data);
        $this->template->render();
    }

    /**
     * This function handles the badgescan post data
     */
    public function badgescan() {

        $survey_id = $this->uri->segment(3);
        $contact_type_id = $this->uri->segment(4);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid contact collection type
        if (!$this->contact_model->is_valid_contact_request($contact_type_id)) {
            redirect(base_url() . 'survey/contacts/' . $survey_id);
        }

        $survey_detail = $this->survey_model->get_survey($survey_id);
        //check the necessary permission
        permission_helper($survey_id);

        //$data['badge'] = $this->contact_model->get_badgescan($survey_id);
        //$data['eid'] = $survey_id;
        //$data['contact_type'] = $contact_type_id;

        $existing_contacts = unserialize($survey_detail->data_collection_method_id);

        
        if ($_POST) {
            $this->form_validation->set_rules('server', 'server', 'required');
            $this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');
            $this->form_validation->set_rules('idsection', 'idsection', 'required');
            $this->form_validation->set_rules('idquestionsearch', 'idquestionsearch', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'contact/badgescan', $data);
                $this->template->render();
            } else {
                //delete previous record
                $this->contact_model->delete_badgescan($survey_id);

                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $server = $this->input->post('server');
                $idsection = $this->input->post('idsection');
                $idquestionsearch = $this->input->post('idquestionsearch');
                $idquestionoutput = $this->input->post('idquestionoutput');
                $language_id = $survey_detail->language_id;

                $allow_db_download = $this->input->post('download_status');
                $allow_barcode_scan = $this->input->post('barcode_status');
                $allow_lookup_search = $this->input->post('search_status');




                /*ALTER TABLE `badgescan` ADD `db_download` INT NOT NULL , ADD `barcode_scan` INT NOT NULL , ADD `lookup_search` INT NOT NULL ;*/
                /*ALTER TABLE `badgescan` DROP `barcode_scan`;*/


                //for the first time when the badgescan icon is clicked from collection page
                if (!in_array($contact_type_id, $existing_contacts)) {  
                        $existing_contacts [] = $contact_type_id;
                    }
                

                if (!$allow_barcode_scan) {
                    //remove the collection method from repective survey
                    foreach ($existing_contacts as $k => $contact) {
                        if ($contact == 3) {
                            unset($existing_contacts[$k]);
                            break;
                        }
                    
                    }
                } 

                //var_dump($existing_contacts);die;
                $data = array(
                    'data_collection_method_id' => serialize($existing_contacts),
                );
                $this->survey_model->update($survey_id, $data);

                $post_data = array(
                    'username' => $username,
                    'password' => $password,
                    'questionnaire_id' => $survey_id,
                    'server' => $server,
                    'idsection' => $idsection,
                    'idquestionsearch' => $idquestionsearch,
                    'idquestionoutput' => $idquestionoutput,
                    'language_id' => $language_id,
                    'db_download'=> $allow_db_download,
                    'lookup_search' =>$allow_lookup_search
                );
                $this->contact_model->save_badgescan($post_data);
                $this->session->set_flashdata('success', 'badgescan updated successfully');
                redirect(base_url() . 'survey/contacts/' . $survey_id);
            }
        }
    }

}
