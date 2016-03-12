<?php
$attributes = array('name' => 'list_salesperson', 'id' => 'list_salesperson', 'class' => 'list_salesperson');
?>
<div class="lists">
    <h3>Sales Person</h3>

    <?php if (!empty($salespersons)): ?>
        <?php echo form_open(base_url() . 'salesperson/manage', $attributes); ?>  
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
            <?php foreach ($salespersons as $salesperson): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $salesperson->id; ?>" />
                        <?php if ($salesperson->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td><?php echo $salesperson->name; ?></td>
                    <td class="edit"><?php echo anchor(base_url() . 'salesperson/edit/' . $salesperson->id, 'edit'); ?></td>
                    <td class="delete"><?php echo anchor(base_url() . 'salesperson/delete/' . $salesperson->id, 'Delete'); ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>
