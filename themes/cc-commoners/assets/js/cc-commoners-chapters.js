jQuery(document).ready(function($){
    
    var Country_data = [];
    var info_box = $('#world-map-info');
    var writeInfoBox = function (value) {
        info_box.find('.chapter-title').html(value.name);
        info_box.find('.chapter-date').html(value.date);
        info_box.find('.chapter-lead-name').html(value.chapter_lead);
        info_box.find('.button.contact').attr('href','mailto:'+value.email);
        info_box.find('.button.url').attr('href', value.url);
        info_box.find('.button.meet').attr('href', value.meeting_url);
    }
    var windowPosition = function(pointX, pointY) {
        
        var topY = (pointY < 630) ? pointY + 10 : 630 - info_box.height();
            if ($(window).width <= 1280) {
                leftX = (pointX < 1040) ? pointX + 10 : 1040 - info_box.width();
            } else {
                var additionalLeft = (($(window).width() - 1200) / 2),
                    newRightLimit = additionalLeft + 1040 - (info_box.width() / 2),
                    atLimit = ((pointX + info_box.width()) >= $(window).width());
                    
                leftX = (pointX < newRightLimit) ? pointX + 10 : newRightLimit - info_box.width();
            }
            
        info_box.css({
            'top': topY,
            'left': leftX
        });
    }
    $.post(Ajax.url, { action: 'event-get-chapters'}, function(data){
        Country_data = data;
        $.each(data,function(index, value){
            
            $('#cc_worldmap').find('#'+value.country_code).addClass('active');
            $('#cc_worldmap').find('#'+value.country_code).hover(function(e){
                windowPosition(e.pageX, e.pageY);
                writeInfoBox(value);
                info_box.show();
            }, function(e){
                // info_box.hide();
            });
            info_box.on('mouseleave', function(e) {
                info_box.hide();
            });
            $('#cc_worldmap').find('#' + value.country_code).on('mousemove',function(e){
                windowPosition(e.pageX, e.pageY);
            });
        });
    });
    var chapter_table = $('#chapters-table').DataTable({
        "lengthChange": false
    });
    $('.buttons').find('.button').on('click', function(e){
        e.preventDefault();
        var object = $(this),
            target = object.attr('href');
            $('.buttons').find('.button.active').removeClass('active');
            object.addClass('active');
            $('.view-content.active').removeClass('active');
            $(target).addClass('active');
        return false;
    });
});