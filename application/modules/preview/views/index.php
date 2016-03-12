<div class="lists">
    <h3>Surveys Preview</h3>
    <?php if (!empty($questions)): ?>
        <table class="previewServey">    
            <tr>
                <th>S.N.</th>
                <th>Question</th>
                <th>Answers</th>
            </tr>           
            <?php $i=0; foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo ++$i; ?></td>
                    <td><?php echo $question->question; ?></td>
                    <td><?php echo get_answer($question); ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>
    <div class="backBtn">
        <a href="javascript:void(0);" title="Back" onclick="history.go(-1)">Back</a>
    </div>
</div>
