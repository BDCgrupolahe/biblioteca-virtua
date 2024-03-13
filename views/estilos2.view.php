<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
<link rel="stylesheet" href="./abajo.css">
<script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js'></script>

<link href="<?= constant('URL') ?>public/css/abajo.css" rel="stylesheet" />
<script>
    /*
inspiration
https://cz.pinterest.com/pin/830703093790696716/
*/

    var swiper = new Swiper(".swiper", {
        effect: "coverflow",
        grabCursor: true,
        spaceBetween: 30,
        centeredSlides: false,
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 0,
            modifier: 1,
            slideShadows: false
        },
        loop: true,
        // pagination: {
        //     el: ".swiper-pagination",
        //     clickable: true
        // },
        keyboard: {
            enabled: true
        },
        mousewheel: {
            thresholdDelta: 70
        },
        breakpoints: {
            460: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 3
            },
            1024: {
                slidesPerView: 3
            },
            1600: {
                slidesPerView: 3.6
            }
        }
    });
</script>