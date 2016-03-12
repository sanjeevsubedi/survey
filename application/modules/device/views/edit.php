<?php
$attributes = array('name' => 'edit_device', 'id' => 'edit_device', 'class' => 'edit_device');
?>
<div class="formInner">
    <h3>Update Device</h3>
    <?php echo form_open(base_url() . 'device/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="device">Name</label>
        <input type="text" name="device" id="device" class="letxt" value="<?php echo set_value('device', $device->name); ?>" >
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
    </div>
    <?php echo form_close(); ?>
</div>
