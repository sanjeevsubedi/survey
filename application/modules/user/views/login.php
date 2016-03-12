<?php
$attributes = array('name' => 'frmLogin', 'id' => 'frmLogin', 'class' => 'form-horizontal');
?>
<div class="span4 box">
    <?php
    if (isset($error) && !empty($error)) {
        echo '<div class="alert alert-error">' . $error . '</div>';
    }
    ?>
    <?php echo form_open(base_url() . 'user/auth/login', $attributes); ?>                 

    <fieldset class="borderBottom">
        <div class="control-group <?php echo (form_error('user_name')) ? 'error' : ''; ?>">
            <h2>Welcome to expoRM<br /> <span>Please sign in with your expoRM account.</span></h2>
            <input type="text" name="user_name" id="user_name" class=" span12 input-xlarge focused" placeholder="Username" value="<?php echo set_value('user_name') ?>">
            <?php if (form_error('user_name')): ?>
                <span class="help-inline"> <?php echo form_error('user_name'); ?></span>
            <?php endif; ?>
        </div>
        <div class="control-group <?php echo (form_error('user_password')) ? 'error' : ''; ?>">
            <input type="password" name="user_password" id="user_password" class="span12" placeholder="Password" value="">
            <?php if (form_error('user_password')): ?>
                <span class="help-inline"> <?php echo form_error('user_password'); ?></span>
            <?php endif; ?>
        </div>
    </fieldset>

    <fieldset class="loginBoxBg rememberMe">
        <input type="checkbox" name="signedIn" value="1" id="remember" <?php echo set_checkbox('signedIn', 1, $remember); ?> />
        <span><label for="remember">Keep Me Signed in</label></span>
    </fieldset>

    <fieldset class="loginBoxBg">
        <!--<input type="checkbox" class="checkbox" value="option1" name="optionsCheckboxes"> Keep me logged in-->
        <span><input type="submit" class="btn btn-large btn-primary" value="LOG IN"></span>
    </fieldset>    

    <?php echo form_close(); ?>

    <hr>

    <div class="sign-up-free">
        <h2>Sign up free now!</h2>
    </div>
    <div class="reg-btn">
        <a class="btn btn-large btn-primary" href="<?php echo site_url('user/auth/register'); ?>">Register</a>
    </div>


</div>
<div class="otherLinks">
    <div>
        <!-- <a href="<?php echo site_url('user/auth/register'); ?>">Register</a> |  -->
        <a class="download-app" href="#">Download App</a> |
        <a class="forget-password" href="<?php echo site_url('user/auth/forget_password'); ?>">Forget Password?</a> 
    </div>
    <ul>
        <li><a href="#"><img src="<?php echo base_url() ?>assets/img/icons/mob2.png" alt=""></a></li>
        <li><a href="#"><img src="<?php echo base_url() ?>assets/img/icons/mob1.png" alt=""></a></li>
    </ul>
</div>
