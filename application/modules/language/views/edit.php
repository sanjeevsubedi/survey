<?php
$attributes = array('name' => 'edit_language', 'id' => 'edit_language', 'class' => 'edit_language common-form');
?>
<div class="formInner">
    <h3>Update language</h3>
    <?php echo form_open(base_url() . 'language/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="language">Name</label>
        <input type="text" name="language" id="language" class="letxt" value="<?php echo set_value('language', $language->name); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
    </div>
    <?php echo form_close(); ?>
</div>
