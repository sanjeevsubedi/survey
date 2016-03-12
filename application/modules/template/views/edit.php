<?php
$attributes = array('name' => 'edit_template', 'id' => 'edit_template', 'class' => 'edit_template');
?>
<div class="formInner">
    <h3>Update template</h3>
    <?php echo form_open_multipart(base_url() . 'template/edit/' . $eid, $attributes); ?>  
    <fieldset>
        <label for="template">Name</label>
        <input type="text" name="template" id="template" class="letxt" value="<?php echo set_value('template', $template->name); ?>" >
    </fieldset>
    <!--<fieldset>
        <label for="font_style">Font family</label>
        <input type="text" name="font_style" id="font_style" class="letxt" value="<?php echo set_value('font_style', $template->font_style); ?>" >
    </fieldset>-->
    <fieldset>
        <label for="font_style">Font Family</label>
        <select name="font_style" id="font_style" class="letxt">
            <?php foreach (unserialize(FONT_FAMILY) as $font): ?>
                <?php echo $template->font_style; ?>
                <option value="<?php echo $font; ?>" <?php if ($template->font_style == $font) echo "selected = 'selected'" ?>><?php echo $font; ?></option>
            <?php endforeach; ?>
        </select>
    </fieldset>
    <fieldset>
        <label for="font_color">Font color</label>
        <input type="text" name="font_color" id="font_color" class="letxt" value="<?php echo set_value('font_color', $template->font_color); ?>" >
    </fieldset>

    <fieldset class="color-settings">
        <label for="template">Survey Title Settings:</label>
        <span>Font-size: </span>
        <select name="survey_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if ($template->survey_title_size == $i) echo "selected = 'selected'" ?>><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="survey_title_color" id="survey_title_color" class="letxt" value="<?php echo set_value('survey_title_color', $template->survey_title_color); ?>" >
    </fieldset>

    <fieldset class="color-settings">
        <label for="template">Question Title Settings:</label>
        <span>Font-size: </span>
        <select name="question_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if ($template->question_title_size == $i) echo "selected = 'selected'" ?>><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="question_title_color" id="question_title_color" class="letxt" value="<?php echo set_value('question_title_color', $template->question_title_color); ?>" >
    </fieldset>

    <fieldset class="color-settings">
        <label for="template">Comment Settings:</label>
        <span>Font-size: </span>
        <select name="option_font_size" class="letxt">
            <?php for ($i = 10; $i <= 35; $i++): ?>
                <option value="<?php echo $i; ?>" <?php if ($template->option_size == $i) echo "selected = 'selected'" ?>><?php echo $i . 'px'; ?></option>
            <?php endfor; ?>
        </select>
        <span>Font-color: </span>
        <input type="text" name="option_title_color" id="option_title_color" class="letxt" value="<?php echo set_value('option_title_color', $template->option_title_color); ?>" >
    </fieldset>
    <!--<fieldset>
        <label for="bg_color">Background color</label>
        <input type="text" name="bg_color" id="bg_color" class="letxt" value="<?php echo set_value('bg_color', $template->bg_color); ?>" >
    </fieldset>
    -->
    <?php
//var_dump($template_schemes);die;
    ?>

    <?php foreach ($template_schemes as $k => $template_scheme): ?>
        <?php $k++; ?>
        <fieldset>
            <label for="bg_img">Background Scheme <?php echo $k; ?></label>
            <div>       
                <input type="file" name="bg_img<?php echo $k; ?>" id="bg_img<?php echo $k; ?>" class="letxt common_logo" />
                <?php if (!empty($template_scheme->bg_image_ipad)): ?>
                    <a href="<?php echo base_url() . DS . 'uploads' . DS . 'template' . DS . $template_scheme->bg_image_ipad ?>" target="_blank" class="view-logo" id="viewl<?php echo $template_scheme->id; ?>" >View Template</a>
                <?php endif; ?>
            </div>
        </fieldset>
    <?php endforeach; ?>




    <div class="nextBtn">
        <input type="submit" value="Next" class="update">
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
    
    //ajax callback to preview the template
    /*$(".remove-tmp").click(function() {
        var template_id = $(this).attr('rel');
        $.post("<?php //echo base_url()           ?>ajax/index/del_template/", { "template_id": template_id}, function(theResponse){
            //console.log(theResponse);
            $('.rem'+template_id).remove();
            $(".viewl"+<?php //echo $template_scheme->id;           ?>).remove();
            return false;
        });                                                              
    });*/
</script>