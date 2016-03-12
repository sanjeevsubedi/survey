<style>
.spinner{display: none;margin-left:160px;}
#floatingCirclesG{
position:relative;
width:128px;
height:128px;
-moz-transform:scale(0.6);
-webkit-transform:scale(0.6);
-ms-transform:scale(0.6);
-o-transform:scale(0.6);
transform:scale(0.6);
}

.f_circleG{
position:absolute;
background-color:#1775B3;
height:23px;
width:23px;
-moz-border-radius:12px;
-moz-animation-name:f_fadeG;
-moz-animation-duration:1.04s;
-moz-animation-iteration-count:infinite;
-moz-animation-direction:normal;
-webkit-border-radius:12px;
-webkit-animation-name:f_fadeG;
-webkit-animation-duration:1.04s;
-webkit-animation-iteration-count:infinite;
-webkit-animation-direction:normal;
-ms-border-radius:12px;
-ms-animation-name:f_fadeG;
-ms-animation-duration:1.04s;
-ms-animation-iteration-count:infinite;
-ms-animation-direction:normal;
-o-border-radius:12px;
-o-animation-name:f_fadeG;
-o-animation-duration:1.04s;
-o-animation-iteration-count:infinite;
-o-animation-direction:normal;
border-radius:12px;
animation-name:f_fadeG;
animation-duration:1.04s;
animation-iteration-count:infinite;
animation-direction:normal;
}

#frotateG_01{
left:0;
top:52px;
-moz-animation-delay:0.39s;
-webkit-animation-delay:0.39s;
-ms-animation-delay:0.39s;
-o-animation-delay:0.39s;
animation-delay:0.39s;
}

#frotateG_02{
left:15px;
top:15px;
-moz-animation-delay:0.52s;
-webkit-animation-delay:0.52s;
-ms-animation-delay:0.52s;
-o-animation-delay:0.52s;
animation-delay:0.52s;
}

#frotateG_03{
left:52px;
top:0;
-moz-animation-delay:0.65s;
-webkit-animation-delay:0.65s;
-ms-animation-delay:0.65s;
-o-animation-delay:0.65s;
animation-delay:0.65s;
}

#frotateG_04{
right:15px;
top:15px;
-moz-animation-delay:0.78s;
-webkit-animation-delay:0.78s;
-ms-animation-delay:0.78s;
-o-animation-delay:0.78s;
animation-delay:0.78s;
}

#frotateG_05{
right:0;
top:52px;
-moz-animation-delay:0.91s;
-webkit-animation-delay:0.91s;
-ms-animation-delay:0.91s;
-o-animation-delay:0.91s;
animation-delay:0.91s;
}

#frotateG_06{
right:15px;
bottom:15px;
-moz-animation-delay:1.04s;
-webkit-animation-delay:1.04s;
-ms-animation-delay:1.04s;
-o-animation-delay:1.04s;
animation-delay:1.04s;
}

#frotateG_07{
left:52px;
bottom:0;
-moz-animation-delay:1.17s;
-webkit-animation-delay:1.17s;
-ms-animation-delay:1.17s;
-o-animation-delay:1.17s;
animation-delay:1.17s;
}

#frotateG_08{
left:15px;
bottom:15px;
-moz-animation-delay:1.3s;
-webkit-animation-delay:1.3s;
-ms-animation-delay:1.3s;
-o-animation-delay:1.3s;
animation-delay:1.3s;
}

@-moz-keyframes f_fadeG{
0%{
background-color:#000000}

100%{
background-color:#1775B3}

}

@-webkit-keyframes f_fadeG{
0%{
background-color:#000000}

100%{
background-color:#1775B3}

}

@-ms-keyframes f_fadeG{
0%{
background-color:#000000}

100%{
background-color:#1775B3}

}

@-o-keyframes f_fadeG{
0%{
background-color:#000000}

100%{
background-color:#1775B3}

}

@keyframes f_fadeG{
0%{
background-color:#000000}

100%{
background-color:#1775B3}

}
.broadcaster .users li{padding: 5px 0;border-bottom:1px solid #000;}
.broadcaster .users li ul li{padding: 0;border:0;}
</style>
<?php
$attributes = array('name' => 'new_message', 'id' => 'new_message', 'class' => '');
//var_dump($tokens);die;
?>
<div class="broadcaster">
	<?php  echo form_open('', $attributes);?>
    <h3 class="broadcast-title">Broadcast Message</h3>
    <fieldset>
        <label for="options">Message</label>
        <div class="textarea">
            <textarea name="broadcast-msg" rows="15" cols="200" id="broadcast-msg" class="broadcast-msg required"></textarea>
         </div>
</fieldset>
    <fieldset>
        <div><b>Users:</b></div>
       
            <!--<select name="type" id="field-type">
                    <option value=""> </option>
            </select>
        -->
        <div class="all-users">
        	<ul>
        	<?php foreach($survey_users as $user):?>
        	<?php //var_dump($user);die;?>
        	
        			<li>- <?php echo $user->first_name. ' '. $user->last_name. ' ('. $user->email.')';?></li>
        	
        	<?php endforeach;?>
        		</ul>
                <div class="spinner">
                    <div id="floatingCirclesG">
                        <div class="f_circleG" id="frotateG_01">
                        </div>
                        <div class="f_circleG" id="frotateG_02">
                        </div>
                        <div class="f_circleG" id="frotateG_03">
                        </div>
                        <div class="f_circleG" id="frotateG_04">
                        </div>
                        <div class="f_circleG" id="frotateG_05">
                        </div>
                        <div class="f_circleG" id="frotateG_06">
                        </div>
                        <div class="f_circleG" id="frotateG_07">
                        </div>
                        <div class="f_circleG" id="frotateG_08">
                        </div>
                    </div>

                </div>
        </div>

    </fieldset>

        <fieldset>
             <div><b>LoggedIn Users:</b></div>

                    <?php if(!empty($individual_tokens )):?>
                     <div class="users">
                        <ul>
                        <?php foreach($individual_tokens as $individual_token):?>
                        <?php //var_dump($user);die;?>
                        <?php 
                        $loggedInuser = $individual_token['user'] ;?>
                            <li>- <?php echo $loggedInuser->first_name. ' '. $loggedInuser->last_name. ' ('. $loggedInuser->email.')';?>
                                
                                <p><b>Devices (<?php echo $individual_token['count'];?>):</b></p>
                                 <ul>
                                    <?php foreach($individual_token['devices'] as $device):?>

                                         <li><?php echo $device->device_type ? $device->device_type : 'unknown';?></li>

                                    <?php endforeach;?>
                                 </ul>

                            </li>

                        <?php endforeach;?>
                    <?php endif;?>
                        </ul>
                </div>

        </fieldset>

<fieldset>
   
    	<label for="type">&nbsp</label>
        <input type="button" value="Next" class="save" id="send_msg">
    </fieldset>
    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function(){

        //$("#new_message").validate({});
        var tokens = '<?php echo $tokens;?>';// array of array
       
        $("#send_msg").on('click',function(){

            $("#new_message input, #new_message input").prop('disabled', true);
          
            if ($("#new_message").valid()) {
                //
                    var messages = $("#broadcast-msg").val();

                    jQuery.ajax({
                        url: 'http://bubblecrm.info/pushservices/index.php',
                        data: {tokens:tokens,msg:messages},
                        type: "POST",
                        beforeSend: function(){
                            $(".spinner").show();
                        },
                        success: function(response) {
                           var res = JSON.parse(response);
                           if(res.success) {
                                $(".spinner").hide();
                                alert("Messages sent successfully"); 
                                 $("#new_message textarea").val('');
                                $("#new_message input, #new_message textarea").prop('disabled', false);
                                //location.reload();
                            }
                           
                           //location.reload();
                        },
                        error: function(err) {
                            return false;
                        }
                    });

            } 

        })
    
    })
</script>