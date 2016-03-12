<?php
$attributes = array('name' => 'new_salesperson', 'id' => 'new_salesperson', 'class' => 'new_salesperson common-form');
?>
<div class="formInner">
    <h3>Add Sales Person</h3>
    <?php echo form_open(base_url() . 'salesperson/add', $attributes); ?>  
    <fieldset>
        <label for="salesperson">Name</label>
        <input type="text" name="salesperson" id="salesperson" class="letxt" value="<?php echo set_value('device'); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" />
    </div>
    <?php echo form_close(); ?>
</div>
