<table width="600" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td width="1" bgcolor="#fff"></td>
            <td width="14" bgcolor="#fff"></td>
            <td width="20" bgcolor="#fff">&nbsp;</td>
            <td valign="top" bgcolor="#fff">
                <br>
                <span style="font-family:arial,helvetica;font-size:12px;">
                    <b>
                        <br><?php echo "Hi " . $first_name . ","; ?>
                    </b>
                    <br><br>
                    <!--<div class="">                               
                         Your login information are as below.
                    </div>
                    <br>
                    <div class="">  
                      User name : <?php //echo $email;        ?><br/>
                      Password : <?php //echo $password;        ?>
                    </div>-->

                    <div>You have been registered successfully.<br/>
                        Please follow this link to access the system.<br/>
                        <?php echo $activation_link; ?>
                    </div>
                    <br>
                </span>
            </td>
            <td width="30" bgcolor="#fff">&nbsp;</td>
            <td width="1" bgcolor="#fff"></td>
        </tr>
    </tbody>
</table>