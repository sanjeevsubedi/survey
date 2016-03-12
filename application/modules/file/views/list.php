<?php
$attributes = array('name' => 'document_list', 'id' => 'document_list', 'class' => 'document_list common-form');
?>
<div class="formInner">
    <h3 class="titleB">Library <span><?php echo $survey->name;?></span></h3>
    <?php echo form_open_multipart(base_url() . 'file/save_doc/' . $eid, $attributes); ?>  
    <div id="accordion">
        <h3><label for="question">Manage documents</label><span class="status"></span></h3>
        <div class="content-container">
            <fieldset>
                <div class="doc-info">These are the documents you added with your questions. If you would like to add more just....
                </div>
                <div class="clearfix"></div>
                <div class="input-container middle-container">
                    <p>These are the documents you added with your questions. You can delete or add documents within the table below.</p>

                    <div class="doc-middle-file-bg">
                        <?php foreach ($questions as $k => $question): ?> 
                            <?php foreach ($question['qf'] as $qfile): ?>
                                <div class="doc-list">
                                    <div class="doc-file-img">
                                        <img src="<?php echo $qfile->img_path; ?>" alt="">
                                    </div>

                                    <div class="doc-file-name">
                                        <?php
                                        echo anchor(base_url() . 'uploads/question/' .
                                                $qfile->name, $qfile->name, array('target' => '_blank'));
                                        ?> <span>(<?php echo $question['q'] ?>)</span>
                                    </div>

                                    <a href="<?php echo base_url() . 'file/doc_del/' . $eid . '/q/' . $qfile->id ?>" class="del-doc-libaray">&nbsp;</a>

                                </div>
                            <?php endforeach; ?>

                            <?php foreach ($question['af'] as $afile): ?>
                                <div class="doc-list">
                                    <div class="doc-file-img">
                                        <img src="<?php echo $afile->img_path; ?>" alt="">
                                    </div>

                                    <div class="doc-file-name">
                                        <a href="<?php echo base_url() . 'uploads/answer/' . $afile->name; ?>" target="_blank"><?php echo $afile->name; ?></a>
                                        <span>(<?php echo $question['q'] ?>)</span>
                                    </div>

                                    <a href="<?php echo base_url() . 'file/doc_del/' . $eid . '/a/' . $afile->id; ?>" class="del-doc-libaray">&nbsp;</a>

                                </div>

                            <?php endforeach; ?>

                        <?php endforeach ?>
                        <?php foreach ($s_docs as $sfile): ?>
                            <div class="doc-list">
                                <div class="doc-file-img">
                                    <img src="<?php echo $sfile->img_path; ?>" alt="">
                                </div>

                                <div class="doc-file-name">
                                    <a href="<?php echo base_url() . 'uploads/survey/' . $sfile->name; ?>" target="_blank"><?php echo $sfile->name; ?></a>
                                    <!--<span>(<?php //echo $question['q']                                                              ?>)</span>-->
                                </div>

                                <a href="<?php echo base_url() . 'file/doc_del/' . $eid . '/s/' . $sfile->id; ?>" class="del-doc-libaray">&nbsp;</a>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="manage-doc-upload">
                    <!-- <label>Add More documents</label> -->
                    <fieldset id="fileTypeInput">
                        <input type="file" name="survey_documents[]" id="question_documents" class="letxt compulsory multi"/>
                        <!-- <input type="submit" name="upload_next" value="Add More documents" class="upload-doc" /> -->
                    </fieldset>
                </div>


                <fieldset>
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'survey/step_5/' . $eid, 'Back'); ?>
                    </div>
                    <div class="nextBtn">
                        <input type="submit" name="next" value="Next" class="nxt-accordion"/>
                        <?php //echo anchor(base_url() . 'language/survey_lang/' . $eid, 'Next'); ?>
                    </div>


                </fieldset>


            </fieldset>
        </div>
    </div> 

    <?php echo form_close(); ?>
</div>


<script>
    $(document).ready(function(){
        //initialize accordion
        accordionHandler("accordion");
        $("#document_list").validate();
    })
</script>