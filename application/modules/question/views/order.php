<?php
$attributes = array('name' => 'order', 'id' => 'order', 'class' => 'order common-form');
?>
<div class="lists formInner">
    <h3>
        Question overview
    </h3>

    <?php echo form_open(base_url() . 'question/order/' . $eid, $attributes); ?>  
    <fieldset>
        <div id="questions">
            <?php $i = 1; ?>
            <?php if (!empty($questions)): ?>
                <div class="head-ui">
                    <span>Order</span>
                    <span>Compulsory</span>
                    <span>Edit</span>
                    <span>Delete</span>
                    <!--<span>Logic</span>-->
                </div>
                <ul>
                    <?php foreach ($questions as $question): ?>
                        <li class="edit" id="question_<?php echo $question->question_id; ?>">
                            <label><?php echo $question->question; ?>
                                <span style="font-weight: normal;display: block;">(<?php echo $question->question_type; ?>)</span>
                            </label>
                            <div class="questionWrap">
                                <span class="order">order</span>
                                <span><input type="checkbox" name="required[]" value="<?php echo $question->question_id; ?>" <?php echo set_checkbox('required', $question->question_id, in_array($question->question_id, $compulsory)) ?>/></span>
                                <?php echo anchor(base_url() . 'question/update/' . $eid . '/' . $question->question_type_id . '/' . $question->question_id, 'Edit'); ?>
                                <?php echo anchor(base_url() . 'question/delete/' . $eid . '/' . $question->question_id, 'Delete', array('class' => 'delete')); ?>
                                <!--<?php if ($question->question_type_id == 1): ?>
                                    <?php echo anchor(base_url() . 'logic/index/' . $eid . '/' . $question->question_id, 'Add Logic', array('class' => 'logic')); ?>
                                <?php else: ?>
                                    <?php echo anchor('#', 'n/a', array('class' => 'na')); ?>
                                <?php endif; ?>-->
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </fieldset> 
    <fieldset>
        <div class="backBtn">
            <?php echo anchor(base_url() . 'survey/step_5/' . $eid, 'Back'); ?>
        </div>
        <div class="nextBtn">
            <input type="submit" name="next" value="Next" class="save" id="next" />
        </div>
    </fieldset>
    <?php echo form_close(); ?> 
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $(function() {
            $("#questions ul").sortable({opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable("serialize");
                    $.post("<?php echo base_url() ?>ajax/index/question_order", order, function(theResponse) {
                    });
                }
            });
            
            $(".na").click(function(e){
            e.preventDefault();
            })
        });

    });
</script>



