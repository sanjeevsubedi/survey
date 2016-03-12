<div class="singleColum" style="width:340px; margin:0 auto;">
    <h3>Forget password</h3>
    <?php
    $attributes = array('name' => 'forget_pass_form', 'id' => 'forget_password');
    echo form_open('user/auth/forget_password', $attributes);
    ?>
    <fieldset>
        <div class="forget-password">
            <!-- <label for="e-mail"> E-Mail-Adresse</label> -->
            <input type="text" class="form-text" placeholder="Email address" name="email" id="e-mail" value=""/>
            <span class="message"></span>
        </div>

        <div>
            <input type="submit" value="Send">
        </div>
    </fieldset>
    <?php echo form_close(); ?>
    <div class="backBtn forgot-back">
        <a href="<?php echo base_url() ?>">Back</a> 
    </div>
</div><!--/.singleColum-->


<script>
    (function($){
        $(document).ready(function(){     
            $("#forget_password").validate({
                rules: {
                    email: {
                        required : true,
                        email : true
                    
                    }
                },
                messages: {
                    email: {
                        required:"Required",
                        email:"Enter valid Email"
                    }
                },
                errorPlacement: function(error, element) {
                    if ( element.is(":radio") )
                        error.appendTo( element.parent().next().next() );
                    else if ( element.is(":checkbox") )
                        error.appendTo ( element.next() );
                    else
                        error.appendTo( element.parent().children("span.message") );
                },
                // set this class to error-labels to indicate valid fields
                success: function(label) {
                    // set &nbsp; as text for IE
                    label.html("&nbsp;").addClass("checked");
                }
            });
        });
    })(jQuery);
</script>

