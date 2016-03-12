<?php
$attributes = array('name' => 'edit_salesperson', 'id' => 'edit_salesperson', 'class' => 'edit_salesperson');

//var_dump($survey_salesperson);
//die;
?>
<div class="formInner">
    <h3>Update Salesperson</h3>
    <?php echo form_open(base_url() . 'salesperson/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="salesperson">Name</label>
        <input type="text" name="salesperson" id="salesperson" class="letxt" value="<?php echo set_value('salesperson', $salesperson->name); ?>" >
    </fieldset>
    <fieldset>
        <label for="type">Questionnaire</label>
        <?php if (!empty($surveys)): ?>
            <ul class="salesperson_list">
                <?php foreach ($surveys as $survey): ?>
                    <?php
                    $cond = '';
                    $cond = in_array($survey->id, $survey_salesperson) ? TRUE : FALSE;
                    ?>
                    <li><input type="checkbox" value="<?php echo $survey->id; ?>" name="surveys[]" <?php echo set_checkbox('surveys', $survey->id, $cond); ?>/><small><?php echo $survey->name; ?></small></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
    </div>
    <?php echo form_close(); ?>
</div>
