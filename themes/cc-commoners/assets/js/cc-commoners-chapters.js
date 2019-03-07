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
    
    var windowPosition = function(target) {
        console.log(target);
        var position = target.offset(),
            boundingBox = target[0].getBBox(),
            topY = position.top + boundingBox.height - 30,
            leftX = position.left + boundingBox.width - 30,
            mapOffset = $('#cc_worldmap').offset();
            
        if ( leftX + info_box.width() > $(document).width() ) {
            leftX = leftX - info_box.width();
        }
        if ( topY + info_box.height() > mapOffset.top + $('#cc_worldmap').height()) {
            topY = topY - info_box.height();
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
                var object = $(this);
                windowPosition(object);
                writeInfoBox(value);
                info_box.show();
            }, function(e){
                // info_box.hide();
            });
            info_box.on('mouseleave', function(e) {
                info_box.hide();
            });
            // $('#cc_worldmap').find('#' + value.country_code).on('mousemove',function(e){
            //     var object = $(this),   
            //     windowPosition(object);
            // });
        });
    });
    // $(document).on('mousemove', function(e) {
    //     console.log('MOUSE X: '+e.pageX, 'MOUSE Y: '+e.pageY);
    // });
    var chapter_table = $('#chapters-table').DataTable({
        "lengthChange": false,
        "responsive" : true
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