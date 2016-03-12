<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question contengency-form common-form');
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
                                <!--only checkbox, textbox,selectbox and radio button is shown for single choice question type-->
                                <?php if ($data_entry_type->id == 1 || $data_entry_type->id == 3 || $data_entry_type->id == 2 || $data_entry_type->id == 4): ?>      
                                    <li class="clearfix"><strong><?php echo $data_entry_type->name; ?></strong><div class="preview-container"><a href="#" rel="<?php echo $data_entry_type->id; ?>" class="custom-tooltip">Preview</a></div>
                                        <input class="contingency-data_entry" type="radio" class="required" name="data_entry_type" value="<?php echo $data_entry_type->id; ?>" <?php echo set_radio('data_entry_type', $data_entry_type->id, 4 == $data_entry_type->id ? TRUE : FALSE); ?>/>
                                    </li>
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
        <div class="content-container contingency-answers">
            <fieldset>
                <div class="scroll-container">
                    <div class="horizontal_container" style="width:875px;">
                        <div class="add_answer_parent_h" id="horizonal_ans_container">
                            <a href="#" class="add-answer-h">Add column</a>
                            <?php for ($i = 0; $i < $total_answers_h; $i++): ?>
                                <div id='answer_h<?php echo $i; ?>"'><input type ='text' name='h_answers[]' class="answers answers-h required" value="<?php echo set_value('h_answers', @$_REQUEST['h_answers'][$i]); ?>" />
                                    <?php if ($i != 0): ?>
                                        <a href='#' rel="h_answers<?php echo $i; ?>" class='trigger_anchor_h'>Remove</a>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="vertical_container" style="width:875px;">
                        <div class="add_answer_parent_v" id="vertical_ans_container">
                            <?php for ($i = 0; $i < $total_answers_v; $i++): ?>
                                <div id='answer_v<?php echo $i; ?>"'><input type ='text' name='v_answers[]' class="answers answers-v required" value="<?php echo set_value('v_answers', @$_REQUEST['v_answers'][$i]); ?>" />
                                    <?php if ($i != 0): ?>
                                        <a href='#' rel="v_answers<?php echo $i; ?>" class='trigger_anchor_v'>Remove</a>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div id="c-input-type" class="c-doc_container">
                            <div class= 'entry-container' id='entry-0'>
                                <div class='c-dataentry'>
                                </div>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <a href="#" class="add-answer-v">Add row</a>
                    </div>
                    <div class="actions">
                        <input type="button" name="back" value="Back" class="prev-accordion" />
                        <input type="button" name="next" value="Next" class="nxt-accordion" />
                    </div>
            </fieldset>

        </div>
        <h3><label for="documents">Documents</label><span class="status"></span></h3>
        <div class="content-container">
            <div class="que_container">
                <div class="qAppenddiv">
                    <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                    <label for="documents">Add documents to question</label>
                </div>
                <fieldset id="q_doc_container">
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi" accept="<?php echo ACCEPT_FILE; ?>"/>
                </fieldset>
            </div>
            <div class="ans_container">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <fieldset id="a_doc_container">
                    <!--<input type="file" name="answer_documents[]" id="answer_documents" class="letxt compulsory multi"/>-->
                    <div id="added-answersc" class="new-added-ans"></div>
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
        accordionHandler("accordion");
        
        $(".custom-tooltip").dataentrypreview();
        $(".custom-tooltip").dataentrypreview('hover','<?php echo site_url("question/preview_dataentry_type") ?>','<?php echo $question_type; ?>');

        
        $("#new_question").validate(
        { 
            rules: {
                AccordionField: {
                    required: true
                }
            },
            ignore: [],
            invalidHandler: function(event, validator) {
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
        
    })
</script> 