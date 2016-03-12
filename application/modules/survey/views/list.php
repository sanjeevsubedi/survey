<a id="data-intro-popup" href="#data-intro-popup-container"></a>
<div id="data-intro-popup-container" style="display:none;width:400px;">
    <div class="start-now">Start now</div>
    <div class="thanks">Thanks for signing up!</div>
    <div class="data-pop-desc">
        You are using the FREE Demo Version
        of expoCRM. You can create up to
        <span class="lead-count">15 Leads</span> for free....<span class="x-count">xxxx</span>
        <br>START NOW!
    </div>
    <div class="set-up-new">
        <a href="<?php echo base_url() . 'survey' . DS . 'step_1' ?>">Set up New Questionnaire</a>
    </div>
    <br><br>
    <div class="use-dummy">
        <a href="#" id="goToDummy">Use Dummy Questionnaire</a>
    </div>
    <div class="not-sure"><p>Not sure how to use expoCRM?</p></div>
    <div class="take-tour"><a href="#">Take a tour</a></div>

</div>

<div class="new-survey-icon"><a class="createAsurvey" href="<?php echo base_url() ?>survey/step_1">New Questionnaire</a></div>
<div class="formInner" id="main-form-intro">
    <h3>Get Started</h3>
    <div id="accordion2">
        <h3> <label for="survey">Active Questionnaires</label></h3>
        <div class="grid-row">
            <?php if (!empty($active_surveys)): ?>
                <?php
                $total = count($active_surveys);
                $i = 1;
                ?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <?php foreach ($active_surveys as $survey): ?>
                        <tr class="row-wrapper">
                            <td class="cols-one" width="25%">
                                <table>
                                    <tr>
                                        <td><h2>
                                                <?php echo anchor(base_url() . 'survey/step_1/' . $survey->id, $survey->name); ?>
                                            </h2><span><?php echo $survey->created_date; ?></span>
                                            <?php if (empty($survey->template_owner)): ?>
                                                <a href="#uploadnow" class="uploadnowtrigger" data-info="This Questionaire is not complete - Please upload
                                                   background-image !" data-id="<?php echo $survey->id; ?>"><img src="<?php echo base_url(); ?>assets/img/warning.png"></a>
                                               <?php endif; ?>

                                        </td>
                                        <td><a href="<?php echo base_url() . 'survey/copy/' . $survey->id ?>" ><img src="<?php echo base_url(); ?>assets/img/letterpad-icon.png" alt="letterpad-icon" width="28" height="28" /></a></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><a href="<?php echo base_url() . 'surveyreport/' . $survey->id ?>" target="_blank"><img src="<?php echo base_url(); ?>assets/img/gray-download-icon.png" alt="gray-download-icon" width="28" height="28" /></a></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><img src="<?php echo base_url(); ?>assets/img/gray-tick.png" alt="gray-tick-icon" width="28" height="28" /></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="cols-two" width="75%">
                                <table>
                                    <tr class="grid-heading">
                                        <th><?php echo anchor(base_url() . 'survey/step_1/' . $survey->id, "Layout"); ?></th>
                                        <th>Installations</th>
                                        <th>In use</th>
                                        <th><div style="position:relative;"><a href="#"                                         <?php if (($role == "company-super-admin" && $user_company_id == $survey->company_id) || $role == "super-admin") : ?>
                                                                               class="evaluate" <?php endif; ?> id="ev<?php echo $survey->id ?>" rel="<?php echo $survey->id ?>">Evaluation</a></div></th>
                        </tr>
                        <tr class="first-row-content">
                            <td><?php echo $survey->question; ?> Question(s)</td>
                            <td><?php echo $survey->download; ?> Device(s)</td>
                            <td><?php echo $survey->questionnnaires; ?> Questionaire(s)</td>
                            <td>Partly</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><span class="red"><?php echo $survey->processed_bcards; ?>/<?php echo $survey->uploaded_bcards; ?> Business Cards</span>
                                <ul id="circle">
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#" class="red">3</a></li>
                                </ul>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </td>
                    </tr>
                    <?php if ($i != $total): ?>
                        <tr><td colspan="2"><br /></td></tr>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <h3><label for="device">Completed Questionnaires</label></h3>
        <div class="grid-row">
            <?php if (!empty($completed_surveys)): ?>
                <?php
                $total = count($completed_surveys);
                $i = 1;
                ?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <?php foreach ($completed_surveys as $survey): ?>   
                        <tr class="row-wrapper">
                            <td class="cols-one" width="25%">
                                <table>
                                    <tr>
                                        <td><h2><?php echo anchor(base_url() . 'survey/step_1/' . $survey->id, $survey->name); ?> </h2><span><?php echo $survey->created_date; ?></span></td>
                                        <td>
                                            <?php if (($role == "company-super-admin" && $user_company_id == $survey->company_id) || $role == "super-admin") : ?>
                                                <a href="<?php echo base_url() . 'survey/copy_concluded_survey/' . $survey->id ?>"><img src="<?php echo base_url(); ?>assets/img/letterpad-icon.png" alt="letterpad-icon" width="28" height="28" /></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <?php if (($role == "company-super-admin" && $user_company_id == $survey->company_id) || $role == "super-admin") : ?>
                                                <a href="<?php echo base_url() . 'survey/delete/' . $survey->id ?>"><img src="<?php echo base_url(); ?>assets/img/gray-delete-icon.png" alt="gray-delete-icon" width="28" height="28" /></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <?php if (($role == "company-super-admin" && $user_company_id == $survey->company_id) || $role == "super-admin") : ?>
                                                <a href="#"><img src="<?php echo base_url(); ?>assets/img/dark-tick.png" alt="dark-tick-icon" width="28" height="28" /></a></td>
                                        <?php endif; ?>
                                    </tr>
                                </table>
                            </td>
                            <td class="cols-two" width="75%">
                                <table>
                                    <tr class="grid-heading">
                                        <th class="lightBlue">Layout</th>
                                        <th class="lightBlue">Installations</th>
                                        <th class="lightBlue">In use</th>
                                        <th class="evalution">Evaluation</th>
                                    </tr>
                                    <tr class="first-row-content">
                                        <td><?php echo $survey->question; ?> Question(s)</td>
                                        <td><?php echo $survey->download; ?> Device(s)</td>
                                        <td><span class="green">23 Visitors</span></td>
                                        <td>Complete</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php if ($i != $total): ?>
                            <tr><td colspan="2"><br /></td></tr>
                        <?php endif; ?>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        var firstAccess = !!('<?php echo $first_access; ?>');
        $("#data-intro-popup").fancybox(
        {
            helpers:  {
                overlay : {
                    css : {
                        'background' : 'rgba(0,0,0,0.5)'
                    }
                }
            },
            wrapCSS    : 'survey-startup'
        }
    ).trigger(firstAccess ? 'click' : '');

        //evaluate event handler
        $('.evaluate').click(function() {
            var surveyId = $(this).attr("rel");
            var content = '<div id="popup-wrapper" class="popup-width popupinfoWrapper">'+
                '<div class="productPreview">'+
                '<h2>EVALUATION</h2>'+
                '<div class="popup-grid-wrapper clearfix" style="min-height:125px">'+
                '<div class="popup-grid-inner">'+
                '<p>'+
                'This questionaire is not concluded yet. If you would like to conclude the questionaire now to get a final'+
                'evaluation or do you still want to collect further In use Evaluation questionaires?'+
                '</p>'+
                '</div>'+
                '<a id="close" rel="'+surveyId+'" title="Close" href="#">Close</a>'+
                '<div class="links">'+
                '<a href="<?php echo base_url() . 'survey/conclude/' ?>'+surveyId+'" class="lft">&rsaquo; Conclude Questionaire</a>'+
                '<a href="#" class="rgt">&rsaquo; Partly evaluate</a>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>';
            
            $(content).insertBefore($(this));
            return false;
        })
        
        $("#close").live('click',function(){
            var id = $(this).attr('rel');
            $("#ev"+id).prev().remove();
            return false;
        })
        
        $("#popup-wrapper").live('click',function(ev){
            ev.stopPropagation();
        })
        
        $(document).click(function(ev){
            $(".evaluate").prev().remove();
            $(".pop-container").remove();
        })
        
        $("#goToDummy").on('click',function(){
            $.fancybox.close();
            return false;
        })
        
        
        $(".uploadnowtrigger").hover(function(){
            var info = $(this).attr('data-info');
            var dataTitle = "Background image";
            var surveyId = $(this).attr('data-id');
            $(".pop-container").remove();
            var content = '<div class="pop-container" style="z-index:99"><div class="helpPopup popupWrapper uploadWrapper"  style="position:absolute;bottom:0;left:0">'+
                '<div class="upload-title">'+dataTitle+'</div>'+
                '<p>'+info+'</p>'+
                '<a href="<?php echo base_url() ?>survey/step_1/'+surveyId+'">Upload now</a>'+
                '<a href="#" title="Close" id="close" class="tooltip-close">Close</a>'+
                '</div></div>';
            $(content).insertBefore($(this));
            return false;
        })
        
        $(".tooltip-close").live('click',function(){
            $(".pop-container").remove();
            return false;
        })
        
        $(".pop-container").live('click',function(ev){
            ev.stopPropagation();
        })

        
        
    })
</script>

