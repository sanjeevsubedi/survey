<div class="formInner">
    <div id="accordion2">
        <h3> <label for="business card">Business card</label><span class="status"></span></h3>
        <div class="content-container acivate-deactivate-card">
            <fieldset id="panel_1">
                <p>You can activate/deactivate Business Card Recognition below. Once it's deactivated the possibility to photograph Business Cards will not be included.
                </p>
                <div class="input-container activate-deactivate-bus-card">
                    <span class="activate-card text-blue">Activate Business Card Recognition</span>
                    <input type="button" id="activate" style="margin-right:-7px;" class="cstatus cardactive"/>
                    <input type="button" id="deactivate" class="cstatus"/>
                    <span class="activate-card">Deactivate Business Card Recognition</span>
                </div>  
                <div class="actions">
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'survey/contacts/' . $eid, 'Back'); ?>
                    </div>
                    <div class="nextBtn">
                        <?php echo anchor(base_url() . 'survey/contacts/' . $eid, 'Next'); ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".cstatus").on('click',function(){
            var me = $(this);
            var id = $(this).attr('id');
            var survey_id = '<?php echo $eid; ?>';
            $.post("<?php echo base_url() ?>ajax/index/card_status/", { "survey_id": survey_id,"status":id, "type":"bcard"}, function(theResponse){
                $('.cstatus').removeClass('cardactive');
                $('.activate-card').removeClass('cardactive');
                $(me).addClass('cardactive');
                 $('.activate-card').removeClass('text-blue');
                if(me.attr('id') == 'deactivate') {
                    me.next().addClass('text-blue')
                } else {
                    me.prev().addClass('text-blue')
                }
                //var url = '<?php //echo base_url() . 'survey' . DS . 'contacts' . DS . $eid            ?>';
                //window.location.href = url;
            });  
                         
        })
    });
</script>