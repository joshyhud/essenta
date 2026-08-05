jQuery(document).ready(function ($) {
  $(".hamburger").click(function () {
    $(".header-bottom-container").toggleClass("is-active");
    $("html, body").toggleClass("is-hidden");
  });
  $(".sub-menu-link").click(function () {
    $(this).toggleClass("is-active");
    $(this).next().toggleClass("is-active");
  });
});

jQuery(document).ready(function ($) {
  const menuToggle = $("#menu-toggle");
  const menuList = $("#mobile-menu-list");

  menuToggle.click(function () {
    menuList.toggleClass("active");
    menuToggle.toggleClass("activeToggle");
  });

  const menuItems = $(".desktop-menu li.menu-item-has-children");
  let activeSubmenu = null;

  menuItems.each(function () {
    const submenu = $(this).find(".sub-menu");

    // Show submenu when top-level item is hovered
    $(this).mouseenter(function () {
      if (activeSubmenu) {
        activeSubmenu.removeClass("open");
      }
      submenu.addClass("open");
      activeSubmenu = submenu;
    });
  });

  // Remove the active submenu class on scroll
  $(window).scroll(function () {
    if (activeSubmenu) {
      activeSubmenu.removeClass("open");
      activeSubmenu = null;
    }
  });

  // Optional: Remove the active submenu class when the mouse leaves the menu entirely
  $(".desktop-menu .sub-menu").mouseleave(function () {
    if (activeSubmenu) {
      activeSubmenu.removeClass("open");
      activeSubmenu = null;
    }
  });
});

jQuery(document).ready(function ($) {
  $(".dropdown-toggle").each(function () {
    $(this).click(function (event) {
      event.preventDefault();
      const expanded = $(this).attr("aria-expanded") === "true";
      $(this).attr("aria-expanded", !expanded);
      $(this).toggleClass("open");
      $(this).parent().toggleClass("open");
    });
  });

  // carousel slick slider
  $(".carousel-cards").slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    prevArrow: $(".slick-carousel .slick-prev"),
    nextArrow: $(".slick-carousel .slick-next"),
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  // Logo carousel slick slider
  jQuery(".logo-carousel").slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    speed: 5000,
    autoplaySpeed: 0,
    pauseOnHover: false,
    cssEase: "linear",
    arrows: false,
    dots: false,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  // tesimonials carousel slick slider
  $(".testimonial-slider").on("setPosition", function () {
    var slickTrack = $(this).find(".slick-track");
    var slickTrackHeight = $(slickTrack).height();
    $(this)
      .find(".slide")
      .css("height", slickTrackHeight + "px");
  });

  var testimonialSliderOne = $(".testimonial-slider-1");
  var testimonialSliderTwo = $(".testimonial-slider-2");
  var testimonialResizeTimer;

  function initTestimonialSliderOne() {
    if (!testimonialSliderOne.length) return;

    if (testimonialSliderOne.hasClass("slick-initialized")) {
      testimonialSliderOne.slick("unslick");
    }

    testimonialSliderOne.slick({
      slidesToShow: 2.5,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      autoplay: true,
      speed: 15000,
      autoplaySpeed: 0,
      cssEase: "linear",
      pauseOnHover: true,
      responsive: [
        {
          breakpoint: 980,
          settings: {
            slidesToShow: 1,
            autoplay: false,
            speed: 300,
          },
        },
      ],
    });
  }

  function initTestimonialSliderTwo() {
    if (!testimonialSliderTwo.length) return;

    if (testimonialSliderTwo.hasClass("slick-initialized")) {
      testimonialSliderTwo.slick("unslick");
    }

    testimonialSliderTwo.slick({
      slidesToShow: 2.5,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 15000,
      cssEase: "linear",
      rtl: true,
      pauseOnHover: true,
      responsive: [
        {
          breakpoint: 980,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          },
        },
      ],
    });
  }

  initTestimonialSliderOne();
  initTestimonialSliderTwo();

  let resizeTimer;

  $(window).on("resize", function () {
    clearTimeout(resizeTimer);

    resizeTimer = setTimeout(function () {
      initTestimonialSliderOne();
      initTestimonialSliderTwo();
    }, 250);
  });

  // Step section — scroll-driven accordion
  $(".step-section-wrapper").each(function (sectionIndex) {
    var $wrapper = $(this);
    var $section = $wrapper.find(".step-section");
    var $steps = $section.find(".step-section-step");
    var $images = $section.find(".step-section-image");
    var stepCount = $steps.length;
    var scrollPerStep = 400; // px of natural scroll to advance one step
    var ns = ".stepsection" + sectionIndex;

    if (stepCount < 1) return;

    function setActiveStep(index) {
      var i = Math.max(0, Math.min(index, stepCount - 1));
      $steps.each(function (n) {
        this.open = n === i;
      });
      $images.removeClass("is-active").attr("aria-hidden", "true");
      $images
        .filter('[data-step-index="' + i + '"]')
        .addClass("is-active")
        .attr("aria-hidden", "false");
    }

    function setImageByOpenStep() {
      var $openStep = $steps.filter("[open]").first();
      if (!$openStep.length) return;
      var openIndex = parseInt($openStep.data("step-index"), 10);
      if (Number.isNaN(openIndex)) return;

      $images.removeClass("is-active").attr("aria-hidden", "true");
      $images
        .filter('[data-step-index="' + openIndex + '"]')
        .addClass("is-active")
        .attr("aria-hidden", "false");
    }

    function updateWrapperHeight() {
      if ($(window).width() <= 980) {
        $wrapper.css("height", "");
        return;
      }
      // Extra height = one viewport for initial entry + one per step + one to exit
      $wrapper.css(
        "height",
        window.innerHeight + stepCount * scrollPerStep + "px",
      );
    }

    updateWrapperHeight();
    setImageByOpenStep();
    $(window).on("resize" + ns, updateWrapperHeight);

    // Keep image state in sync with whichever accordion item is currently open.
    $steps.on("toggle" + ns, function () {
      if (this.open) {
        setImageByOpenStep();
      }
    });

    $(window).on("scroll" + ns, function () {
      if ($(window).width() <= 980) return;
      var wrapperTop = $wrapper.offset().top;
      var scrolledIn = $(window).scrollTop() - wrapperTop;
      if (scrolledIn < 0) {
        setActiveStep(0);
        return;
      }
      setActiveStep(Math.floor(scrolledIn / scrollPerStep));
    });

    // Click on a step summary — scroll to the matching scroll position so the
    // scroll handler drives the state and normal scroll-through continues after.
    $steps.find("summary").on("click" + ns, function (e) {
      if ($(window).width() <= 980) return; // let mobile behave natively
      e.preventDefault();
      var targetIndex = parseInt(
        $(this).closest(".step-section-step").data("step-index"),
        10,
      );
      var targetScroll = $wrapper.offset().top + targetIndex * scrollPerStep;
      $("html, body").animate({ scrollTop: targetScroll }, 350);
    });
  });

  // Scrolling content — sticky header + sticky first slide layered stack.
  $(".scrolling-contents-wrapper").each(function (sectionIndex) {
    var $section = $(this).find(".scrolling-contents");
    var $header = $section.find(".scrolling-content-header").first();
    var ns = ".scrollingcontent" + sectionIndex;

    if (!$section.length || !$header.length) return;

    function syncHeaderHeightVar() {
      var headerHeight = $header.outerHeight() || 0;
      $section[0].style.setProperty(
        "--scrolling-header-height",
        headerHeight + "px",
      );
    }

    syncHeaderHeightVar();
    $(window).on("resize" + ns, syncHeaderHeightVar);
  });

  // Overlay cards section — keep cards over image until pinned, then animate.
  $(".overlay-cards-scroll-wrapper").each(function (sectionIndex) {
    var $wrapper = $(this);
    var $overlay = $wrapper.find(".cards-overlay").first();
    var $cards = $overlay.find(".card-overlay");
    var ns = ".overlaycards" + sectionIndex;

    if (!$overlay.length || !$cards.length) return;

    function resetMobileState() {
      $wrapper.css("height", "");
      $overlay[0].style.setProperty("--overlay-cards-progress", "0");
      $cards.addClass("is-in-view");
    }

    function updateLayout() {
      if ($(window).width() <= 980) {
        resetMobileState();
        return;
      }

      var animationDistance = Math.max(320, $cards.length * 180);

      // One viewport for the pinned image + extra distance for card animation.
      $wrapper.css("height", window.innerHeight + animationDistance + "px");
    }

    function syncCardsWithPageScroll() {
      if ($(window).width() <= 980) return;

      var wrapperTop = $wrapper.offset().top;
      var maxTravel = Math.max(0, $wrapper.outerHeight() - window.innerHeight);
      var relative = $(window).scrollTop() - wrapperTop;
      var progress = 0;

      if (maxTravel > 0) {
        progress = Math.max(0, Math.min(relative / maxTravel, 1));
      }

      $overlay[0].style.setProperty(
        "--overlay-cards-progress",
        progress.toFixed(4),
      );

      $cards.each(function (index) {
        var revealAt = Math.min(0.92, 0.14 + index * 0.14);
        $(this).toggleClass("is-in-view", progress >= revealAt);
      });

      if (relative < 0) {
        $overlay[0].style.setProperty("--overlay-cards-progress", "0");
        $cards.removeClass("is-in-view");
      } else if (relative > maxTravel) {
        $overlay[0].style.setProperty("--overlay-cards-progress", "1");
        $cards.addClass("is-in-view");
      }
    }

    updateLayout();
    syncCardsWithPageScroll();

    $(window).on("resize" + ns, function () {
      updateLayout();
      syncCardsWithPageScroll();
    });

    $(window).on("scroll" + ns, syncCardsWithPageScroll);
  });

  $(".mobile-menu-toggle").click(function () {
    $(".mobile-menu").toggleClass("active");
  });

  $(".mobile-menu-close").click(function () {
    $(".mobile-menu").removeClass("active");
  });

  $(window).resize(function () {
    $(".mobile-menu").removeClass("active");
  });

  if ($(".homepage-hero").length === 0) {
    $(".site-header").addClass("no-header");
  }

  const header = $(".site-header");
  const homepageHero = $(".homepage-hero");
  let stickyHideTimer = null;
  let heroBottomPos = 0;

  function calculateHeroBottomPos() {
    if (homepageHero.length) {
      heroBottomPos = homepageHero.offset().top + homepageHero.outerHeight();
    }
  }

  calculateHeroBottomPos();
  $(window).on("resize", calculateHeroBottomPos);

  $(window).scroll(function () {
    if ($(window).scrollTop() > heroBottomPos) {
      if (stickyHideTimer) {
        clearTimeout(stickyHideTimer);
        stickyHideTimer = null;
      }

      if (!header.hasClass("sticky")) {
        header.addClass("sticky");
        window.requestAnimationFrame(function () {
          header.addClass("slide-up");
        });
      } else {
        header.addClass("slide-up");
      }
    } else {
      header.removeClass("slide-up");

      if (stickyHideTimer) {
        clearTimeout(stickyHideTimer);
      }

      // Match CSS transition duration so opacity fades out before un-sticking.
      stickyHideTimer = setTimeout(function () {
        header.removeClass("sticky");
        stickyHideTimer = null;
      }, 500);
    }
  });

  // End of Jquery
});
