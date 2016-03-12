<?php
$attributes = array('name' => 'new_language', 'id' => 'new_language', 'class' => 'new_language common-form');
?>
<div class="formInner">
    <h3>Add language</h3>
    <?php echo form_open(base_url() . 'language/add', $attributes); ?>  
    <fieldset>
        <label for="language">Name</label>
        <input type="text" name="language" id="language" class="letxt" value="<?php echo set_value('language'); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="save">
    </div>
    <?php echo form_close(); ?>
</div>
