<!DOCTYPE html>
<html lang="en"><head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">

        <title>Exporm</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">
        <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/highcharts.js"></script>
         <script src="<?php echo base_url(); ?>assets/js/jquery.stickytableheaders.js"></script>
        <script>
            $(document).ready(function () {
                // add multiple Check / Uncheck functionality
                $(".parent").click(function () {
                    $(".child").attr("checked", this.checked);
                });

                // if all child checkbox are selected, check the Parent checkbox
                // and viceversa
                $(".child").click(function () {

                    if ($(".child").length == $(".child:checked").length) {
                        $(".parent").attr("checked", "checked");
                    } else {
                        $(".parent").removeAttr("checked");
                    }
                });
                $("table").stickyTableHeaders();
            })

        </script>
        <!-- Le styles -->


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link href="<?php echo base_url(); ?>assets/css/report.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
    </head>
    <body>
        <div class="surveyreport">
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
            <div>
                <?php echo $content; ?>
            </div>
        </div>
    </body>
</html>