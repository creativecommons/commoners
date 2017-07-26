jQuery(document).ready( function($) {
    // close postboxes that should be closed
    $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
    // postboxes setup
    postboxes.add_postbox_toggles('<?php echo $this->settings_hook ?>');
    $('#cas_version_inp').select2();
    $('.to_select_2').select2({placeholder: casmaestro.choose_role});
    $('#ldap_proto').select2();

    $('input[name=e-mail_registration]').change(function () {
        if ($(this).val() == 3)
            $('#ldap_container').slideDown();
        else
            $('#ldap_container').slideUp();
    });

    if($('input[name=e-mail_registration]:checked').val() == 3)
        $('#ldap_container').show();
    

    $('#welcome_mail_tab').click(function () {

        $(this).addClass('active');
        $('#wait_for_access_tab').removeClass('active');

        $('#welcome_mail').show();
        $('#wait_for_access').hide();

        return false;
    }).addClass('active').find('a').click(function () {$('#welcome_mail_tab').click(); return false;});

    $('#wait_for_access_tab').click(function () {

        $('#welcome_mail_tab').removeClass('active');
        $(this).addClass('active');

        $('#welcome_mail').hide();
        $('#wait_for_access').show();

        return false;
    }).find('a').click(function () {$('#wait_for_access_tab').click();return false;});
});