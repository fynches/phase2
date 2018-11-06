/* owl-carousel */
jQuery('.owl-carousel').owlCarousel({
    loop:true,
    margin:32,
    responsiveClass:true,
    stagePadding: 300,
    autoplay: true,
    autoplayTimeout: 5000,
    dots: true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        320:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 10
        },
        
        600:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 100
        },
        700:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 100
        },
        1024:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 190

        },
        1200:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 300

        },
        1900:{
            items:1,
            nav:true,
            dots: true,
            loop:true,
            stagePadding: 400

        }
    }
});
/* End */

    
/* Add/Remove class */
$('.input-desc input').focus(function () {
    $(this).closest(".input-desc").addClass('input-img');
}).blur(function () {
    $(this).closest("div.input-desc").removeClass('input-img');
});
/* End */

/* Sticky Header */
$(window).scroll(function(){
    if ($(this).scrollTop() > 150) {
       $('#myHeader').addClass('sticky');
    } else {
       $('#myHeader').removeClass('sticky');
    }
});
/* End */

