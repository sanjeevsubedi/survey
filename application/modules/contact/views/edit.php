<?php
//var_dump($languages);die;

$attributes = array('name' => 'edit_contact', 'id' => 'edit_contact', 'class' => 'edit_contact');
?>
<div class="formInner">
    <h3>Update Contact Field</h3>
    <?php echo form_open(base_url() . 'contact/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="contact">Name</label>
        <input type="text" name="contact" id="contact" class="letxt" value="<?php echo set_value('contact', $contact->name); ?>" >
    </fieldset>

    <fieldset>
        <label for="contact">Identifier</label>
        <input type="text" name="identifier" id="identifier" class="letxt" value="<?php echo set_value('identifier', $contact->identifier); ?>" >
    </fieldset>
    <fieldset>
        <label for="type">Field Type</label>
        <?php if (!empty($types)): ?>
            <select name="type" id="field-type">
                <option value="<?php echo $contact->field_config_id; ?>"><?php echo $type->type; ?></option>
            </select>
        <?php endif; ?>

    </fieldset>
    <fieldset>
        <label for="validation_rule">Validation Rule</label>
        <select name="validation_rule" id="validation_rule">
            <option value="string" <?php if ($contact->validation_rule == "string") echo "selected = 'selected'"; ?>>string</option>
            <option value="email" <?php if ($contact->validation_rule == "email") echo "selected = 'selected'"; ?>>email</option>
            <option value="date" <?php if ($contact->validation_rule == "date") echo "selected = 'selected'"; ?>>date</option>
            <option value="number" <?php if ($contact->validation_rule == "number") echo "selected = 'selected'"; ?>>number</option>
            <option value="telephone" <?php if ($contact->validation_rule == "telephone") echo "selected = 'selected'"; ?>>telephone</option>
            <option value="country" <?php if ($contact->validation_rule == "country") echo "selected = 'selected'"; ?>>country</option>
            <option value="salesperson" <?php if ($contact->validation_rule == "salesperson") echo "selected = 'selected'"; ?>>sales person</option>
        </select>
    </fieldset>
    <!-- if selected value is select field type-->
    <?php if ($type->type == 'select'): ?>
        <fieldset id="select-options">
            <label for="options">Options</label>
            <div class="textarea">
                <textarea name="options" rows="5" cols="10" class="letxtarea">
                    <?php echo set_value('options', $contact->options); ?>
                </textarea>
                <p>Leave empty for textbox.<br />Enter one value per line.</p>
            </div>
        </fieldset>
    <?php endif; ?>
    <fieldset>
        <label for="translation">Translation</label>
        <div class="contact-trans-wrapper">
            <table id="contact-translation">
                <thead>
                    <tr>
                        <th>english</th>
                        <?php foreach ($languages['langs'] as $language): ?>
                            <?php if ($language->id != 8): ?>     
                                <th><?php echo $language->name; ?></th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $contact->name; ?></td>
                        <?php foreach ($languages['langs'] as $k => $language): ?>
                            <?php if ($language->id != 8): ?> 
                                <?php
                                $contact_field = !empty($languages['translation'][$k]) ? $languages['translation'][$k]->contact_field : "";
                                $contact_field_id = !empty($languages['translation'][$k]) ? $languages['translation'][$k]->contact_field_id : "";
                                ?>
                                <td>
                                    <input type="text" value = "<?php echo $contact_field; ?>" name="clang[<?php echo $language->id; ?>]" />
                                    <input type="hidden" name="cfield_ids[<?php echo $language->id; ?>]" value="<?php echo $contact_field_id; ?>"/>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>

    </fieldset>
    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
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