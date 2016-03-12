<?php
$attributes = array('name' => 'list_permission', 'id' => 'list_permission', 'class' => 'list_permission');
?>
<div class="lists">
    <h3>Permissions</h3>

    <?php if (!empty($permissions)): ?>
        <?php echo form_open(base_url() . 'permission/manage', $attributes); ?>  
        <div class="update-actions">    
            <select name="action">
                <option value="delete">Delete</option>
                <option value="publish">Publish</option>
                <option value="unpublish">Unpublish</option>
            </select>
            <input type="submit" value="Update" />
        </div>
        <table>    
            <tr><th><input type="checkbox" name="parent" id="parent" /></th><th>Name</th><th colspan="3">Actions</th></tr>           
            <?php foreach ($permissions as $permission): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $permission->id; ?>" />
                        <?php if ($permission->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td>
                        <?php echo $permission->name; ?>

                    </td>
                    <td class="edit"><?php echo anchor(base_url() . 'permission/edit/' . $permission->id, 'Edit'); ?></td>
                    <td class="delete"><?php echo anchor(base_url() . 'permission/delete/' . $permission->id, 'Delete'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>
</div>