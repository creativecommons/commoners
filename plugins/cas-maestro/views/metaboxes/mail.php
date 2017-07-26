<?php $settings = $this->settings; ?>
<h2><?php _e("Sender", 'CAS_Maestro'); ?></h2>
<table width="700px" cellspacing="2" cellpadding="5" class="editform">
       <tr>
         <td colspan="2">
          <?php _e("All mails are sent from a unique sender. This sender may recieve notification emails alerting for new users and/or activations, estabilished by the following configuration.", 'CAS_Maestro'); ?>
       </tr>
       <tr valign="center"> 
        <th width="150px" scope="row"><?php _e("E-mail address:", 'CAS_Maestro'); ?>*</th> 
        <td><input type="text" placeholder="" name="global_sender" id="email_suffix_inp" value="<?php echo $settings['global_sender']; ?>" size="35" /></td>
      </tr>
       <tr valign="center"> 
        <th width="150px" scope="row"><?php _e("Full name:", 'CAS_Maestro'); ?></th> 
        <td><input type="text" placeholder="" name="full_name" id="full_name_suffix_inp" value="<?php echo $settings['full_name']; ?>" size="35" /></td>
      </tr>      
    </table>

    <h2><?php _e("Mails", 'CAS_Maestro'); ?></h2>
    <p><?php _e("Please set up on witch actions should emails be sent. It’s possible to send emails to the user and the sender, depending on your configuration.", 'CAS_Maestro'); ?></p>

    <div class="mail_tabs">
      <ul>
        <li id="welcome_mail_tab"><a href="#"><?php _e("Welcoming", 'CAS_Maestro'); ?></a></li>
        <li id="wait_for_access_tab"><a href="#"><?php _e("Wait for access", 'CAS_Maestro'); ?></a></li>
      </ul>
    </div>

    <div class="message_container">
      <div id="welcome_mail">
        <p><input name="welcome_send_user" type="checkbox" id="new_user_inp0" value="1" <?php checked('1', $this->settings['welcome_mail']['send_user']); ?> /><label for="new_user_inp0"><?php _e("Send to the User", 'CAS_Maestro'); ?></label><input name="welcome_send_global" type="checkbox" id="new_user_inp1" value="1" <?php checked('1', $this->settings['welcome_mail']['send_global']); ?> /><label for="new_user_inp1"><?php _e("Send to the Sender", 'CAS_Maestro'); ?></label></p>
        <h2><?php _e("Subject", 'CAS_Maestro'); ?></h2>
        <p><input type="text" name="welcome_subject" id="email_suffix_inp" value="<?php echo $settings['welcome_mail']['subject']; ?>" size="35" placeholder="Type subject"/></p>
        <h2><?php _e("Body", 'CAS_Maestro'); ?></h2>
        <div class="mail_body">
          <div>
            <p><?php _e("Message body sent to the User", 'CAS_Maestro'); ?></p>
            <textarea type="text" name="welcome_user_body" id="user_email_suffix_inp"><?php echo $this->settings['welcome_mail']['user_body']?></textarea>          
          </div>
          <div>
            <p><?php _e("Message body sent to the Sender", 'CAS_Maestro'); ?></p>
            <textarea type="text" name="welcome_global_body" id="global_email_suffix_inp"><?php echo $this->settings['welcome_mail']['global_body']?></textarea>
          </div>
        </div>
      </div>
      
      <div id="wait_for_access">
        <p><input name="wait_send_user" type="checkbox" id="new_user_inp2" value="1" <?php checked('1', $this->settings['wait_mail']['send_user']); ?> /><label for="new_user_inp2"><?php _e("Send to the User", 'CAS_Maestro'); ?></label><input name="wait_send_global" type="checkbox" id="new_user_inp3" value="1" <?php checked('1', $this->settings['wait_mail']['send_global']); ?> /><label for="new_user_inp3"><?php _e("Send to the Sender", 'CAS_Maestro'); ?></label></p>
        <h2><?php _e("Subject", 'CAS_Maestro'); ?></h2>
        <p><input type="text" name="wait_subject" id="email_suffix_inp" value="<?php echo $settings['wait_mail']['subject']; ?>" size="35" placeholder="Type subject"/></p>
        <h2><?php _e("Body", 'CAS_Maestro'); ?></h2>
        <div class="mail_body">
          <div>
            <p><?php _e("Message body sent to the User", 'CAS_Maestro'); ?></p>
            <textarea type="text" name="wait_user_body" id="email_suffix_inp"><?php echo $this->settings['wait_mail']['user_body']?></textarea>
          </div>
          <div>
            <p><?php _e("Message body sent to the Sender", 'CAS_Maestro'); ?></p>
            <textarea type="text" name="wait_global_body" id="email_suffix_inp"><?php echo $this->settings['wait_mail']['global_body']?></textarea>
          </div>
        </div>
      </div>

      <p class="grey_text"><?php _e("You can use the following tokens: %sitename% for the website name, %username% for the user’s name and %realname% for the user’s real name.", 'CAS_Maestro'); ?></p>

      <div style="clear: both;"></div>
    </div>

    <div class="submit">
        <input type="submit" name="submit" class="button-primary" value="<?php _e('Update options') ?>" />
    </div>
    <div style="clear: both;"></div>