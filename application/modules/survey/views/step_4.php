<?php
$attributes = array('name' => 'step_4', 'id' => 'step_4', 'class' => 'step_4 common-form');
?>
<div class="lists">
    <p class="description">* If a field is to be Compulsory please use teh check box, field can also be moved to the correct postition</p> 
    <h3>Contact Form</h3>
    <?php echo form_open(base_url() . 'survey/step_5/' . $eid, $attributes); ?>  
    <fieldset>
        <div id="contact-fields">
            <?php $i = 1; ?>
            <?php if (!empty($contacts)): ?>
                <ul>
                    <?php foreach ($contacts as $contact): ?>
                        <li id="field_<?php echo $contact->contact_form_field_id; ?>">
                        	<div class="checkfields">
                                <input type="checkbox" name="required[]" value="<?php echo $contact->contact_form_field_id; ?>" <?php echo set_checkbox('required', $contact->contact_form_field_id, in_array($contact->contact_form_field_id, $required_form_fields)) ?>/>
                                <label><?php echo $contact->name; ?></label>
                            </div>
                            <?php if ($contact->type == 'textbox'): ?>
                                <input type="text" name="<?php echo $contact->contact_form_field_id; ?>" />
                            <?php endif; ?>

                            <?php if ($contact->type == 'select'): ?>
                                <?php
                                $options = $contact->options;
                                $options = explode("\n", $options);
                                ?>
                                <select name="<?php echo $contact->contact_form_field_id; ?>">
                                    <?php foreach ($options as $option): ?>
                                        <option value=""><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </li>
                        <?php $i++; ?> 
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </div>
    </fieldset> 
    <fieldset>
    	<div class="backBtn">
	        <?php echo anchor(base_url() . 'survey/step_3/' . $eid, 'Back'); ?>
        </div>
        <div class="nextBtn">
            <input type="submit" name="next" value="Next">
        </div>
    </fieldset>
    <?php echo form_close(); ?> 
</div>


<script type="text/javascript">
    $(document).ready(function(){ 
						   
        $(function() {
            $("#contact-fields ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
                    var order = $(this).sortable("serialize");
                    //alert(order);
                    $.post("<?php echo base_url() ?>ajax/index", order, function(theResponse){
                        //$("#contentRight").html(theResponse);
                    }); 															 
                }								  
            });
        });

    });	
</script>


