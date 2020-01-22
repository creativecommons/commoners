jQuery(document).ready(function($){
    
    var Country_data = [];
    var info_box = $('#chapters-map-header');
    var country_name = $('#country-name');
    var writeInfoBox = function (value) {
        info_box.find('.chapter-title').html(value.name);
        info_box.find('.chapter-date').html(value.date);
        info_box.find('.chapter-lead-name').html(value.chapter_lead);
        info_box.find('.button.more').attr('href',value.link);
    }
    
    var windowPosition = function(event) {
        var topY = event.pageY + 10,
            leftX = event.pageX + 10,
            positionObj = { 'top': topY, 'left': leftX }

        country_name.css(positionObj);
    }
    $.post(Ajax.url, { action: 'event-get-chapters'}, function(data){
        Country_data = data;
        $.each(data,function(index, value){
            
            $('#cc_worldmap').find('#'+value.country_code).addClass('active');
            $('#cc_worldmap').find('#'+value.country_code).on('click',function(e){
                writeInfoBox(value);
                info_box.show();
            });
            $('#cc_worldmap').find('#'+value.country_code).hover(function(e){
                var object = $(this);
                country_name.find('.chapter-title').html(value.name);
                country_name.show();
                object.on('mousemove', function(e){
                    windowPosition(e);
                });            
            }, function(e){
                country_name.hide();
            });
        });
    });
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
    var panZoomMap = svgPanZoom('#cc_worldmap', {
         zoomEnabled: true,
          controlIconsEnabled: true,
          fit: true,
          dblClickZoomEnabled: true,
          mouseWheelZoomEnabled: false,
          center: true,
          viewportSelector: '#cc_worldmap_group'
     });
});