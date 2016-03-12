<?php
$attributes = array('name' => 'new_survey', 'id' => 'new_survey', 'class' => 'new_survey');
?>
<?php echo form_open_multipart(base_url() . 'survey/step_1/' . $eid, $attributes); ?>
<div id="new-template" style="overflow:hidden;"></div>
<div class="formInner" id="main-form-intro">
    <h3>Survey setup</h3>
    <div id="accordion2">
        <h3><label for="survey">Name of Questionnaire</label><span class="status" id="stat0"></span></h3>
        <div class="content-container">
            <fieldset id="panel_0">
                <label for="survey">Please enter name of Questionaire</label>
                <div class="input-container"><input type="text" name="survey" value="<?php echo set_value('survey', !empty($survey) ? $survey->name : '') ?>" id="survey" class="letxt required"></div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev_0" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="nxt_0" />
                </div>
            </fieldset>
        </div>
        <h3> <label for="device">Choose Devices</label><span class="status" id="stat1"></span></h3>
        <div class="content-container">
            <fieldset id="panel_1">
                <label for="device">Please Choose the devices that will be used</label>
                <div class="devices input-container">
                    <?php if (!empty($devices)): ?>
                        <?php foreach ($devices as $device): ?>

                            <?php
                            //ipad is checked by default when survey is new otherwise according to selected device
                            $cond = '';
                            if (!empty($survey)) {
                                $cond = in_array($device->id, $survey_devices) ? TRUE : FALSE;
                            } else {
                                if ($device->name == "ipad") {
                                    $cond = TRUE;
                                }
                            }
                            ?>

                            <p><input type="checkbox" value="<?php echo $device->id; ?>" class="required" name="device[]" <?php echo set_checkbox('device', $device->id, $cond); ?>/><small><?php echo $device->name; ?></small></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev_1" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="nxt_1" />
                </div>
            </fieldset>
        </div>

 <?php
                            
                            $scan_cond = '';
                            if (!empty($survey)) {
                                $scan_cond = $survey->scan_only ? TRUE : FALSE;
                            } else {
                                    $scan_cond = FALSE;
                                
                            }
                            ?>
                            <?php if($scan_cond || empty($survey)):?>
                <h3> <label for="scan_only">Scan Mode app</label><span class="status" id="stat2"></span></h3>
        <div class="content-container">
            <fieldset id="panel_1">
                <label for="scan_only">Is scan mode app?</label>
                <div class="devices input-container">

                       
                           

                      <p><input type="checkbox" value="1" class="" <?php if($scan_cond) echo 'disabled="disabled"'; ?> name="scan_only" <?php echo set_checkbox('is_scan_app', $survey->scan_only, $scan_cond); ?>/></p>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="prev_1" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="nxt_1" />
                </div>
            </fieldset>
        </div>
<?php endif;?>
        <h3><label for="design">Design</label><span class="status" id="stat3"></span></h3>
        <div class="content-container" id="template-section">
            <fieldset id="panel_3">
                <div class="template_categories_wrappper">
                    <div class="template_category">
                        <?php if (!empty($templates)): ?>
                            <div id="existing-templates-container">
                                <div class="exisiting-designs">
                                    <input type="radio" name="template_type" value="own-existing" <?php echo set_checkbox('template_type', 'own-existing', !empty($survey) ? $survey->template_owner == 'o' ? TRUE : FALSE  : "") ?> class="required" id="existing-temp-check" />
                                    <label>Please choose one of your existing Designs:</label>
                                    <select name="template" id="template">
                                        <?php foreach ($templates as $template): ?>
                                            <option value="<?php echo $template->id; ?>" <?php echo set_select('template', $template->id, !empty($survey) ? $template->id == $survey->template_id ? TRUE : FALSE  : ''); ?>><?php echo $template->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <span class="else">Or else</span> 
                        <?php endif; ?>
                        <div id="system-templates-grid">
                            <input type="radio" name="template_type" value="system" id="system-temp-check" <?php echo set_checkbox('template_type', 'system', empty($survey) ? TRUE : $survey->template_owner == 's' ? TRUE : FALSE); ?> class="required"/>
                            <label style="width:90%; margin-bottom:30px;">choose one of the following System-Templates or upload your own Design:</label>
                            <div class="clearfix"></div>
                            <table class="system-templates">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Color Scheme 1</th>
                                    <th>Color Scheme 2</th>
                                    <th>Color Scheme 3</th>
                                    <th>Color Scheme 4</th>
                                </tr>
                                <?php foreach ($template_schemes as $template_scheme): ?>
                                    <tr>
                                        <td><?php echo $template_scheme->name; ?></td>
                                        <?php foreach ($template_scheme->schemes as $scheme): ?>
                                            <td>
                                                <input type="radio" name="tpl" <?php echo set_checkbox('tpl', $scheme->id, !empty($survey) ? ($survey->template_id == $scheme->id && $survey->template_owner == 's') ? TRUE : FALSE  : ""); ?> value ="<?php echo $scheme->id ?>" />
                                                <div class="scheme-preview">
                                                    <img src="<?php echo base_url() . 'uploads' . DS . 'template' . DS . $scheme->bg_image_ipad; ?>" width="100" height="100" />
                                                </div>
                                            <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="own-design-tpl"><span>Own Design*</span></td>
                                    <td class="own-design-box"><span><input type="radio" name="template_type" value="new" />Upload your own Design</span></td>
                                    <td colspan="3" class="own-design-text">*This is an <span>advanced setting</span> where you can upload your own design within the System-Graphical-Requirements.</td>
                                </tr>
                            </table>
                        </div>
                    </div> 
                </div>
                <div id="btnForms" style="display:none"></div>
                <div class="own_logo logo-container clearfix" style="display:none">
                    <span>
                        <label>Upload your logo</label>
                        <em>(Format: png, gif, jpg, jpeg height: 60px, width: 111px)</em>
                    </span>
                    <div class="logo-right-container">
                        <div class="filetype-wrapper upload-icon" id="logo-img-wrapper">
                            <input type="file" name="logo_img" class="common_logo" value="Upload" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                    <?php if (!empty($survey->logo)): ?>
                        <div class="logo-option">
                            <a  href="#" id="exisiting_preview" data-logo="<?php echo $survey->logo; ?>" class="view-logo">Preview Existing Logo</a>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="intro-back" />
                    <input type="submit" name="next" value="Next" class="nxt-accordion" id="intro-next"/>
                </div>
            </fieldset>
        </div>

    </div>
</div>

<!-- styleguide box-->
<a id="data-styleguide" href="#data-styleguide-container"></a>
<div id="data-styleguide-container" style="display:none;width:400px;">
    <div class="guide">STYLEGUIDE</div>
    <p>If you want to upload your own design, you need to...</p>
    <div class="creat-own-bg">
        <h3>Create your own background image</h3>
        <p><i>Please create a background image for your survey.</i></p>
        <p>Format: <span>png, gif, jpg, jpeg</span></p>
        <p>Size: <span>Width: 1024px, Height: 768px</span></p>
    </div>

    <div class="include-your-logo">
        <h3>Include your Logo</h3>
        <p><i>Please include your Logo within the background Image, in the top left corner</i></p>
        <p>Format: <span>png, gif, jpg, jpeg</span></p>
        <p>Size: <span>Width: 111px, Height: 60px</span></p>
    </div>

    <div class="choose-font">
        <h3>Choose a Font</h3>
        <p><i>Please choose Font Type and Colour for your Survey within the drop down menu.</i></p>
    </div>

    <div class="need-help-design">
        <h3>Need help creating your own Design?</h3>
        <span><a href="#">» contact us</a></span>
        <span><a href="#">» download Styleguide as PDF</a></span>
    </div>
    <ul class="later-now">
        <li><a href="#" id="uploadlater">Upload later</a></li>
        <li><a href="#" id="uploadnow">Upload now</a></li>
    </ul>
</div>



<?php echo form_close(); ?>

<script type="text/javascript">
    var surveyEdit = !!('<?php echo isset($eid) && !empty($eid) ? TRUE : FALSE; ?>');
    var tempOwner = !!('<?php echo isset($eid) && !empty($eid) && $survey->template_owner == 'o' ? TRUE : FALSE; ?>'); 
    var tplType = $("input:radio[name=template_type]");
    var logoCont = $(".logo-container");
    var systemTpl = $("#system-temp-check");
    //default checked on system templates 
    if($(systemTpl).attr('checked')) {
        if(!surveyEdit) {
            $("input:radio[name=tpl]:first").attr('checked', true);
        }
    }
    
    tplType.on('click',function(){
        var type = $(this).attr('value');
        if(type == "own-existing" || type == "new") {
            if($(this).attr('checked',true)) {
                $("input:radio[name=tpl]").attr('checked',false).attr('disabled',true);
                $("a.jqTransformRadio").removeClass('jqTransformChecked');
            } 
        } else if(type == "system") {
            //add section
            if(($(this).attr('checked',true) && !surveyEdit) || tempOwner) {
                $("input:radio[name=tpl]:first").attr('checked', true);
                $("input:radio[name=tpl]").attr('disabled',false);
                $("input:radio[name=tpl]:first ").prev().addClass('jqTransformChecked');
            } else {
                var selectedSchemeId = '<?php echo (isset($survey->template_id) && !empty($survey->template_id)) ? $survey->template_id : ''; ?>';
                $("input:radio[name=tpl][value="+selectedSchemeId+"]").attr('checked', true);
                $("input:radio[name=tpl]").attr('disabled',false);
                $("input:radio[name=tpl][value="+selectedSchemeId+"]").prev().addClass('jqTransformChecked');
            }
        } 
    })
  
    $("#intro-next").on('click',function(){
        var type = $("input:radio[name=template_type]:checked").val();
        
        if(type == "own-existing") {
            return true;
        } else if(type == "system") {
            if(systemTpl.attr('checked') && !logoCont.is(":visible")) {
                $(".template_category").toggle("slide", { direction: "left" }, 2000,function(){
                    $(logoCont).toggle("slide", { direction: "left" }, function(){
                        $(".template_category").hide();
                        $(logoCont).show();
                    });
                });
                
            }
            
            var newLogo = $("input:hidden[name=logo]");
            if(!surveyEdit) {
                //logo is required
                if(newLogo.length == 0 && logoCont.is(":visible")) {
                    alert("logo is required");
                    return false;
                } else if(newLogo.length == 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                var existingLogo = $("#exisiting_preview");
                if(existingLogo.length > 0 && logoCont.is(":visible")) {
                    return true
                } else {
                    //logo is required
                    if(newLogo.length == 0 && logoCont.is(":visible")) {
                        alert("logo is required");
                        return false;
                    } else if(newLogo.length == 0) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }

        } else if(type == "new") {
            //show the popup
            $("#data-styleguide").fancybox({  wrapCSS:'styleguide'}).trigger('click');
        }
        return false;
    })
    
    //routes to new template upload section
    $("#uploadnow").on('click',function(){
        $.fancybox.close();
        if ($('#new_survey').valid()) {
            $(this).attr('checked','checked');
            var companyId = <?php echo $company_id; ?>;
            var mainIntro = $("#main-form-intro");
            var newTmp = $("#new-template");
            mainIntro.toggle("slide", { direction: "left" }, 1000,function(){
                newTmp.html("<div class='ajax-loader'><img src="+"<?php echo base_url() . 'assets' . DS . 'img' . DS . 'ajax-loader.gif'; ?> /></div>");
                newTmp.load("<?php echo base_url() . 'template' . DS . 'ajax_create_template' ?>?company_id="+companyId);
                mainIntro.hide();
                newTmp.css('display','block');
            });
            return false;     
        }
        else {
            alert("Please fill above required fields");
            return false;
        }
    })
    
    //upload later section closes the popup box
    $("#uploadlater").on('click',function(){
        $.fancybox.close();
        $("#new_survey").submit();
        return true;
    })
    
    $("#intro-back").on('click',function(e){
        if(logoCont.is(":visible")) {
            $(logoCont).toggle("slide", { direction: "left" }, function(){
                $("#template-section, .template_category").show();
                $("#ui-accordion-accordion2-panel-1").hide();
                $(logoCont).hide();
            });
        } else {
            $(".content-container").hide();
            $("#ui-accordion-accordion2-panel-1").show();
        }
        
    })
    
    // fancybox instances initialization
    //$("#exisiting_preview").fancybox({wrapCSS:'template-prv'});
    //$("#logopreview").fancybox({wrapCSS:'templogopreview'});
    $("#fontpreview").fancybox();
    $("#ipad_preview").fancybox();
    $("#galaxy_preview").fancybox();
    $("#mobile_preview").fancybox();

    //show the popup in the own design page
    $("#style-accor").fancybox({  wrapCSS:'styleguide'});

    //initialize accordion
    accordionHandler("accordion2");
  
    $("#new_survey").validate(
    { 
        rules: {
            AccordionField: {
                required: true
            }
        },
        ignore: []
    });
    
    var tempLogo = "";
    $('input[type="file"]').ajaxfileupload({
        'action': '<?php echo base_url() ?>ajax/index/image_upload_info',
        'params': {
            'type': 'logo',
            'temp_insert': 'yes',
            'file_name': 'logo_img'
        },
        'onComplete': function(response) {
            console.log('custom handler for file:');
            if(response.status == false) {
                return false;
            }
            tempLogo = response;
            var previewLink = '<div class="previewWrapper clearfix" style="margin-left:0">'+
                '<input type="hidden" name="logo" value = "'+tempLogo+'" /><a class="preview-btn" href="#" id="logopreview">Preview</a>';                
            $("#logo-img-wrapper").next().remove();
            $(previewLink).insertAfter("#logo-img-wrapper");
            //change the icon color to gray
            var divWrapper= $("#logo-img-wrapper");
            if(divWrapper.hasClass("upload-icon")) {
                divWrapper.removeClass("upload-icon");
                divWrapper.addClass("uploaded-icon");
            }
        }
    });
    
    //ajax callback to preview the logo
    $("#logopreview").live('click',function() {
        var swap = tempLogo;
        var template_id = $('input[name=tpl]:checked').val();
        $.fancybox({
            wrapCSS:'template-prv',
            href : '<?php echo site_url("ajax/index/preview_template") ?>',
            type : 'ajax',
            ajax : {
                type: 'POST',
                data: {
                    logo: swap,
                    template_id: template_id,
                    survey_id: '<?php echo!empty($eid) ? $eid : 0; ?>'
                }
            }
        })
        return false;
    });
    
    
    //ajax callback to preview the template
    $("#exisiting_preview").on('click',function() {
        var swap = $(this).attr('data-logo');
        var template_id = $('input[name=tpl]:checked').val();
        $.fancybox({
            wrapCSS:'logo-prv',
            href : '<?php echo site_url("ajax/index/preview_template") ?>',
            type : 'ajax',
            ajax : {
                type: 'POST',
                data: {
                    logo: swap,
                    template_id: template_id,
                    survey_id: '<?php echo!empty($eid) ? $eid : 0; ?>'
                }
            }
        })
        return false;
    });

       
    //ajax callback to remove the logo
    $(".remove-logo").click(function() {
        var survey_id = $(this).attr('rel');
        $.post("<?php echo base_url() ?>ajax/index/del_logo/", { "survey_id": survey_id}, function(theResponse){
            //console.log(theResponse);
            $('.remove-logo').remove();
            $(".view-logo").remove();
            return false;
        });                                                              
    });
       
    //});
</script>