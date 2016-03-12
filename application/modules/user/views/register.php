<?php
$salutations = unserialize(SALUTATION);
?>
<div style="width:800px; margin:0 auto;">
    <div class="singleColum">
        <!-- <h3>Create your Account</h3> -->
        <h3>Welcome!</h3>

        <?php
        $attributes = array('name' => 'registration_form', 'id' => 'registration_form_id', 'class' => 'common-form');
        echo form_open('user/auth/register/' . $token_id, $attributes);
        ?>

        <div class="right-register-col">

            <fieldset>
                <?php if (!isset($token_user)): ?>
                    <div style="text-align:center; margin-bottom:30px;">
                        <h2>Welcome to expoCRM</h2>
                        <p>Get started with the FREE Demo Version of expoCRM.
                            <br>Sign up in 30 seconds! No credit card required.</p>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; margin-bottom:30px;">
                        <h2>Welcome to expoCRM</h2>
                        <p>Please add your password below!
                            <br></p>
                    </div>
                <?php endif; ?>
                <div class="columns">
                    <select class="right" name="salutation" id="salutation" <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>>
                        <option value="" selected="selected">Salutation</option>
                        <?php foreach ($salutations as $salutation): ?>
                            <option value="<?php echo $salutation; ?>" <?php echo set_select('salutation', $salutation, isset($token_user) ? $token_user->salutation == $salutation ? TRUE : FALSE  : ''); ?>><?php echo $salutation; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="message"></span>
                </div>
                <div class="columns">&nbsp;</div>

                <div class="columns">
                    <input type="text" class="right form-text" name="first_name" placeholder="First name" id="first_name" value="<?php echo set_value('first_name', isset($token_user) ? $token_user->first_name : ""); ?>"
                           <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>
                    <span class="message"></span>
                </div>

                <div class="columns">
                    <input type="text" class="form-text" name="last_name" placeholder="Last name" id="last_name" value="<?php echo set_value('last_name', isset($token_user) ? $token_user->last_name : ""); ?>"
                           <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>
                    <span class="message"></span>
                </div>

                <div class="columns">
                    <input type="text" class="right form-text" name="company" placeholder="Company" id="company" value="<?php echo set_value('company', isset($token_user) ? $token_user->company : ""); ?>"
                           <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/> 

                    <span class="message"></span>
                </div>

                <div class="columns">
                    <input type="text" class="form-text" placeholder="Email" name="email" value="<?php echo set_value('email', isset($token_user) ? $token_user->email : ""); ?>" id="email"
                           <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>

                    <span class="message"></span>
                </div>

                <div class="columns">
                    <input type="password" class="right form-text" placeholder="Password" name="password" id="password" value=""/>
                    <span class="message"></span> 
                </div>

                <div class="columns">
                    <input type="password"  class="form-text" placeholder="Repeat password" name="repassword" value="" id="repassword"/>
                    <span class="message"></span>
                </div>

                <div class="submit-btn">
                    <?php if (isset($token_user)): ?>
                        <input type="submit" value="start" name="start" />
                    <?php else: ?>
                        <input type="submit" value="register" name="register" />
                    <?php endif; ?>
                </div>

            </fieldset>

            <!-- <fieldset>
                <select name="salutation" id="salutation" <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>>
                    <option value="" selected="selected">Salutation</option>
            <?php foreach ($salutations as $salutation): ?>
                            <option value="<?php echo $salutation; ?>" <?php echo set_select('salutation', $salutation, isset($token) ? $token->salutation == $salutation ? TRUE : FALSE  : ''); ?>><?php echo $salutation; ?></option>
            <?php endforeach; ?>
                </select>
                <span class="message"></span>
            </fieldset>
    
            <fieldset>
                <label for="first_name">First name*</label>
                <input type="text" class="form-text" name="first_name" placeholder="name" id="first_name" value="<?php echo set_value('first_name', isset($token_user) ? $token_user->first_name : ""); ?>"
            <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>
                <span class="message"></span>    
            </fieldset>
    
            <fieldset>
                <label for="last_name">Last name*</label>
                <input type="text" class="form-text" name="last_name" placeholder="last name" id="last_name" value="<?php echo set_value('last_name', isset($token_user) ? $token_user->last_name : ""); ?>"
            <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>
                <span class="message"></span>    
            </fieldset>
    
            <fieldset>
                <label for="company">Company*</label>
                <input type="text" class="form-text" name="company" placeholder="company" id="company" value="<?php echo set_value('company', isset($token_user) ? $token_user->company : ""); ?>"
            <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/> 
    
                <span class="message"></span>    
            </fieldset>
        </div>
        <div class="right-register-col">
            <fieldset>
                <label for="email">E-Mail*</label>
                <input type="text" class="form-text" placeholder="email" name="email" value="<?php echo set_value('email', isset($token_user) ? $token_user->email : ""); ?>" id="email"
            <?php echo isset($token_user) ? "disabled = 'disabled'" : "" ?>/>
    
                <span class="message"></span>
            </fieldset>
    
            <fieldset>
                <label for="pass">Password*</label>
                <input type="password" class="form-text" placeholder="password" name="password" id="password" value=""/>
                <span class="message"></span>    
            </fieldset>
            <fieldset>
                <label for="repassword">Confirm Password*</label>
                <input type="password"  class="form-text" placeholder="repeat password" name="repassword" value="" id="repassword"/>
                <span class="message"></span>
            </fieldset>
        </div>
        <fieldset>
            <?php if (isset($token_user)): ?>
                    <input type="submit" value="start" name="start" />
            <?php else: ?>
                    <input type="submit" value="register" name="register" />
            <?php endif; ?>
        </fieldset> -->

        </div>
    </div>
    <script>
        (function($){
            $(document).ready(function(){  
                $("#registration_form_id1").validate({
                    rules: {
                        first_name: {
                            required : true
                
                        },
                
                        last_name: {
                            required : true
                
                        },
                
                        email: {
                            required : true,
                            email : true
                    
                        },
                
                        password: {
                            required : true
                        },
                
                        repassword:{
                            required: true,
                            equalTo :'#password'
                        },
                
                        company:{
                            required: true
                        }
                    },
                    messages: {
                        first_name: {
                            required:"Required"
                    
                        },
                        last_name: {
                            required:"Required"
                   
                        },
                
                        email: {
                            required:"Required",
                            email:"Enter valid email address"
                        },
	
                        password:{
                            required : "Required"
                        },
                        repassword:{
                            required : "Required",
                            equalTo:"Password doesnot match"
            
                        },
                        company:{
                            required : "Required"
                        }
                    },
                    errorPlacement: function(error, element) {
                        if ( element.is(":radio") )
                            error.appendTo( element.parent().children("span.message") );
                        //error.appendTo( element.parent().next().next() );
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

