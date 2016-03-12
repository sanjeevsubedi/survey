<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('template/template_model');
        $this->load->model('user/user_model');
    }

    /**
     * This function displays and manages the template add form
     */
    public function add() {
        load_js('colorpicker');

        if ($_POST) {
            $this->form_validation->set_rules('template', 'template', 'required');
            $this->form_validation->set_rules('font_color', 'font color', 'required');
            $this->form_validation->set_rules('survey_title_color', 'survey title color', 'required');
            $this->form_validation->set_rules('survey_font_size', 'survey font-size', 'required');
            $this->form_validation->set_rules('question_title_color', 'question title color', 'required');
            $this->form_validation->set_rules('question_font_size', 'question font-size', 'required');



            //validation rule for template bg images
            for ($i = 1; $i <= 4; $i++) {
                $img_name = 'bg_img' . $i;
                if (empty($_FILES[$img_name]['name'])) {
                    $scheme_name = "Scheme " . $i;
                    $this->form_validation->set_rules($img_name, $scheme_name, 'required');
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'new');
                $this->template->render();
            } else {
                $name = $this->input->post('template');
                $font_style = $this->input->post('font_style');
                $font_color = $this->input->post('font_color');
                //$bg_color = $this->input->post('bg_color');
                //finding company id from user id.
                $user_id = $this->session->userdata('sess_cms_uid');
                $company_id = $this->user_model->get_company_id_by_user_id($user_id);

                $survey_title_color = $this->input->post('survey_title_color');
                $survey_font_size = $this->input->post('survey_font_size');
                $question_font_size = $this->input->post('question_font_size');
                $question_title_color = $this->input->post('question_title_color');
                $option_font_size = $this->input->post('option_font_size');
                $option_title_color = $this->input->post('option_title_color');



                $survey_title_settings = serialize(array('color' => $survey_title_color, 'size' => $survey_font_size));
                $question_title_settings = serialize(array('color' => $question_title_color, 'size' => $question_font_size));
                $option_settings = serialize(array('color' => $option_title_color, 'size' => $option_font_size));

                //save template information
                $data = array(
                    'name' => $name,
                    'font_style' => $font_style,
                    'font_color' => $font_color,
                    'bg_color' => '',
                    'bg_image' => '',
                    'status' => 1,
                    'company_id' => $company_id,
                    'survey_title_settings' => $survey_title_settings,
                    'question_title_settings' => $question_title_settings,
                    'option_settings' => $option_settings,
                );
                $template_id = $this->template_model->save($data);


                //upload bg image
                $file_name = "";
                $this->load->model('file/file_model');
                $this->load->helper('randomnumber');
                $config_template['upload_path'] = UPLOADPATH . DS . 'template';
                $config_template['allowed_types'] = 'gif|jpg|png';

                foreach ($_FILES as $field => $file) {

                    $config_template['file_name'] = get_random_string(20);
                    $this->load->library('upload', $config_template);
                    $this->upload->initialize($config_template);

                    if ($file['error'] == 0) {
                        if ($this->upload->do_upload($field)) {
                            $data = $this->upload->data();
                            $file_name = $data['file_name'];

                            //saves template schemes
                            $data_scheme = array(
                                'name' => $field,
                                'bg_image_ipad' => $file_name,
                                'template_id' => $template_id,
                            );
                            $this->template_model->save_scheme($data_scheme);
                        } else {
                            $errors = $this->upload->display_errors();
                        }
                    }
                }
                $this->session->set_flashdata('success', 'template added successfully');
                redirect(base_url() . 'template/all');
            }
        } else {
            $data = '';
            $this->template->write_view('content', 'new', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the template edit form
     */
    public function edit() {
        load_js('colorpicker');
        $id = $this->uri->segment(3);

        //checks the valid survey
        if (!$this->template_model->is_valid_tpl_request($id)) {
            redirect(base_url() . 'template/all');
        }

        $template = $this->template_model->get_template($id);
        $template_schemes = $this->template_model->get_schemes($id);
        $file_name = $template->bg_image;

        $survey_settings = $template->survey_title_settings;
        $survey_settings = unserialize($survey_settings);
        $template->survey_title_color = $survey_settings['color'];
        $template->survey_title_size = $survey_settings['size'];

        $question_settings = $template->question_title_settings;
        $question_settings = unserialize($question_settings);
        $template->question_title_color = $question_settings['color'];
        $template->question_title_size = $question_settings['size'];

        $option_settings = $template->option_settings;
        $option_settings = unserialize($option_settings);
        $template->option_title_color = $option_settings['color'];
        $template->option_size = $option_settings['size'];


        $data['template'] = $template;
        $data['template_schemes'] = $template_schemes;
        $data['eid'] = $id;
        if ($_POST) {
            $this->form_validation->set_rules('template', 'template', 'required');
            $this->form_validation->set_rules('font_color', 'font color', 'required');
            $this->form_validation->set_rules('survey_title_color', 'survey title color', 'required');
            $this->form_validation->set_rules('survey_font_size', 'survey font-size', 'required');
            $this->form_validation->set_rules('question_title_color', 'question title color', 'required');
            $this->form_validation->set_rules('question_font_size', 'question font-size', 'required');

            //only the system templates will have different schemes
            if ($template->company_id == 0) {
                for ($i = 1; $i <= 4; $i++) {
                    $img_name = 'bg_img' . $i;
                    $tpl_detail = $this->template_model->get_scheme_by_name($img_name, $id);
                    if (empty($_FILES[$img_name]['name']) && empty($tpl_detail->bg_image_ipad)) {
                        $scheme_name = "Scheme " . $i;
                        $this->form_validation->set_rules($img_name, $scheme_name, 'required');
                    }
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $this->template->write_view('content', 'edit', $data);
                $this->template->render();
            } else {
                $name = $this->input->post('template');
                $font_style = $this->input->post('font_style');
                $font_color = $this->input->post('font_color');

                $survey_title_color = $this->input->post('survey_title_color');
                $survey_font_size = $this->input->post('survey_font_size');
                $question_font_size = $this->input->post('question_font_size');
                $question_title_color = $this->input->post('question_title_color');
                $option_font_size = $this->input->post('option_font_size');
                $option_title_color = $this->input->post('option_title_color');

                $survey_title_settings = serialize(array('color' => $survey_title_color, 'size' => $survey_font_size));
                $question_title_settings = serialize(array('color' => $question_title_color, 'size' => $question_font_size));
                $option_settings = serialize(array('color' => $option_title_color, 'size' => $option_font_size));


                $data = array(
                    'name' => $name,
                    'font_style' => $font_style,
                    'font_color' => $font_color,
                    'survey_title_settings' => $survey_title_settings,
                    'question_title_settings' => $question_title_settings,
                    'option_settings' => $option_settings,
                );

                $this->template_model->update($id, $data);


                //only the system templates will have different schemes
                if ($template->company_id == 0) {
                    //upload bg image
                    $this->load->model('file/file_model');
                    $this->load->helper('randomnumber');
                    $config_template['upload_path'] = UPLOADPATH . DS . 'template';
                    $config_template['allowed_types'] = 'gif|jpg|png';

                    foreach ($_FILES as $field => $file) {
                        $config_template['file_name'] = get_random_string(20);
                        $this->load->library('upload', $config_template);
                        $this->upload->initialize($config_template);

                        if ($file['error'] == 0 && !empty($file['name'])) {
                            if ($this->upload->do_upload($field)) {
                                $data = $this->upload->data();
                                $file_name = $data['file_name'];

                                $data_scheme = array(
                                    'name' => $field,
                                    'bg_image_ipad' => $file_name,
                                    'template_id' => $id,
                                );

                                $tpl_detail = $this->template_model->get_scheme_by_name($field, $id);
                                @unlink(UPLOADPATH . DS . 'template' . DS . $tpl_detail->bg_image_ipad);
                                $this->template_model->update_scheme($tpl_detail->id, $data_scheme);
                            } else {
                                $errors = $this->upload->display_errors();
                            }
                        }
                    }
                }
                $this->session->set_flashdata('success', 'template updated successfully');
                redirect(base_url() . 'template/all');
            }
        } else {
            $this->template->write_view('content', 'edit', $data);
            $this->template->render();
        }
    }

    /**
     * This function displays all the templates
     */
    public function all() {

        //for permission to view templates
        $role_id = $this->session->userdata(SESS_ROLE_ID);
        $user_id = $this->session->userdata('sess_cms_uid');
        $user_role = $this->user_model->get_role($role_id);

        $company_id = $this->user_model->get_company_id_by_user_id($user_id);
        $data['templates'] = $this->template_model->get_templates(NULL, FALSE);
        $this->template->write_view('content', 'list', $data);
        $this->template->render();
    }

    /**
     * This function deletes the template
     */
    public function delete() {
        $id = $this->uri->segment(3);
        if (!is_numeric($id)) {
            redirect(base_url() . 'template/all');
        }


        /* if ($template->owner == 'o') {
          $template = $this->template_model->get_template($id);
          @unlink(UPLOADPATH . DS . 'template' . DS . $template->bg_image);
          } else { */
        $schemes = $this->template_model->get_schemes($id);
        foreach ($schemes as $scheme) {
            @unlink(UPLOADPATH . DS . 'template' . DS . $scheme->bg_image_ipad);
        }
        //}

        $this->template_model->delete($id);
        $this->session->set_flashdata('success', 'template deleted successfully');
        redirect(base_url() . 'template/all');
    }

    /**
     * This function manage the template
     */
    public function manage() {

        $action = $this->input->post('action');
        $selected_values = $this->input->post('child');

        if (empty($selected_values)) {
            $this->session->set_flashdata('error', 'Please select a record from the list');
            redirect(base_url() . 'template/all');
        } else {
            switch ($action) {
                case 'delete':
                    foreach ($selected_values as $id) {
                        $template = $this->template_model->get_template($id);
                        @unlink(UPLOADPATH . DS . 'template' . DS . $template->bg_image);
                        $this->template_model->delete($id);
                    }
                    break;
                case 'publish':
                    foreach ($selected_values as $id) {
                        $this->template_model->update($id, array('status' => 1));
                    }
                    break;
                case 'unpublish':
                    foreach ($selected_values as $id) {
                        $this->template_model->update($id, array('status' => 0));
                    }
                    break;
                default:
                    break;
            }

            $this->session->set_flashdata('success', 'template updated successfully');
            redirect(base_url() . 'template/all');
        }
    }

    /**
     * This function displays accordion interface of creating a new template via ajax request
     */
    public function ajax_create_template() {
        $this->load->view('accor_new');
    }

}
