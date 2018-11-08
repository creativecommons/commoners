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

});