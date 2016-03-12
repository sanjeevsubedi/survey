<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question common-form');
?>
<div class="formInner">
    <span class="<?php echo $type_class; ?>"></span>
    <h3 class="titleB question-type-heading"><?php echo $qtype; ?></h3>
    <?php echo form_open_multipart(base_url() . 'question/add/' . $eid . '/' . $question_type, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <label for="question">Enter question</label>
                <div class="input-container">
                    <input type="text" name="question" id="question" class="letxt question required" value="<?php echo set_value('question'); ?>" >
                </div>
                <div class="actions">
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'survey/step_5/' . $eid, 'Back'); ?>
                    </div>
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>
        <h3><label for="question">Data entry</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="input-container">
                    <?php if (!empty($data_entry_types)): ?>
                        <ul class="data-entry-lists">
                            <?php foreach ($data_entry_types as $data_entry_type): ?>
                                <?php if ($question_type == 1): ?>
                                    <!--only radio button is shown for single choice question type-->
                                    <?php if ($data_entry_type->id == 2 /* || $data_entry_type->id == 4 */): ?>
                                        <li class="clearfix"><strong><?php echo $data_entry_type->name; ?></strong><div class="preview-container"><a href="#" rel="<?php echo $data_entry_type->id; ?>" class="custom-tooltip">Preview</a></div>
                                            <input type="radio" checked="checked" class="required" name="data_entry_type" value="<?php echo $data_entry_type->id; ?>"/>
                                        </li>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!--only checkbox and textbox is shown for multichoice question type-->
                                    <?php if ($data_entry_type->id == 3 || $data_entry_type->id == 1 /* || $data_entry_type->id == 4 */): ?>    
                                        <li class="clearfix"><strong><?php echo $data_entry_type->name; ?></strong><div class="preview-container"><a href="#" rel="<?php echo $data_entry_type->id; ?>" class="custom-tooltip">Preview</a></div>
                                            <input type="radio" class="required" name="data_entry_type" value="<?php echo $data_entry_type->id; ?>" <?php echo set_radio('data_entry_type', $data_entry_type->id, ($data_entry_type->id == 3) ? TRUE : FALSE); ?> />
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
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
                    <?php for ($i = 0; $i < $total_answers; $i++): ?>
                        <div id='answer<?php echo $i; ?>' data-ans ='<?php echo $i; ?>'>
                            <input type ='text' name='answers[]' class="answers required" value="<?php echo set_value('answers', @$_REQUEST['answers'][$i]); ?>" />
                            <?php if ($i != 0): ?>
                                <a href='#' rel="answer<?php echo $i; ?>" class='trigger_anchor'>Remove</a>
                            <?php endif; ?>

                            <?php if ($i == 0): ?>
                                <a class="trigger_anchor" rel="answer100001" href="#" style="visibility:hidden;">Remove</a> 
                            <?php endif; ?>
                            <div class="subans-holder-container"></div>
                            <div><a class="add-subanswer-trigger" href="#" rel="subanswer-holder<?php echo $i; ?>">Add sub answer</a></div>
                        </div>
                        <div class="dynamic-answer"></div>
                    <?php endfor; ?>
                </div>   
                <div class="clearfix"></div>
                <div><a href="#" class="add-answer">Add answer</a></div>
                <div class="clearfix"></div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>
        <h3><label for="documents">Documents</label><span class="status"></span></h3>
        <div class="content-container">
            <div class="que_container que_container_2">
                <div class="qAppenddiv">
                    <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                    <label for="documents">Add documents to question</label>
                </div>
                <fieldset id="q_doc_container">
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi" accept="<?php echo ACCEPT_FILE; ?>"/>
                </fieldset>
            </div>
            <div class="ans_container ans_container_1">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <fieldset id="a_doc_container">
                    <!--<input type="file" name="answer_documents[]" id="answer_documents" class="letxt compulsory multi"/>-->
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
        
        var tooltip = $(".custom-tooltip").dataentrypreview();
        tooltip.dataentrypreview('hover','<?php echo site_url("question/preview_dataentry_type") ?>','<?php echo $question_type; ?>');

        
        $("#new_question").validate(
        { 
            rules: {
                AccordionField: {
                    required: true
                }
            },
            ignore: [],
            invalidHandler: function(event, validator) {
                //valider();
            }
        }

    );
    })
</script>