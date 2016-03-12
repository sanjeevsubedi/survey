<?php
$attributes = array('name' => 'step_2', 'id' => 'step_2', 'class' => 'step_2 common-form');
$draggable = "draggable ui-draggable";
?>
<div class="lists formInner">

    <?php echo form_open(base_url() . 'survey/step_2/' . $eid, $attributes); ?>
    <div id="accordion2">
        <h3> <label for="contact_form">Contact Form</label><span class="status" id="stat1"></span></h3>
        <div class="content-container contact-field-container">
            <fieldset id="panel_1">
                <div class="input-containerR">
                    <div class="lists dragContainer">
                        <p class="dragTitle">Please add the fields you would like to have in your contact Form.</p>
                        <div class="alignCenter">
                            <div class="dragBox">
                                <div class="contact_fields" id="origin">
                                    <?php if (!empty($contacts)): ?>
                                        <?php foreach ($contacts as $contact): ?>
                                            <!--default contact fields are not shown on this box-->
                                            <?php if (!in_array($contact->id, $default_contacts)): ?>                                    
                                                <?php if (!in_array($contact->id, $contact_form_fields)): ?>
                                                    <div class="remaining_contact_fields draggable cfields">
                                                        <input type="hidden" name="choosen_field[]" class="choosen_fields" value="<?php echo $contact->id; ?>" />
                                                        <?php echo $contact->name; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="sepratorAro">&nbsp;</div>
                            <div class="dragBox">
                                <div class="contact-destination" id="drop">  
                                    <span class="pullFrequired">Pull Field Required</span>

                                    <!--Default contacts fields are shown for the first time-->
                                    <?php if (empty($contact_form_fields_objs)): ?>
                                        <?php foreach ($default_contacts_objs as $default_contacts_obj): ?>
                                            <div class="choosen_contact_fields cfields">
                                                <input type="hidden" name="choosen_field[]" value="<?php echo $default_contacts_obj->id; ?>" />
                                                <?php echo $default_contacts_obj->name; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if (!empty($contact_form_fields_objs)): ?>
                                        <?php foreach ($contact_form_fields_objs as $contact_form_fields_obj): ?>
                                            <div class="choosen_contact_fields cfields <?php echo!in_array($contact_form_fields_obj->contact_form_field_id, $default_contacts) ? $draggable : '' ?>">
                                                <input type="hidden" name="choosen_field[]" value="<?php echo $contact_form_fields_obj->contact_form_field_id; ?>" />
                                                <?php echo $contact_form_fields_obj->name; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div><!--/.dragBox-->
                        </div>

                        <!--<a class="likeTochange" href="<?php echo base_url() ?>contact/add/1/<?php //echo $eid;                                                                                                                                                                                     ?>"><div>If you would like a add field in form click here</div></a>-->
                    </div>
                </div>  
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" id="choose_contact_field" />
                    <input type="button" name="next" value="Next" class="nxt-accordion-contact" id="show_contact_field" />
                </div>
            </fieldset>
            <fieldset id="panel1-ajax" style="display:none;"></fieldset>
        </div>


    </div>

    <?php echo form_close(); ?> 
</div>
<script type="text/javascript">
    $(document).ready(function(){
        
        // custom form elements
        $("form.new_survey, form.common-form").jqTransform();
        //initialize the accordion
        accordionHandler("accordion2");
        
        $("#step_2").validate(
        { 
            rules: {
                AccordionField: {
                    required: true
                }
            },
            ignore: []

        }

    );
    
        //choose contact field
        $("#show_contact_field").live("click",function(){
            var contactFieldIds = [];
            var nextBtn = $(this);
            var parentContainer = $(".contact-field-container");
            var fieldsetContainer = $(".contact-field-container fieldset");
            var newFieldConatainer = $("#panel1-ajax");

            if($("#drop .cfields").length == 0) {
                alert("Contact field is required.");
                return false;
            }

            $("#drop .cfields").each(function(){

                contactFieldIds.push($(this).find("input").attr('value'));
            })
            
            console.log(contactFieldIds);
            
            $.post("<?php echo base_url() ?>ajax/index/choosen_contact_fields/", {
                "contact_ids": contactFieldIds, "survey_id": <?php echo $eid; ?>}, function(theResponse){
                //console.log(theResponse);
                fieldsetContainer.toggle("slide", { direction: "left" }, 1000,function(){
                    fieldsetContainer.hide();
                    newFieldConatainer.css('display','block');
                });
                
                newFieldConatainer.load("<?php echo base_url() . 'survey' . DS . 'ajax_choosen_contact_field' . DS . $eid ?>"
                , function() {
                }    
            );
                
            });   
        })
        
        
        
        //drag and drop functionality
        $(".draggable").draggable({
            cursor: "crosshair",
            revert: "invalid"
        });
        $("#drop").droppable({
            accept: ".draggable",
            drop: function (event, ui) {
                console.log("drop");
                $(this).removeClass("border").removeClass("over");
                var dropped = ui.draggable;
                var droppedOn = $(this);
                $(dropped).detach().css({
                    top: 0,
                    left: 0
                }).appendTo(droppedOn);


            },
            over: function (event, elem) {
                $(this).addClass("over");
                console.log("over");
            },
            out: function (event, elem) {
                $(this).removeClass("over");
            }
        });
        $("#drop").sortable();

        $("#origin").droppable({
            accept: ".draggable",
            drop: function (event, ui) {
                console.log("drop");
                $(this).removeClass("border").removeClass("over");
                var dropped = ui.draggable;
                var droppedOn = $(this);
                $(dropped).detach().css({
                    top: 0,
                    left: 0
                }).appendTo(droppedOn);
            }
        });
     
    });
</script>