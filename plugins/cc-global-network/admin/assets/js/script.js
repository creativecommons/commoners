////////////////////////////////////
// CCGN ADMIN SCRIPTS
////////////////////////////////////
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        var member_type = $('#member_type').val(),
            column_member_type = data[2],
            date_start = $('#date-start').val(),
            date_end = $('#date-end').val(),
            column_date = data[7];
        if ((member_type != '') && ((settings.sTableId == 'ccgn-table-applications-approval') || (settings.sTableId == 'ccgn-list-new-individuals')  ) ) {
            if (member_type == column_member_type) {
                return true;
            } else {
                return false;
            }
        } else if (((date_start != '') || (date_end != '')) && (settings.sTableId == 'ccgn-list-new-individuals' )) {
            var target_date = new Date(column_date),
                from_date = ( date_start != '' ) ? new Date(date_start) : new Date(wpApiSettings.site_epoch),
                to_date = ( date_end != '' ) ? new Date(date_end) : Date.now();
                if ( (target_date >= from_date) && (target_date <= to_date ) ) {
                    return true;
                } else {
                    return false;
                }
        } 
        else {
            return true;
        }
    }
);
function format(d) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" class="detail-table" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
            '<td><strong>Vouchers declined</strong></td>' +
            '<td class="data-left">' + d.vouches_declined + '</td>' +

            '<td class="data-right"><strong>Votes for</strong></td>' +
            '<td>' + d.votes_for + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td><strong>Vouchers for</strong></td>' +
            '<td class="data-left">' + d.vouches_for + '</td>' +

            '<td class="data-right"><strong>Votes against</strong></td>' +
            '<td>' + d.votes_against + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td><strong>Vouchers against</strong></td>' +
            '<td class="data-left">'+ d.vouches_against +'</td>' +
        '</tr>' +
        '</table>';
}

jQuery(document).ready(function($){
    $.resetVouchers = function(id) {
        $('#reset-vouchers-for-sure').off('click');
        tb_show("Reset Vouchers", "#TB_inline?width=600&height=250&inlineId=resetvouchers-modal");
        $('#close-reset-vouchers').on('click', function(e){
            e.preventDefault();
            tb_remove();
            return false;
        });
        $('#reset-vouchers-for-sure').on('click', function(e){
            var sec = $('#reset_vouchers_nonce').val(),
                this_button = $(this);
            $.ajax({
                url: wpApiSettings.ajax_url,
                type: 'POST',
                data: {
                    action: 'reset_vouchers',
                    user_id: id,
                    sec: sec
                },
                beforeSend: function () {
                    this_button.text('Working...');
                },
                success: function (data) {
                    this_button.text("Yes, I'm sure");
                    $('#alert-messages').html('');
                    if (data == 'ok') {
                        tb_remove();
                        $('#alert-messages').append('<div class="updated notice is-dismissible"><p>Vouching state restored</div></p>').find('.notice').delay(3200).fadeOut(300);
                        $('#search-all-members').trigger('click');
                    }
                    if (data == 'error') {
                        $('#alert-messages').append('<div class="updated notice is-dismissible"><p>There was an error restoring the vouching state</div></p>').find('.notice').delay(3200).fadeOut(300);
                        tb_remove();
                    }
                }
            });
        });
    }
    var table1 = $('#ccgn-table-applications-approval').DataTable({
       'columns': [
            {
               "className": 'details-control',
               "orderable": false,
               "data": null,
               "defaultContent": '<span class="dashicons dashicons-arrow-down-alt2"></span>'
            },
            { 'data': 'applicant' },
            { 'data': 'applicant_type' },
            { 'data': 'user_mail' },
            { 'data': 'vouching_status' },
            { 'data': 'voting_status' },
            { 'data': 'application_date' }
       ],
       'columnDefs': [
           {
               targets: 1,
               'render': function (data, type, row, meta) {
                   return '<a href="' + row.applicant_url + '">' + data+'</a>';
               }
           }
       ],
       'ajax': {
           'url': wpApiSettings.root + 'commoners/v2/application-approval/list',
           'type': 'POST',
           'beforeSend': function (xhr) {
               xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
           },
           'data': { 'current_user': wpApiSettings.current_user }
       },
       rowCallback: function(row, data) {
           if (data.already_voted_by_me == 'yes') {
               $(row).addClass('green-mark');
           }
       }
   });
    var table_members = $('#ccgn-list-new-individuals').DataTable({
        'columns': [
            { 'data': 'display_name' },
            { 'data': 'user_email' },
            { 'data': 'user_type' },
            { 'data': 'location' },
            { 'data': 'location_chapter' },
            { 'data': 'member_interests' },
            { 'data': 'member_vouchers' },
            { 'data': 'member_approval_date' }
        ],
        'ajax': {
            'url': wpApiSettings.root + 'commoners/v2/list-members',
            'type': 'POST',
            'beforeSend': function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
            'data': { 
                'current_user': wpApiSettings.current_user,
                'start_date': wpApiSettings.site_epoch,
                'end_date': wpApiSettings.date_now
            }
        },
        rowCallback: function (row, data) {
            if (data.user_type == 'Institution') {
                $(row).addClass('red-mark');
            }
        }
    });
    var table_mc_voting = $('#ccgn-list-mc-voting').DataTable({
        'columns': [
            { 'data': 'user_name' },
            { 'data': 'user_email' },
            { 'data': 'voting_yes' },
            { 'data': 'voting_no' },
        ],
        'ajax': {
            'url': wpApiSettings.root + 'commoners/v2/mc-voting/list',
            'type': 'POST',
            'beforeSend': function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
            'data': { 'current_user': wpApiSettings.current_user }
        }
    });
    var table_members_by_country = $('#ccgn-members-by-country').DataTable({
        'columns': [
            { 'data': 'country' },
            { 'data': 'country_count' }
        ],
        'ajax': {
            'url': wpApiSettings.root + 'commoners/v2/list-members/by-country',
            'type': 'POST',
            'beforeSend': function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
            'data': { 'current_user': wpApiSettings.current_user }
        }
    });
    var table_search_users = $('#ccgn-search-users').DataTable({
        'columns': [
            { 'data': 'ID' },
            { 'data': 'user_name' },
            { 'data': 'user_mail' },
            { 'data': 'user_roles' },
            { 'data': 'user_register_date' },
            { 'data': 'user_application_status' },
            { 'data': 'user_status_update' },
            {
                "className": 'user_actions',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            }
        ],
        'columnDefs': [
            {
                'targets': 7,
                'render' : function(data, type, row, meta) {
                    var output = '';
                    if (wpApiSettings.is_sub_admin != 'yes') {
                        output += '<span class="inline-buttons">';
                            output += '<a href="?page=global-network-application-change-vouchers&user_id=' + data.ID + '" target="_blank" class="button button-icon change_vouchers" data-user-id="' + data.ID + '" title="Change Vouchers"><span class="dashicons dashicons-universal-access-alt"></span></a>'; 
                            output += '<button class="button button-icon reset_vouchers" onClick="$.resetVouchers(' + data.ID + ')" data-user-id="' + data.ID + '" title="Reset Vouchers selection"><span class="dashicons dashicons-image-rotate"></span></button>'; 
                        output += '</span>';
                    } else {
                        output += 'No actions';
                    }
                    return output;
                }
            }
        ] 
    });
    $('#search-all-members').on('click', function(e){
        e.preventDefault;
        var obj = $(this);
        $.ajax({
            url: wpApiSettings.root + 'commoners/v2/list-members/by-id',
            'type': 'POST',
            'beforeSend': function (xhr) {
                obj.text('Loading...');
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
            'data': { 
                'current_user': wpApiSettings.current_user,
                'search_user': $('#user_id').val()
            },
            success: function(data) {
                table_search_users.clear();
                table_search_users.rows.add(data);
                table_search_users.draw();
                obj.text('Search Member');
            }
        });
        return false;
    });
    $('.ccgn-approval-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table1.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });
    $('#member_type').on('change',function () {
        table1.draw();
        table_members.draw();
    });
    $('.ui-datepicker-input').on('change', function () {
        table_members.draw();
    });
    $('.email-list').on('click', function(e){
        $('#emails-modal').find('p').html(
            table_members
                .columns(1, { search: 'applied' })
                .data()
                .eq(0)      // Reduce the 2D array into a 1D array of data
                .sort()       // Sort data alphabetically
                .unique()     // Reduce to unique values
                .join(', ')
        );
    });
    $('.ui-datepicker-input').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    
});