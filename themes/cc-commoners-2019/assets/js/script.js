jQuery(document).ready(function($){
    $(document).foundation();
    if ( $('.widget.user-status').length ) {
        $('#user-status-panel').foundation('open');
    }
    $('.entry-gallery').slick({

    });

    $('.entry-display').find('.entry-title').on('click', function(e){
        e.preventDefault();
        var object = $(this),
            target = $(object.attr('href'));
        target.toggleClass('closed');
        object.toggleClass('active');
        return false;
    });
    $('.big-select').find('.selector').on('click', function(e){
        e.preventDefault();
        var object = $(this),
            target = $(object.attr('href'));
        target.toggleClass('closed');
        object.toggleClass('active');
        return false;
    });
});