<?php
$tooltip = array(
    'backoffice-user' => '<div >
    <h3>BACKOFFICE USER</h3>
    <p>As a Backoffice User you are allowed to:</p>
        <ul>
        <li>Access all Contact with</li>
        <li>Business Card Recognition</li>
    </ul>
</div>',
    'questionnaire-user' => '<div >
    <h3>QUESTIONAIRE USER</h3>
     <p>As a Questionaire User you are allowed to:</p>
        <ul>
       <li>View own Contact</li>
        <li>View own reports</li>
    </ul>
</div>',
    'questionnaire-admin' => '<div >
    <h3>QUESTIONAIRE ADMIN</h3>
     <p>As a Questionaire Admin you are allowed to:</p>
        <ul>
         <li>Add Questionaire User</li>
        <li>Delete Questionaire User</li>
        <li>Create Questionaire</li>
        <li>View Questionaires</li>
        <li>View Questionaire Reports</li>
        <!--<li>Add User</li>
        <li>Delete User</li>
        <li>Create Questionaire</li>
        <li>Delete Questionaire</li>
        <li>View all Questionaires</li>
        <li>View all Reports</li>-->
    </ul>
</div>',
);

$salutations = unserialize(SALUTATION);
$attributes = array('name' => 'user_right', 'id' => 'user_right', 'class' => 'user_right');
$attributes_new = array('name' => 'user_new', 'id' => 'user_new', 'class' => 'user_new common-form');
?>
<script>
    var permissions = [];
</script>
<div class="formInner">
    <h3 class="titleB">Users<span><?php echo $survey->name;?></span></h3>
    <div id="accordion">
        <h3><label for="users">Current users</label></h3>
        <div class="content-container">
            <fieldset>
                <div class="user-overview">Here is an overview of your current users or else you can add new users below.</div>
                <div class="user-table">
                    <?php if (!empty($cuser_lists)): ?>
                        <?php
                        $total = count($cuser_lists);
                        $count = 0;
                        ?>
                        <table class="sticky-tbl">    
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cuser_lists as $userlist): ?>
                                <?php
                                if ($count % 2 == 0) {
                                    $class = 'even';
                                } else {
                                    $class = 'odd';
                                }
                                ?>
                                <tr class="<?php echo $class; ?>">
                                    <td><?php echo $userlist->email; ?></td>
                                    <td><?php echo $userlist->first_name; ?></td>
                                    <td><?php echo $userlist->last_name; ?></td>
                                    <td><a class="delete-confirm" href="<?php echo base_url().'member/del_user_list/'.$eid.'/'.$userlist->id;?>">Delete</a></td>
                                </tr> 
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                        </table>

                        <div id="add-user-container" class="add-user-popup" style="display: none; width:400px;">
                            <div class="popup-title">Add User</div>
                            <p>When you are adding a new User we will send an e-mail to the address you have provided for verifacation.</p>
                            <?php echo form_open(base_url() . 'member/popup_register/' . $eid, $attributes_new); ?>  
                            <fieldset>
                                <!-- <label for="salutation">Salutation</label> -->
                                <select name="salutation" id="salutation" style="margin-left:0;">
                                    <option>Salutation</option>
                                    <?php foreach ($salutations as $salutation): ?>
                                        <option value="<?php echo $salutation; ?>"><?php echo $salutation; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="message"></span>
                            </fieldset>

                            <fieldset>
                                <!-- <label for="first_name">First name*</label> -->
                                <input type="text" class="form-text" name="first_name" placeholder="Name" id="first_name" value="<?php echo set_value('first_name'); ?>"/>
                                <span class="message"></span>    
                            </fieldset>

                            <fieldset>
                                <!--  <label for="last_name">Last name*</label> -->
                                <input type="text" class="form-text" name="last_name" placeholder="last name" id="last_name" value="<?php echo set_value('last_name'); ?>"/>
                                <span class="message"></span>    
                            </fieldset>
                            <fieldset>
                                <!-- <label for="email">E-Mail*</label> -->
                                <input type="text" class="form-text" placeholder="Email" name="email" value="<?php echo set_value('email') ?>" id="email"/>
                                <span class="message"></span>
                            </fieldset>
                            <input type="submit" value="save" name="save" />
                            <?php echo form_close(); ?> 
                        </div>
                        <a href="#add-user-container" id="add-user-pop" class="user-btn">add new user</a>

                        <?php echo form_close(); ?>
                    <?php else: ?>
                        <div class="no-records">No records found</div>
                    <?php endif; ?>


                </div>

                <!-- for importing members from excel file -->
                <div class="upload_file" style="">
                    <p class="import-instruction"><span class="icon-import"></span>Import from excel file</p>
                    <?php echo form_open_multipart(base_url() . 'member/import_excel_with_password/' . $eid); ?>  
                    <input type="file" name="members_excel" >
                    <button id="submit_excel" type="submit">Import</button>
                    <?php echo form_close(); ?>
                </div>
                <!-- importing members from excel file end -->

                <div class="actions">
                    <div class="backBtn">
                        <?php echo anchor(base_url() . 'language/survey_lang/' . $eid, 'Back'); ?>
                    </div>
                    <input type="button" name="next" value="Next" class="nxt-accordion" />
                </div>
            </fieldset>
        </div>

        <h3><label for="users">Manage users</label></h3>
        <div class="content-container">
            <fieldset>
                <div class="user-overview">Please check all users that you would
                    like to add to this questionaire:
                </div>
                <div class="user-table">
                    <?php if (!empty($cuser_lists)): ?>
                        <?php
                        $counts = 0;
                        ?>
                        <table id="tableOne" class="sticky-tbl">
                            <thead> 
                                <tr>
                                    <th ><input type="checkbox" name="parent" id="parent" class="parent-for-hidden" /></th>
                                    <th>Email</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cuser_lists as $userlist): ?>
                                    <?php
                                    if ($counts % 2 == 0) {
                                        $class = 'even';
                                    } else {
                                        $class = 'odd';
                                    }
                                    ?>
                                    <tr class="<?php echo $class; ?>">
                                        <td><input type="checkbox" <?php if ($userlist->id == $survey_owner) echo "disabled"; ?> name="child[]" 
                                            <?php if (in_array($userlist->id, $auth_users)) echo "checked = 'checked'"; ?>
                                                   class="child" id="<?php if ($userlist->id == $survey_owner) echo 'owner-row'; ?>" value="<?php echo $userlist->id; ?>" />
                                        </td>
                                        <td><?php echo $userlist->email; ?></td>
                                        <td><?php echo $userlist->first_name; ?></td>
                                        <td><?php echo $userlist->last_name; ?></td>
                                        <td><a class="delete-confirm" href="<?php echo base_url().'member/del_user_list/'.$eid.'/'.$userlist->id;?>">Delete</a></td>
                                    </tr> 
                                    <?php $counts++; ?>
                                <?php endforeach; ?>
                            <tbody>
                            <tfoot>
                                <tr style="display:none;">
                                    <td colspan="4">
                                        No users match the filter.
                                    </td>
                                </tr>   
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <div class="no-records">No records found</div>
                    <?php endif; ?>
                </div>
                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="button" name="next" value="Next" class="nxt-accordion" id="authorize-users" />
                </div>
            </fieldset>
        </div>

        <h3><label for="users">User rights</label></h3>
        <div class="content-container">
            <fieldset>
                <div class="user-overview">Please check all users that you would
                    like to add to this questionaire:
                </div>
                <?php echo form_open(base_url() . 'member/assign_priveledge/' . $eid, $attributes); ?>  

                <div class="user-table user-table-3">

                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($permissions as $permission): ?>
                                    <th><span style="float:left;"><?php echo $permission->name; ?></span>
                                        <span style="margin-left:5px; float:left;">
                                            <a href="#" data-desc="<?php echo $tooltip[$permission->slug]; ?>" class="custom-tooltip"><img src="<?php echo base_url() ?>assets/img/user-rights-icon.png" alt=""></a></span></th>
                            <script>
                                permissions.push('<?php echo $permission->id ?>'); 
                            </script>
                        <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($user_lists)): ?>
                                <?php foreach ($user_lists as $userlist): ?>
                                    <tr>
                                        <td><?php echo $userlist->email; ?></td>
                                        <?php foreach ($permissions as $permission): ?>
                                            <td>
                                                <input type="radio" <?php echo set_checkbox('right[' . $userlist->id . ']', isset($userlist->privelege) && $permission->id, $permission->id == $userlist->privelege ? TRUE : FALSE); ?> name="right[<?php echo $userlist->id; ?>]" value="<?php echo $permission->id; ?>" />
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td class="white"></td><td colspan="3" style="background:#E7F9FF;border:none;"><div class="no-records">No records found</div></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>


                </div>

                <div class="actions">
                    <input type="button" name="back" value="Back" class="prev-accordion" />
                    <input type="submit" name="next" value="Next" class="nxt-accordion" />
                </div> <?php echo form_close(); ?>
            </fieldset>
        </div>

    </div>
    <!--<div class="backBtn">
    <?php //echo anchor(base_url() . 'language/survey_lang/' . $eid, 'Back');  ?>
    </div>-->
</div>

<script>
    $(document).ready(function(){
$(".sticky-tbl").stickyTableHeaders({fixedOffset: 60});

        //for quick search start 
        //sushil 8 october
        //Setup the sorting for the table with the first column initially sorted ascending
        //and the rows striped using the zebra widget
        $("#tableOne").tablesorter({
            //{ sortList: [[0, 0]], widgets: ['zebra'] }
             headers: { 0: { sorter: false},4: { sorter: false} }

            });

        //Setup the quickSearch plugin with on onAfter event that first checks to see how
        //many rows are visible in the body of the table. If there are rows still visible
        //call tableSorter functions to update the sorting and then hide the tables footer. 
        //Else show the tables footer  
        $("#tableOne tbody tr").quicksearch({
            labelText: 'Search: ',
            attached: '#tableOne',
            position: 'before',
            delay: 100,
            loaderText: 'Loading...',
            onAfter: function() {
                if ($("#tableOne tbody tr:visible").length != 0) {
                    $("#tableOne").trigger("update");
                    $("#tableOne").trigger("appendCache");
                    $("#tableOne tfoot tr").hide();
                }
                else {
                    $("#tableOne tfoot tr").show();
                }
            }
        });
        //quick search end

        $(".custom-tooltip").hover(function(){
            var desc = $(this).attr('data-desc');
            $(".pop-container").remove();
            var content = '<div class="pop-container" style="z-index:99"><div class="helpPopup popupWrapper userpermission-list"  style="position:absolute;bottom:0;left:0">'+
                desc+
                '<a href="#" title="Close" id="close" class="tooltip-close">Close</a>'+
                '</div>';
            $(content).insertBefore($(this));
            return false;
        })
        
        $(".tooltip-close").live('click',function(){
            $(".pop-container").remove();
            return false;
        })
        
        $(".pop-container").live('click',function(ev){
            ev.stopPropagation();
        })
        
        $(document).click(function(ev){
            $(".pop-container").remove();
        })
        
        accordionHandler("accordion");
        $("#user_new").validate({
            rules: {
                first_name: {
                    required : true
                
                },
                last_name: {
                    required : true
                
                },
                email: {
                    required : true,
                    email : true,
                    remote: {
                        url: "<?php echo base_url() ?>ajax/index/check_email_exists/",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            }
                        }
                    }
                    
                }
            },
            messages: {
                first_name: {
                    required:"Required"
                    
                },
                last_name: {
                    required:"Required"
                   
                },
                email: {
                    required:"Required",
                    email:"Enter valid email address",
                    remote: "email already exists" 
                }
            }
        });
        
        //popup registration form
        $("#add-user-pop").fancybox({wrapCSS:'adduser-pop'});
       
        //ajax callback to add the user to the survey
        $("#authorize-users").click(function() {
            var userIds = [];
            $("input[class=child]:checked").each(function(){
                userIds.push($(this).attr('value'));
            })
            //userIds.sort();
            //console.log(userIds);
            var survey_id = '<?php echo $eid ?>';
            var output = "";
            $.post("<?php echo base_url() ?>ajax/index/user_authorize/", { "survey_id": survey_id, "user_id":userIds}, function(response){
                if(response != "") {
                    var response = JSON.parse(response);
                    console.log(response);
                    $(response).each(function(i,record){
                        output += "<tr><td>"+record.email+"</td>";
                        $(permissions).each(function(index,value){
                            var selected = ''/*record.permission_id == value ? 'checked = checked' : ""*/;
                            
                            if(record.permission_id == value) {
                                selected = 'checked = checked';
                            } else if(record.permission_id == 0 && value == 2) {  //by default questionnaire admin is selected
                                selected = 'checked = checked';
                            } 
                            
                            output += "<td><input type='radio' "+selected+" name= 'right["+record.id+"]' value='"+value+"' /></td>";
                        })
                        output += "</tr>";
                    })
                }
                $(".user-table-3 table tbody").html('');
                $(".user-table-3 table tbody").html(output);
            });                                                              
        });
        $(".delete-confirm").click(function (ev) {
            var confirmation = confirm('Are you sure to delete?');
            if (!confirmation) {
                ev.preventDefault();
            }
        })
       
    })
</script>