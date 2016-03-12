<?php
$attributes = array('name' => 'new_language', 'id' => 'new_language', 'class' => 'new_language common-form');
?>
<div class="formInner">
    <?php echo form_open(base_url() . 'language/survey_lang/' . $eid, $attributes); ?> 
    <div id="accordion">
        <h3><label for="question">Current Languages</label></h3>
        <div class="content-container">
            <div class="choosen-langs">
                <p>Choose the language you used within this Questionaire:</p>
            </div>
            <fieldset>
                <div class="input-container top-current-languages">
                    <?php if (!empty($languages)): ?>
                        <select name="upper-language" class="languages" id="upper-langs" <?php echo $has_lang ? 'disabled="disabled"' : "" ?>>
                            <?php foreach ($languages as $language): ?>
                                <option class="<?php echo $language->name; ?>" value="<?php echo $language->id; ?>" <?php echo set_select('language', $language->id, $language->id == $survey->language_id ? TRUE : FALSE); ?>><?php echo $language->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                </div>
                <div class="actions">
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'file/show_docs/' . $eid, 'Back'); ?>
                    </div>
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="final-next"/>

                </div>
            </fieldset>
        </div>

        <h3><label for="question">Add Languages</label></h3>
        <div class="content-container">
            <div id="tr-interface">
                <div class="add-more-langs">
                    <div class="add-more-langs-title">
                        <p>These are the languages you set up so far:</p> 
                    </div>
                    <div class="lang-lists">
                        <?php if (!empty($survey_languages)): ?>
                            <ul>
                                <?php foreach ($survey_languages as $lang_key => $language): ?>
                                    <li class="<?php echo $language['language'] . '-choosen'; ?>">
                                        <?php if (!$language['is_original'] && $language['translation_set_id'] != 0): ?>
                                            <?php echo anchor(base_url() . 'language/translate/' . $eid . '/' . $lang_key, $language['language']); ?>
                                        <?php else: ?>
                                            <?php echo $language['language']; ?>
                                        <?php endif; ?> 


                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                    </div>                    
                </div>
                <?php if (!empty($available_languages)): ?>
                    <div class="choosen-langs">
                        <p>If you would like to add another language, please select below:</p>
                    </div>
                    <fieldset>
                        <div class="selectWrapper input-container">
                            <select name="language" id="language-list">
                                <?php foreach ($available_languages as $lang_id => $language): ?>
                                    <?php //if (!in_array($language->id, $survey_languages_ids)): ?>
                                    <option class="<?php echo $language; ?>" value="<?php echo $lang_id; ?>"><?php echo $language; ?></option>
                                    <?php //endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="actions">
                            <input type="button" name="back" value="Back" class="prev-accordion" />
                            <input type="submit" name="next" value="Next" class="nxt-accordion final-btn"/>
                        </div>
                    </fieldset>
                <?php endif; ?>
            </div>
        </div>

    </div> 
    <?php echo form_close(); ?>
</div>

<!-- popup container-->
<a href="#popup-box" id="popup-trigger"></a>
<div id="popup-box" style="display:none">
    <div class="popup-title">Language</div>
    <div class="popup-body">
        <p>What would you like to complete language now or add another language?</p>
        <input type="submit" name="more" value="Add another language" class="save another" />
        <input type="submit" name="done" value="Complete language" class="save done" />
    </div>
</div>

<script>
    $(document).ready(function(){
        //popup section
        $("#final-next").on('click',function(ev){
            $("#popup-trigger").fancybox({  wrapCSS:'quesitonPopup'}).trigger('click');
            ev.stopPropagation();
            ev.preventDefault();

        })
        
        $(".save").on('click',function(){
            var complete = $(this).hasClass('done');
            if(complete) {
                $('form').submit();
            } else {
                var surveyId = '<?php echo $eid; ?>';
                var langId = $("#upper-langs").val();
                $.post("<?php echo base_url() ?>ajax/index/add_lang/", { "survey_id": surveyId, "lang":langId}, function(response){
                    if(response != "") { 
                        $(".lang-lists").html('');
                        $(".lang-lists").html("<ul><li class='"+response+"-choosen'>"+response+"</li></ul>");
                    }
                    $.fancybox.close();
                }); 
            }
        })
    })
</script>
<script>
    $(document).ready(function(){
        var hasMore = !!'<?php echo $more ?>';
        //initialize accordion
        if(hasMore) {
            accordionHandler("accordion",1);
        } else {
            accordionHandler("accordion");
        }
        
        
        $("#new_language").validate(
        { 
            rules: {
                AccordionField: {
                    required: true
                }
            },
            ignore: []
        }

    );
        var langsUpper = [];
        $("#upper-langs option").each(function(index,value){
            langsUpper.push($(this).attr('class'));
        })
        var prvUpper = $("#upper-langs").prev();
        prvUpper.children('li').each(function(i,v){
            $(this).addClass(langsUpper[i]);
        })
        
        
        //adding languages classes
        var langs = [];
        $("#language-list option").each(function(index,value){
            langs.push($(this).attr('class'));
        })
        var prv = $("#language-list").prev();
        prv.children('li').each(function(i,v){
            $(this).addClass(langs[i]);
        })
        
    })
</script>