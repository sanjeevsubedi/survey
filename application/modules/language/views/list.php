<?php
$attributes = array('name' => 'list_language', 'id' => 'list_language', 'class' => 'list_language common-form');
?>
<div class="lists">
    <h3>languages</h3>

    <?php if (!empty($languages)): ?>
        <?php echo form_open(base_url() . 'language/manage', $attributes); ?>  
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
            <?php foreach ($languages as $language): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $language->id; ?>" />
                        <?php if ($language->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td><?php echo $language->name; ?></td>
                    <td class="edit"><?php echo anchor(base_url() . 'language/edit/' . $language->id, 'edit'); ?></td>
                    <td class="delete"><?php echo anchor(base_url() . 'language/delete/' . $language->id, 'Delete'); ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>
