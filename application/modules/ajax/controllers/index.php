<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * This function handles the ajax request of sorting contact form field
     */
    /* public function index() {
      $this->load->model('contact/contact_model');
      $ids = $_POST;
      if ($ids) {
      $order = 1;
      foreach ($ids as $key => $val) {
      foreach ($val as $mkey => $mval) {
      if ($mval != '') {
      $data = array(
      "order" => $order,
      );
      $this->contact_model->update_contact($mval, $data);
      $order++;
      }
      }
      }
      }
      } */

    /**
     * This function handles the ajax request of sorting questions in desired order
     */
    public function question_order() {
        $this->load->model('question/question_model');
        $ids = $_POST;
        if ($ids) {
            $order = 1;
            foreach ($ids as $key => $val) {
                foreach ($val as $mkey => $mval) {
                    if ($mval != '') {
                        $data = array(
                            "order" => $order,
                        );
                        $this->question_model->update($mval, $data);
                        $order++;
                    }
                }
            }
        }
    }

    /**
     * This function handles the ajax request of deleting the file resources
     */
    public function delete_file() {
        $this->load->model('file/file_model');
        $resource_id = $_POST['fileId'];
        $resource_name = $_POST['fileName'];
        $type = $_POST['type'];
        //verifies the valid file type
        if ($type == 'q') {
            @unlink(UPLOADPATH . DS . 'question' . DS . $resource_name);
            $this->file_model->delete_question_file($resource_id);
        } else {
            @unlink(UPLOADPATH . DS . 'answer' . DS . $resource_name);
            $this->file_model->delete_answer_file($resource_id);
        }
    }

    /**
     * This function handles the ajax request of deleting the logic condition
     */
    public function delete_condition() {
        $this->load->model('logic/logic_model');
        $condition_id = $_POST['CondId'];
        $this->logic_model->delete_condition($condition_id);
    }

    /**
     * This function handles the ajax request of deleting the logic condition
     */
    public function delete_logic() {
        $this->load->model('logic/logic_model');
        $logic_id = $_POST['LogicId'];
        $this->logic_model->delete($logic_id);
    }

    /**
     * This function handles the ajax request of deleting the file resources
     */
    public function delete_answer() {
        $this->load->model('answer/answer_model');
        $this->load->model('file/file_model');
        $answser_id = $_POST['answerId'];
        $question_id = $_POST['questionId'];
        $question_type_id = $_POST['questionType'];
        $previous_ans_unserialized = $this->answer_model->get_answers_unserialized($question_id, $question_type_id);
        //remove the selected answer
        switch ($question_type_id) {
            case 1:
            case 3:
            case 7:
                unset($previous_ans_unserialized['options'][$answser_id]);

                //removes the answer document entry and its associated files
                $ans_doc_details = $this->file_model->get_resourceid_answer($question_id, $answser_id);
                $file_id = $ans_doc_details->id;
                @unlink(UPLOADPATH . DS . 'answer' . DS . $ans_doc_details->name);
                $this->file_model->delete_answer_file($file_id);

                //saves new answers
                $this->answer_model->update_answer($question_id, array(
                    'name' => serialize($previous_ans_unserialized),
                ));

                //deletes sub answer of specific answer
                $this->answer_model->delete_subanswer_answer($question_id, $answser_id);

                //deletes all the subanswers data type entry of specific answer
                $this->answer_model->delete_single_subanswer_data_entry($question_id, $answser_id);

                break;
            case 4:
                $type = $_POST['type'];

                //removes the answer document entry and its associated files
                $ans_doc_details = $this->file_model->get_resources_answerall($question_id);

                foreach ($ans_doc_details as $ans_doc_detail) {
                    $answerid = $ans_doc_detail->answer_id;
                    $answer_details = explode('_', $answerid);
                    $hkey = $answer_details[0];
                    $vkey = $answer_details[1];

                    if ($type == 'h' && $vkey == $answser_id) {
                        $new_answser_id = $hkey . '_' . $vkey;
                        $ans_doc = $this->file_model->get_resourceid_answer($question_id, $new_answser_id);
                        echo $ans_doc->name;
                        //@unlink(UPLOADPATH . DS . 'answer' . DS . $ans_doc->name);
                        //$this->file_model->delete_answer_file($new_answser_id);
                    } else if ($type == 'v' && $hkey == $answser_id) {
                        $new_answser_id = $hkey . '_' . $vkey;
                        $ans_doc = $this->file_model->get_resourceid_answer($question_id, $new_answser_id);
                        echo $ans_doc->name;
                        //@unlink(UPLOADPATH . DS . 'answer' . DS . $ans_doc->name);
                        //$this->file_model->delete_answer_file($new_answser_id);
                    }
                }


                /* $file_id = $ans_doc_details->id;
                  @unlink(UPLOADPATH . DS . 'answer' . DS . $ans_doc_details->name);
                  $this->file_model->delete_answer_file($file_id);
                 */
                /* $previous_h_answers = $previous_ans_unserialized['h_answer'];
                  $previous_v_answers = $previous_ans_unserialized['v_answer'];
                  if ($type == 'h') {
                  unset($previous_h_answers[$answser_id]);
                  } else {
                  unset($previous_v_answers[$answser_id]);
                  }
                  $final_answer = array("h_answer" => $previous_h_answers, "v_answer" => $previous_v_answers);
                  //saves new answers
                  $this->answer_model->update_answer($question_id, array(
                  'name' => serialize($final_answer),
                  )); */
                break;
        }
        echo $_POST['type'];
    }

    /**
     * This function handles the ajax request of viewing template design
     */
    public function preview_template() {
        $this->load->model('template/template_model');
        $scheme_id = $_POST['template_id'];
        $logo_name = $_POST['logo'];

        $template_scheme_data = $this->template_model->get_scheme($scheme_id);
        $template_id = $template_scheme_data->template_id;

        $template_data = $this->template_model->get_template($template_id);
        $template_name = $template_data->name;
        $this->load->model('survey/survey_model');
        $img = "";

        if (!empty($logo_name)) {
            $img = '<img src="' . base_url() . 'uploads/logo/' . $logo_name . '" height="60" width="111" style="margin:10px;" />';
        }

        echo $template_design = '<div>
<div class=tmp-name>' . $template_name . '</div>
    <div style="font-size:14px;height:415px;width:600px;background-size:cover;background-image:url(' . base_url() . 'uploads/template/' . $template_scheme_data->bg_image_ipad . ');background-repeat:no-repeat">' . $img .
        '<p class="text-description" style="margin:10px;color:#' . $template_data->font_color . ';font-family:' . $template_data->font_style . '" >Sample text font family and color demo</p></div>

                            </div>';
    }

    /**
     * This function handles the ajax request of vauto saving logic
     */
    public function save_logic() {
        $this->load->model('logic/logic_model');
        $data['question_id'] = $this->input->post('question_id');
        $data['jump_to'] = $this->input->post('jump_to');
        $logic_id = $this->input->post('logic_id');
        if ($logic_id) {
            $this->logic_model->update($logic_id, $data); // update
        } else {
            $logic_id = $this->logic_model->save($data); // save
        }
        echo json_encode(array('msg' => true));
    }

    /**
     * This function handles the ajax request of deleting the subanswer of particular answer
     */
    public function delete_subanswer() {
        $this->load->model('answer/answer_model');
        $answser_id = $_POST['answerId'];
        $question_id = $_POST['questionId'];

        //deletes sub answer of specific answer
        $this->answer_model->delete_subanswer_answer($question_id, $answser_id);
        //deletes all the subanswers data type entry of specific answer
        $this->answer_model->delete_single_subanswer_data_entry($question_id, $answser_id);
    }

    /**
     * This function handles the ajax request of adding contact form fields
     */
    public function choosen_contact_fields() {

        $contact_fields = $_POST['contact_ids'];
        $survey_id = $_POST['survey_id'];
        $this->load->model('contact/contact_model');

        $past_fields = $this->contact_model->get_contact_field_questionnaire($survey_id);

        $past_fields_ids = array();
        $past_fields_objs = array();
        foreach ($past_fields as $past_field) {
            $past_fields_ids [] = $past_field->contact_form_field_id;
            $past_fields_objs [$past_field->contact_form_field_id] = $past_field;
        }

        //delete past records
        $this->contact_model->delete_contact($survey_id);
        $r = 0;
        $c = 0;

        foreach ($contact_fields as $contact_field) {

            if ($c == 2) {
                $r++;
                $c = 0;
            }

            $data = array(
                'questionnaire_id' => $survey_id,
                'contact_form_field_id' => $contact_field,
                'is_required' => in_array($contact_field, $past_fields_ids) ? $past_fields_objs[$contact_field]->is_required : 0,
                'is_visible' => in_array($contact_field, $past_fields_ids) ? $past_fields_objs[$contact_field]->is_visible : 1,
                'row' => in_array($contact_field, $past_fields_ids) ? $past_fields_objs[$contact_field]->row : $r,
                'order' => in_array($contact_field, $past_fields_ids) ? $past_fields_objs[$contact_field]->order : $c,
            );
            $this->contact_model->save_contact($data);

            $c++;
        }
    }

    /**
     * This function handles the ajax request of adding required contact form fields and order as well
     */
    /* public function required_contact_fields() {

      $required_fields = $_POST['required_contact_ids'];
      $this->load->model('contact/contact_model');

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
      } */

    /**
     * This function gives the information of uploaded image via ajax
     */
    public function image_upload_info() {
        $upload_folder = $_POST['type'];
        $original_file_name = $_POST['file_name'];
        $template_image_type = isset($_POST['temp_image']) ? $_POST['temp_image'] : "";
        $uploaded_image_name = "";
        $this->load->helper('randomnumber');
        $new_file_name = get_random_string(16);
        $tmp_path = $_FILES[$original_file_name]['tmp_name'];
        $file_name = $_FILES[$original_file_name]['name'];
        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
        $upload_path = UPLOADPATH . DS . $upload_folder . DS . $new_file_name . '.' . $file_ext;

        if (move_uploaded_file($tmp_path, $upload_path)) {
            $uploaded_image_name = $new_file_name . '.' . $file_ext;

            //insert into temporary file table
            if (isset($_POST['temp_insert'])) {
                $this->load->model('file/file_model');
                $tmp_data = array('name' => $uploaded_image_name, 'type' => $upload_folder, 'status' => 0, 'device' => $template_image_type);
                $this->file_model->save_temporary_file($tmp_data);
            }
        }
        echo $uploaded_image_name;
    }

    /**
     * This function handles the ajax request of adding new tempate design
     */
    public function save_template() {
        $uploaded_image_name = $_POST['uploaded_image'];
        $template_details = $_POST['template_fields'];
        $company_id = $_POST['company_id'];
        $this->load->model('template/template_model');
        $data = array(
            'name' => $template_details['name'],
            'font_style' => $template_details['fontfamily'],
            'font_color' => $template_details['fontcolor'],
            'bg_color' => '',
            'bg_image' => $uploaded_image_name,
            'status' => 1,
            'company_id' => $company_id);
        $this->template_model->save($data);
    }

    /**
     * This function handles the ajax request of deleting survey logo
     */
    public function del_logo() {
        $this->load->model('survey/survey_model');
        $survey_id = $_POST['survey_id'];
        $survey = $this->survey_model->get_survey($survey_id);
        //removes the logo of survey
        @unlink(UPLOADPATH . DS . 'logo' . DS . $survey->logo);
        $this->survey_model->update($survey_id, array('logo' => ''));
    }

    /**
     * This function handles the ajax request of deleting tempate
     */
    /* public function del_template() {
      $this->load->model('template/template_model');
      $template_id = $_POST['template_id'];
      $template = $this->template_model->get_template($template_id);
      //removes the logo of survey
      @unlink(UPLOADPATH . DS . 'template' . DS . $template->bg_image);
      $this->template_model->update($template_id, array('bg_image' => ''));
      } */

    /**
     * This function handles the ajax request of ordering the contact fields
     */
    public function order_contact_fields() {
        $updated_required_ids = $_POST['required_ids'];
        $contact_fields = $_POST['contact_obj'];
        $survey_id = $_POST['survey_id'];
        $this->load->model('contact/contact_model');

        $updated_visibility_ids = $_POST['visibility_ids'];

        
        //gets the required contact form fields previously selected
        $visible_field_objs = $this->contact_model->get_visible_contact_field($survey_id);

        $visible_field_ids = array();
        foreach ($visible_field_objs as $visible_field_obj) {
            $visible_field_ids [] = $visible_field_obj->contact_form_field_id;
        }

        //gets the required contact form fields previously selected
        $required_field_objs = $this->contact_model->get_requried_contact_field($survey_id);

        $required_field_ids = array();
        foreach ($required_field_objs as $required_field_obj) {
            $required_field_ids [] = $required_field_obj->contact_form_field_id;
        }

        //delete past records
        $this->contact_model->delete_contact($survey_id);

        foreach ($contact_fields as $row => $contact_field) {
            foreach ($contact_field as $k => $contact_field_id) {

                $data = array(
                    'questionnaire_id' => $survey_id,
                    'contact_form_field_id' => $contact_field_id,
                    'order' => $k,
                    'row' => $row,
                    'is_required' => in_array($contact_field_id, $required_field_ids) ? 1 : 0,
                    'is_visible' => in_array($contact_field_id, $visible_field_ids) ? 1 : 0,
                );
                $this->contact_model->save_contact($data);
            }
        }

        //reset old required fields
        $this->contact_model->update_required_fields_survey(1,$survey_id, array('is_required' => 0));
        //update the required field
        if ($updated_required_ids) {
            foreach ($updated_required_ids as $updated_required_id) {
                $data = array(
                    'is_required' => 1,
                );
                $this->contact_model->update_required_contact_fields($updated_required_id,$survey_id, $data);
            }
        }



        //reset old visible fields
        $this->contact_model->update_visible_fields_survey(1,$survey_id, array('is_visible' => 0));
        //update the visible field
        if ($updated_visibility_ids) {
            foreach ($updated_visibility_ids as $updated_visibility_id) {
                $data = array(
                    'is_visible' => 1,
                );
                $this->contact_model->update_required_contact_fields($updated_visibility_id,$survey_id, $data);
            }
        }




    }

    /**
     * This function handles the ajax request of viewing new template design
     */
    public function preview_new_template() {
        $template_name = $_POST['template'];
        $fontfamily = !empty($_POST['font']) ? $_POST['font'] : "";
        $fontcolor = !empty($_POST['fontcolor']) ? $_POST['fontcolor'] : "";
        $logo_name = !empty($_POST['logo']) ? $_POST['logo'] : "";
        $img = "";

        if (!empty($logo_name)) {
            $img = '<img src="' . base_url() . 'uploads/logo/' . $logo_name . '" height="60" width="111" style="margin:10px;" />';
        }

        echo $template_design = '<div>
<div class=tmp-name>' . $template_name . '</div>
    <div style="height:415px;width:600px;background-size:cover;background-image:url(' . base_url() . '/uploads/template/' . $template_name . ');background-repeat:no-repeat">' . $img .
        '<p class="text-description" style="font-size:14px; margin:10px;color:#' . $fontcolor . ';font-family:' . $fontfamily . '" >Sample text font family and color demo</p>
                             </div></div>';
    }

    /**
     * This function handles the ajax activating/deactivating business card
     */
    public function card_status() {
        $survey_id = $_POST['survey_id'];
        $status = $_POST['status'];
        $this->load->model('survey/survey_model');
        $survey_detail = $this->survey_model->get_survey($survey_id);
        $existing_contacts = unserialize($survey_detail->data_collection_method_id);
        $type = $_POST['type'];

        //remove business card/badge collection method from a survey
        if ($type == "bcard") {
            if ($status == "deactivate") {
                foreach ($existing_contacts as $k => $contact) {
                    if ($contact == 2) {
                        unset($existing_contacts[$k]);
                        break;
                    }
                }
            } else {
                $existing_contacts [] = 2; // 2 inidcates the id of business card contact collection type
            }
        } else {
            if ($status == "deactivate") {
                foreach ($existing_contacts as $k => $contact) {
                    if ($contact == 3) {
                        unset($existing_contacts[$k]);
                        break;
                    }
                }
            } else {
                $existing_contacts [] = 3; // 3 inidcates the id of badgescan contact collection type
            }
        }
        $data = array(
            'data_collection_method_id' => serialize($existing_contacts),
        );
        $this->survey_model->update($survey_id, $data);
    }

    /**
     * This function handles the user exists check via ajax 
     */
    public function check_email_exists() {
        $email = $_POST['email'];
        $status = $this->user_model->is_already_registered($email);
        if ($status) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * This function adds the user in the particular survey 
     */
    public function user_authorize() {
        $survey_id = $_POST['survey_id'];
        $user_ids = isset($_POST['user_id']) ? $_POST['user_id'] : "";
        $this->load->model('permission/permission_model');
        $this->load->model('user/user_model');
        $previous_priveleges = $this->permission_model->get_survey_privelege($survey_id);
        //remove all the past records
        $this->permission_model->delete_priveledge($survey_id);
        if (!empty($user_ids)) {

            $past_priveleges = array();
            if (!empty($previous_priveleges)) {
                foreach ($previous_priveleges as $previous_privelege) {
                    $past_priveleges [$previous_privelege->user_id] = $previous_privelege->permission_id;
                }
            }

            foreach ($user_ids as $id) {
                $permission = 0; // no permission assigned 
                if (array_key_exists($id, $past_priveleges)) {
                    $permission = $past_priveleges[$id];
                }
                $this->permission_model->save_priveledge(array('questionnaire_id' => $survey_id, 'user_id' => $id, 'permission_id' => $permission));
            }

            $updated_records = $this->permission_model->get_survey_privelege($survey_id);
            $final_response = array();
            foreach ($updated_records as $record) {
                $user = new stdClass();
                $user_details = $this->user_model->get_user($record->user_id);
                $user->email = $user_details->email;
                $user->id = $user_details->id;
                $user->permission_id = $record->permission_id;
                $final_response [] = $user;
            }
            echo json_encode($final_response);
        }
    }

    /**
     * This function adds the new language to a particular survey
     */
    public function add_lang() {
        $survey_id = $_POST['survey_id'];
        $language = $_POST['lang'];
        $this->load->model('survey/survey_model');
        $this->load->model('language/language_model');
        $survey_detail = $this->survey_model->get_survey($survey_id);
        if ($survey_detail->language_id == 0) {
            $data_lang = array(
                'language_id' => $language,
            );
            $this->survey_model->update($survey_id, $data_lang);
            $lang_detail = $this->language_model->get_language($language);
            echo $lang_detail->name;
        }
    }

}

