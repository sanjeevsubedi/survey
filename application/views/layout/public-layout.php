<!DOCTYPE html>
<html lang="en"><head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" >
        <title>Exporm</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">

        <?php
        $this->load->view('includes/head');
//echo $_scripts;
//echo $_styles;
        ?>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <!--Header-->
        <!--Top navBar-->

        <!--Header-->

        <!--Body-->
        <div class="container-fluid">
            <!--      <div class="row-fluid sidebarBg">-->
            <!--Sidebar Accordion Menu-->

            <div>
                <?php $this->load->view('includes/left_menu'); ?> 

                <div class="container-fluid contentBg well">
                    <div class="navbar navbar-fixed-top">
                        <div class="navbar-inner">
                            <?php $this->load->view('includes/header'); ?>
                        </div>

                    </div>

                    <div class="row-fluid">
                        <!--<ul>
                            <li>Style</li>
                            <li>Contact Collection</li>
                            <li>Questions</li>
                            <li>Languages</li>
                            <li>Data</li>
                        </ul>-->
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="success-msg">
                                <?php
                                echo $this->session->flashdata('success');
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="error-msg">
                                <?php
                                echo $this->session->flashdata('error');
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (validation_errors()): ?>
                            <div class="val-errors">
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="span12">

                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>

            </div>
            <!--      </div>/row-->
        </div><!--/.fluid-container-->

        <!--Body-->

        <!--Footer-->
        <?php $this->load->view('includes/footer'); ?>
        <!--Footer-->
    </body>
</html>