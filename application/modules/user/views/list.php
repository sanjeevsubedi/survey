<?php
$attributes = array('name' => 'user_list', 'id' => 'user_list', 'class' => 'user_list');
?>
<div class="lists">
    <h3>Users</h3>

    <?php if (!empty($user_list)): ?>
        <?php echo form_open(base_url() . 'member/manage', $attributes); ?>  
        <!--<div class="update-actions">    
            <select name="action">
                <option value="delete">Delete</option>
                <option value="publish">Active</option>
                <option value="unpublish">Passive</option>
            </select>
            <input type="submit" value="Update" />
        </div>-->
        <table>    
            <tr>
                <th><!--<input type="checkbox" name="parent" id="parent" />--></th>
                <th>First Name</th>
                <th>Last Name</th><th>Company</th>   
                <th>Status</th><th colspan="2">Actions</th></tr>           
            <?php foreach ($user_list as $userlist): ?>
                <tr>
                    <td>
                        <!--<input type="checkbox" <?php if ($userlist->role_id == 2) echo "disabled"; ?> name="child[]" class="child" value="<?php echo $userlist->id; ?>" />-->
                    </td>
                    <td><?php echo $userlist->first_name; ?></td>
                    <td><?php echo $userlist->last_name; ?></td>
                    <td><?php echo $this->user_model->get_company_name_by_id($userlist->company_id); ?></td>
                    <td><?php if ($userlist->status == 1): ?>Active  <?php else: ?>Inactive<?php endif; ?>
                    </td>
                    <td class="edit"><?php echo anchor(base_url() . 'member/edit/' . $userlist->id, 'edit'); ?></td>
                    <td><?php //echo anchor(base_url() . 'user/delete_user/' . $userlist->id, 'Delete');                   ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?> 
    <?php else: ?>
        <div class="no-records">No records found</div>
    <?php endif; ?>

</div>