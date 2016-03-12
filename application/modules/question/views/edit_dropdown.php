<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'pull-down new_question common-form');
//var_dump($answers);die;
?>
<div class="formInner">
    <span class="<?php echo $type_class; ?>"></span>
    <h3 class="titleB question-type-heading"><?php echo $qtype; ?>: <?php echo $question->question_name; ?></h3>
    <?php echo form_open_multipart(base_url() . 'question/update/' . $eid . '/' . $question_type . '/' . $question_id, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <!--<input type="checkbox" name="qothers" <?php if (isset($question->other) && $question->other == 1) echo "checked = 'checked'" ?>value="1" />-->
                <label for="question">Enter question</label>
                <div class="input-container">
                    <input type="text" name="question" id="question" class="letxt question required" value="<?php echo set_value('question', $question->question_name); ?>" >
                </div>
                <div class="actions">
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'survey/step_5/' . $eid, 'Back'); ?>
                    </div>
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
                            <input type ='text' name='answers[]' id ="ans-<?php echo $eid . '-' . $k ?>" class="answers required" value="<?php echo set_value('answers', key($answer)); ?>" /> 		
                            <!-- just to hold the space -->
                            <?php if ($k == 0): ?>
                                <a class="trigger_anchor" rel="answer100001" href="#" style="visibility:hidden;">Remove</a> 
                            <?php endif; ?>	
                            <?php if ($k > 0): ?>
                                <a href='#' rel="<?php echo $k; ?>" id="delanswer-<?php echo $question_id . '-' . $question_type; ?>"class='del_answer'>Remove</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="dynamic-answer"></div>
                </div>
                <div class="clearfix"></div>
                <a href="#" class="add-answer" type="dd">Add answer</a>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>
        <h3><label for="documents">Documents</label><span class="status"></span></h3>
        <div class="content-container">
            <div class="que_container file-add-cnt">
                <div class="qAppenddiv">
                    <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                    <label for="documents">Add documents to question</label>
                </div>
                <fieldset id="q_doc_container">
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi" accept="<?php echo ACCEPT_FILE; ?>"/>
                    <div style="clear:both;"></div>
                    <div id="files_list" class="MultiFile-list">
                        <?php foreach ($q_resources as $q_resource): ?>
                            <div id="del-question-<?php echo $q_resource->id ?>" class="MultiFile-label">
                                <a href="#" class="MultiFile-remove del_question_file" rel="q" title="<?php echo $q_resource->name; ?>" id="<?php echo $q_resource->id ?>">Remove</a>
                                <span title="<?php echo $q_resource->name ?>" class="MultiFile-title">
                                    <a href="<?php echo base_url() . 'uploads' . DS . 'question' . DS . $q_resource->name ?>" target="_blank"><?php echo $q_resource->name ?></a>
                                </span>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>
            <div class="ans_container ans_container_1">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <fieldset id="a_doc_container">
                    <!--<input type="file" name="answer_documents[]" id="answer_documents" class="letxt compulsory multi"/>-->
                    <div style="clear:both;"></div>
                    <div id="files_list" class="MultiFile-list">
                        <?php foreach ($a_resources as $k => $a_resource): ?>
                            <div id="del-answer-<?php echo $a_resource['ans']->id ?>" class="MultiFile-label ans-doc-container">
                                <label class="ans-doc"><?php echo $a_resource['ans']->name; ?></label>
                                <?php if (!empty($a_resource['docs'])): ?>
                                    <div id="del-answer-doc-<?php echo $a_resource['docs']->id ?>">

                                        <span title="<?php echo $a_resource['docs']->name; ?>" class="MultiFile-title">
                                            <a href="<?php echo base_url() . 'uploads' . DS . 'answer' . DS . $a_resource['docs']->name ?>" target="_blank"><?php echo $a_resource['docs']->name; ?></a> 
                                        </span>
                                        <a href="#" class="MultiFile-remove del_file_ans" rel="a" id="<?php echo $a_resource['docs']->id ?>" title="<?php echo $a_resource['docs']->name; ?>">Remove</a>
                                    </div>
                                <?php else: ?>
                                    <div class="filetype-wrapper multipleDocumentAns singleFileWrapper">
                                        <input type="file" name="answer_documents[<?php echo $k; ?>]" class="common_doc" />
                                    </div>
                                    <input type="hidden" name="answer_id[]" value="<?php echo $k; ?>"/>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="added-answers"></div>
                </fieldset>
            </div>
            <div class="actions">
                <input type="button" name="back" value="Back" class="prev-accordion" />
                <div class="nextBtn">
                    <a href="#popup-box" id="popup-trigger">Next</a>
                </div>
            </div>
        </div>
    </div> 
    <input type="hidden" name="track_btn" value="done" />
    <?php echo form_close(); ?>
    <?php include(APPPATH . 'modules' . DS . 'question' . DS . 'views' . DS . 'popupview.php'); ?>
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
            }
        }

    );
        //deletes question and answer files via ajax
        $(".del_question_file, .del_file_ans").click(function() {
            var fileId = $(this).attr("id");
            var type = $(this).attr("rel");
            var fileName = $(this).attr("title");
            $.post("<?php echo base_url() ?>ajax/index/delete_file/", { "fileId": fileId,"type":type,"fileName":fileName}, function(theResponse){
                if(type == 'q') {
                    $("#del-question-"+fileId).remove();  
                }else {
                    $("#del-answer-doc-"+fileId).html('<input type="file" name="answer_documents[]" />');
                }
            });
            return false;            
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
                $("#del-answer-"+answerId).remove();
            });
            return false;            
        });
        
    })
</script>