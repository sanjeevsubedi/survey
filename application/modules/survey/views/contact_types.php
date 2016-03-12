<?php
$tooltip = array(
    'contact-form' => "'Contact Collection'
Lorem ipsum dolor sit
amet, consetetur dolore
magna aliquyam erat,
sed diam voluptua.",
    'badge-scan' => "'Badge Scan'
Lorem ipsum dolor sit
amet, consetetur dolore
magna aliquyam erat,
sed diam voluptua.",
    'business-card' => "'Business card'
Lorem ipsum dolor sit
amet, consetetur dolore
magna aliquyam erat,
sed diam voluptua.",
    'crm-address' => "'CRM'
Lorem ipsum dolor sit
amet, consetetur dolore
magna aliquyam erat,
sed diam voluptua.",
);
?>
<div class="formInner">
    <h3 class="titleB">Please select your contact Recognition:<span><?php echo $survey->name;?></span></h3>
    <?php if (!empty($contact_types)): ?>
        <ul class="questionType">
            <?php foreach ($contact_types as $contact_type): ?>
                <?php
                $class = (in_array($contact_type->id, $contact_collections)) || ($badge->db_download == 1) || ($badge->lookup_search == 1) ? "cedit-" . $contact_type->identifier : "cadd-" . $contact_type->identifier;
                ?>

                <li class="<?php echo $class; ?>">
                    <span>
                        <small>
                        </small>
                    </span>

                    <div class="contact-col-name">

                        <a href="#" data-title="<?php echo $contact_type->identifier; ?>" title ="<?php echo $tooltip[$contact_type->identifier]; ?>" class="custom-tooltip">    <?php echo $contact_type->name; ?></a>
                    </div>
<?php //echo $badge->lookup_search;?>
                    <?php if ((in_array($contact_type->id, $contact_collections)) || ($badge->db_download == 1) || ($badge->lookup_search == 1)): ?>                    
                        <?php echo anchor(base_url() . "contact/collection/" . $eid . '/' . $contact_type->id, 'edit'); ?>
                    <?php else: ?>
                        <?php echo anchor(base_url() . "contact/collection/" . $eid . '/' . $contact_type->id, 'add'); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <fieldset>
        <!--<div class="backBtn">
        <?php echo anchor(base_url() . 'survey/step_2/' . $eid, 'Back'); ?>
        </div>-->
    </fieldset>
</div>
<script>
    $(document).ready(function(){
                
        $(".custom-tooltip").hover(function(){
            var title = $(this).attr('title');
            var dataTitle = $(this).attr('data-title');
            switch(dataTitle) {
                case 'contact-form':
                    dataTitle = 'Contact Form'
                    break;
                case 'badge-scan':
                    dataTitle = 'Badge scan'
                    break;
                case 'business-card':
                    dataTitle = 'Business card'
                    break;
                case 'crm-address':
                    dataTitle = 'CRM address'
                    break;
            }
            $(".pop-container").remove();
            var content = '<div class="pop-container" style="z-index:99"><div class="helpPopup popupWrapper"  style="position:absolute;bottom:0;left:0">'+
                '<h2>'+dataTitle+'</h2>'+
                '<p>'+title+'</p>'+
                '<span>Image</span>'+
                '<a href="#" title="Close" id="close" class="tooltip-close">Close</a>'+
                '</div></div>';
            $(content).insertBefore($(this));
            return false;
        })
        
        $(".tooltip-close").live('click',function(){
            $(".pop-container").remove();
            return false;
        })
        
        $(".pop-container").live('click',function(ev){
            ev.stopPropagation();
        })
        
        $(document).click(function(ev){
            $(".pop-container").remove();
        })
       
    })
</script>
