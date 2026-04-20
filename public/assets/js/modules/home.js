const Home = {
  init() {
    this.initCollectionsSwiper();
    this.initTopProductsSwiper();
  },

  initCollectionsSwiper() {
    new Swiper(".collectionsSwiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".collections-next",
        prevEl: ".collections-prev",
      },
      pagination: {
        el: ".collections-pagination",
        clickable: true,
        bulletClass: "swiper-pagination-bullet",
        bulletActiveClass: "swiper-pagination-bullet-active",
      },
      effect: "slide",
      speed: 600,
    });
  },

  initTopProductsSwiper() {
    new Swiper(".topProductsSwiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".topProducts-next",
        prevEl: ".topProducts-prev",
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        bulletClass: "swiper-pagination-bullet",
        bulletActiveClass: "swiper-pagination-bullet-active",
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
        1280: {
          slidesPerView: 5,
          spaceBetween: 30,
        },
      },
    });
  },
};

export default Home;
