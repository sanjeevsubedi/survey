<?php
$attributes = array('name' => 'new_device', 'id' => 'new_device', 'class' => 'new_device');
?>
<div class="formInner">
    <h3>Add Device</h3>
    <?php echo form_open(base_url() . 'device/add', $attributes); ?>  
    <fieldset>
        <label for="device">Name</label>
        <input type="text" name="device" id="device" class="letxt" value="<?php echo set_value('device'); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" />
    </div>
    <?php echo form_close(); ?>
</div>
