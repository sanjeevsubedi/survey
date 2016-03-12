<?php
$attributes = array('name' => 'new_contact', 'id' => 'new_contact', 'class' => 'new_contact');
?>
<div class="formInner">
    <h3>Add Contact Field</h3>
    <?php
    $return_case = $this->uri->segment(3);
    $return_id = $this->uri->segment(4);
    if ($return_case) {
        echo form_open(base_url() . 'contact/add/1/' . $return_id, $attributes);
    } else {
        echo form_open(base_url() . 'contact/add', $attributes);
    }
    ?>  
    <fieldset>
        <label for="contact">Name</label>
        <input type="text" name="contact" id="contact" class="letxt" value="<?php echo set_value('contact'); ?>" >
    </fieldset>
    <fieldset>
        <label for="contact">Identifier</label>
        <input type="text" name="identifier" id="identifier" class="letxt" value="<?php echo set_value('identifier'); ?>" >
    </fieldset>

    <fieldset>
        <label for="type">Field Type</label>
        <?php if (!empty($types)): ?>
            <select name="type" id="field-type">
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type->id . '_', $type->type; ?>" <?php echo set_select('type', $type->id); ?>><?php echo $type->type; ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

    </fieldset>
    <fieldset>
        <label for="validation_rule">Validation Rule</label>
        <select name="validation_rule" id="validation_rule">
            <option value="string">string</option>
            <option value="email">email</option>
            <option value="date">date</option>
            <option value="number">number</option>
            <option value="telephone">telephone</option>
            <option value="country">country</option>
            <option value="salesperson">salesperson</option>
        </select>
    </fieldset>
    <!-- if selected value is select field type-->

    <fieldset id="select-options">
        <label for="options">Options</label>
        <div class="textarea">
            <textarea name="options" rows="5" cols="10" class="letxtarea"></textarea>
            <p>Leave empty for textbox.<br />Enter one value per line.</p>
        </div>
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="save">
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    $(document).ready(function(){
        $("#contact").keyup(function(){
            var identifier = $(this).val().replace(/\s/g,'_');
            $("#identifier").val(identifier);
        })
    })
</script>