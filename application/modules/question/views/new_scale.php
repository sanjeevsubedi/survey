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

        <h3><label for="answer">Answers</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="scale-container">
                    <div class="answer-range">
                        <div>
                            <input type="radio" name="range_option" value="1" checked="checked" class="letxt" id="fixed"/>
                            <label for="range">Add Range</label>
                            <div class="s-from">
                                <strong>From</strong>
                                <input type="text" id="from" name="from" class="letxt" value="<?php echo set_value('from'); ?>" />
                            </div>
                            <div class="s-to">
                                <strong>to</strong>
                                <input type="text" id="to" name="to" class="letxt" value="<?php echo set_value('to'); ?>" >
                            </div>
                        </div>
                        <div id="range-error"></div>
                        <p>(The value of teh scalue will be a 100% of 100 otherwise you have the option here to define the intervales)</p>
                    </div>
                    <div class="answer-range">
                        <div class="s-interval">
                            <input type="radio" name="range_option" value="2" class="letxt" id="interval"/>
                            <label for="range">Add fixed values</label>
                            <input type="text" name="custom_value" id="fixed_value" class="letxt" value="<?php echo set_value('fixed_value'); ?>" >
                        </div>
                        <p>(Please seperate the values with a comma)</p>
                    </div>
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
            <!-- <div class="ans_container">
                <div class="aAppenddiv">
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>
                <div id="added-answers"></div>
                <fieldset id="a_doc_container" class="document_ans">
                    <input type="file" name="answer_documents[]" id="answer_documents" maxlength="1" class="letxt compulsory common_doc multi"/>
                </fieldset>
            </div> -->
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
        //pattern
        var regex = /^([0-9]+,)+[0-9]+$/; 
        
        function checkRegex(inputVal){
            if(inputVal.match(regex)) {
                return true;
            } else {
                return false;
            }
        }
        
        jQuery.validator.addMethod("checkIntervalValues", function(value, element) {
            return this.optional(element) || (checkRegex(value));
        }, "Desired values should be separated by comas");
        
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
                       
            rules: {
                from: {
                    required: {
                        depends: function(element) {
                            return ($('#fixed').attr('checked'));
                        }
                    },
                    number: true
                },
                to: {
                    required: {
                        depends: function(element) {
                            return ($('#fixed').attr('checked'));
                        }
                    },
                    number: true
                },
                custom_value: {
                    required: {
                        depends: function(element) {
                            return ($('#interval').attr('checked'));
                        }
                    },
                    checkIntervalValues : true
                }
            }, 
            messages: { 
                from: 'Please enter the integer intial value',
                to: 'Please enter the integer end value',
                custom_value : 'Please enter the desired integer values separated by comas'
            } 
        }

    );
    })
</script>