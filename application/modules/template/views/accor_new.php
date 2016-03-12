<div class="formInner">
    <h3>Survey setup- Create your own design</h3>
    <div id="accordion-new-template">
        <h3> <label for="template">Name of Design</label><span class="status" id="statd0"></span></h3>
        <div class="content-container">
            <fieldset id="paneld_0">
                <label for="template">Please enter a name of a template</label>
                <div class="input-container">
                    <input type="text" name="new_template" id="new_template" class="letxt required" value="<?php echo set_value('template'); ?>" >
                    <div class="error-container"></div>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev-main-form" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="nxt_0" />
                </div>
            </fieldset>
        </div>

        <h3> <label for="bg_image">Background image</label><span class="status" id="statd3"></span></h3>
        <div class="content-container">
            <fieldset id="paneld_3">
                <div class="template-images ipad-img">
                    <label for="font">Please upload a background image for iPad
                        <em>(Format: png, gif, jpg, jpeg, Width:1024px, Height: 768px)</em>
                    </label>
                    <div class="input-container">
                        <div class="background-image" id="img_ipad">
                            <div class="filetype-wrapper">
                                <input type="file" class="common_logo" name="bg_img_ipad" id="bg_img_ipad" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="template-images bg-color">
                    <label for="font">Please choose a background color for mobile devices.
                    </label>
                    <div class="input-container">
                        <div class="background-color">
                            <div class="filetype-wrapper">
                                <input type="text" name="bg_color_mobile" id="bg_color_mobile" class="letxt"  />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="template-images bg-color" id="img_logo">
                    <label for="font">Please choose a logo for mobile devices
                    </label>
                    <div class="input-container">
                        <div class="filetype-wrapper">
                            <input type="file" name="logo_img_mobile" id="logo_img_mobile" value="Upload" />
                        </div>
                    </div>
                </div>



                <div class="clearfix"></div>
                <!-- <div class="template-images galaxy">
                     <label for="font">Please upload a background image for Galaxy 10
                         <em>(Blindtext Format/Image Size/etc... GIF, jpg, png)</em>
                     </label>
                     <div class="input-container">
                         <div class="background-image" id="img_galaxy">
                             <div class="filetype-wrapper">
                                 <input type="file" class="common_logo" name="bg_img_galaxy" id="bg_img_galaxy" />
                             </div>
                         </div>
                     </div>
                 </div>-->
                <div class="clearfix"></div>
                <!--<div class="template-images mobile">
                    <label for="font">Please upload a background image for Mobile
                        <em>(Blindtext Format/Image Size/etc... GIF, jpg, png)</em>
                    </label>
                    <div class="input-container">
                        <div class="background-image" id="img_mobile">
                            <div class="filetype-wrapper">
                                <input type="file" class="common_logo" name="bg_img_mobile" id="bg_img_mobile" />
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="style-guide">
                    <a href="#syle-accor-temp" id="style-accor">
                        Styleguide
                    </a>
                    <div id="syle-accor-temp" style="display: none">text goes here</div>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev_3" />
                    <input type="button" name="next" value="Next" class="nxt-accordion"/>
                </div>

            </fieldset>
        </div> 

        <h3> <label for="font_color">Font</label><span class="status" id="statd2"></span></h3>
        <div class="content-container">
            <fieldset id="paneld_2">
                <label for="font">Please choose a font</label>
                <div class="input-container">
                    <select name="font_style" id="font_style" class="letxt">
                        <?php foreach (unserialize(FONT_FAMILY) as $font): ?>
                            <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label for="font">Please choose a font color</label>
                <div class="input-container">
                    <input type="text" name="font_color" id="font_color" class="letxt" value="<?php echo set_value('font_color'); ?>" >
                </div>


                <label for="template">Survey Title Settings:</label>  
                <div class="input-container color-settings">
                    <span>Font-size: </span>
                    <select name="survey_font_size" class="letxt">
                        <?php for ($i = 10; $i <= 35; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
                        <?php endfor; ?>
                    </select>
                    <span>Font-color: </span>
                    <input type="text" name="survey_title_color" id="survey_title_color" class="letxt" value="<?php echo set_value('template'); ?>" >
                </div>



                <label for="template">Question Title Settings</label>
                <div class="input-container color-settings">
                    <span>Font-size: </span>
                    <select name="question_font_size" class="letxt">
                        <?php for ($i = 10; $i <= 35; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
                        <?php endfor; ?>
                    </select>
                    <span>Font-color: </span>
                    <input type="text" name="question_title_color" id="question_title_color" class="letxt" value="<?php echo set_value('template'); ?>" >

                </div>

                <label for="template">Comment Settings:</label>
                <div class="input-container color-settings">
                    <span>Font-size: </span>
                    <select name="option_font_size" class="letxt">
                        <?php for ($i = 10; $i <= 35; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i . 'px'; ?></option>
                        <?php endfor; ?>
                    </select>
                    <span>Font-color: </span>
                    <input type="text" name="option_title_color" id="option_title_color" class="letxt" value="<?php echo set_value('option_title_color'); ?>" >
                </div>


                <div class="clearfix"></div>
                <a id="fontpreview" href="#btnForms" class="preview-btn" >Preview</a>

                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev_2" />
                    <input type="submit" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialize the accordion
        accordionHandler("accordion-new-template");

        var mainIntro = $("#main-form-intro");
        var newTmp = $("#new-template");
        var iPadTemplate = "";
        var galaxyTemplate = "";
        var mobileTemplate = "";
        var mobileLogo = "";
        $('#bg_img_ipad').ajaxfileupload({
            'action': '<?php echo base_url() ?>ajax/index/image_upload_info',
            'params': {
                'type': 'template',
                'file_name': 'bg_img_ipad',
                'temp_insert':'yes',
                'temp_image':'ipad'
            },
            'onComplete': function(response) {
                console.log(response);
                console.log('custom handler for file:');
                if(response.status == false) {
                    return false;
                }
                iPadTemplate = response;
                var previewLink = '<div class="previewWrapper clearfix">'+
                    '<input type="hidden" name="ipad_img" value = "'+iPadTemplate+'" /><a id="ipad_preview" href="#" class="preview-btn">Preview</a></div>';                
                $("#img_ipad").next().remove();
                $(previewLink).insertAfter("#img_ipad");
                //change the icon color
                $("#img_ipad .filetype-wrapper").addClass("uploaded-icon");

            },
            validate_extensions : true
        });
        
        $('#bg_img_galaxy').ajaxfileupload({
            'action': '<?php echo base_url() ?>ajax/index/image_upload_info',
            'params': {
                'type': 'template',
                'file_name': 'bg_img_galaxy',
                'temp_insert':'yes',
                'temp_image':'galaxy'
            },
            'onComplete': function(response) {
                console.log('custom handler for file:');
                if(response.status == false) {
                    return false;
                }
                galaxyTemplate = response;
                var previewLink = '<div class="previewWrapper clearfix">'+
                    '<input type="hidden" name="galaxy_img" value = "'+galaxyTemplate+'" /><a id="galaxy_preview" href="#" class="preview-btn">Preview</a></div>';                
                $("#img_galaxy").next().remove();
                $(previewLink).insertAfter("#img_galaxy");
                //change the icon color
                $("#img_galaxy .filetype-wrapper").addClass("uploaded-icon");
            }
        });
        $('#bg_img_mobile').ajaxfileupload({
            'action': '<?php echo base_url() ?>ajax/index/image_upload_info',
            'params': {
                'type': 'template',
                'file_name': 'bg_img_mobile',
                'temp_insert':'yes',
                'temp_image':'mobile'
            },
            'onComplete': function(response) {
                console.log('custom handler for file:');
                if(response.status == false) {
                    return false;
                }
                mobileTemplate = response;
                var previewLink = '<div class="previewWrapper clearfix">'+
                    '<input type="hidden" name="mobile_img" value = "'+mobileTemplate+'" /><a id="mobile_preview" href="#" class="preview-btn">Preview</a></div>';                
                $("#img_mobile").next().remove();
                $(previewLink).insertAfter("#img_mobile");
                //change the icon color
                $("#img_mobile .filetype-wrapper").addClass("uploaded-icon");
            }
        });
        
        
        $('#logo_img_mobile').ajaxfileupload({
            'action': '<?php echo base_url() ?>ajax/index/image_upload_info',
            'params': {
                'type': 'logo',
                'file_name': 'logo_img_mobile',
                'temp_insert':'yes',
                'temp_image':'mobile'
            },
            'onComplete': function(response) {
                console.log('custom handler for file:');
                if(response.status == false) {
                    return false;
                }
                mobileLogo = response;
                var previewLink = '<div class="previewWrapper clearfix">'+
                    '<input type="hidden" name="mobile_logo" value = "'+mobileLogo+'" /><!--<a id="logo_preview" href="#" class="preview-btn">Preview</a>--></div>';                
                $("#img_logo").next().remove();
                $(previewLink).insertAfter("#img_logo");
                //change the icon color
                $("#img_logo .filetype-wrapper").addClass("uploaded-icon");
            }
        });
        
        

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
        
        $('#bg_color_mobile').ColorPicker({
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
        
        
        //ajax callback to preview the new template
        $("#ipad_preview, #galaxy_preview, #mobile_preview, #fontpreview").live('click',function() {
            var type = $(this).attr('id');
            var temp = "";
            if(type == "ipad_preview") {
                temp = iPadTemplate;
            } else if(type == "galaxy_preview") {
                temp = galaxyTemplate;
            } else if(type == "mobile_preview") {
                temp = mobileTemplate;
            }
            
            var tfont = "";
            var tfontcolor = "";
            var tlogo = "";
            
            if(type == 'fontpreview') {
                tfont = $("#font_style").val();
                tfontcolor = $("#font_color").val();
                temp = iPadTemplate;
            }
            
            /*$.post("<?php echo base_url() ?>ajax/index/preview_new_template/", {template:temp,font:tfont,logo:tlogo,fontcolor:tfontcolor }, function(theResponse){
                //console.log(theResponse);
                $("#btnForms").html(theResponse);
            });*/
            
            
            $.fancybox({
                wrapCSS:'logo-prv',
                href : '<?php echo site_url("ajax/index/preview_new_template") ?>',
                type : 'ajax',
                ajax : {
                    type: 'POST',
                    data: {
                        logo: tlogo,
                        template: temp,
                        fontcolor: tfontcolor,
                        font: tfont
                    }
                }
            })
            
        });
        
        //gives the previous form
        $("#prev-main-form").live('click',function() {
            newTmp.toggle("slide", { direction: "left" }, 1000,function(){
                newTmp.html('');
                mainIntro.css('display','block');
                $("#new_design").removeAttr('checked');
                $("#new_design").prev().prev().removeClass('jqTransformChecked');
                 
            });
            return false;     
        });
    
    });
</script>