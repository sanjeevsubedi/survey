<?php
$attributes = array('name' => 'list_template', 'id' => 'list_template', 'class' => 'list_template');
?>
<div class="lists">
    <h3>templates</h3>

    <?php if (!empty($templates)): ?>
        <?php echo form_open(base_url() . 'template/manage', $attributes); ?>  
        <div class="update-actions">    
            <select name="action">
                <option value="delete">Delete</option>
                <option value="publish">Publish</option>
                <option value="unpublish">Unpublish</option>
            </select>
            <input type="submit" value="Update" />
        </div>
        <table>    
            <tr><th><input type="checkbox" name="parent" id="parent" /></th><th>Name</th><th colspan="2">Actions</th></tr>           
            <?php foreach ($templates as $template): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $template->id; ?>" />
                        <?php if ($template->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td><?php echo $template->name; ?></td>
                    <td class="edit"><?php echo anchor(base_url() . 'template/edit/' . $template->id, 'edit'); ?></td>
                    <td class="delete"><?php echo anchor(base_url() . 'template/delete/' . $template->id, 'Delete'); ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>
