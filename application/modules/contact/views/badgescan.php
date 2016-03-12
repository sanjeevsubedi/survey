<?php
$attributes = array('name' => 'badgescan', 'id' => 'badgescan', 'class' => 'badgescan common-form');
?>
<div class="formInner">
    <?php echo form_open(base_url() . 'contact/badgescan/' . $eid . '/' . $contact_type, $attributes); ?>
    <div id="accordion2">
        <h3> <label for="badge scan">Badge scan</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset id="panel_1">
                <div class="input-container">
                    <fieldset>
                        <label for="server">Server</label>
                        <input type="text" name="server" class="letxt" value="<?php echo set_value('server', isset($badge->server) ? $badge->server : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="server">Username</label>
                        <input type="text" name="username" class="letxt" value="<?php echo set_value('username', isset($badge->username) ? $badge->username : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="server">Password</label>
                        <input type="text" name="password" class="letxt" value="<?php echo set_value('password', isset($badge->password) ? $badge->password : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="server">Id Section</label>
                        <input type="text" name="idsection" class="letxt" value="<?php echo set_value('idsection', isset($badge->idsection) ? $badge->idsection : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="server">Id Question Search</label>
                        <input type="text" name="idquestionsearch" class="letxt" value="<?php echo set_value('idquestionsearch', isset($badge->idquestionsearch) ? $badge->idquestionsearch : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label for="server">Id Question Output</label>
                        <input type="text" name="idquestionoutput" class="letxt" value="<?php echo set_value('idquestionoutput', isset($badge->idquestionoutput) ? $badge->idquestionoutput : ''); ?>" />
                    </fieldset>
                    <fieldset>
                        <label>Allow database download</label>
                        <div class="input-container activate-deactivate-bus-card">
                         <!--   <span class="activate-card text-blue">Activate</span>
                            <input type="button" id="activate" style="margin-right:-7px;" class="cstatus <?php echo $badgescan_active ? 'cardactive' : ''; ?>"/>
                            <input type="button" id="deactivate" class="cstatus <?php echo $badgescan_deactive ? 'cardactive' : ''; ?>"/>
                            <span class="activate-card">Deactivate</span>-->
                            <?php //var_dump($badgescan_active);?>
                            <input type="checkbox" value ="1" name="download_status" <?php if ($badge->db_download) echo "checked = 'checked'"; else ""; ?> />
                        </div>  
                    </fieldset>

                    <fieldset>
                        <label>Show barcode scan button in contact form</label>
                        <div class="input-container activate-deactivate-bus-card">
                            <input type="checkbox" value ="1" name="barcode_status" <?php if ($badgescan_status) echo "checked = 'checked'"; else ""; ?> />
                        </div>  
                    </fieldset>

                      <fieldset>
                        <label>Show search field in contact form</label>
                        <div class="input-container activate-deactivate-bus-card">
                            <input type="checkbox" value ="1" name="search_status" <?php if ($badge->lookup_search) echo "checked = 'checked'"; else ""; ?> />
                        </div>  
                    </fieldset>


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
        /*   $(".cstatus").on('click',function(){
            var me = $(this);
            var id = $(this).attr('id');
            var survey_id = '<?php echo $eid; ?>';
            $.post("<?php echo base_url() ?>ajax/index/card_status/", { "survey_id": survey_id,"status":id,"type":"badgescan"}, function(theResponse){
                $('.cstatus').removeClass('cardactive');
                $('.activate-card').removeClass('cardactive');
                $(me).addClass('cardactive');
                $('.activate-card').removeClass('text-blue');
                if(me.attr('id') == 'deactivate') {
                    me.next().addClass('text-blue')
                } else {
                    me.prev().addClass('text-blue')
                }
                //var url = '<?php //echo base_url() . 'survey' . DS . 'contacts' . DS . $eid                           ?>';
                //window.location.href = url;
            });  
                         
        })*/
    });
</script>