<style>
    .jump-questions {color:#000; text-align: left;}
    .jump-questions div{clear:both;}
</style>
<?php
$attributes = array('name' => 'logic', 'id' => 'logic', 'class' => 'logic common-form');
//var_dump($logic);
//die;
?>
<div class="formInner">
    <h3 class="titleB">Edit Logics</h3>
    <?php echo form_open_multipart(base_url() . 'logic/addedit/' . $survey_id . '/' . $question_id, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Question</label></h3>
        <div class="content-container">
            <fieldset>
                <label><?php echo @$question_id . ". " . @$question->question_name; ?></label>
                <input type="hidden" name="logic_id" id="logic_id" class="letxt" value="<?php echo @$logic->id; ?>" >
                <input type="hidden" name="question_id" id="question_id" class="letxt" value="<?php echo @$question_id; ?>" >
                <input type="hidden" name="survey_id" id="survey_id" class="letxt" value="<?php echo @$survey_id; ?>" >
            </fieldset>
        </div>
        <h3><label for="logics">Logics</label></h3>
        <div class="content-container">
            <fieldset class="add_answer_parent">
                <!--list of conditions-->
                <?php if (!empty($logics)): ?>
                    <table boarder="0">
                        <tbody>
                            <tr>
                                <th>Option Value</th>
                                <th>Condition</th>
                                <th>Jump To Question</th>
                                <th>Group Logic</th>
                                <th>Action</th>
                            </tr>
                            <?php foreach ($logics as $value) : ?>
                                <tr id="del-<?php echo $value->id; ?>">
                                    <td><?php echo $value->opt_name ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->q_name . ' (' . $value->jump_to . ')'; ?></td>
                                    <td><?php echo $value->group_logic ?></td>
                                    <td><?php echo anchor(base_url() . 'logic/index/' . @$survey_id . '/' . $question_id . '/' . $value->id, 'Edit'); ?> | <a href="#" id="<?php echo $value->id; ?>" class="delete-logic">Delete</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </fieldset>
            <fieldset class="add_answer_parent">
                <label>Jump To Question No.(When Conditions meet)</label>
                <!--<select name="jump_to">
                    <option value="">Choose Question</option>
                <?php //foreach ($jump_questions as $value) : ?>
                                <option value="<?php //echo $value->question_id;                       ?>" <?php //echo (@$logic->jump_to == $value->question_id) ? "selected='selected'" : "";                       ?>><?php //echo $value->question_id . ". " . $value->question;                       ?></option>
                <?php //endforeach; ?>
                </select>-->
                <div class="jump-questions">
                    <?php foreach ($jump_questions as $value) : ?>
                        <div><?php echo $value->question_id . ". " . $value->question; ?>
                            <input type="radio" name="jump_to" <?php echo (@$logic->jump_to == $value->question_id) ? "checked='checked'" : ""; ?> value="<?php echo $value->question_id; ?>" />   
                        </div>               
                    <?php endforeach; ?>
                </div>

            </fieldset>
            <fieldset>
                <label for="group_logic">Group Logic</label>
                <select class="group_logic" name="group_logic">
                    <option value="and" <?php echo (@$logic->group_logic == 'and') ? "selected='selected'" : ""; ?>>AND</option>
                    <!--<option value="or" <?php //echo (@$logic->group_logic == 'or') ? "selected='selected'" : ""; ?>>OR</option>-->
                </select>
            </fieldset>
            <fieldset>
                <label for="operator_id">When Value </label>
                <select class="operator" name="operator_id">
                    <?php foreach ($operators as $value) : ?>
                        <option value="<?php echo $value->id; ?>" <?php echo (@$logic->operator_id == $value->id) ? "selected='selected'" : ""; ?>><?php echo $value->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>
            <fieldset>
                <label for="value">Value</label>
                <?php echo get_condition_input_field($question, @$logic->value); ?>

            </fieldset>
            <fieldset>
                <input type="submit" value="Save" class="logics-save save" id="next">
            </fieldset>
        </div>
    </div> 
    <fieldset>
        <div class="backBtn">
            <?php echo anchor(base_url() . 'question/order/' . @$survey_id, 'Back'); ?>
        </div>
    </fieldset>
    <?php echo form_close(); ?>
</div>


<script>
    $(document).ready(function() {
        //accordion styling
        $("#accordion").accordion({
            active: <?php echo 1; //echo @$this->session->userdata('accordion');                                ?>
        }, {
            heightStyle: "content"
        });

        //deletes conditions via ajax
        $(".delete-condition").click(function() {
            var CondId = $(this).attr("id");
            var bool;
            bool = confirm("Confirm to delete!");
            if (!bool) {
                e.preventDefault();
            }
            if (bool) {
                $.post("<?php echo base_url() ?>ajax/index/delete_condition/", {"CondId": CondId}, function(theResponse) {
                    $("#del-" + CondId).remove();
                });
            }
        });

        //deletes logic via ajax
        $(".delete-logic").click(function() {
            var LogicId = $(this).attr("id");
            var bool;
            bool = confirm("Confirm to delete!");
            if (!bool) {
                e.preventDefault();
            }
            if (bool) {
                $.post("<?php echo base_url() ?>ajax/index/delete_logic/", {"LogicId": LogicId}, function(theResponse) {
                    window.location.reload(true);
                });
            }
        });
    });
</script>
<script>
    //save jump to question
    function saveUpdate() {
        var postURL = '';
        postURL = '<?php echo site_url('ajax/index/save_logic') ?>';

        $.ajax({type: "POST", url: postURL, dataType: "json", cache: false, data: $('#logic').serialize(), success: function(result)
            {
                window.location.reload(true);
            }
        });
        return false;
    }
</script> 