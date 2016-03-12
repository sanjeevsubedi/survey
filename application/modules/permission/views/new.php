<?php
$attributes = array('name' => 'new_permission', 'id' => 'new_permission', 'class' => 'new_permission');
?>
<div class="formInner">
    <h3>Add Permission</h3>
    <?php echo form_open(base_url() . 'permission/add', $attributes); ?>  
    <fieldset>
        <label for="permission">Name</label>
        <input type="text" name="permission" id="permission" class="letxt" value="<?php echo set_value('permission'); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" />
    </div>
    <?php echo form_close(); ?>
</div>