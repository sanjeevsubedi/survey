<?php /*
  echo "<pre>";
  print_r($contacts);
  "</pre>";
  die; */
?>
<script>
    var totalOrigin = [];
    var connectInfo = []
</script>
<div class="lists" id="wrapper-contact">
    <h4 class="description"><strong>Please note:</strong> If a field is to be compulsory, please mark the check box!</h4> 
    <fieldset class="dragDrop">
        <?php $i = 1; ?>
        <?php if (!empty($contacts)): ?>
            <?php
            $total = count($contacts);
            ?>

            <?php foreach ($contacts as $k => $contact):
                ?>
                <?php $k++; ?>
                <script>
                    var totalOriginExcludingCurrent = [];
                    for (var i=1; i<=<?php echo $total ?>; i++) {
                        if(i!=<?php echo $k; ?>) {
                            totalOriginExcludingCurrent.push("#origin"+i); 
                        }
                    }
                    totalOrigin.push('#origin'+<?php echo $k; ?>) 
                    connectInfo.push($("#origin<?php echo $k; ?>").sortable({connectWith: totalOriginExcludingCurrent.join(',')}));
                </script>

                <div id="origin<?php echo $k; ?>" class="fbox">

                    <?php foreach ($contact as $rk => $row): ?>
                        <?php if (is_object($row)): ?>
                            <div id="<?php echo $row->contact_form_field_id; ?>" class="field-id draggable">
                                <div class="checkfields">
                                    <input type="checkbox" class="required_fields" name="required[]" value="<?php echo $row->contact_form_field_id; ?>" <?php echo set_checkbox('required', $row->contact_form_field_id, in_array($row->contact_form_field_id, $required_form_fields)) ?>/>
                                </div>
                                <?php if ($row->type == 'textbox'): ?>
                                    <input type="text" name="<?php echo $row->contact_form_field_id; ?>" value="<?php echo $row->name; ?>" />
                                <?php endif; ?>
                                 <div class="checkfields-visibility">
                                 <?php 
                                 $visible = false;
                                    if(in_array($row->contact_form_field_id, $visible_form_fields)) {
                                        $visible = true;
                                    } 
                                 ?>
                                    <span style="position:relative;top:5px;">visible in app</span> <input type="checkbox" class="visibility_fields" name="visibility[]" value="<?php echo $row->contact_form_field_id; ?>" <?php echo set_checkbox('visibility', $row->contact_form_field_id,$visible ) ?>/>
                                </div>

                                <?php if ($row->type == 'select'): ?>
                                    <?php
                                    $options = $row->options;
                                    $options = explode("\n", $options);
                                    ?>
                                    <select name="<?php echo $row->contact_form_field_id; ?>">
                                        <option><?php echo $row->name; ?></option>
                                        <?php foreach ($options as $option): ?>
                                            <option value=""><?php echo $option; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>


                </div>
                <?php $i++; ?> 
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="actions">
            <input type="button" name="back" value="Back" class="prev-sel-contact" id="list_contact_field" />
            <input type="button" name="next" value="Next" class="nxt-sel-contact" id="sel_contact_field" />
        </div>
    </fieldset> 
</div>
<script type="text/javascript">
    
    var handler= function(){
        $.each(connectInfo,function(index,value){
            value;  
        })                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            			
    }
    
    $(document).ready(function(){
        
        $("#list_contact_field").live('click',function(){
            $(".contact-field-container #panel1-ajax").toggle("slide", { direction: "left" }, 1000,function(){
                $(this).hide();
                $(".contact-field-container #panel_1").css('display','block');
            });
        })
        
        $(totalOrigin.join(',')).disableSelection();
        handler();
        var count = 0;
        $("#sel_contact_field").click(function() {
            count++
            if(count == 1) {
                var orderObj ={};
                var requiredFields = [];
                var visibilityFields = [];
                $("#wrapper-contact div.fbox").each(function(index) {
               
                    //itemDesc = $(this).find('img').attr('id');
                    var orderArr= [];
						
                    var imgElement = $(this).find('div.draggable');
                    var imgLength = imgElement.length;
                    var row = index;
                    if(imgLength>1){
							
                        imgElement.each(function(index){
								 
                            imgID = $(this).attr('id');
								
                            orderArr.push(imgID);
                        });
                        //console.log(orderArr);
                        orderObj[row]=orderArr;
                    }
                    else if(imgLength==1){
                        imgIDArr = [];
                        imgID = imgElement.attr('id');
                        imgIDArr.push(imgID);
                        orderObj[row]=imgIDArr;
							
                    }
                    //console.log(orderObj);
                });
                console.log(orderObj);
            
                $(".required_fields").each(function(){
                    if($(this).attr('checked')) {
                        requiredFields.push($(this).attr('value'));
                    }
                })

                $(".visibility_fields").each(function(){
                    if($(this).attr('checked')) {
                        visibilityFields.push($(this).attr('value'));
                    }
                })
            
                $.post("<?php echo base_url() ?>ajax/index/order_contact_fields/", {
                    "contact_obj": orderObj, "survey_id": <?php echo $eid; ?>,'required_ids':requiredFields,'visibility_ids':visibilityFields}, function(theResponse){
                    var url = '<?php echo base_url() . 'survey' . DS . 'contacts' . DS . $eid ?>';
                    window.location.href = url;
                });
            
            } 
            
        });
    });	
</script>