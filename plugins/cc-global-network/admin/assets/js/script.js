////////////////////////////////////
// CCGN ADMIN SCRIPTS
////////////////////////////////////
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        var member_type = $('#member_type').val();
        var column_member_type = data[2]; // use data for the age column
        if (member_type != '') {
            if (member_type == column_member_type) {
                return true;
            } else {
                return false;
            }
        } else {
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
            '<td class="data-right"> <select class="user_select">'+
                '<option value="">Select new state</option>'+
                '<option value="vouched">Charter form</option>' +
                '<option value="vouched">Detail Forms</option>' +
                '<option value="vouched">Received</option>' +
                '<option value="vouched">Vouching</option>' +
                '<option value="vouched">Legal</option>' +
                '<option value="vouched">Update Vouchers</option>'+
                '<option value="vouched">Update details</option>' +
                '<option value="vouched">Rejected</option>' +
                '<option value="vouched">Accepted</option>' +
                '<option value="vouched">On hold</option>' +
            '</select> <button class="button button-primary">Reset user state</button></td>'+
        '</tr>' +
        '<tr>' +
            '<td><strong>Vouchers for</strong></td>' +
            '<td class="data-left">' + d.vouches_for + '</td>' +

            '<td class="data-right"><strong>Votes against</strong></td>' +
            '<td>' + d.votes_for + '</td>' +
        '</tr>' +
        '<tr>' +
            '<td><strong>Vouchers against</strong></td>' +
            '<td class="data-left">'+ d.vouches_against +'</td>' +
        '</tr>' +
        '</table>';
}
jQuery(document).ready(function($){
   var table1 = $('.ccgn-approval-table').DataTable({
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
       'ajax': {
           'url': wpApiSettings.root + 'commoners/v2/application-approval/list',
           'type': 'POST',
           'beforeSend': function (xhr) {
               xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
           },
           'data': { 'current_user': wpApiSettings.current_user }
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
            'data': { 'current_user': wpApiSettings.current_user }
        }
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
    
});