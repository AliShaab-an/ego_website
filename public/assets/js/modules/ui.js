const UI = {
  init() {
    this.initLoginPopup();
    this.initSidebar();
    this.initFilterSidebar();
    this.initAccordions();
  },

  initLoginPopup() {
    const overlay = document.getElementById("loginOverlay");
    const signupOverlay = document.getElementById("signupOverlay");

    const openBtns = document.querySelectorAll("#openLogin, #openLoginPhone");
    const closeLogin = document.getElementById("closeLogin");
    const closeSignup = document.getElementById("closeSignup");
    const signInRedirect = document.getElementById("signInRedirect");
    const loginRedirect = document.getElementById("loginRedirect");

    openBtns.forEach((btn) =>
      btn?.addEventListener("click", () => {
        document.body.style.overflow = "hidden";
        overlay?.classList.remove("hidden");
      })
    );

    closeLogin?.addEventListener("click", () => {
      overlay?.classList.add("hidden");
      document.body.style.overflow = "";
    });

    signInRedirect?.addEventListener("click", (e) => {
      e.preventDefault();
      overlay?.classList.add("hidden");
      signupOverlay?.classList.remove("hidden");
    });

    loginRedirect?.addEventListener("click", (e) => {
      e.preventDefault();
      signupOverlay?.classList.add("hidden");
      overlay?.classList.remove("hidden");
    });

    closeSignup?.addEventListener("click", () => {
      signupOverlay?.classList.add("hidden");
      document.body.style.overflow = "";
    });
  },

  initSidebar() {
    const sidebar = document.getElementById("mobileSidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const openMenu = document.getElementById("openSidebar");
    const closeMenu = document.getElementById("closeSidebar");

    openMenu?.addEventListener("click", () => {
      sidebar?.classList.remove("-translate-x-full");
      overlay?.classList.remove("hidden");
    });

    [closeMenu, overlay].forEach((el) =>
      el?.addEventListener("click", () => {
        sidebar?.classList.add("-translate-x-full");
        overlay?.classList.add("hidden");
      })
    );
  },

  initFilterSidebar() {
    const sidebar = document.getElementById("filterSidebar");
    const openBtn = document.getElementById("openFilter");
    const closeBtn = document.getElementById("closeFilter");

    openBtn?.addEventListener("click", () => {
      sidebar?.classList.remove("-translate-x-full");
      document.body.classList.add("overflow-hidden");
    });

    closeBtn?.addEventListener("click", () => {
      sidebar?.classList.add("-translate-x-full");
      document.body.classList.remove("overflow-hidden");
    });
  },

  initAccordions() {
    $(".accordion-btn").click(function () {
      $(this).next(".accordion-content").slideToggle();
      $(this).find("i").toggleClass("rotate-90");
    });
  },

  initCarousels() {
    document.querySelectorAll("[data-carousel]").forEach((carousel) => {
      const track = carousel.querySelector("[data-track]");
      const dotsContainer = carousel.querySelector("[data-dots]");
      const slides = Array.from(track.children);
      if (slides.length === 0) return;

      const slideWidth = slides[0].offsetWidth + 20; // includes gap
      let currentIndex = 0;
      let autoPlayInterval = null;

      /* === 1. Generate dots (mobile only) === */
      if (dotsContainer) {
        dotsContainer.innerHTML = ""; // clear
        slides.forEach((_, i) => {
          const dot = document.createElement("div");
          dot.className =
            "w-2.5 h-2.5 rounded-full bg-gray-400 opacity-50 transition";
          if (i === 0) dot.classList.add("bg-brand", "opacity-100");
          dotsContainer.appendChild(dot);
        });
      }

      const updateDots = (index) => {
        if (!dotsContainer) return;
        const dots = dotsContainer.querySelectorAll("div");
        dots.forEach((dot, i) => {
          dot.className =
            "w-2.5 h-2.5 rounded-full transition " +
            (i === index ? "bg-brand opacity-100" : "bg-gray-400 opacity-50");
        });
      };

      /* === 2. Smooth looping autoplay === */
      const autoScroll = () => {
        currentIndex = (currentIndex + 1) % slides.length; // loop
        const scrollPosition = slideWidth * currentIndex;
        track.scrollTo({ left: scrollPosition, behavior: "smooth" });
        updateDots(currentIndex);
      };

      const startAutoPlay = () => {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(autoScroll, 2500);
      };
      const stopAutoPlay = () => {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
      };

      /* === 3. Pause on hover (desktop only) === */
      track.addEventListener("mouseenter", stopAutoPlay);
      track.addEventListener("mouseleave", startAutoPlay);

      /* === 4. Touch/drag support === */
      let isDragging = false;
      let startX = 0;
      let scrollLeft = 0;

      track.addEventListener("mousedown", (e) => {
        isDragging = true;
        startX = e.pageX - track.offsetLeft;
        scrollLeft = track.scrollLeft;
        track.classList.add("cursor-grabbing");
        stopAutoPlay();
      });

      track.addEventListener("mouseleave", () => {
        isDragging = false;
        track.classList.remove("cursor-grabbing");
      });

      track.addEventListener("mouseup", () => {
        isDragging = false;
        track.classList.remove("cursor-grabbing");
        startAutoPlay();
      });

      track.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX) * 1.5;
        track.scrollLeft = scrollLeft - walk;
      });

      /* === 5. Start everything === */
      updateDots(0);
      startAutoPlay();
    });
  },
};

export default UI;
