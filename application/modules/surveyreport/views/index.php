<?php
//echo "<pre>";
//print_r($survey_users);
//die;
?>
<script>

    $(document).ready(function () {
        $(".delete-confirm").click(function (ev) {
            var confirmation = confirm('Are you sure to delete?');
            if (!confirmation) {
                ev.preventDefault();
            }
        })

        $(".multidel").click(function (ev) {


            if ($(".child:checked").length == 0) {
                alert("Please check at least one record");
                return false;
            }

            var confirmation = confirm('Are you sure to delete?');
            if (!confirmation) {
                return false;
            }
        })
    })


</script>
<?php if(!$backOfficeUser):?>
<a class="export-report" href="<?php echo site_url('surveyreport/generatexls/' . $survey_id . '/' . $cur_user); ?>" title="Export to excel">Export to Excel</a>&nbsp;&nbsp;
<a class="export-package" href="<?php echo site_url('surveyreport/zip/' . $survey_id . '/' . $cur_user); ?>" title="Download Excel Export and Images">Download Excel Export and Images</a>&nbsp;&nbsp;
<p class="survey-name"><?php echo $survey->name;?></p>
<p class="lead-count">Total lead count: <?php echo $leadcount; ?></p>
<a class="importbcard export-report" href="<?php echo site_url('bcard/process_all/' . $survey_id) ?>">Import business card data</a>
<?php endif;?>
<form action="<?php echo site_url('surveyreport/multidelete'); ?>" method="post">
    <?php if(!$backOfficeUser):?>
    <input type="submit" class="multidel" value="delete" />
<?php endif;?>
    <input type="hidden" name="sid" value="<?php echo $survey_id; ?>" />
    <table border="1" class="survey-overview-table">
        <thead>
            <tr>
            <?php if(!$backOfficeUser):?>
                <th><input type="checkbox" class="parent"/></th>
                
                <th>Action</th>
            <?php endif;?>
                <th>Lead generated time<?php
                    $i = 0;
                    $sheet->setCellValue(get_alphabet($i) . "1", "Lead generated time");
                    ?>
                </th>
                <th> Uploaded time <?php
                    
                    $sheet->setCellValue(get_alphabet(++$i) . "1", "Uploaded time");
                    ?>
                </th>
                
                <th>Card Id <?php
                    $sheet->setCellValue(get_alphabet( ++$i) . "1", "Card Id");
                    ?></th>
                <th>EventMonitor Guid <?php
                    $sheet->setCellValue(get_alphabet( ++$i) . "1", "EventMonitor Guid");
                    ?></th>
                       <th>Barcode Content <?php
                    $sheet->setCellValue(get_alphabet( ++$i) . "1", "EventMonitor Guid");
                    ?></th>
                <th>Uploaded By <?php
                    $sheet->setCellValue(get_alphabet( ++$i) . "1", "Uploaded By");
                    ?></th>
                <?php foreach ($contact_details_heading as $value): ?>
                    <th><?php
                        $sheet->setCellValue(get_alphabet( ++$i) . "1", @$value->name);
                        echo @$value->name;
                        ?></th>
                <?php endforeach; ?>
                <!--Other info headings-->
                <th>Device <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Device"); ?></th>
                <th>Image Link <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Image Link"); ?></th>
                <!--<th>Original Image <?php //$sheet->setCellValue(get_alphabet(++$i) . "1", "Original Image");                                       ?></th>-->
                <th>Telemarketing Qualification <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Telemarketing Qualification"); ?></th>
                <th>Assign To <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Assign To"); ?></th>
                <th>Comment <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Comment"); ?></th>
                <th>Comment Image<?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Comment Image"); ?></th>
                <th>Comment Audio<?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Comment Audio"); ?></th>
                <th>Receive Email <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Receive Email"); ?></th>

                <!--Question Heading-->
                <?php $other_count = 0;?>
                <?php foreach ($questions_heading as $value): ?>
                    <th><?php
                        $sheet->setCellValue(get_alphabet( ++$i) . "1", @$value->name);
                        //echo @$value->name;
                        echo anchor("statistic/question_stat/" . @$value->question_id, @$value->name, array('target' => '_blank'));
                        ?></th>
                    <?php if ($value->other === "1"): ?>
                        <th>Other <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Other"); ?></th>
                        <?php $other_count ++ ;?>
                    <?php endif; ?>
                    <th>Comment <?php $sheet->setCellValue(get_alphabet( ++$i) . "1", "Comment"); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead><tbody>
            <?php if (!empty($survey_users)): ?>
                <?php
                $j = 2;
                foreach ($survey_users as $value):
                    ?>
                    <tr>
                        <?php if(!$backOfficeUser):?>
                        <td><input type="checkbox" name="child[]" class="child" value="<?php echo $value->survey_user_id; ?>"/></td>
                        
                        <td>
                            <a target="_blank" href="<?php echo site_url('surveyreport/edit/' . $survey_id . '/' . $value->survey_user_id); ?>" title="Edit">Edit</a>
                            |
                            <a href="<?php echo site_url('surveyreport/delete/' . $survey_id . '/' . $value->survey_user_id); ?>" class ="delete-confirm" title="Delete">Delete</a>
                        </td>
                    <?php endif;?>

                        <?php echo extract_row_data($survey_id, $value->survey_user_id, $sheet, $j); ?>
                        <?php
                        $j++;
                    endforeach;
                    ?>
                    <!--Save XLS file-->
                    <?php save_xls_file($xls, $survey_id, $cur_user); ?>
                </tr>
                <?php
            else:
                $colspan = 14 + count($contact_details_heading) + 2 * count($questions_heading) + $other_count;
                echo "<tr><td colspan='" . $colspan . "' align='center'>No Records Found</td></tr>";
                ?>
            <?php endif; ?>

        </tbody>
    </table>
</form