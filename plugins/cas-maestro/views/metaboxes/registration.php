<?php 
$raw_roles = get_editable_roles();

//Get number of allowed users
$num_allowed = count($this->allowed_users)+1;

foreach($raw_roles as $role => $value) {
  $roles[] = $role;
  $roles_name[] = __($value['name']);
}
?>
  <script type="text/javascript">
    var roles = <?php echo json_encode($roles)?>;
    var roles_name = <?php echo json_encode($roles_name)?>;
    var CurrentTextboxes = <?php echo $num_allowed?>;
  </script>

  <h2><?php _e("User registration", 'CAS_Maestro'); ?></h2>
  <div>
 <?php if(current_user_can('manage_options')): ?>
    <div class="main_content">
      <div>
        <p><?php _e("In order to register a new user an email address is required. In case you don’t want to set the email address manually, please chose from the options below.", 'CAS_Maestro'); ?></p>
        <?php if($this->settings['e-mail_registration'] < 1 || $this->settings['e-mail_registration'] > 3 ) $this->settings['e-mail_registration'] = 1; ?>
        <input id="no-e-mail" type="radio" name="e-mail_registration" value="1" <?php echo ($this->settings['e-mail_registration'] == '1')?'checked':''; ?>><label for="no-e-mail"><?php _e("No mail creation", 'CAS_Maestro'); ?></label><br />
        <input id="e-mail-suffix" type="radio" name="e-mail_registration" value="2" <?php echo ($this->settings['e-mail_registration'] == '2')?'checked':''; ?>><label for="e-mail-suffix"><?php _e("E-mail suffix username@", 'CAS_Maestro'); ?></label><input type="text" name="email_suffix" id="email_suffix_inp" value="<?php echo $this->settings['email_suffix']; ?>" placeholder="<?php echo parse_url(site_url(),PHP_URL_HOST)?>" size="15" /><br />
        <input id="ldap-e-mail" type="radio" name="e-mail_registration" value="3" <?php echo ($this->settings['e-mail_registration'] == '3')?'checked':''; ?>><label for="ldap-e-mail"><?php _e("LDAP server connection", 'CAS_Maestro'); ?></label>
      </div>
      <div id="ldap_container">
        <p><?php _e("You should finish this configuration with LDAP server data. For anonymous server access, which could not be enough, leave the fields “RDN User” and “Password” blank.", 'CAS_Maestro'); ?></p>

        <table width="700px" cellspacing="2" cellpadding="5" class="editform">
            <tr valign="center">
              <th width="150px" scope="row">Protocol version</th>
              <td>
                <select name="ldap_protocol" id="ldap_proto" style="width: 75px">
                  <option value="3" <?php echo ($this->settings['ldap_protocol'] == '3')?'selected':''; ?>>3</option>
                  <option value="2" <?php echo ($this->settings['ldap_protocol'] == '2')?'selected':''; ?>>2</option>
                  <option value="1" <?php echo ($this->settings['ldap_protocol'] == '1')?'selected':''; ?>>1</option>
                </select>
              </td>
            </tr>
            <tr valign="center">
              <th width="150px" scope="row"><?php _e("Server hostname", 'CAS_Maestro'); ?>* <br /><span><?php echo sprintf(__("(with %s or %s)", 'CAS_Maestro'), 'ldap://', 'ldaps://'); ?></span></th> 
              <td><input type="text" <?php check_empty($this->settings['ldap_server'])?>  name="ldap_server" id="ldap_server" value="<?php echo $this->settings['ldap_server']; ?>" size="35" /></td>
            </tr>
            <tr valign="center">
              <th width="150px" scope="row"> <?php _e("Username <abbr title='(Relative Distinguished Name)'>RDN</abbr>", 'CAS_Maestro');?></th>
              <td><input type="text" name="ldap_username_rdn" id="ldap_user" value="<?php echo $this->settings['ldap_username_rdn']; ?>" size="35" /></td>
            </tr>
            <tr valign="center"> 
              <th width="150px" scope="row"><?php _e("Password", 'CAS_Maestro'); ?></th>
              <td><input type="text" name="ldap_password" id="ldap_pass" value="<?php echo $this->settings['ldap_password']; ?>" size="35" /></td>
            </tr>
            <tr valign="center">
              <th width="150px" scope="row"><?php _e("Base DN", 'CAS_Maestro'); ?>*</th>
              <td><input type="text" <?php check_empty($this->settings['ldap_basedn'])?> name="ldap_basedn" id="ldap_bdn" value="<?php echo $this->settings['ldap_basedn']; ?>" size="35" /></td>
            </tr>
          </table>
          <div class='availability_result' id='ldap_availability_result'></div>
      </div>
    </div>
<?php endif; ?>
    <div <?php echo (current_user_can('manage_options') ? 'class="sidebar"' : '') ?>>
      <p><?php _e("The option to register all users, allows all users to be added to the system with subscriber profile, with no exception.", 'CAS_Maestro'); ?></p>
      <p><input name="new_user" type="checkbox" id="new_user_inp" value="1" <?php checked('1', $this->settings['new_user']); ?> /><label for="new_user_inp"><?php _e("Register all users?", 'CAS_Maestro'); ?></label></p>
    </div>
  </div>
  <div style="clear:both"></div>

  <h2><?php _e("Users Allowed to Register", 'CAS_Maestro'); ?></h2> 
  <p><?php _e("If you indentify users with the ability to register on the system upfront without confirmation you can also set their names and profile.", 'CAS_Maestro'); ?></p>
  <div>
     <table id="autoAdd">
        <tbody>
          <?php
          $i=1;
          foreach($this->allowed_users as $username => $curr_role) {
          $roles = array();
          $select_options="<option></option>";
          $roles_name = array();
            foreach($raw_roles as $role => $value) {
              $roles[] = $role;
              $roles_name[] = __($value['name']);
              $selected ='';
              if($curr_role == $role)
                $selected = 'selected';
              $select_options .= "<option value='$role' $selected>" . __($value['name']) . "</option>\n";
            }
          ?>
            <tr>
            <td class="prefix">
              <input type="text" class="istid" id="txt<?php echo $i?>" name="username[<?php echo $i?>]" value="<?php echo $username?>" style="width: 150px;"></input>
            </td>
            <td>
              <select class="to_select_2" name="role[<?php echo $i?>]" style="width: 180px;">
                <?php echo $select_options?>
              </select>
            </td>
          </tr>
          <?php $i++; } ?>
          <tr>
            <td class="prefix"><input class="istid" type="text" id="txt<?php echo $i?>" name="username[<?php echo $i?>]" style="width: 150px;"></input></td>
            <td>
              <select class="to_select_2" name="role[<?php echo $i?>]" style="width: 180px;">
             <?php   
             $select_options="<option></option>";
             foreach($raw_roles as $role => $value) {
              $roles[] = $role;
              $roles_name[] = __($value['name']);
              $selected ='';
              $select_options .= "<option value='$role'>".__($value['name'])."</option>\n";
              } ?>

              <?php echo $select_options?>
              </select>       
            </td>
          </tr>
        </tbody>
       </table>
       <p class="grey_text"><?php _e("To add another user, just fill the last blank element.", 'CAS_Maestro'); ?></p>
    </div>

    <div class="submit">
        <input type="submit" name="submit" class="button-primary" value="<?php _e('Update options') ?>" />
    </div>
    <div style="clear: both;"></div>
