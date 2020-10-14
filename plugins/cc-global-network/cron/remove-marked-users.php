<?php

defined('ABSPATH') or die('No script kiddies please!');

/*
  This script checks for accounts who needs to be removed according to the new rejection policy
  - Applications which were not completed/submitted
  - Applications which failed to update their vouchers
  - Applications which were rejected by the Membership Committee (30 days after the rejection was sent)

  This code may close an application!
 */

////////////////////////////////////////////////////////////////////////////////
// Defines
////////////////////////////////////////////////////////////////////////////////

// Be careful changing this value, you may send a reminder sooner than expected
define('CCGN_REMOVE_APPLICATION_AFTER_DAYS', 30);


////////////////////////////////////////////////////////////////////////////////
// Checking and sending
////////////////////////////////////////////////////////////////////////////////

// remove accounts after retention period

function ccgn_close_and_remove_retention_data_applicant($applicant_id)
{
    _ccgn_application_delete_entries_created_by($applicant_id);
    delete_user_meta($applicant_id, CCGN_APPLICATION_TYPE);
    delete_user_meta($applicant_id, CCGN_APPLICATION_STATE);
    delete_user_meta($applicant_id, CCGN_USER_IS_AUTOVOUCHED);

    $delete = wp_delete_user($applicant_id);
}


function ccgn_check_accounts_to_be_removed()
{
    $states_to_delete = array(
      CCGN_APPLICATION_STATE_DELETE,
      CCGN_APPLICATION_STATE_DIDNT_UPDATE_VOUCHERS,
      CCGN_APPLICATION_STATE_CHARTER,
      CCGN_APPLICATION_STATE_DETAILS,
      CCGN_APPLICATION_STATE_LEGAL
    );
    $now = new DateTime('now');
    foreach ($states_to_delete as $state) {
      $applicants = ccgn_applicant_ids_with_state( $state );
      foreach ($applicants as $applicant_id) {
        if ( $state == CCGN_APPLICATION_STATE_LEGAL) {
          $stage = get_user_meta($applicant_id, 'ccgn-institutional-agreement', true);
          if (!empty($stage) && ($stage['status'] == 'sent-agreement')) {
            $agreement_date = new DateTime($stage['date']);
            $days_in_state = $agreement_date->diff($now)->days;
            if ($days_in_state > CCGN_REMOVE_APPLICATION_AFTER_DAYS) {
              ccgn_close_and_remove_retention_data_applicant($applicant_id);
            } 
          }
        } else {
          $status_date = get_user_meta($applicant_id, 'ccgn-application-state-date', true);
          $state_date = new DateTime($status_date);
          $days_in_state = $state_date->diff($now)->days;
          if ($days_in_state > CCGN_REMOVE_APPLICATION_AFTER_DAYS) {
            ccgn_close_and_remove_retention_data_applicant($applicant_id);
          } 
        }
      }
    }
}

function ccgn_schedule_add_retention_data()
{
    if (!wp_next_scheduled('ccgn_check_accounts_to_be_removed')) {
        wp_schedule_event(
            time(),
            'daily',
            'ccgn_check_accounts_to_be_removed'
        );
    }
}

function ccgn_schedule_remove_retention_data()
{
    wp_clear_scheduled_hook('ccgn_check_accounts_to_be_removed');
}
