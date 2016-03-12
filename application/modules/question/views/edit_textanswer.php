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
            <div class="input-container">
                <fieldset>
                    <label for="question">Enter question</label>
                    <input type="text" name="question" id="question" class="letxt question required" value="<?php echo set_value('question', $question->question_name); ?>" >
                </fieldset>
            </div>
            <div class="actions">
                <div class="backBtn">
                    <?php echo anchor(base_url() . 'survey/step_5/' . $eid, 'Back'); ?>
                </div>
                <input type="button" name="next" value="Next" class="nxt-accordion" />
            </div>
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
                                <span title="<?php echo $q_resource->name ?>" class="MultiFile-title"><?php echo $q_resource->name ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
        
        //deletes question via ajax
        $(".del_question_file").click(function() {
            var fileId = $(this).attr("id");
            var fileName = $(this).attr("title"); 
            var type = $(this).attr("rel");
            $.post("<?php echo base_url() ?>ajax/index/delete_file/", { "fileId": fileId,"type":type,"fileName":fileName}, function(theResponse){
                $("#del-question-"+fileId).remove();  
            });
            return false;            
        })
        
    })
</script>