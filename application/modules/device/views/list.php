<?php
$attributes = array('name' => 'list_device', 'id' => 'list_device', 'class' => 'list_device');
?>
<div class="lists">
    <h3>Devices</h3>

    <?php if (!empty($devices)): ?>
        <?php echo form_open(base_url() . 'device/manage', $attributes); ?>  
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
            <?php foreach ($devices as $device): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $device->id; ?>" />
                        <?php if ($device->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td><?php echo $device->name; ?></td>
                    <td class="edit"><?php echo anchor(base_url() . 'device/edit/' . $device->id, 'edit'); ?></td>
                    <td class="delete"><?php echo anchor(base_url() . 'device/delete/' . $device->id, 'Delete'); ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>
