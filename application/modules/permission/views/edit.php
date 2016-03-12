<?php
$attributes = array('name' => 'edit_permission', 'id' => 'edit_permission', 'class' => 'edit_permission');
?>
<div class="formInner">
    <h3>Update Permission</h3>
    <?php echo form_open(base_url() . 'permission/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="permission">Name</label>
        <input type="text" name="permission" id="permission" class="letxt" value="<?php echo set_value('permission', $permission->name); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
    </div>
    <?php echo form_close(); ?>
</div>
