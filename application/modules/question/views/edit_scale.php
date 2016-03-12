<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question common-form');
?>

<div class="formInner">
    <span class="<?php echo $type_class; ?>"></span>
    <h3 class="titleB question-type-heading"><?php echo $qtype; ?>: <?php echo $question->question_name; ?></h3>
    <?php echo form_open_multipart(base_url() . 'question/update/' . $eid . '/' . $question_type . '/' . $question_id, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <label for="question">Enter question</label>
                <div class="input-container">
                    <input type="text" name="question" id="question" class="letxt question" value="<?php echo set_value('question', $question->question_name); ?>" >
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
                            <input type="radio" name="range_option" value="1" <?php echo $type == "range" ? "checked" : ""; ?> class="letxt" id="fixed"/>
                            <label for="range">Add Range</label>
                            <div class="s-from">
                                <strong> From</strong>
                                <input type="text" id="from" name="from" class="letxt" value="<?php echo set_value('from', $start); ?>" />
                            </div>
                            <div class="s-to">
                                <strong>to</strong>
                                <input type="text" id="to" name="to" class="letxt" value="<?php echo set_value('to', $end); ?>" >
                            </div>
                        </div>
                        <div id="range-error"></div>
                        <p>(The value of teh scalue will be a 100% of 100 otherwise you have the option here to define the intervales)</p>
                    </div>
                    <div class="answer-range">
                        <div class="s-interval">
                            <input type="radio" name="range_option" value="2" <?php echo $type == "interval" ? "checked = checked" : ""; ?>class="letxt" id="interval"/>
                            <label for="range">Add fixed values</label>
                            <input type="text" name="custom_value" id="fixed_value" class="letxt" value="<?php echo set_value('fixed_value', $interval); ?>" >
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
            <div class="ans_container">
                <div class="aAppenddiv">            
                    <input type="checkbox" name="chk_a_doc" value="1" id="chk_a_doc" class="letxt"/>
                    <label for="documents">Add documents to answers</label>
                </div>

                <!-- <fieldset id="a_doc_container">
                    <div style="clear:both;"></div>
                    <div id="files_list" class="MultiFile-list">
                <?php if (!empty($a_resources)): ?>
                                    <div id="del-answer-<?php echo $a_resources->id ?>" class="MultiFile-label ans-doc-container">
                                        <div id="del-answer-doc-<?php echo $a_resources->id ?>">
                                            <span title="<?php echo $a_resources->name; ?>" class="MultiFile-title"><?php echo $a_resources->name; ?></span>
                                            <a href="#" class="MultiFile-remove del_file_ans" rel="a" id="<?php echo $a_resources->id ?>" title="<?php echo $a_resources->name; ?>">Remove</a>
                                        </div>
                                    </div>
                <?php else: ?>
                                    <input type="file" name="answer_documents[]" class="common_doc"/>
                                    <input type="hidden" name="answer_id[]" value="0"/>
                <?php endif; ?>
                    </div>
                </fieldset> -->
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <div class="nextBtn">
                        <a href="#popup-box" id="popup-trigger">Next</a>
                    </div>
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
                // 'this' refers to the form
                var errors = validator.numberOfInvalids();
                valider();
            },
            
            rules: {
                from: {
                    required: {
                        depends: function(element) {
                            return ($('#fixed').attr('checked'));
                        }
                    }
                },
                to: {
                    required: {
                        depends: function(element) {
                            return ($('#fixed').attr('checked'));
                        }
                    }
                },
                custom_value: {
                    required: {
                        depends: function(element) {
                            return ($('#interval').attr('checked'));
                        }
                    }
                }
            }, 
            messages: { 
                from: 'Please enter the intial value',
                to: 'Please enter the end value',
                custom_value : 'Please enter the desired values'
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
        
    })
</script>