<div class="navHolder">
    <div class="leftNavpanel">
        <h1 class="brandLogo" style="color:#fff;">Meplan<a class="brand" href="#"><!--<img src="<?php //echo base_url();                     ?>assets/img/logo.png" width="140px" />--></a></h1>
        <ul class="nav_menus">
            <?php if ($this->user_model->check_session()): ?>
                <?php $role = $this->user_model->get_role($this->session->userdata(SESS_ROLE_ID)); ?>
                <?php if ($role == "super-admin"): ?>
                    <li><a href="<?php echo base_url() ?>device/manage" class="exp">Device</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>device/all">Manage Devices</a></li>
                            <li><a href="<?php echo base_url() ?>device/add">Add Device</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url() ?>permission/all" class="exp">Permission</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>permission/all">Manage Permission</a></li>
                            <li><a href="<?php echo base_url() ?>permission/add">Add Permission</a></li>
                        </ul>
                    </li>
                    <!--<li><a href="<?php echo base_url() ?>language/manage" class="exp">Language</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>language/all">Manage Languages</a></li>
                            <li><a href="<?php echo base_url() ?>language/add">Add Language</a></li>
                        </ul>
                    </li>-->
                    <li><a href="<?php echo base_url() ?>template/all" class="exp">Template</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>template/all">Manage Templates</a></li>
                            <li><a href="<?php echo base_url() ?>template/add">Add Template</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url() ?>cfield/manage" class="exp">Contact Form Field</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>cfield/all">Manage Contact Form Fields</a></li>
                            <li><a href="<?php echo base_url() ?>cfield/add">Add Contact Form Field</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($role == "company-super-admin" || $role == "super-admin"): ?>
                    <!--<li><a href="<?php //echo base_url()               ?>user/index" class="exp">User</a>
                        <ul class="sub">
                            <li><a href="<?php //echo base_url()               ?>member/all">Manage User</a></li>
                        </ul>
                    </li>-->
                <?php endif; ?>

                <?php if ($role == "company-super-admin"): ?>
                    <li><a href="<?php echo base_url() ?>salesperson/manage" class="exp">Salesperson</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>salesperson/all">Manage Salesperson</a></li>
                            <li><a href="<?php echo base_url() ?>salesperson/add">Add Salesperson</a></li>
                            <li><a href="<?php echo base_url() ?>salesperson/excel_import">Import Salesperson</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url() ?>salesperson/manage" class="exp">Backup</a>
                        <ul class="sub">
                            <li><a href="<?php echo base_url() ?>survey/upload_backup">Upload responses to server</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="<?php echo base_url() ?>user/auth/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

