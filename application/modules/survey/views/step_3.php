<?php
$attributes = array('name' => 'step_3', 'id' => 'step_3', 'class' => 'step_3 common-form');
?>
<div class="lists">
    <p>Please add the fields you would like to have in your contact Form.</p>
    <fieldset class="dragLists">
        <div class="dragBox">
            <h3 class="titleB">Fields</h3>
            <div class="contact_fields" id="origin">
                <?php if (!empty($contacts)): ?>
                    <?php foreach ($contacts as $contact): ?>
                        <?php if (!in_array($contact->id, $contact_form_fields)): ?>

                            <div class="remaining_contact_fields draggable">
                                <input type="hidden" name="choosen_field[]" value="<?php echo $contact->id; ?>" />
                                <?php echo $contact->name; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php echo form_open(base_url() . 'survey/step_3/' . $eid, $attributes); ?>
        <div class="dragBox">
            <h3 class="titleB">Pull Field</h3>
            <div class="contact-destination" id="drop">      	
                <?php if (!empty($contact_form_fields_objs)): ?>
                    <?php foreach ($contact_form_fields_objs as $contact_form_fields_obj): ?>
                        <div class="choosen_contact_fields draggable ui-draggable">
                            <input type="hidden" name="choosen_field[]" value="<?php echo $contact_form_fields_obj->contact_form_field_id; ?>" />
                            <?php echo $contact_form_fields_obj->name; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div><!--/.dragBox-->
        <div style="clear: both;"></div>
    </fieldset> 
    <fieldset>
        <div class="backBtn">
            <?php echo anchor(base_url() . 'survey/step_2/' . $eid, 'Back'); ?>
        </div>
        <div class="nextBtn">
            <input type="submit" name="next" value="Next">
        </div>
    </fieldset>

    <a class="likeTochange" href="<?php echo base_url() ?>contact/add/1/<?php echo $eid; ?>"><div>If you would like a add field in form click here</div></a>
    <?php echo form_close(); ?> 
</div>



<script>
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
</script>