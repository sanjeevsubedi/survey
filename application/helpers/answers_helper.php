<?php

/**
 * Helper to return answer of questions
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date April 2, 2013
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */
if (!function_exists('get_answer')) {

    function get_answer($question) {
        $ci = &get_instance();
        $answer = "";
        $answers = $ci->answer_model->get_answers($question->question_id);
        $ans_options = @unserialize($answers->name);
        switch ($question->question_type_id) {
            case 1:
                $answer = get_answer_block_1($question, $ans_options);
                break;
            case 2:
                $range_options = @$ans_options['range'];
                $interval_options = @$ans_options['interval'];
                if ($range_options) {
                    $ans_options = $range_options;
                    $ans_options = explode(",", $ans_options);
                    $answer = "<div class='sliderRange'><div class='rangeBlock'>";
                    foreach ($ans_options as $value) {
                        $answer .= "<div class='range'>" . $value . "</div>";
                    }
                    $answer .= "</div></div>";
                } else if ($interval_options) {
                    $ans_options = $interval_options;
                    $ans_options = explode(",", $ans_options);
                    $answer = "<div class='sliderRange'><div class='intervalBlock'>";
                    foreach ($ans_options as $value) {
                        $answer .= "<div class='interval'>" . $value . "</div>";
                    }
                    $answer .= "</div></div>";
                }
                break;
            case 3 :
                $answer = get_answer_block_3($question, $ans_options);
                break;
            case 4:
                $answer = get_answer_block_4($question->data_entry_type_id, $ans_options);
                break;
            case 5:

                break;
            case 6:
                $answer = "<input type='text' />";
                break;
        }
        return $answer;
    }

}


if (!function_exists('get_answer_block_1')) {

    function get_answer_block_1($question, $answer_options) {
        $answer = "";
        $ci = &get_instance();

        switch ($question->data_entry_type_id) {
            case 1:

                break;
            case 2:
                $ans_options = $answer_options['options'];
                foreach ($ans_options as $key => $val) {
                    $answer.="<input type='radio'/> " . $val . " ";
                    $sub_answer = $ci->answer_model->get_subanswer($question->question_id, $key);
                    if (!empty($sub_answer)) {
                        $answer.="<div class='sub-answer'>";
                        foreach ($sub_answer as $val1) {
                            $answer.="<input type='checkbox'/><span class='labeL'>" . $val1 . "</span>";
                        }
                        $answer.="</div>";
                    }
                }
                break;
            case 3 :

                break;
            case 4:
                $ans_options = $answer_options['options'];
                $answer.="<select>";
                foreach ($ans_options as $val) {
                    $answer.="<option >" . $val . "</option>";
                }
                $answer.="</select>";
                break;
            case 5:

                break;
        }
        return $answer;
    }

}

if (!function_exists('get_answer_block_3')) {

    function get_answer_block_3($question, $answer_options) {
        $answer = "";
        $ci = &get_instance();
        switch ($question->data_entry_type_id) {
            case 1:

                break;
            case 2:

                break;
            case 3 :
                $ans_options = $answer_options['options'];
                foreach ($ans_options as $key => $val) {
                    $answer.="<input type='checkbox'/><span class='labeL'>" . $val . "</span>";
                    $sub_answer = $ci->answer_model->get_subanswer($question->question_id, $key);
                    if (!empty($sub_answer)) {
                        $answer.="<div class='sub-answer'>";
                        foreach ($sub_answer as $val1) {
                            $answer.="<input type='checkbox'/><span class='labeL'>" . $val1 . "</span>";
                        }
                        $answer.="</div>";
                    }
                }
                break;
            case 4:
                $ans_options = $answer_options['options'];
                $answer.="<select>";
                foreach ($ans_options as $val) {
                    $answer.="<option >" . $val . "</option>";
                }
                $answer.="</select>";
                break;
            case 5:

                break;
        }
        return $answer;
    }

}

if (!function_exists('get_answer_block_4')) {

    function get_answer_block_4($data_entry_type_id, $answer_options) {
        $answer = "";
        $h_answers = $answer_options['h_answer'];
        $v_answers = $answer_options['v_answer'];
        $button = "";
        switch ($data_entry_type_id) {
            case 1:

                break;
            case 2:
                $button = "<input type='radio'/>";
                break;
            case 3 :
                $button = "<input type='checkbox'/>";
                break;
            case 4:

                break;
            case 5:

                break;
        }
        $answer .='<table border="1" class="tableBlock">
                <tbody>
                    <tr>
                    <th>&nbsp;</th>';
        foreach ($h_answers as $value) {
            $answer.="<th>" . $value . "</th>";
        }
        $answer.="</tr>";
        foreach ($v_answers as $value) {
            $answer.="<tr>";
            $answer.="<th>" . $value . "</th>";
            for ($i = 1; $i <= count($h_answers); $i++) {
                $answer.="<td>" . $button . "</td>";
            }
            $answer.="<tr>";
        }
        $answer .='</tbody>';
        $answer .='</table>';
        return $answer;
    }

}
if (!function_exists('get_condition_input_field')) {

    function get_condition_input_field($question, $value=null) {
        $ci = &get_instance();
        $answer = "";
        $answers = $ci->answer_model->get_answers($question->question_id);
        $ans_options = @unserialize($answers->name);
        switch ($question->question_type_id) {
            case 1:
            case 3:
                $ans_options = $ans_options['options'];
                $answer.="<select class='value' name='value'>";
                $answer.="<option value=''> Choose </option>";
                foreach ($ans_options as $key => $val) {
                    $selected = ($key == $value) ? "selected='selected'" : "";
                    $answer.="<option value='" . $key . "' " . $selected . ">" . $val . "</option>";
                }
                $answer .="</select>";
                break;
            case 2:
                $range_options = @$ans_options['range'];
                $interval_options = @$ans_options['interval'];
                if ($range_options) {
                    $ans_options = $range_options;
                    $answer = '<input type="text" name="value" id="value" class="letxt" value="' . set_value("value", $value) . '" >(Enter value Between (' . $ans_options . '))';
                } else if ($interval_options) {
                    $ans_options = $interval_options;
                    $ans_options = explode(",", $ans_options);
                    $answer.="<select class='value' name='value'>";
                    $answer.="<option value=''> Choose </option>";
                    foreach ($ans_options as $key => $val) {
                        $selected = ($key == $value) ? "selected='selected'" : "";
                        $answer.="<option value='" . $key . "' " . $selected . ">" . $val . "</option>";
                    }
                    $answer .="</select>";
                }
                break;
            case 4:
                $answer = '<input type="text" name="value" id="value" class="letxt" value="' . set_value("value", $value) . '" >';
                break;
            case 5:

                break;
            case 6:
                $answer = '<input type="text" name="value" id="value" class="letxt" value="' . set_value("value", $value) . '" >';
                break;
        }
        return $answer;
    }

}
?>
