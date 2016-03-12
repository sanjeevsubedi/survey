<?php
$attributes = array('name' => 'new_question', 'id' => 'new_question', 'class' => 'new_question contengency-form common-form');
$contingency_middle = "";
$hoptions = "";

$hIndex = 0;
$vIndex = 0;
$type = "";
$information = "After adding an Answer, please press return to add next answer";

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
        <h3><label for="question">Data entry</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="input-container">
                    <?php if (!empty($data_entry_types)): ?>
                        <ul class="data-entry-lists">                           
                            <?php foreach ($data_entry_types as $data_entry_type): ?>
                                <!--only checkbox and radio button is shown for single choice question type-->
                                <?php if ($data_entry_type->id == 1 || $data_entry_type->id == 3 || $data_entry_type->id == 2 || $data_entry_type->id == 4): ?>      
                                    <li class="clearfix"><strong><?php echo $data_entry_type->name; ?></strong><div class="preview-container"><a href="#" rel="<?php echo $data_entry_type->id; ?>" class="custom-tooltip">Preview</a></div>
                                        <input type="radio" class="required" name="data_entry_type" value="<?php echo $data_entry_type->id; ?>"<?php echo set_radio('data_entry_type', $data_entry_type->id, $question->data_entry_type_id == $data_entry_type->id ? TRUE : FALSE); ?> />
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

        <h3><label for="answer">Answers</label></h3>
        <div class="content-container contingency-answers">
            <fieldset>
                <div class="scroll-container">
                    <div class="horizontal_container" style="width:875px;">
                        <div class="add_answer_parent_h" id="horizonal_ans_container">
                            <a href="#" class="add-answer-h">Add column</a>
                            <?php foreach ($h_answers as $k => $h_answer): ?>
                                <!-- contigency middle part-->
                                <?php $hoptions .= $type; ?>
                                <div id='answerh<?php echo $k; ?>'><input type ='text' name='h_answers[]' class="answers answers-h required" value="<?php echo set_value('answers', $h_answer); ?>" id ="ansch-<?php echo $eid . '-' . $k ?>"/> 
                                    <?php if ($k > 0): ?>
                                        <a href='#' rel="<?php echo $k; ?>" id="delanswer-<?php echo $question_id . '-' . $question_type; ?>"class='del_answer trigger_anchor_h' type="h"></a>
                                    <?php endif; ?>
                                </div>
                                <?php $hIndex++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="vertical_container" style="width:875px;">
                        <div class="add_answer_parent_v" id="vertical_ans_container">
                            <?php foreach ($v_answers as $k => $v_answer): ?>
                                <?php $contingency_middle .= "<div class='clearfix'></div>" . $hoptions; ?>    
                                <div id='answerv<?php echo $k; ?>'>
                                    <input type="checkbox" class="other-chkbox" name="others[<?php echo $k; ?>]" <?php if (isset($is_other[$k]) && $is_other[$k] == 1) echo "checked = 'checked'" ?>value="1" />
                                    <input type ='text' name='v_answers[]' class="answers answers-v required" value="<?php echo set_value('answers', $v_answer); ?>" id ="ansc-<?php echo $eid . '-' . $k ?>"/> 
                                    <?php if ($k > 0): ?>
                                        <a href='#' rel="<?php echo $k; ?>" id="delanswer-<?php echo $question_id . '-' . $question_type; ?>"class='del_answer trigger_anchor_v' type="v"></a>
                                    <?php endif; ?>
                                </div>

                                <?php
                                $type .= "<div class= 'entry-container' id='entry-" . $vIndex . "'>";
                                for ($hi = 0; $hi < $hIndex; $hi++) {
                                    if ($question->data_entry_type_id == 3) {
                                        $type .= "<div class='c-dataentry c-dataentry" . $hi . "' id='" . $vIndex . $hi . "'><input type = 'checkbox' /></div>";
                                    } else if ($question->data_entry_type_id == 4) {
                                        // if (!empty($select_options)) {
                                        $type .= "<div class='c-dataentry c-dataentry" . $hi . "'><select id='add" . $vIndex . $hi . "'><option value=''>option</option></select><a href='#optionlist" . $vIndex . $hi . "' class = 'dropdown-options' id='" . $vIndex . $hi . "'>+</a>" .
                                                "<div class='optioncontainer' id='optionlist" . $vIndex . $hi . "' style='display:none'>" .
                                                "<div class='info-options'>" . $information . "</div><textarea name='options[" . $vIndex . $hi . "] rows='5' cols='5' class='jqtransformdone'>" .
                                                $select_options[$k . $hi]
                                                . "</textarea>" .
                                                "<a href='#' id='save_" . $vIndex . $hi . "' class = 'save-option'>save</a></div></div>";
                                        //}
                                    } else if ($question->data_entry_type_id == 1) {
                                        $type .= "<div class='c-dataentry c-dataentry" . $hi . "' id='" . $vIndex . $hi . "' ><input type='text'/></div>";
                                    } else {
                                        $type .= "<div class='c-dataentry c-dataentry" . $hi . "' id='" . $vIndex . $hi . "'><input type = 'radio' /></div>";
                                    }
                                }
                                $type .='</div>';
                                $vIndex++;
                                $type .= "<div class='clearfix'></div>";
                                ?>           
                            <?php endforeach; ?>
                        </div>
                        <div id="c-input-type" class="c-doc_container">
                            <?php echo $type; ?>
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
        <h3><label for="documents">Documents</label></h3>
        <div class="content-container">
            <div class="que_container file-add-cnt">
                <input type="checkbox" name="chk_q_doc" value="1" id="chk_q_doc" class="letxt"/>
                <label for="documents" style="width:595px; background:#ccc; padding:5px 15px;">Add documents to question</label>
                <div style="clear:both;"></div>
                <fieldset id="q_doc_container">
                    <div style="clear:both;"></div>
                    <input type="file" name="question_documents[]" id="question_documents" class="letxt compulsory multi" accept="<?php echo ACCEPT_FILE; ?>"/>
                    <div style="clear:both;"></div>
                    <div id="files_list" class="MultiFile-list">
                        <div style="clear:both;"></div>
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
                <fieldset id="a_doc_container">
                    <div class="new-added-ans">
                        <?php echo $c_docs; ?>
                    </div>
                    <!--<div id="added-answersc"></div>-->
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
        
        
        //deletes question and answer files via ajax
        $(".del_question_file, .c-docs").click(function() {
            var fileId = $(this).attr("id");
            var type = $(this).attr("rel");
            var fileName = $(this).attr("title");
            $.post("<?php echo base_url() ?>ajax/index/delete_file/", { "fileId": fileId,"type":type,"fileName":fileName}, function(theResponse){
                if(type == 'q') {
                    $("#del-question-"+fileId).remove();  
                }else {
                    $("#del-answer-doc-"+fileId + ' a').remove();

                    $("#del-answer-doc-"+fileId).prepend('<input type="file" name="answer_documents[]" />');
                }
            });
            return false;            
        });
        
        //deletes answer via ajax
        $(".del_answer").click(function() {
            var answerId = $(this).attr("rel");
            var questionDetail = $(this).attr("id");
            var type = $(this).attr("type");
            var questionArray = questionDetail.split("-");
            var questionId = questionArray[1];
            var questionType = questionArray[2];
            var me = $(this);
            
            $.post("<?php echo base_url() ?>ajax/index/delete_answer/", {"answerId":answerId,"questionId":questionId,"questionType":questionType,"type":type}, function(theResponse){
                if(theResponse == 'v') {
                    $(".vans"+answerId).remove();
                }else {
                    $(".hans"+answerId).remove();
                }
                //remove the document column or row as well
                $(".doc-contingency").each(function(){
                    var classes = ($(this).attr('class'));
                    var classDetail = classes.split(' ');
                    var docClass = classDetail[1];
                    var docClassDetail = docClass.split('_');
                
                    if(theResponse == 'v') {
                        if(docClassDetail[0] == 'doc'+answerId) {
                            $(this).remove();
                        }
                    } else {
                        if(docClassDetail[1] == answerId) {
                            console.log(docClassDetail[1]);
                            $(this).remove();
                        }
                    }
                    
                })
                
                
                //resets middle part
                if(me.hasClass('trigger_anchor_h')) {
                    var key = me.attr('rel');
                    var parentContainer = me.parent().attr('id');
                    //remove all the related middle part
                    $(".answers-h").each(function(index,value){
                        
                        if($(this).parent().attr('id') == parentContainer){
                            $(".c-dataentry"+index).remove();
                            return false;
                        }
                    })
                    $("#answerh"+key).remove();
        
                    //reset the indexes of middle part
                    $(".entry-container").each(function(eindex,evalue){
                        $(this).attr('id','entry-'+eindex);
                        $(this).find(".c-dataentry").each(function(cindex,cvalue){
                
                            $(this).attr('class','');
                            $(this).addClass('c-dataentry');
                            $(this).addClass('c-dataentry'+cindex);
                
                            $(this).find("select").attr('id','add'+eindex+cindex);
                            $(this).find("a.dropdown-options").attr('id',''+eindex+cindex);
                            $(this).find("a.dropdown-options").attr('href','#optionlist'+eindex+cindex);
                            $(this).find(".optioncontainer a").attr('id','save_'+eindex+cindex);
                            $(this).find(".optioncontainer").attr('id','optionlist'+eindex+cindex);
                            $(this).find("textarea").attr('name','options['+eindex+cindex+']');
                        })
                    })
                } else {
                    var key = me.attr('rel');
                    var parentContainer = me.parent().attr('id');

                    //remove all the related middle part
                    $(".answers-v").each(function(index,value){
                        //console.log(index);
                        if($(this).parent().attr('id') == parentContainer){
                            $("#entry-"+index).remove();
                            //console.log($(".entry-container").length);
                
                            //reset the indexes of middle part
                            $(".entry-container").each(function(eindex,evalue){
                                $(this).attr('id','entry-'+eindex);
                                $(this).find(".c-dataentry").each(function(cindex,cvalue){
                                    $(this).find("select").attr('id','add'+eindex+cindex);
                                    $(this).find("a.dropdown-options").attr('id',''+eindex+cindex);
                                    $(this).find("a.dropdown-options").attr('href','#optionlist'+eindex+cindex);
                                    $(this).find(".optioncontainer a").attr('id','save_'+eindex+cindex);
                                    $(this).find(".optioncontainer").attr('id','optionlist'+eindex+cindex);
                                    $(this).find("textarea").attr('name','options['+eindex+cindex+']');
                                })
                            })
                            return false;
                        }  
                    })
       
                    $("#answerv"+key).remove();
                }
            });
            return false;            
        });
        
    })
</script> 