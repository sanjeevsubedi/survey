<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('question/question_model');
        $this->load->model('answer/answer_model');
        $this->load->model('survey/survey_model');
        $this->load->model('file/file_model');
        $this->load->helper('randomnumber');
        $this->load->helper('permission');
        $this->load->model('permission/permission_model');
    }

    public function add_missing_responses($survey_id){
         //insert empty response for newly inserted questions for users who has already taken survey
       
        $ex_questions = array();
        $unanswered_questions = array();

        $this->load->model('surveyreport/survey_response_model');
        $existing_survey_users = $this->survey_response_model->get_surveyresponse_details($survey_id);
       
        if(!empty($existing_survey_users)) {   
            
            $existing_questions = $this->question_model->get_quiestions_questionnaire($survey_id);
            if(!empty($existing_questions)) {

                foreach($existing_questions as $ex_question) {
                    $ex_questions [] = $ex_question->question_id;
                }


                foreach($existing_survey_users as $ex_user) {
                    $answered_question_ids = $this->survey_response_model->get_answered_question($ex_user->survey_user_id);
                    $unanswered_questions = array_diff($ex_questions, $answered_question_ids);
                    
                    if(!empty($unanswered_questions)){
                         foreach($unanswered_questions as $unanswered_question) {
                            // insert into survey_question table
                            $insert_data = array('questionnaire_id' => $survey_id, 'survey_user_id' => $ex_user->survey_user_id, 'question_id' => $unanswered_question, 'question_comment' => '');
                            $this->survey_model->save($insert_data, 'survey_question');

                        }
                    }
                }

            }
        }
    }

    /**
     * This function displays and manages the question add form
     */
    public function add() {
        load_js('fancybox');

        //sets the accordion flag to true
        $this->template->write('accordion_disable_flag', TRUE);

        $survey_id = $this->uri->segment(3);
        $question_type_id = $this->uri->segment(4);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid question type
        if (!$this->question_model->is_valid_question_type_reqest($question_type_id)) {
            redirect(base_url() . 'survey/all');
        }

        //check the necessary permission
        permission_helper($survey_id);

        $data['type_class'] = $this->question_model->load_class($question_type_id);
        $qtype = $this->question_model->get_questiontype_row($question_type_id);
        $data['qtype'] = $qtype->nice_name;
        $data['eid'] = $survey_id;
        $data['question_type'] = $question_type_id;
        $data['data_entry_types'] = $this->question_model->get_data_entry_type(NULL, FALSE);
        $data['total_answers'] = 1;
        $data['total_answers_h'] = 1;
        $data['total_answers_v'] = 1;
        $view = $this->question_model->load_question($question_type_id);
        $survey_details = $this->survey_model->get_survey($survey_id);
        if ($_POST) {
            switch ($question_type_id) {
                //single choice and multi choice data processing
                case 1:
                case 3 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');
                    $this->form_validation->set_rules('answers[]', 'Answer', 'required');

                    $data['total_answers'] = count($this->input->post('answers'));
                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {

                        $question = $this->input->post('question');
                        $answers = $this->input->post('answers');
                        $subanswers = $this->input->post('subanswers');
                        $data_entry_type = $this->input->post('data_entry_type');
                        $sub_ans_data_entry_type_sub = $this->input->post('data_entry_type_sub');

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => $data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => 0,
                            'language_id' => $survey_details->language_id,
                            'translation_set_id' => 0,
                        );

                        //saves question
                        $question_id = $this->question_model->save($data);

                        //saves answers
                        if (!empty($answers)) {
                            $final_answer = array('options' => $answers);
                            $this->answer_model->save(array(
                                'question_id' => $question_id,
                                'name' => serialize($final_answer),
                            ));

                            //saves subanswer
                            if (!empty($subanswers)) {
                                foreach ($subanswers as $k => $subanswer) {
                                    if (!empty($subanswer)) {
                                        $answer_array = explode("\n", $subanswer);
                                        foreach ($answer_array as $sa => $answer) {
                                            $this->db->insert("sub_answer", array(
                                                'name' => $answer,
                                                'question_id' => $question_id,
                                                'answer_id' => $k,
                                                'order'=> $sa
                                            ));
                                        }

                                        //insert the data entry type of subanswer
                                        if ($sub_ans_data_entry_type_sub) {
                                            $data_sub_data_entry = array('question_id' => $question_id,
                                                'answer_id' => $k, 'data_entry_type_id' => $sub_ans_data_entry_type_sub[$k]);
                                            $this->answer_model->save_subans_data_entry_type($data_sub_data_entry);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;

                //pull down    
                case 7 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('answers[]', 'Answer', 'required');

                    $data['total_answers'] = count($this->input->post('answers'));
                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {
                        $question = $this->input->post('question');
                        $answers = $this->input->post('answers');

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 4,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => 0,
                            'language_id' => $survey_details->language_id,
                            'translation_set_id' => 0,
                        );

                        //saves question
                        $question_id = $this->question_model->save($data);

                        //saves answers
                        if (!empty($answers)) {
                            $final_answer = array('options' => $answers);
                            $this->answer_model->save(array(
                                'question_id' => $question_id,
                                'name' => serialize($final_answer),
                            ));
                        }
                    }
                    break;

                //scale question type data processing
                case 2:
                    $radio_option = $this->input->post('range_option');
                    $from_value = $this->input->post('from');
                    $to_value = $this->input->post('to');

                    $custom_range_values = $this->input->post('custom_value');
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('range_option', 'Range', 'required');

                    if ($radio_option == 1) {
                        if (!$from_value) {
                            $this->form_validation->set_rules('from', 'From', 'required');
                        }
                        if (!$to_value) {
                            $this->form_validation->set_rules('to', 'To', 'required');
                        }
                        $answer_key = 'range';
                        $answer_value = $from_value . ',' . $to_value;
                    } else if ($radio_option == 2) {
                        if (!$custom_range_values) {
                            $this->form_validation->set_rules('custom_value', 'Range interval', 'required');
                        }
                        $answer_key = 'interval';
                        $answer_value = $custom_range_values;
                    }

                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {

                        $question = $this->input->post('question');

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 5, //data type is none
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => 0,
                            'language_id' => $survey_details->language_id,
                            'translation_set_id' => 0,
                        );

                        //saves question
                        $question_id = $this->question_model->save($data);

                        //saves answer
                        if ((!empty($answer_key)) && (!empty($answer_value))) {
                            $serialized_ans = serialize(array($answer_key => $answer_value));
                            $this->answer_model->save(array(
                                'question_id' => $question_id,
                                'name' => $serialized_ans,
                            ));
                        }
                    }

                    break;
                //textbox question type data processing
                case 6 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');

                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {

                        $question = $this->input->post('question');
                        //$data_entry_type = $this->input->post('data_entry_type');

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 1, //$data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => 0,
                            'language_id' => $survey_details->language_id,
                            'translation_set_id' => 0,
                        );

                        //saves question
                        $question_id = $this->question_model->save($data);
                    }
                    break;
                //contingency table data processing
                case 4 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');
                    $this->form_validation->set_rules('h_answers[]', 'Horizontal Answer', 'required');
                    $this->form_validation->set_rules('v_answers[]', 'Vertical Answer', 'required');

                    $data['total_answers_h'] = count($this->input->post('h_answers'));
                    $data['total_answers_v'] = count($this->input->post('v_answers'));
                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {

                        $question = $this->input->post('question');
                        $h_answers = $this->input->post('h_answers');
                        $v_answers = $this->input->post('v_answers');
                        $data_entry_type = $this->input->post('data_entry_type');
                        //$position = $this->input->post('answer_id');
                        //if selectbox is choosen
                        if ($data_entry_type == 4) {
                            $selectbox_options = $this->input->post('options');
                        }

                        $final_answers = array("h_answer" => $h_answers, "v_answer" => $v_answers, "select_options" => $selectbox_options);

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => $data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => 0,
                            'language_id' => $survey_details->language_id,
                            'translation_set_id' => 0,
                        );

                        //saves question
                        $question_id = $this->question_model->save($data);

                        //add missing responses if survey has already started
                        $this->add_missing_responses($survey_id);

                        //saves answers
                        if (!empty($final_answers)) {
                            $this->answer_model->save(array(
                                'question_id' => $question_id,
                                'name' => serialize($final_answers),
                            ));
                        }
                    }
                    break;
            }
            //upload multiple question and answer files
            if (!empty($question_id)) {
                foreach ($_FILES as $field_name => $value) {
                    $upload_dir_name = ($field_name == 'question_documents') ? 'question' : 'answer';
                    foreach ($value['name'] as $k => $file_name) {
                        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
                        $temp_file = $value['tmp_name'][$k];
                        if (@is_uploaded_file($temp_file)) {
                            $upload_dir = UPLOADPATH . DS . $upload_dir_name . DS;
                            $rand_file_name = $this->file_model->get_duplicate_file($file_name);
                            //$rand_file_name = get_random_string(16) . "." . $file_ext;
                            @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);

                            if ($field_name == 'question_documents') {
                                $file_data_q = array('question_id' => $question_id, 'name' => $rand_file_name);
                                $this->file_model->save_question_file($file_data_q);
                            } else {
                                //document position of contingency answers
                                /* if ($question_type_id == 4) {
                                  $k = $position[$k];
                                  } */
                                $file_data_a = array('answer_id' => $k, 'question_id' => $question_id, 'name' => $rand_file_name);
                                $this->file_model->save_answer_file($file_data_a);
                            }
                        }
                    }
                }
            }
            $this->session->set_flashdata('success', 'Question added successfully');
            
            //add blank responses for the old survey users if there are any
            
            /*$existing_users = $this->survey_response_model->get_survey_users($survey_id); // who already took survey
         
            if(!empty($existing_users)) {
                foreach($existing_users as $existing_user) {
                     $insert_data_new = array('questionnaire_id' => $survey_id, 'survey_user_id' => $existing_user, 'question_id' => $question_id, 'question_comment' => '');
                     $this->survey_model->save($insert_data_new, 'survey_question');
                }
            }*/
            
            //$more = $this->input->post('more');
            //$done = $this->input->post('done');
            $track_btn = $this->input->post('track_btn');
            if ($track_btn == 'another') {
                redirect(base_url() . 'survey/step_5/' . $survey_id);
            }
            redirect(base_url() . 'question/order/' . $survey_id);
        } else {
            $this->template->write_view('content', $view, $data);
            $this->template->render();
        }
    }

    /**
     * This function displays and manages the question edit form
     */
    public function update() {
        //sets the accordion flag to false
        $this->template->write('accordion_disable_flag', FALSE);
        load_js('fancybox');
        $survey_id = $this->uri->segment(3);
        $question_type_id = $this->uri->segment(4);
        $question_id = $this->uri->segment(5);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid question type
        if (!$this->question_model->is_valid_question_type_reqest($question_type_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid question type and prevents the url tampering
        if (!$this->question_model->is_valid_question_reqest($question_id)) {
            redirect(base_url() . 'survey/all');
        }

        //check the necessary permission
        permission_helper($survey_id);

        $data['type_class'] = $this->question_model->load_class($question_type_id);
        $qtype = $this->question_model->get_questiontype_row($question_type_id);
        $data['qtype'] = $qtype->nice_name;
        $data['eid'] = $survey_id;
        $data['question_type'] = $question_type_id;
        $data['question_id'] = $question_id;
        $data['data_entry_types'] = $this->question_model->get_data_entry_type(NULL, FALSE);

        $question_detail = $this->question_model->get_question($question_id);
        $data['question'] = $question_detail;

        $data['q_resources'] = $this->file_model->get_resources_question($question_id);
        $data['a_resources'] = $this->file_model->get_resources_answer($question_id, 0);
        $answer_arr = $this->answer_model->get_answers_unserialized($question_id, $question_type_id);
        switch ($question_type_id) {
            case 1:
            case 3:
                $answer_docs = array();
                $whole_answer = array();
                $data_entry_sub_answer = array();
                if (!empty($answer_arr)) {
                    foreach ($answer_arr['options'] as $k => $v) {
                        $subans = $this->answer_model->get_subanswer($question_id, $k);
                        $subans = implode("\n", $subans);
                        $whole_answer [] = array($v => $subans);
                        $data_entry_sub_answer[] = $this->answer_model->get_subanswer_dataentry_type($question_id, $k);

                        //store documents of every answer along with answers
                        $file_res = $this->file_model->get_resources_answer($question_id, $k);

                        //prepare answer object
                        $ans_obj = new stdClass();
                        $ans_obj->id = $k;
                        $ans_obj->name = $v;

                        $answer_docs [] = array('ans' => $ans_obj, 'docs' => $file_res);
                    }
                }
                $data['subans_data_entry'] = $data_entry_sub_answer;
                $data['answers'] = $whole_answer;
                $data['is_other'] = !empty($answer_arr['is_other']) ? $answer_arr['is_other'] : "";
                $data['a_resources'] = $answer_docs;
                break;
            case 7:
                $answer_docs = array();
                $whole_answer = array();
                $data_entry_sub_answer = array();
                if (!empty($answer_arr)) {
                    foreach ($answer_arr['options'] as $k => $v) {
                        $whole_answer [] = array($v => "");
                        //store documents of every answer along with answers
                        $file_res = $this->file_model->get_resources_answer($question_id, $k);

                        //prepare answer object
                        $ans_obj = new stdClass();
                        $ans_obj->id = $k;
                        $ans_obj->name = $v;

                        $answer_docs [] = array('ans' => $ans_obj, 'docs' => $file_res);
                    }
                }
                $data['answers'] = $whole_answer;
                $data['is_other'] = !empty($answer_arr['is_other']) ? $answer_arr['is_other'] : "";
                $data['a_resources'] = $answer_docs;
                break;
            case 2:
                $data['interval'] = "";
                $data['start'] = "";
                $data['end'] = "";
                if (!empty($answer_arr)) {
                    $data['type'] = key($answer_arr);
                    if (key($answer_arr) == "range") {
                        $range_detail = explode(",", $answer_arr['range']);
                        $data['start'] = $range_detail[0];
                        $data['end'] = $range_detail[1];
                    } elseif (key($answer_arr) == "interval") {
                        $data['interval'] = $answer_arr['interval'];
                    }
                }
                break;
            case 4:
                $v_answers = $answer_arr['v_answer'];
                $h_answers = $answer_arr['h_answer'];
                $data['h_answers'] = $h_answers;
                $data['v_answers'] = $v_answers;
                $data['is_other'] = !empty($answer_arr['is_other']) ? $answer_arr['is_other'] : "";
                //$data['answers'] = $answer_arr;
                //only shown for select box
                if ($question_detail->data_entry_type_id == 4) {
                    $data['select_options'] = isset($answer_arr['select_options']) ? $answer_arr['select_options'] : '';
                }

                $contingency_middle = '<div class="c-doc_container">';
                $horizontal_container = '<div class="h-cont">';
                $vertical_container = '<div class="v-cont">';
                $iteration = false;

                foreach ($v_answers as $vk => $v_answer) {
                    foreach ($h_answers as $hk => $h_answer) {
                        if (!$iteration) {
                            $horizontal_container .= '<div class="hans' . $hk . '">' . $h_answer . '</div>';
                        }
                        $answer_docs = $this->file_model->get_resources_answer($question_id, $vk . '_' . $hk);
                        $output = "";
                        if (!empty($answer_docs->name)) {
                            $output = anchor(base_url() . DS . 'uploads' . DS . 'answer' . DS .
                                    $answer_docs->name, $answer_docs->name, array('target' => '_blank',
                                'title' => $answer_docs->name));
                            $doc_id = $answer_docs->id;
                            $remove = anchor("#", 'Remove', array('rel' => 'a',
                                'class' => 'c-docs', 'id' => $answer_docs->id, 'title' => $answer_docs->name));
                        } else {
                            $doc_id = "";
                            $remove = "";
                            $output = '<div class="filetype-wrapper multipleDocumentAns">
<input type="file" name="answer_documents[' . $vk . '_' . $hk . ']" /></div>';
                        }
                        $contingency_middle .= '<div class="doc-contingency doc' . $vk . '_' . $hk . '" id="del-answer-doc-' . $doc_id . '">'
                                . $output . '<div class="rem">' . $remove . '</div>' .
                                '<input type="hidden" name="answer_id[]" value="' . $vk . '_' . $hk . '"/></div>';
                    }
                    $contingency_middle .= "<div class='clearfix'></div>";

                    $vertical_container .= '<div class="vans' . $vk . '">' . $v_answer . '</div>';
                    $iteration = true;
                }

                $horizontal_container .= '</div>';
                $vertical_container .= '</div>';
                $data['c_docs'] = '<div>' . $horizontal_container .
                        '<div class="clearfix"></div><div class="v-d-container">' . $vertical_container .
                        $contingency_middle . '</div></div></div>';

                break;
        }

        $view = $this->question_model->load_question($question_type_id, "edit");

        if ($_POST) {
            switch ($question_type_id) {
                //single choice and multi choice data processing
                case 1:
                case 3 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');
                    $this->form_validation->set_rules('answers[]', 'Answer', 'required');
                    $this->form_validation->set_rules('subanswers[]', 'Answer', 'trim');

                    $data['total_answers'] = count($this->input->post('answers'));
                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {
                        $question = $this->input->post('question');
                        $answers = $this->input->post('answers');
                        $subanswers = $this->input->post('subanswers');
                        $data_entry_type = $this->input->post('data_entry_type');
                        //$sub_ans_data_entry_type_sub = $this->input->post('data_entry_type_sub');
                        $is_other = $this->input->post('others');
                        $q_other = $is_other ? 1 : 0; //if any option has other then it's enabled for question

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => $data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => $question_detail->is_compulsory,
                            'other' => $q_other,
                        );

                        //saves question
                        $this->question_model->update($question_id, $data);

                        //saves answers
                        if (!empty($answers)) {
                            $final_answer = array('options' => $answers, 'is_other' => $is_other);
                            $this->answer_model->update_answer($question_id, array(
                                'name' => serialize($final_answer),
                            ));

                            //deletes old subanswers
                            $this->answer_model->delete_subanswer($question_id);

                            //deletes old subanswers data entry type
                            $this->answer_model->delete_subanswer_data_entry_type($question_id);

                            //saves subanswer
                            foreach ($subanswers as $k => $subanswer) {
                                if (!empty($subanswer)) {
                                    $answer_array = explode("\n", $subanswer);
                                    foreach ($answer_array as $sa => $answer) {
                                        $this->answer_model->save_subanswer(array(
                                            'name' => $answer,
                                            'question_id' => $question_id,
                                            'answer_id' => $k,
                                            'order'=> $sa
                                        ));
                                    }

                                    //insert the data entry type of subanswer
                                    $sub_ans_data_entry_type_sub = $this->input->post('data_entry_type_sub' . $k);

                                    if ($sub_ans_data_entry_type_sub) {
                                        $data_sub_data_entry = array('question_id' => $question_id,
                                            'answer_id' => $k, 'data_entry_type_id' => $sub_ans_data_entry_type_sub
                                                /* $sub_ans_data_entry_type_sub[$k] */                                                );
                                        $this->answer_model->save_subans_data_entry_type($data_sub_data_entry);
                                    }
                                }
                            }
                        }
                    }
                    break;
                //pull down    
                case 7 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('answers[]', 'Answer', 'required');

                    $data['total_answers'] = count($this->input->post('answers'));
                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {
                        $question = $this->input->post('question');
                        $answers = $this->input->post('answers');
                        $is_other = $this->input->post('others');
                        $q_other = $is_other ? 1 : 0; //if any option has other then it's enabled for question
                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 4, // selectbox
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => $question_detail->is_compulsory,
                            'other' => $q_other,
                        );

                        //saves question
                        $this->question_model->update($question_id, $data);

                        //saves answers
                        if (!empty($answers)) {
                            $final_answer = array('options' => $answers, 'is_other' => $is_other);
                            $this->answer_model->update_answer($question_id, array(
                                'name' => serialize($final_answer),
                            ));
                        }
                    }
                    break;

                //textbox question type data processing
                case 6 :
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');

                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {

                        $question = $this->input->post('question');
                        //$data_entry_type = $this->input->post('data_entry_type');

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 1, //$data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => $question_detail->is_compulsory,
                        );

                        //saves question
                        $this->question_model->update($question_id, $data);
                    }
                    break;
                //scale question type data processing
                case 2:
                    $radio_option = $this->input->post('range_option');
                    $from_value = $this->input->post('from');
                    $to_value = $this->input->post('to');

                    $custom_range_values = $this->input->post('custom_value');
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('range_option', 'Range', 'required');

                    if ($radio_option == 1) {
                        if (!$from_value) {
                            $this->form_validation->set_rules('from', 'From', 'required');
                        }
                        if (!$to_value) {
                            $this->form_validation->set_rules('to', 'To', 'required');
                        }
                        $answer_key = 'range';
                        $answer_value = $from_value . ',' . $to_value;
                    } else if ($radio_option == 2) {
                        if (!$custom_range_values) {
                            $this->form_validation->set_rules('custom_value', 'Range interval', 'required');
                        }
                        $answer_key = 'interval';
                        $answer_value = $custom_range_values;
                    }

                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {
                        $question = $this->input->post('question');
                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => 5, //data type is none
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => $question_detail->is_compulsory,
                        );

                        //saves question
                        $this->question_model->update($question_id, $data);

                        //saves answer
                        if ((!empty($answer_key)) && (!empty($answer_value))) {
                            $serialized_ans = serialize(array($answer_key => $answer_value));
                            $this->answer_model->update_answer($question_id, array(
                                'name' => $serialized_ans,
                            ));
                        }
                    }
                    break;
                //contigency table question type data processing
                case 4:
                    $this->form_validation->set_rules('question', 'Question', 'required');
                    $this->form_validation->set_rules('data_entry_type', 'Data entry', 'trim');
                    $this->form_validation->set_rules('h_answers[]', 'Horizontal Answer', 'required');
                    $this->form_validation->set_rules('v_answers[]', 'Vertical Answer', 'required');

                    if ($this->form_validation->run() == FALSE) {
                        $this->template->write_view('content', $view, $data);
                        $this->template->render();
                    } else {
                        $question = $this->input->post('question');
                        $h_answers = $this->input->post('h_answers');
                        $v_answers = $this->input->post('v_answers');
                        $data_entry_type = $this->input->post('data_entry_type');
                        $is_other = $this->input->post('others');
                        $q_other = $is_other ? 1 : 0; //if any option has other then it's enabled for question
                        //if selectbox is choosen
                        if ($data_entry_type == 4) {
                            $selectbox_options = $this->input->post('options');
                        }
                        $final_answers = array("h_answer" => $h_answers, "v_answer" => $v_answers, "select_options" => $selectbox_options, 'is_other' => $is_other);

                        $data = array(
                            'name' => $question,
                            'questionnaire_id' => $survey_id,
                            'data_entry_type_id' => $data_entry_type,
                            'question_type_id' => $question_type_id,
                            'is_compulsory' => $question_detail->is_compulsory,
                            'other' => $q_other,
                        );

                        //saves question
                        $this->question_model->update($question_id, $data);

                        //saves answers
                        if (!empty($final_answers)) {
                            $this->answer_model->update_answer($question_id, array(
                                'name' => serialize($final_answers),
                            ));
                        }
                    }
                    break;
            }

            //upload multiple question files and answer files.
            if (!empty($question_id)) {
                $hidden_ans_id = $this->input->post('answer_id');
                foreach ($_FILES as $field_name => $value) {
                    $upload_dir_name = ($field_name == 'question_documents') ? 'question' : 'answer';
                    //$type = ($field_name == 'question_documents') ? 'q' : 'a';
                    foreach ($value['name'] as $k => $file_name) {
                        $file_ext = substr($file_name, strrpos($file_name, '.') + 1);
                        $temp_file = $value['tmp_name'][$k];
                        if (@is_uploaded_file($temp_file)) {
                            $upload_dir = UPLOADPATH . DS . $upload_dir_name . DS;
                            $rand_file_name = $this->file_model->get_duplicate_file($file_name);
                            //$rand_file_name = get_random_string(16) . "." . $file_ext;
                            @move_uploaded_file($temp_file, $upload_dir . $rand_file_name);

                            if ($field_name == 'question_documents') {
                                $file_data_q = array('question_id' => $question_id, 'name' => $rand_file_name);
                                $this->file_model->save_question_file($file_data_q);
                            } else {
                                $file_data_a = array('answer_id' => $k, 'question_id' => $question_id, 'name' => $rand_file_name);
                                $this->file_model->save_answer_file($file_data_a);
                            }
                        }
                    }
                }
            }

            $this->session->set_flashdata('success', 'Question updated successfully');
            //$more = $this->input->post('more');
            //$done = $this->input->post('done');
            $track_btn = $this->input->post('track_btn');
            if ($track_btn == 'another') {
                redirect(base_url() . 'survey/step_5/' . $survey_id);
            }
            redirect(base_url() . 'question/order/' . $survey_id);
        } else {
            $this->template->write_view('content', $view, $data);
            $this->template->render();
        }
    }

    /**
     * This function gives the interface for ordering the question
     */
    public function order() {
        $survey_id = $this->uri->segment(3);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }
        //check the necessary permission
        permission_helper($survey_id);

        //unset 
        $this->session->unset_userdata('accordion');

        $data['eid'] = $survey_id;
        $data['questions'] = $this->question_model->get_quiestions_questionnaire($survey_id);
        $compulsory_questions = $this->question_model->get_compulsory_question($survey_id);
        $compulsory_question_ids = array();
        foreach ($compulsory_questions as $compulsory_question) {
            $compulsory_question_ids [] = $compulsory_question->id;
        }
        $data['compulsory'] = $compulsory_question_ids;
        if ($_POST) {
            // reset old compulsory value to 0
            $this->question_model->update_question_survey($survey_id, array("is_compulsory" => 0));

            $compulsory = $question = $this->input->post('required');
            if ($compulsory) {
                foreach ($compulsory as $id) {
                    $update_data = array("is_compulsory" => 1);
                    $this->question_model->update($id, $update_data);
                }
            }
            //$this->session->set_flashdata('success', 'Question updated successfully');
            redirect(base_url() . 'file/show_docs/' . $survey_id);
        } else {
            $this->template->write_view('content', 'order', $data);
            $this->template->render();
        }
    }

    /**
     * This function gives the tooltip for sample question
     */
    public function preview_question_type() {
        $this->load->helper('preview');
        $survey = $_GET['survey_id'];
        $question_type = $_GET['question_type'];
        echo preview_question_type($survey, $question_type);
    }

    /**
     * This function gives the tooltip for various data entry types
     */
    public function preview_dataentry_type() {
        $dat_entry_type = $_GET['data_entry_type'];
        $question_type = $_GET['question_type'];
        $this->load->helper('preview_dataentry');
        echo preview_data_entry_type($dat_entry_type, $question_type);
    }

    /**
     * This function deletes the questions and all its related stuffs
     */
    public function delete() {
        $survey_id = $this->uri->segment(3);
        $question_id = $this->uri->segment(4);
        //this step verifies the valid survey
        if (!$this->survey_model->is_valid_survey_reqest($survey_id)) {
            redirect(base_url() . 'survey/all');
        }

        //verifies the valid question and prevents the url tampering
        if (!$this->question_model->is_valid_question_reqest($question_id)) {
            redirect(base_url() . 'survey/all');
        }
        //check the necessary permission
        permission_helper($survey_id);

        //delete quesiton and answer resources
        $file_results = $this->file_model->get_resources_question($question_id);
        foreach ($file_results as $file_result) {
            @unlink(UPLOADPATH . DS . 'question' . DS . $file_result->name);
        }

        //deletes all the answer files
        $file_results = $this->file_model->get_resources_answerall($question_id);
        foreach ($file_results as $file_result) {
            @unlink(UPLOADPATH . DS . 'answer' . DS . $file_result->name);
        }

        //delete question records
        $this->question_model->delete($question_id);
        $this->session->set_flashdata('success', 'question deleted successfully');
        redirect(base_url() . 'question/order/' . $survey_id);
    }

}

