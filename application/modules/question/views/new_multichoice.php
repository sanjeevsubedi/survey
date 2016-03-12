<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question common-form');
?>

<div class="formInner">
    <h3 class="titleB">Add question</h3>
    <?php echo form_open_multipart(base_url() . 'question/add/' . $eid . '/' . $question_type, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="input-container">
                    <input type="text" name="question" id="question" class="letxt question required" value="<?php echo set_value('question'); ?>" >
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
                                    <option value="<?php echo $data_entry_type->id; ?>" <?php echo set_select('data_entry_type', $data_entry_type->id); ?>><?php echo $data_entry_type->name; ?></option>
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
                    <?php for ($i = 0; $i < $total_answers; $i++): ?>
                        <div id='answer<?php echo $i; ?>"'>
                            <input type ='text' name='answers[]' class="answers required" value="<?php echo set_value('answers', @$_REQUEST['answers'][$i]); ?>" />
                            <?php if ($i == 0): ?>
                                <a class="trigger_anchor" rel="answer100001" href="#" style="visibility:hidden;">Remove</a> 
                            <?php endif; ?>
                            <?php if ($i != 0): ?>
                                <a href='#' rel="answer<?php echo $i; ?>" class='trigger_anchor'>Remove</a>
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
            <div class="que_container">
                <div class="qAppenddiv">
                    <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                    <label for="documents">Add documents to question</label>
                </div>
                <fieldset id="q_doc_container">
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi"/>
                </fieldset>
            </div>
            <div class="ans_container">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <fieldset id="a_doc_container">
                    <div id="added-answers"></div>
                     <!--<input type="file" name="answer_documents[]" id="answer_documents" class="letxt compulsory multi"/>-->
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
                var errors = validator.numberOfInvalids();
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