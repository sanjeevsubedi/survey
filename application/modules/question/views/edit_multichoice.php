<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question common-form');
?>
<div class="formInner">
    <h3 class="titleB">Edit question</h3>
    <?php echo form_open_multipart(base_url() . 'question/update/' . $eid . '/' . $question_type . '/' . $question_id, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="input-container">
                    <input type="text" name="question" id="question" class="letxt question required" value="<?php echo set_value('question', $question->question_name); ?>" >
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>
        <h3><label for="question">Data entry</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="input-container">
                    <?php if (!empty($data_entry_types)): ?>
                        <select name="data_entry_type" id="data_entry_type">
                            <?php foreach ($data_entry_types as $data_entry_type): ?>
                                <!--only checkbox and textbox is shown for multichoice question type-->
                                <?php if ($data_entry_type->id == 3 || $data_entry_type->id == 1): ?>     
                                    <option value="<?php echo $data_entry_type->id; ?>" <?php echo set_select('data_entry_type', $data_entry_type->id, ($data_entry_type->id == $question->data_entry_type_id) ? TRUE : FALSE); ?>><?php echo $data_entry_type->name; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>

        <h3><label for="answer">Answers</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset class="add_answer_parent">
                <div class="input-container">
                    <?php foreach ($answers as $k => $answer): ?>
                        <div id='answer<?php echo $k; ?>'>
                            <input type="checkbox" name="others[<?php echo $k; ?>]" <?php if (isset($is_other[$k]) && $is_other[$k] == 1) echo "checked = 'checked'" ?>value="1" />
                            <input type ='text' name='answers[]' class="answers required" value="<?php echo set_value('answers', key($answer)); ?>" /> 
                            <!-- just to hold the space -->
                            <?php if ($k == 0): ?>
                                <a class="trigger_anchor" rel="answer100001" href="#" style="visibility:hidden;">Remove</a> 
                            <?php endif; ?>                       
                            <?php if ($k > 0): ?>
                                <a href='#' rel="<?php echo $k; ?>" id="delanswer-<?php echo $question_id . '-' . $question_type; ?>"class='del_answer'>Remove</a>
                            <?php endif; ?>
                            <div class="subans-holder-container" id="subans-holder-container<?php echo $k; ?>">
                                <?php if (!empty($answer[key($answer)])): ?>
                                    <div class="subanswer-holder" id="subans-holder<?php echo $k; ?>">
                                        <p style='clear:both;'>Data entry type</p>
                                        <select name='data_entry_type_sub[]' id='data_entry_type_sub'>
                                            <option value='2' <?php if (!empty($subans_data_entry[$k]) && $subans_data_entry[$k]->data_entry_type_id == 2) echo "selected = 'selected'"; ?>>radiobutton</option>
                                            <option value='3' <?php if (!empty($subans_data_entry[$k]) && $subans_data_entry[$k]->data_entry_type_id == 3) echo "selected = 'selected'"; ?>>checkbox</option>
                                        </select>
                                        <div class='subanswers'>Leave empty or add one subanswer per line</div>
                                        <textarea rows="4" cols="3" name="subanswers[]">
                                            <?php echo $answer[key($answer)]; ?>
                                        </textarea>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (empty($answer[key($answer)])): ?>
                                <div><a rel="<?php echo $k; ?>" href="#" class="add-subanswer-trigger">Add sub answer</a></div>
                            <?php else: ?>
                                <div><a rel="<?php echo $k; ?>" id="delsubanswer-<?php echo $question_id ?>" href="#" class="delete-subanswer-trigger">remove sub answer</a></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="dynamic-answer"></div>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
            <a href="#" class="add-answer">Add answer</a>
        </div>
        <h3><label for="documents">Documents</label></h3>
        <div class="content-container">
            <div class="que_container">
                <div class="qAppenddiv">            
                    <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                    <label for="documents">Add documents to question</label>
                </div>
                <fieldset id="q_doc_container">
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi"/>
                    <div style="clear:both;"></div>
                    <div id="files_list">
                        <?php foreach ($q_resources as $q_resource): ?>
                            <div id="del-<?php echo $q_resource->id ?>">
                                <?php echo $q_resource->name; ?>
                                <input type="button" value="Delete" rel="<?php echo $q_resource->name ?>" id="<?php echo $q_resource->id ?>" class="del_file">
                                <?php //echo anchor(base_url() . 'question/edit/' . $eid . '/' . $question_type . '/' . $question_id . '/' . $q_resource->id, 'Delete'); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>

            </div>
            <div class="ans_container">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <div id="added-answers"></div>
                <fieldset id="a_doc_container">
                    <input type="file" name="answer_documents[]" id="answer_documents" class="letxt compulsory multi"/>
                    <div style="clear:both;"></div>
                    <div id="files_list">
                        <?php foreach ($a_resources as $a_resource): ?>
                            <div id="del-<?php echo $a_resource->id ?>">
                                <?php echo $a_resource->name; ?>
                                <input type="button" value="Delete" rel="<?php echo $a_resource->name ?>" id="<?php echo $a_resource->id ?>" class="del_file">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>
        </div>


    </div> 
    <fieldset>
        <div class="done-adding">
            <input type="submit" name="more" value="Add another question" class="save another" />
        </div>
        <div class="add-another">
            <input type="submit" name="done" value="Done adding question" class="save done" />
        </div>
    </fieldset>
    <?php echo form_close(); ?>
</div>


<script>
    $(document).ready(function(){
        
        //initialize accordion
        accordionHandler("accordion");
        $("#new_question").validate(
        { 
            rules: {
                AccordionField: {
                    required: true
                }
            },
            ignore: [],
            invalidHandler: function(event, validator) {
                // 'this' refers to the form
                //var errors = validator.numberOfInvalids();
                valider();
            }
        }

    );
        
        function valider() {
            $("div.ui-accordion-content").each(function(){
                if($(this).find('fieldset').has('label').length) {
                    $(this).show();
                }
            })
            
        }
        
        //deletes question files via ajax
        $(".del_file").click(function() {
            var fileId = $(this).attr("id");
            var fileName = $(this).attr("rel");
            $.post("<?php echo base_url() ?>ajax/index/delete_file/", { "fileId": fileId,"fileName":fileName}, function(theResponse){
                $("#del-"+fileId).remove();  
            }); 															 
        });
        
        //deletes answer via ajax
        $(".del_answer").click(function() {
            var answerId = $(this).attr("rel");
            var questionDetail = $(this).attr("id");
            var questionArray = questionDetail.split("-");
            var questionId = questionArray[1];
            var questionType = questionArray[2];
            $.post("<?php echo base_url() ?>ajax/index/delete_answer/", {"answerId":answerId,"questionId":questionId,"questionType":questionType}, function(theResponse){
                $("#answer"+answerId).remove(); 
            });
            return false;            
        });
       
        
        var count_ans = 100000
        //deletes subanswer via ajax
        $(".delete-subanswer-trigger").live('click',function() {
            var answerId = $(this).attr("rel");
            var questionDetail = $(this).attr("id");
            if(questionDetail) {
                var questionArray = questionDetail.split("-");
                var questionId = questionArray[1];
                var addSubans =  "<div><a class='add-subanswer-trigger' href='#' rel='subanswer-holder"+count_ans +"'>Add sub answer</a></div>";
 
                $.post("<?php echo base_url() ?>ajax/index/delete_subanswer/", {"answerId":answerId,"questionId":questionId}, function(theResponse){
                    $("#subans-holder"+answerId).remove(); 
                    $(this).parent().empty();
                    $(this).parent().html("<a class='add-subanswer-trigger' href='#' rel='subanswer-holder"+count_ans +"'>Add sub answer</a>");
                });
                return false;
            }
            
        });
        
        
    })
</script>