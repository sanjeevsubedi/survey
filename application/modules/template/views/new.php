<?php
$attributes = array('name' => 'new_template', 'id' => 'new_template', 'class' => 'new_template');
?>
<div class="formInner">
    <h3>Add template</h3>
    <?php
    echo form_open_multipart(base_url() . 'template/add', $attributes);
    ?>  
    <fieldset>
        <label for="template">Name</label>
        <input type="text" name="template" id="template" class="letxt" value="<?php echo set_value('template'); ?>" >
    </fieldset>
    <!-- <fieldset>
         <label for="template">Font Style</label>
         <input type="text" name="font_style" id="font_style" class="letxt" value="<?php echo set_value('font_style'); ?>" >
     </fieldset>-->

    <fieldset>
        <label for="font_style">Font Family</label>
        <select name="font_style" id="font_style" class="letxt">
            <?php foreach (unserialize(FONT_FAMILY) as $font): ?>
                <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
            <?php endforeach; ?>
        </select>
    </fieldset>

    <fieldset>
        <label for="template">Font color</label>
        <input type="text" name="font_color" id="font_color" class="letxt" value="<?php echo set_value('font_color'); ?>" >
    </fieldset>

    <fieldset class="color-settings">
        <label for="template">Survey Title Settings:</label>
        <span>Font-size: </span>
        <select name="survey_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="survey_title_color" id="survey_title_color" class="letxt" value="<?php echo set_value('survey_title_color'); ?>" >
    </fieldset>

    <fieldset class="color-settings">
        <label for="template">Question Title Settings:</label>
        <span>Font-size: </span>
        <select name="question_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="question_title_color" id="question_title_color" class="letxt" value="<?php echo set_value('question_title_color'); ?>" >
    </fieldset>
    <fieldset class="color-settings">
        <label for="template">Comment Settings:</label>
        <span>Font-size: </span>
        <select name="option_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="option_title_color" id="option_title_color" class="letxt" value="<?php echo set_value('option_title_color'); ?>" >
    </fieldset>

    <!-- <fieldset>
         <label for="template">Background color</label>
         <input type="text" name="bg_color" id="bg_color" class="letxt" value="<?php echo set_value('bg_color'); ?>" >
     </fieldset>
    -->
    <fieldset>
        <label for="bg_img1">Background Scheme 1</label>
        <input type="file" name="bg_img1" id="bg_img1" class="letxt common_logo" />
    </fieldset>
    <fieldset>
        <label for="bg_img2">Background Scheme 2</label>
        <input type="file" name="bg_img2" id="bg_img2" class="letxt common_logo" />
    </fieldset>
    <fieldset>
        <label for="bg_img3">Background Scheme 3</label>
        <input type="file" name="bg_img3" id="bg_img3" class="letxt common_logo" />
    </fieldset>
    <fieldset>
        <label for="bg_img4">Background Scheme 4</label>
        <input type="file" name="bg_img4" id="bg_img4" class="letxt common_logo" />
    </fieldset>

    <div class="nextBtn">
        <input type="submit" value="Next" class="save">
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    //color picker
    $('#font_color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
            $(this).val(hex);
            
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });
    
    $('#survey_title_color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
            $(this).val(hex);
            
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });
    $('#question_title_color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
            $(this).val(hex);
            
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });
    
    $('#option_title_color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
            $(this).val(hex);
            
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });
</script>