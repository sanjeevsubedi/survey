<?php
$attributes = array('name' => 'list_contact', 'id' => 'list_contact', 'class' => 'list_contact');
?>
<?php echo $pagination; ?>
<div class="lists">
    <h3>Contact Fields</h3>

    <?php if (!empty($contacts)): ?>
        <?php echo form_open(base_url() . 'contact/manage', $attributes); ?>  
        <div class="update-actions">    
            <select name="action">
                <option value="delete">Delete</option>
                <option value="publish">Publish</option>
                <option value="unpublish">Unpublish</option>
            </select>
            <input type="submit" value="Update" />
        </div>
        <table>    
            <tr><th><input type="checkbox" name="parent" id="parent" /></th><th>Name</th><th>Type</th><th colspan="2">Actions</th></tr>           
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><input type="checkbox" name="child[]" class="child" value="<?php echo $contact->id; ?>" />
                        <?php if ($contact->status == 1): ?><div class="publish-img"></div>  <?php else: ?><div class="unpublish-img"></div><?php endif; ?>
                    </td>
                    <td><?php echo $contact->name; ?></td>
                    <td><?php echo $contact->field_name; ?></td>
                    <td class="edit">
                        <?php if (!in_array($contact->id, unserialize(DEFAULT_EN_CONTACT_FIELDS))): ?>
                            <?php echo anchor(base_url() . 'contact/edit/' . $contact->id, 'edit'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="delete">
                        <?php if (!in_array($contact->id, unserialize(DEFAULT_EN_CONTACT_FIELDS))): ?>
                            <?php echo anchor(base_url() . 'contact/delete/' . $contact->id, 'Delete'); ?>
                        <?php endif; ?>
                    </td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>
