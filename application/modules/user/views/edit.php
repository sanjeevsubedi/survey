<?php
//var_dump($user);die;
//var_dump($company_info);die;
?>
<div class="singleColum">
    <h3>Update Account</h3>
    <!--<div class="val-errors notification">
        <p>Please complete all required fields to create your  Account.</p>
        <p>* Indicates required fields</p>
    </div>-->
    <?php
    $attributes = array('name' => 'update_user_form', 'id' => 'registration_form_id');
    echo form_open('member/edit/' . $user->id, $attributes);
    ?>
    <fieldset>
        <label for="email">E-Mail*</label>
        <input type="text" class="form-text" name="email" value="<?php echo set_value('email', $user->email); ?>" id="email"/>
        <span class="message"></span>
    </fieldset>
    <fieldset>
        <label for="pass">Password</label>
        <input type="password" class="form-text" name="password" id="password" value=""/>
        <span class="message"></span>
        <span class="leave-empty">Leave empty if you don't want to update</span>
    </fieldset>
    <fieldset>
        <label for="first_name">First name*</label>
        <input type="text" class="form-text" name="first_name" id="first_name" value="<?php echo set_value('first_name', $user->first_name); ?>"/>
        <span class="message"></span>    
    </fieldset>     
    <fieldset>
        <label for="last_name">Last name*</label>
        <input type="text" class="form-text" name="last_name" id="last_name" value="<?php echo set_value('last_name', $user->last_name); ?>"/>
        <span class="message"></span>    
    </fieldset>
    <fieldset>
        <?php
        $this->load->helper('countries');
        $country_list = get_countries();
        ?>
        <label for="country">Country</label>
        <select name="country" id="country">
            <option value="">Select country...</option>
            <?php foreach ($country_list as $value): ?>
                <option value="<?php echo $value ?>" <?php echo set_select('country', $user->country, $user->country == $value ? TRUE : FALSE); ?>><?php echo $value ?></option>
            <?php endforeach; ?>
        </select>
        <span class="message"></span>    
    </fieldset>


    <fieldset>
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="1" <?php echo set_select('status', $user->status, $user->status == 1 ? TRUE : FALSE); ?>>Active</option>
            <option value="0" <?php echo set_select('status', $user->status, $user->status == 0 ? TRUE : FALSE); ?>>Deactive</option>
        </select>
        <span class="message"></span>    
    </fieldset>


    <!--<fieldset>
        <label for="company">Company*</label>
        <input type="text" class="form-text" name="company" id="company" value="<?php echo set_value('company'); ?>"/> OR 

        <select name="company_old" id="company_old">
            <option value="">Select Company...</option>
    <?php foreach ($companys as $value): ?>
                                                                                            <option value="<?php echo $value->id ?>" <?php echo (set_value('company_old') == $value->id) ? "selected='selected'" : ""; ?>><?php echo $value->name; ?></option>
    <?php endforeach; ?>
        </select>
        <span class="message"></span>    
    </fieldset>
    <fieldset class="hideOnCompany">
        <label for="address1">Address 1*</label>
        <input type="text" class="form-text" name="address1" id="address1" value="<?php echo set_value('address1', $company_info->address_1); ?>"/>
        <span class="message"></span>    
    </fieldset>
    <fieldset class="hideOnCompany">
        <label for="address2">Address 2</label>
        <input type="text" class="form-text" name="address2" id="address2" value="<?php echo set_value('address2', $company_info->address_2); ?>"/>
        <span class="message"></span>    
    </fieldset>
    <fieldset class="hideOnCompany">
        <label for="telephone">Business Phone*</label>
        <input type="text" class="form-text" name="telephone" id="telephone" value="<?php echo set_value('telephone', $company_info->telephone); ?>"/>
        <span class="message"></span>    
    </fieldset>-->
    <fieldset>
        <input type="submit" value="Update">
    </fieldset>
    <?php echo form_close(); ?>

</div><!--/.singleColum-->



<script>
    (function($){
        $(document).ready(function(){  
            $("select[name='company_old']").change(function(e){
                if($("#company_old").val()>0){
                    $(".hideOnCompany").hide();
                } else {
                    $(".hideOnCompany").show();
                }
            });
      
            if($("#company_old").val()>0){
                $(".hideOnCompany").hide();
            } else {
                $(".hideOnCompany").show();
            }
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
                
                    country:{
                        required: true
                    },
                    company:{
                        required: $('#company_old').val()==""
                    },
                    company_old:{
                        required: $('#company').val()==""
                    },
                    address1:{
                        required: $('#company_old').val()==""
                    },
                    telephone:{
                        required: $('#company_old').val()==""
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
                    country:{
                        required : "Required"
                    },
                    company:{
                        required : "Required"
                    },
                    company_old:{
                        required : "Required"
                    },
                    address1:{
                        required : "Required"
                    },
                    telephone:{
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

