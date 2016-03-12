<?php

/**
 * Helper to return row data
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Mar 27, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!function_exists('extract_row_data')) {

    function extract_row_data($survey_id, $survey_user_id, $sheet, $j) {
        $ci = &get_instance();
        $i = 0; // initialize alphabet index

        $ci->load->library('encrypt');

        $other_details = $ci->survey_response_model->get_survey_other_details($survey_id, $survey_user_id);
        $uploader = $ci->user_model->get_user(@$other_details->user_id);
       
        $row_data = "<td>" . date("Y-m-d H:i:s", @$other_details->lead_generated_date) . "</td>";
        $sheet->setCellValue(get_alphabet($i) . $j, date("Y-m-d H:i:s", @$other_details->lead_generated_date)); //set cell value

        $row_data .= "<td>" . date("Y-m-d H:i:s", @$other_details->uploaded_date) . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, date("Y-m-d H:i:s", @$other_details->uploaded_date)); //set cell value

        $row_data .= "<td>$survey_user_id</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, $survey_user_id); //set cell value

        $row_data .= "<td>".@$other_details->guid."</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->guid); //set cell value

        $row_data .= "<td>".@$other_details->barcode_content."</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->barcode_content); //set cell value
        
        $row_data .= "<td>$uploader->email</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, $uploader->email); //set cell value
        //survey contact details
        $contact_details = $ci->survey_response_model->get_survey_contact_details($survey_id, FALSE, $survey_user_id);
        foreach ($contact_details as $value) {
            $row_data.= "<td>" . $ci->encrypt->decode($value->value) . "</td>";
            $sheet->setCellValue(get_alphabet(++$i) . $j, $ci->encrypt->decode($value->value)); //set cell value
        }
        //contact other details
        //$other_details = $ci->survey_response_model->get_survey_other_details($survey_id, $survey_user_id);
        $row_data.= "<td>" . @$other_details->device_identifier . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->device_identifier); //set cell value

        $row_data.= "<td><a target = '_blank' href='" . @$other_details->card_image . "'>" . @$other_details->card_image . "</a></td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->card_image); //set cell value
        //$row_data.= "<td><a target = '_blank' href='" . base_url() . 'app_uploads/original/' . @$other_details->original_image . "'>" . @$other_details->original_image . "</a></td>";
        //$sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->original_image); //set cell value

        $row_data.= "<td>" . @$other_details->telemarketing_qualification . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->telemarketing_qualification); //set cell value

        $row_data.= "<td>" . @$other_details->assign_to . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->assign_to); //set cell value

        $row_data.= "<td>" . str_replace('\n', '<br/>', @$other_details->survey_comment) . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, str_replace('\n', '<br/>', @$other_details->survey_comment)); //set cell value

        
        //multiple images
        $cmt_images = !empty($other_details->comment_image) ? explode('\n',$other_details->comment_image) : $other_details->comment_image;
        //var_dump($cmt_images);die;
        $cmt_images_output = "";

        if(!empty($cmt_images)) {
            foreach($cmt_images as $cmt_image){
                $cmt_images_output .= "<a target = '_blank' href='" . $cmt_image . "'>" . $cmt_image . "</a><br/>";
            }
        }

        $row_data.= "<td>".$cmt_images_output."</td>";
        //$row_data.= "<td><a target = '_blank' href='" . @$other_details->comment_image . "'>" . @$other_details->comment_image . "</a></td>";
        

        $sheet->setCellValue(get_alphabet(++$i) . $j, str_replace('\n', '<br/>', @$other_details->comment_image)); //set cell value

        $row_data.= "<td><a target = '_blank' href='" . @$other_details->comment_audio . "'>" . @$other_details->comment_audio . "</a></td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->comment_audio); //set cell value

        $row_data.= "<td>" . @$other_details->receive_email . "</td>";
        $sheet->setCellValue(get_alphabet(++$i) . $j, @$other_details->receive_email); //set cell value
        //question answer details
        $question_details = $ci->survey_response_model->get_survey_questions_details($survey_id, FALSE, $survey_user_id,TRUE);
        foreach ($question_details as $value) {
            $answer_result = get_question_answer($survey_id, $survey_user_id, $value->question_id, $value->other);
            $row_data.= "<td>" . $answer_result['answer'] . "</td>";
            $sheet->setCellValue(get_alphabet(++$i) . $j, $answer_result['answer']); //set cell value

            if ($value->other) {
                $row_data.= "<td>" . $answer_result['other'] . "</td>";
                $sheet->setCellValue(get_alphabet(++$i) . $j, !empty($answer_result['other']) ? $answer_result['other'] : ""); //set cell value
            }
            $row_data.= "<td>".str_replace('\n', '<br/>', $value->question_comment)."</td>";
            $sheet->setCellValue(get_alphabet(++$i) . $j, str_replace('\n', '<br/>', $value->question_comment)); //set cell value
        }
        return $row_data;
    }

}

if (!function_exists('get_question_answer')) {

    function get_question_answer($survey_id, $survey_user_id, $question_id, $other) {
        $final_answer = "";
        $other_answer = "";
        $results = array();
        $ci = &get_instance();
        $user_answer = $ci->survey_response_model->get_survey_question_answer($survey_id, $survey_user_id, $question_id);
        if (!empty($user_answer)) {
            $answer = $ci->survey_response_model->get_question_answer($question_id);
            $ans_options = @unserialize($answer->answer);
            switch (@$answer->question_type_id) {
                case 2:
                    /* $range_options = @$ans_options['range'];
                      $interval_options = @$ans_options['interval'];
                      if ($range_options) {
                      $ans_options = $range_options;
                      } else if ($interval_options) {
                      $ans_options = $interval_options;
                      }
                      $ans_options = explode(",", $ans_options);
                      if ($user_answer->option_id) {
                      $final_answer = $ans_options[$user_answer->option_id];
                      }
                      if ($user_answer->answer) {
                      $other_answer = $user_answer->answer;
                      } */
                    if ($user_answer->answer) {
                        $final_answer = $user_answer->answer;
                    }
                    break;
                case 1:
                case 3 :
                case 7 :
                    $txtsubarray = array();
                    $user_answers = $ci->survey_response_model->get_survey_question_answer($survey_id, $survey_user_id, $question_id, "multiple");
                    $ans_options = $ans_options['options'];
                    foreach ($user_answers as $user_answer) {
                        $final_answer .= $ans_options[$user_answer->option_id];

                        //only for multichoice question having textbox dataentry type
                        if (@$answer->question_type_id == 3 && @$answer->data_entry_type_id == 1) {
                            $final_answer .= '(' . $user_answer->answer . ')';
                        }

                        $temp_stack = $ans_options[$user_answer->option_id];

                        if ($user_answer->sub_answer) {
                            $sub_ans_ids = unserialize($user_answer->sub_answer);
                            if (!empty($sub_ans_ids)) {
                                $final_answer .= ":(";
                                if (is_numeric($sub_ans_ids[0])) {
                                    //$final_answer.= implode(",", $ci->survey_response_model->get_sub_answers($sub_ans_ids, $question_id));
                                      $final_answer.= implode(",", $ci->survey_response_model->get_sub_answers($sub_ans_ids, $question_id,$user_answer->option_id));
                                } else {
                                    foreach ($sub_ans_ids as $sub_ans_id) {
                                        //$subans_name = $ci->survey_response_model->get_sub_answers(array($sub_ans_id->subOpt_id), $question_id);
                                        $subans_name = $ci->survey_response_model->get_sub_answers(array($sub_ans_id->subOpt_id), $question_id,$user_answer->option_id);

                                        if (!empty($subans_name)) {
                                            $txtsubarray [] = $subans_name[0] . ':' . $sub_ans_id->subOpt_value;
                                        }
                                    }
                                    if (!empty($txtsubarray)) {
                                        $final_answer.= implode(",", $txtsubarray);
                                    }
                                }

                                $final_answer .= ")";
                            }
                        }
                        $final_answer.="|";
                        if ($user_answer->answer) {
                            $other_answer.= $temp_stack . ":" . $user_answer->answer . "|";
                        }
                    }
                    $final_answer = substr(trim($final_answer), 0, -1);
                    $other_answer = substr($other_answer, 0, -1);

                    //only for multichoice question having textbox dataentry type there is no other answer
                    if (@$answer->question_type_id == 3 && @$answer->data_entry_type_id == 1) {
                        $other_answer = "";
                    }
                    break;
                case 4:
                    $user_answers = $ci->survey_response_model->get_survey_question_answer($survey_id, $survey_user_id, $question_id, "multiple");
                    $h_answers = $ans_options['h_answer'];
                    $v_answers = $ans_options['v_answer'];
                    foreach ($user_answers as $user_answer) {
                        $final_answer .= $v_answers[$user_answer->option_id];
                        $temp_stack = $v_answers[$user_answer->option_id];
                        if ($user_answer->sub_answer != "") {
                            $final_answer .= "(";
                            $sub_ans_ids = unserialize($user_answer->sub_answer);
                            $sb_ans = array();
                            foreach ($sub_ans_ids as $sub_ans_id) {
                                //selected options for selectbox and textbox shown is shown differently
                                /* if (@$answer->data_entry_type_id != 4) {
                                  $sb_ans[] = $h_answers[$sub_ans_id->id];
                                  } else {
                                  $selected_text = $sub_ans_id->text;
                                  $sb_ans[] = $h_answers[$sub_ans_id->id] . '-' . $selected_text;
                                  } */

                                if (@$answer->data_entry_type_id == 4 || @$answer->data_entry_type_id == 1) {
                                    $selected_text = $sub_ans_id->text;
                                    $sb_ans[] = $h_answers[$sub_ans_id->id] . '-' . $selected_text;
                                } else {
                                    $sb_ans[] = $h_answers[$sub_ans_id->id];
                                }
                            }
                            $final_answer .= implode(",", $sb_ans);
                            $final_answer .= ")";
                        }
                        $final_answer.="|";
                        if ($user_answer->answer) {
                            $other_answer.= $temp_stack . ":" . $user_answer->answer . "|";
                        }
                    }
                    $final_answer = substr(trim($final_answer), 0, -1);
                    $other_answer = substr($other_answer, 0, -1);
                    break;
                case 6:
                    $final_answer = $user_answer->answer;
                    if ($other == 1)
                        $other_answer = $user_answer->answer;
                    break;
            }
        }
        $results['answer'] = $final_answer;
        if ($other == 1)
            $results['other'] = $other_answer;

        return $results;
    }

}


if (!function_exists('save_xls_file')) {

    function save_xls_file($xls, $survey_id, $user) {
        // name of worksheet
        $xls->getActiveSheet()->setTitle("SurveyReport");

        //creating file
        $writer = PHPExcel_IOFactory::createWriter($xls, "Excel5");

        //save file
        $filename = "surveyreport-" . $survey_id . "-" . $user . ".xls";
//        if (file_exists(ROOTPATH . "/xls/" . $filename)) {
//            unlink(ROOTPATH . "/xls/" .$filename); // delete previously created file
//        }
        $writer->save(ROOTPATH . "/xls/" . $filename);
    }

}
?>
