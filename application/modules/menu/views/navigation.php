<?php
$style = !empty($survey_id) ? base_url() . 'survey' . DS . 'step_1' . DS . $survey_id : "#";
$contact = !empty($survey_id) ? base_url() . 'survey' . DS . 'contacts' . DS . $survey_id : "#";
$question = !empty($survey_id) ? base_url() . 'survey' . DS . 'step_5' . DS . $survey_id : "#";
$document = !empty($survey_id) ? base_url() . 'file' . DS . 'show_docs' . DS . $survey_id : "#";
$language = !empty($survey_id) ? base_url() . 'language' . DS . 'survey_lang' . DS . $survey_id : "#";
$user = !empty($survey_id) ? base_url() . 'member' . DS . 'auth_survey_user' . DS . $survey_id : "#";
?>
<div class="broadcast"><a href="<?php echo base_url() . 'surveyreport' . DS . 'send_message'.DS.$survey_id;?>">Broadcast Message</a></div>
<div class="free-demo">Your <a href="#" title="">free demo</a> allows only [X] more Leads!</div>
<ul class="tabs">
    <li id="style">
        <a href="<?php echo $style; ?>">Style</a>
    </li>
    
<?php if(!$scan_only):?>
    <li id="question">
        <a href="<?php echo $question; ?>">Questions</a>
    </li>

    <li id="document">
        <a href="<?php echo $document; ?>">Documents</a>
    </li> 
<?php endif;?>
    <!-- Only original survey is shown this link. Translated survey are not shown this link-->
    <?php if ($is_original_survey || $translation_set_id == 0): ?>
        <li id="language">
            <a href="<?php echo $language; ?>">Languages</a>
        </li>
    <?php endif; ?>

    <li  id="user">
        <a href="<?php echo $user; ?>">Users</a>
    </li>
    <li id="contact" class="last">
        <a href="<?php echo $contact; ?>">Contact Collection</a>
    </li>

</ul>