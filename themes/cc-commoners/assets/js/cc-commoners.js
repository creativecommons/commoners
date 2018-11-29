var swiper = new Swiper('.swiper-container', {
        autoplay: 5000,
        speed: 600,
        loop: true,
        autoplayDisableOnInteraction: false
    });



(function( $ ) {

    $.fn.toggleClickV2 = function(){

        var functions = arguments ;
        //console.log(arguments);

        return this.click(function(){

            var iteration = $(this).data('iteration') || 0;

            //console.log(iteration);

            functions[iteration].apply(this, arguments);
            iteration = (iteration + 1) % functions.length;
            $(this).data('iteration', iteration);


            if(iteration === 1){
                //$(this).siblings().data('iteration', 0);

                $(this).parents(".item-acordion").siblings().find("h2").data('iteration', 0);

                //console.log(this);
            }else{
                //console.log(this);
            }

            //console.log(iteration);

        });
    };

}( jQuery ));


jQuery(document).ready(function($){


    $("body.page-template-page-faqs .item-acordion h2").toggleClickV2(function(e){

        e.preventDefault();

        $(this).next(".item-content").slideDown("normal");

        $(this).parents(".item-acordion").siblings().find(".item-content").slideUp("fast");

        $(this).addClass("titulo-activo");

        $(this).parents(".item-acordion").siblings().find("h2").removeClass("titulo-activo");

    }, function(e){

        e.preventDefault();

        $(this).next(".item-content").slideUp("normal");

        $(this).removeClass("titulo-activo");

    });
    $('.display-details').on('click', function (e) {
        e.preventDefault();
        var obj = $(this),
            target = obj.data('target');
        obj.toggleClass('opened');
        $(target).slideToggle('fast');
        return false;
    });
    $('#set-new-vouch-reason').on('click', function (e) {
        e.preventDefault();
        var obj = $(this),
            new_reason = $('#clarification_voucher').val(),
            entry_id = obj.data('entry-id'),
            applicant_id = obj.data('applicant-id'),
            sec = $('#clarification_voucher_nonce').val();
        $.ajax({
            url: Ajax.url,
            type: 'POST',
            data: {
                action: 'reason_voucher',
                entry_id: entry_id,
                new_reason: new_reason,
                applicant_id: applicant_id,
                sec: sec
            },
            beforeSend: function () {
                obj.text('Working...');
            },
            success: function (data) {
                obj.text("Set new reason");
                $('#change-voucher-messages').html('');
                if (data == 'ok') {
                    location.reload();
                }
                if (data == 'error') {
                    $('#change-voucher-messages').append('<div class="error notice is-dismissible"><p>There was an error sending your request</p></div>').find('.notice').delay(3200).fadeOut(300);
                }
            }
        });
        return false;
    });
    /*
        Avoiding selecting the same voucher in form
    */
    $('#input_41_1').on('change', function(e){
        var thisSelect = $(this),
            selectedValue = thisSelect.val(),
            otherSelectId = '#input_41_2',
            otherSelect = $(otherSelectId);

        $(otherSelectId+' option[disabled="disabled"]').removeAttr('disabled');    
        $(otherSelectId+' option[value="' + selectedValue + '"]').attr('disabled', 'disabled');
        otherSelect.trigger("chosen:updated");
    });
    $('#input_41_2').on('change', function (e) {
        var thisSelect = $(this),
            selectedValue = thisSelect.val(),
            otherSelectId = '#input_41_1',
            otherSelect = $(otherSelectId);

        $(otherSelectId + ' option[disabled="disabled"]').removeAttr('disabled');
        $(otherSelectId + ' option[value="' + selectedValue + '"]').attr('disabled', 'disabled');
        otherSelect.trigger("chosen:updated");
    });
});