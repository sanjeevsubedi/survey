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
        <!-- Le styles -->


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php
        $this->load->view('includes/head');
        ?>
    </head>
    <body class="loginPage">
        <div class="container-fluid logoHeader">
            <div class="row-fluid">
                <div class="span4 boxHeader">
                    <h4 class="icon-lock"><span>expoCRM</span></h4>
                </div>
            </div>
        </div>
        <div class="container-fluid"> 
            <div class="row-fluid">
                <?php echo $content; ?>
            </div>
        </div>
        <footer class="container-fluid">

            <!-- <p> &copy; Copyright <?php echo date("Y"); ?> Meaplan&nbsp;&nbsp;  |  &nbsp;&nbsp;Developed and Designed by: <a href="#">BubbleBridge</a></p> -->
        </footer>

    </body>
</html>