<?php
$arr = array('8', '9');
?>
<div class="formInner">
    <h3 class="titleB">Please select your Questions:<span><?php echo $survey->name;?></span></h3>
    <?php if (!empty($question_types)): ?>
        <ul class="questionType">
            <?php foreach ($question_types as $question_type): ?>
            <?php if (!in_array($question_type->id, $arr)):?>
                <li class="<?php echo $question_type->name; ?>" data-survey="<?php echo $eid; ?>" id="<?php echo $question_type->id; ?>">
                    <span>
                        <small>
                            <?php
                            if (!in_array($question_type->id, $arr)) {
                                $link = base_url() . 'question/add/' . $eid . '/' . $question_type->id;
                            } else {
                                $link = base_url() . "survey/step_5/" . $eid;
                            }
                            ?>
                        </small>
                    </span>
                    <?php echo anchor("#", $question_type->nice_name, array('class' => 'q-type')); ?>
                </li>
            <?php endif; ?>
            <?php endforeach; ?>

        </ul>
    <?php endif; ?>

    <fieldset>
        <!--<div class="backBtn">
        <?php echo anchor(base_url() . 'survey/step_2/' . $eid, 'Back'); ?>
        </div>-->
        <!-- Only original survey is shown this link. Translated survey are not shown this link-->
        <?php if ($is_original_survey || $translation_set_id == 0): ?>
            <div class="saveBtn languageBtn">
                <?php //echo anchor(base_url() . 'language/survey_lang/' . $eid, 'Languages'); ?>
            </div>
        <?php endif; ?>
        <?php if ($has_questions): ?>
            <div class="saveBtn">
                <?php echo anchor(base_url() . 'question/order/' . $eid, 'edit existing questions'); ?>
            </div>
        <?php endif; ?>
    </fieldset>
</div>
<script>
    $(document).ready(function(){
        /*var tooltip = $(".questionType li span small").questiontypepreview();
        tooltip.questiontypepreview('hover','<?php //echo site_url("question/preview_question_type") ?>');
        $("#backoverview").live('click',function(){
            $(".questionTypeTooltip").hide();
            return false;
            
        })*/
        $(".questionType li span small, .q-type").on('click', function() {
            
            if($(this).attr('class') == 'q-type') {
                var questionType = $(this).parent().attr('id');    
            } else {
                var questionType = $(this).parent().parent().attr('id');
            }
            
            
            $.fancybox({
                wrapCSS:'question-type',
                href : '<?php echo site_url("question/preview_question_type") ?>',
                type : 'ajax',
                ajax : {
                    type: 'GET',
                    data: {
                        question_type: questionType,
                        survey_id: '<?php echo $eid ?>'
                    }
                }
            })
            
            if($(this).attr('class') == 'q-type') {
                return false;
            }
        });
        
        
    })
</script>
