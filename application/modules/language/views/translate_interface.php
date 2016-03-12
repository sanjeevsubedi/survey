<?php
$attributes = array('name' => 'translate', 'id' => 'translate', 'class' => 'translate common-form');
//var_dump($quest_ans);
//die;
//print "<pre>";
//print_r($quest_ans);
//print "</pre>";
//die;
$hak = 0;
$vak = 0;
?>
<div class="formInner">
    <?php echo form_open(base_url() . 'language/translate/' . $eid . '/' . $lang, $attributes); ?>  
    <input type="hidden" name="track_btn" value="done" />
    <div class="language-editor-wrapper" id="accordion">
        <h3><label for="question">Current Languages</label><span class="status valid-flag"></span></h3>
        <div class="content-container">
            <div class="choosen-langs">
                <p>Choose the language you used within this Questionaire:</p>
            </div>
            <fieldset>
                <div class="input-container top-current-languages">
                    <?php if (!empty($languages)): ?>
                        <select name="language" class="languages" id="upper-langs" disabled="disabled">
                            <?php foreach ($languages as $language): ?>
                                <option class="<?php echo $language->name; ?>" value="<?php echo $language->id; ?>" <?php echo set_select('language', $language->id, $language->id == $survey_obj->language_id ? TRUE : FALSE); ?>><?php echo $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                </div>
                <div class="actions">
                    <div class="backBtn">
                        <?php //echo anchor(base_url() . 'file/show_docs/' . $eid, 'Back'); ?>
                    </div>
                    <input type="button" name="next" value="Next" class="nxt-accordion"/>

                </div>
            </fieldset>
        </div>
        <h3><label>Add Language</label></h3>
        <div class="content-container">
            <fieldset>
                <div class="lang-info">
                    <div class="initial-lang">
                        <p>Initial language:</p>
                        <span class="<?php echo $original_lang; ?>-initial"> <?php echo $original_lang; ?></span>
                    </div>

                </div>
                <div class="input-container-table">
                    <table class="languageEdit">
                        <tr>
                            <th><?php echo $original_lang; ?></th>
                            <th><?php echo $translated_lang; ?></th>
                        </tr>

                        <?php if (!empty($survey)): ?>
                            <tr>    
                                <td><?php echo $survey['original_name']; ?></td>
                                <td>
                                    <input type="text" class="required" name="original_survey[<?php echo $survey['original_id']; ?>]" value="<?php echo $survey['translate_name']; ?>" />
                                    <input type="hidden" name="translate_survey_id[<?php echo $survey['original_id']; ?>]" value="<?php echo $survey['translate_id']; ?>" />
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($contact_fields)): ?>
                            <?php foreach ($contact_fields as $k => $contact_field): ?>
                                <tr>    
                                    <td><?php echo $contact_field['original_name']; ?></td>
                                    <td>
                                        <input type="text" class="required" name="original_contact_field[<?php echo $contact_field['original_id']; ?>]" value="<?php echo $contact_field['translate_name']; ?>" />
                                        <input type="hidden" name="translate_contact_field_ids[<?php echo $contact_field['original_id']; ?>]" value="<?php echo $contact_field['translate_id']; ?>" />
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($quest_ans)): ?>
                            <?php foreach ($quest_ans as $k => $quest_ans): ?>

                                <tr>    
                                    <td><?php echo $quest_ans['questions']['original'][$k]['name']; ?></td>
                                    <td>
                                        <input type="text" class="required" name="original_question[<?php echo $quest_ans['questions']['original'][$k]['id']; ?>]" value="<?php echo $quest_ans['questions']['translate'][$k]['name']; ?>" />
                                        <input type="hidden" name="translate_question_ids[<?php echo $quest_ans['questions']['original'][$k]['id']; ?>]" value="<?php echo $quest_ans['questions']['translate'][$k]['id'] ?>" />
                                    </td>
                                </tr>
                                <?php foreach ($quest_ans['answers'] as $ak => $answer): ?>
                                    <?php $type = $quest_ans['questions']['original'][$k]['type']; ?>
                                    <tr>
                                        <?php if ($type == 1 || $type == 3): ?>  
                                            <td> <?php echo $answer['main_answer']['original'][0]['name']; ?></td>
                                            <td><input type="text" class="required" name="original_main_answer<?php echo $quest_ans['questions']['original'][$k]['id']; ?>[<?php echo $ak; ?>]" value="<?php echo $answer['main_answer']['translate'][0]['name']; ?>" />
                                            </td>
                                        <?php elseif ($type == 4): ?>
                                            <td>
                                                <?php if (isset($answer['main_answer']['h_original'][0]['name'])): ?>
                                                    <?php echo $answer['main_answer']['h_original'][0]['name']; ?>
                                                <?php else: ?>
                                                    <?php echo $answer['main_answer']['v_original'][0]['name']; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (isset($answer['main_answer']['h_original'][0]['name'])): ?>
                                                    <input type="text"  class="required" name="original_hmain_answer<?php echo $quest_ans['questions']['original'][$k]['id']; ?>[<?php echo $hak; ?>]" value="<?php echo $answer['main_answer']['h_translate'][0]['name']; ?>" />
                                                    <?php $hak++; ?>
                                                <?php else: ?>
                                                    <input type="text" class="required" name="original_vmain_answer<?php echo $quest_ans['questions']['original'][$k]['id']; ?>[<?php echo $vak; ?>]" value="<?php echo $answer['main_answer']['v_translate'][0]['name']; ?>" />
                                                    <?php $vak++; ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php if (!empty($answer['sub_answer']['original'])): ?>
                                        <?php foreach ($answer['sub_answer']['original'] as $sak => $sub_answer): ?>
                                            <tr>
                                                <td><?php echo $sub_answer['name']; ?></td>
                                                <td><input type="text" class="required" name="original_sub_answer<?php echo $quest_ans['questions']['original'][$k]['id']; ?>_<?php echo $ak; ?>[<?php echo $sak; ?>]" value="<?php echo $answer['sub_answer']['translate'][$sak]['name']; ?>" />

                                                    <input type="hidden" name="translate_subans_ids<?php echo $quest_ans['questions']['original'][$k]['id']; ?>_<?php echo $ak; ?>[<?php echo $sak; ?>]" value="<?php echo $answer['sub_answer']['translate'][$sak]['id'] ?>" />

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php
                                $hak = 0;
                                $vak = 0;
                                ?>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>
            </fieldset>
            <div class="actions">
                <input type="button" name="back" value="Back" class="prev-accordion" />
                <input type="button" name="next" value="Next" class="nxt-accordion" id="final-next" />
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <!-- popup container-->
    <a href="#popup-box" id="popup-trigger"></a>
    <div id="popup-box" style="display:none">
        <div class="popup-title">Language</div>
        <div class="popup-body">
            <div>What would you like to complete language now or add another language?</div>
            <input type="submit" name="more" value="Add another language" class="save another" />
            <input type="submit" name="done" value="Complete language" class="save done" />
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            accordionHandler("accordion",1);
            //$("#translate").validate();
            //popup section
            $("#final-next").on('click',function(ev){
                $("#popup-trigger").fancybox({  wrapCSS:'quesitonPopup'}).trigger('click');
            })
            
            $(".save").on('click',function(){
                var complete = $(this).hasClass('done');
                if(complete) {
                    $("input[name=track_btn]").val('done');
                    if ($('#translate').valid()) {
                        $('form').submit();
                    } else {
                        $('form').submit();
                        $.fancybox.close();
                    }
                    
                } else {
                    $("input[name=track_btn]").val('another');
                    if ($('#translate').valid()) {
                        $('form').submit();
                    } else {
                        $('form').submit();
                        $.fancybox.close();
                    }

                }
            })
            
            
        })  
    </script>