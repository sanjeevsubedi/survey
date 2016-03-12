<?php
$attributes = array('name' => 'crm', 'id' => 'crm', 'class' => 'crm common-form');
?>
<div class="formInner">
    <?php echo form_open(base_url() . 'contact/collecton/' . $eid, $attributes); ?>
    <div id="accordion2">
        <h3> <label for="crm address">CRM address</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset id="panel_1">
                <div class="input-container">
                </div>  
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="submit" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>
    </div>
    <?php echo form_close(); ?> 
</div>
<script type="text/javascript">
    $(document).ready(function(){
     
    });
</script>