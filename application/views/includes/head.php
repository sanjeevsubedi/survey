<script>
    //checks whether accordion should be disabled or enabled
    var accordionVal = !!('<?php echo $accordion_disable_flag; ?>');
</script>
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.MultiFile.js"></script>
<script src="<?php echo base_url(); ?>assets/js/accordion_helper.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ajax_fileupload.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.jqtransform.js"></script>
<script src="<?php echo base_url(); ?>assets/js/preview_handler.js"></script>

<?php
echo $_scripts;
echo $_styles;
?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.collapsible.min.js"></script>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/extend.css" rel="stylesheet">

<script type="text/javascript">
    $(function() {
        //===== Collapsible elements management =====//
        $('.exp').collapsible({
            defaultOpen: 'current',
            cookieName: 'navAct',
            cssOpen: 'active',
            cssClose: 'inactive',
            speed: 200
        });
	
        $('.opened').collapsible({
            defaultOpen: 'opened,toggleOpened',
            cssOpen: 'inactive',
            cssClose: 'normal',
            speed: 200
        });
	
        $('.closed').collapsible({
            defaultOpen: '',
            cssOpen: 'inactive',
            cssClose: 'normal',
            speed: 200
        });
	
	
        $('.goTo').collapsible({
            defaultOpen: 'openedDrop',
            cookieName: 'smallNavAct',
            cssOpen: 'active',
            cssClose: 'inactive',
            speed: 100
        });
		
        // custom form elements
        $("form.new_survey, form.common-form, #popup-wrapper, .popupWrapper").jqTransform();
        $(".jqtransform").jqTransform();
    });
</script> 