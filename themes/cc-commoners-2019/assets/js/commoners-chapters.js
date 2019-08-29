jQuery(document).ready(function($){
    
    var Country_data = [];
    var info_box = $('#world-map-info');
    var writeInfoBox = function (value) {
        info_box.find('.chapter-title').html(value.name);
        info_box.find('.chapter-date').html(value.date);
        info_box.find('.chapter-lead-name').html(value.chapter_lead);
        info_box.find('.button.more').attr('href',value.link);
    }
    
    var windowPosition = function(target) {
        var position = target.offset(),
            boundingBox = target[0].getBBox(),
            topY = position.top + boundingBox.height,
            leftX = position.left + boundingBox.width,
            mapOffset = $('#cc_worldmap').offset(),
            mapWidth = $('#cc_worldmap').outerWidth();
            positionObj = { 'top': topY, 'left': leftX }

        if ( leftX + info_box.outerWidth() > $(window).width() ) {
            valueX = $(window).outerWidth() - info_box.outerWidth() - boundingBox.width;
            positionObj.left = valueX;
        }
        if ( topY + info_box.outerHeight() > mapOffset.top + $('#cc_worldmap').outerHeight()) {
            topY = topY - boundingBox.height - info_box.outerHeight();
            positionObj.top = topY;
        }

        info_box.css(positionObj);
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