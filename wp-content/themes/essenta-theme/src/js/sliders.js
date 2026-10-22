jQuery(function ($) {
  if (typeof $.fn.slick !== "function") {
    console.error(
      "Essenta sliders could not initialise: Slick is unavailable.",
    );
    return;
  }

  function initialise($elements, options) {
    $elements.not(".slick-initialized").each(function () {
      if ($(this).children().length) {
        $(this).slick(options);
      }
    });
  }

  function destroy($elements) {
    $elements.filter(".slick-initialized").each(function () {
      var $slider = $(this);
      // Slick's queued resize can otherwise revive a slider after unslick.
      window.clearTimeout($slider.slick("getSlick").windowDelay);
      $slider.slick("unslick");
    });
  }

  $(".content-grid-block").each(function () {
    var $block = $(this);
    var $items = $block.find(".content-grid-items");
    var $dots = $block.find(".content-grid-dots .slider-dots");
    var mobileQuery = window.matchMedia("(max-width: 980px)");

    function initSlider() {
      if (!$items.hasClass("slick-initialized")) {
        initialise($items, {
          slidesToShow: 1.1,
          slidesToScroll: 1,
          arrows: false,
          dots: true,
          infinite: false,
          adaptiveHeight: false,
          appendDots: $dots,
        });
      }
    }

    function destroySlider() {
      if ($items.hasClass("slick-initialized")) {
        destroy($items);
      }
    }

    function handleSlider(e) {
      if (e.matches) {
        initSlider();
      } else {
        destroySlider();
      }
    }

    handleSlider(mobileQuery);
    mobileQuery.addEventListener("change", handleSlider);
  });

  $(".text-carousel-block").each(function () {
    var $block = $(this);
    initialise($block.find(".text-carousel-slider"), {
      slidesToShow: 3,
      slidesToScroll: 1,
      centerMode: true,
      infinite: true,
      arrows: true,
      dots: true,
      prevArrow: $block.find(".slider-nav.text-carousel .slick-prev"),
      nextArrow: $block.find(".slider-nav.text-carousel .slick-next"),
      appendDots: $block.find(".slider-nav.text-carousel .slider-dots"),
      autoplay: true,
      autoplaySpeed: 4000,
      speed: 1000,
      responsive: [
        {
          breakpoint: 980,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            dots: false,
            slidesToShow: 3,
            centerPadding: "20px",
            vertical: true,
            centerMode: false,
            verticalSwiping: true,
            adaptiveHeight: false, // Prevent height adaptation
            variableWidth: false,
          },
        },
      ],
    });
  });

  $(".testimonial-slider-block").each(function () {
    var $block = $(this);
    initialise($block.find(".testimonials-block-slider"), {
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: false,
      dots: true,
      arrows: false,
      appendDots: $block.find(
        ".testimonials-block-slider-nav.testimonials .slider-dots",
      ),
      adaptiveHeight: false,
    });
  });

  $(".insights-block").each(function () {
    var $block = $(this);
    initialise($block.find(".insights-posts-wrapper"), {
      variableWidth: true,
      variableHeight: true,
      slidesToShow: 3.2,
      slidesToScroll: 1,
      arrows: true,
      infinite: false,
      autoplay: false,
      autoplaySpeed: 2000,
      prevArrow: $block.find(".insights-posts-navigation.prev"),
      nextArrow: $block.find(".insights-posts-navigation.next"),

      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2.1,
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 768,
          settings: {
            variableWidth: false,
            slidesToShow: 1.1,
            slidesToScroll: 1,
          },
        },
      ],
    });
  });

  $(".introduction-block").each(function () {
    var $block = $(this);
    var $statisticsSliders = $block
      .find(".statistics-slider--slick")
      .not(".slick-initialized");

    initialise($statisticsSliders, {
      slidesToShow: 2.75,
      slidesToScroll: 1,
      infinite: true,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 10000,
      cssEase: "linear",
      pauseOnHover: false,
      pauseOnFocus: false,
      draggable: true,
      swipe: true,
      swipeToSlide: true,
      touchMove: true,
      waitForAnimate: false,
      dots: false,
      arrows: false,
      adaptiveHeight: false,
      responsive: [
        {
          breakpoint: 980,
          settings: {
            slidesToShow: 2.2,
          },
        },
        {
          breakpoint: 420,
          settings: {
            slidesToShow: 2.2,
          },
        },
      ],
    });

    $statisticsSliders.each(function () {
      var $slider = $(this);
      var slick = $slider.slick("getSlick");
      var isPaused = false;
      var animation;
      var completeAnimation;
      var touch;
      var touchInteraction = false;
      var isSettling = false;

      $slider.attr("tabindex", "0");

      // Keep Slick's slide lifecycle, but pause the animation itself, not just autoplay.
      slick.animateSlide = function (targetLeft, callback) {
        var track = slick.$slideTrack[0];
        var startTransform = window.getComputedStyle(track).transform;
        var startLeft = new DOMMatrixReadOnly(startTransform).m41;

        if (animation) {
          animation.cancel();
        }

        slick.setCSS(targetLeft);
        animation = track.animate(
          [
            {
              transform: startTransform,
            },
            {
              transform: track.style.transform,
            },
          ],
          {
            duration: isSettling
              ? 250
              : (Math.abs(targetLeft - startLeft) / slick.slideWidth) *
                slick.options.speed,
            easing: "linear",
          },
        );

        completeAnimation = function () {
          animation.cancel();
          animation = null;
          completeAnimation = null;
          isSettling = false;
          callback();
        };
        animation.onfinish = completeAnimation;

        if (isPaused && !isSettling) {
          animation.pause();
        }
      };

      function pauseSlider() {
        if (isPaused) {
          return;
        }

        isPaused = true;
        $slider.slick("slickPause");

        if (animation) {
          animation.pause();
        }
      }

      function resumeSlider(afterTouch) {
        if (
          touch ||
          (afterTouch !== true &&
            ((window.matchMedia("(hover: hover)").matches &&
              $slider.is(":hover")) ||
              $slider.is(":focus"))) ||
          $slider.find(":focus").length
        ) {
          return;
        }

        if (!isPaused) {
          return;
        }

        isPaused = false;
        if (animation) {
          animation.play();
        }
        $slider.slick("slickPlay");
        if (animation) {
          slick.autoPlayClear();
        }
      }

      function handleTouchStart(event) {
        event.stopPropagation();
        if (event.touches.length !== 1) {
          return;
        }

        touchInteraction = true;
        pauseSlider();
        touch = {
          x: event.touches[0].clientX,
          y: event.touches[0].clientY,
          left: new DOMMatrixReadOnly(
            window.getComputedStyle(slick.$slideTrack[0]).transform,
          ).m41,
          dragging: false,
          scrolling: false,
        };
      }

      function handleTouchMove(event) {
        event.stopPropagation();
        if (!touch || event.touches.length !== 1 || touch.scrolling) {
          return;
        }

        var deltaX = event.touches[0].clientX - touch.x;
        var deltaY = event.touches[0].clientY - touch.y;

        if (!touch.dragging) {
          if (Math.max(Math.abs(deltaX), Math.abs(deltaY)) < 5) {
            return;
          }
          if (Math.abs(deltaY) > Math.abs(deltaX)) {
            touch.scrolling = true;
            return;
          }
          touch.dragging = true;
          if (animation) {
            animation.cancel();
            animation = null;
            completeAnimation = null;
          }
          slick.animating = false;
        }

        event.preventDefault();
        slick.setCSS(
          Math.max(
            slick.getLeft(slick.slideCount),
            Math.min(slick.getLeft(-1), touch.left + deltaX),
          ),
        );
      }

      function handleTouchEnd(event) {
        event.stopPropagation();
        if (!touch || event.touches.length) {
          return;
        }

        var wasDragging = touch.dragging;
        touch = null;
        if (wasDragging) {
          var left = new DOMMatrixReadOnly(
            window.getComputedStyle(slick.$slideTrack[0]).transform,
          ).m41;
          var index =
            slick.currentSlide +
            Math.round(
              (slick.getLeft(slick.currentSlide) - left) / slick.slideWidth,
            );
          slick.swipeLeft = left;
          isSettling = true;
          slick.slideHandler(index);
        }
        resumeSlider(true);
      }

      // Capture touch gestures before Slick's in-flight animation guard blocks dragging.
      $slider[0].addEventListener("touchstart", handleTouchStart, {
        capture: true,
        passive: true,
      });
      $slider[0].addEventListener("touchmove", handleTouchMove, {
        capture: true,
        passive: false,
      });
      $slider[0].addEventListener("touchend", handleTouchEnd, true);
      $slider[0].addEventListener("touchcancel", handleTouchEnd, true);

      $slider.on("setPosition", function () {
        if (completeAnimation) {
          completeAnimation();
        }
      });
      $slider.on("destroy", function () {
        $slider[0].removeEventListener("touchstart", handleTouchStart, true);
        $slider[0].removeEventListener("touchmove", handleTouchMove, true);
        $slider[0].removeEventListener("touchend", handleTouchEnd, true);
        $slider[0].removeEventListener("touchcancel", handleTouchEnd, true);
        if (animation) {
          animation.cancel();
          animation = null;
          completeAnimation = null;
        }
      });
      $slider.on("pointerenter", function (event) {
        if (event.originalEvent.pointerType === "mouse") {
          touchInteraction = false;
          pauseSlider();
        }
      });
      $slider.on("focusin", function (event) {
        var capabilities =
          event.originalEvent && event.originalEvent.sourceCapabilities;
        if (
          event.target === $slider[0] &&
          ((capabilities && capabilities.firesTouchEvents) ||
            (touchInteraction && !event.target.matches(":focus-visible")))
        ) {
          return;
        }
        pauseSlider();
      });
      $slider.on("mouseleave", resumeSlider);
      $slider.on("focusout", function (event) {
        if (event.relatedTarget && $slider[0].contains(event.relatedTarget)) {
          return;
        }

        window.setTimeout(resumeSlider, 0);
      });
    });

    initialise($block.find(".testimonials-slider"), {
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: false,
      dots: true,
      arrows: false,
      appendDots: $block.find(".slider-nav.testimonials .slider-dots"),
      adaptiveHeight: false,
    });
  });

  initialise($(".partner-logos .partner-logos-slider--forward"), {
    slidesToShow: 6,
    slidesToScroll: 1,
    arrows: false,
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 0,
    speed: 6000,
    cssEase: "linear",
    pauseOnHover: false,
    pauseOnFocus: false,
    variableWidth: true,
    centerMode: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
        },
      },
    ],
  });

  // rtl + variableWidth breaks slick's track rendering, so mirror direction with CSS instead
  initialise($(".partner-logos .partner-logos-slider--reverse"), {
    slidesToShow: 6,
    slidesToScroll: 1,
    arrows: false,
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 0,
    speed: 6000,
    cssEase: "linear",
    pauseOnHover: false,
    pauseOnFocus: false,
    variableWidth: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
        },
      },
    ],
  });

  $(".full-height-slider-block").each(function () {
    var $block = $(this);
    var $slider = $block.find(".full-height-slider");
    var $navButtons = $block.find(".full-height-slider-nav .slide-type-btn");

    initialise($slider, {
      slidesToShow: 1,
      slidesToScroll: 1,
      swipe: false,
      autoplay: true,
      autoplaySpeed: 4000,
      pauseOnHover: false,
      pauseOnFocus: false,
      fade: true,
      arrows: false,
      dots: false,
      infinite: true,
      adaptiveHeight: false,
      speed: 800,
    });

    $navButtons.on("click", function () {
      var slideIndex = parseInt($(this).data("slide-index"), 10);
      $slider.slick("slickGoTo", slideIndex);
    });

    $slider.on(
      "beforeChange",
      function (event, slick, currentSlide, nextSlide) {
        $navButtons
          .removeClass("active")
          .filter('[data-slide-index="' + nextSlide + '"]')
          .addClass("active");
      },
    );
  });

  $(".usp-module-large__feature").each(function () {
    var $feature = $(this);
    var $nav = $feature.siblings(".usp-module-large__nav");

    if (!$nav.length) {
      return;
    }

    if (
      !$.fn.slick ||
      $feature.hasClass("slick-initialized") ||
      $nav.hasClass("slick-initialized")
    ) {
      return;
    }

    var $allNavItems = $nav.children().detach();
    var navSettings = {
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      vertical: true,
      verticalSwiping: true,
      infinite: false,
      responsive: [
        {
          breakpoint: 980,
          settings: {
            vertical: false,
            slidesToShow: 3,
            centerMode: false,
          },
        },
      ],
    };

    function renderAvailableNav(activeIndex) {
      if ($nav.hasClass("slick-initialized")) {
        destroy($nav);
      }

      $nav.empty();
      $allNavItems.each(function () {
        if (Number($(this).data("slide-index")) !== activeIndex) {
          $nav.append(this);
        }
      });
      initialise($nav, navSettings);
    }

    initialise($feature, {
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      vertical: true,
      verticalSwiping: true,
      speed: 400,
      infinite: false,
    });

    renderAvailableNav(0);

    $nav.on("click", ".usp-module-large__nav-item", function () {
      $feature.slick("slickGoTo", Number($(this).data("slide-index")));
    });

    $feature.on("afterChange", function (event, slick, currentSlide) {
      renderAvailableNav(currentSlide);
    });
  });

  $(".team-members-block").each(function () {
    var $block = $(this);

    const $slider = $block.find(".team-members-slider");

    initialise($slider, {
      slidesToShow: 3.5,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      infinite: false,
      prevArrow: $block.find(".team-members-block__prev"),
      nextArrow: $block.find(".team-members-block__next"),
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2.5,
            slidesToScroll: 1,
          },
        },
        {
          breakpoint: 700,
          settings: {
            slidesToShow: 1.35,
            slidesToScroll: 1,
          },
        },
      ],
    });
  });

  $(".full-height-scrolling-content").each(function () {
    var $block = $(this);
    var $slider = $block.find(".scrolling-contents");
    var sectionEl = $block[0];
    var sectionInView = true;
    var $openModal = null;

    function pauseAllVideos() {
      $slider.find(".scrolling-content-video").each(function () {
        this.pause();
      });
    }

    function playActiveVideo() {
      pauseAllVideos();
      if (!sectionInView || $openModal) return;
      $slider.find(".slick-current .scrolling-content-video").each(function () {
        this.play().catch(function () {});
      });
    }

    $slider.on("init afterChange", playActiveVideo);

    var mobileQuery = window.matchMedia("(max-width: 980px)");

    function initSlider(isMobile) {
      initialise($slider, {
        vertical: !isMobile,
        verticalSwiping: !isMobile,
        slidesToShow: isMobile ? 1.1 : 1,
        slidesToScroll: 1,
        arrows: !isMobile,
        dots: false,
        infinite: !isMobile,
        centerMode: !isMobile,
        adaptiveHeight: false,
        prevArrow: $block.find(".scrolling-contents-prev"),
        nextArrow: $block.find(".scrolling-contents-next"),
      });
    }

    function syncSliderOrientation(event) {
      if ($slider.hasClass("slick-initialized")) {
        destroy($slider);
      }

      initSlider(event.matches);
    }

    syncSliderOrientation(mobileQuery);
    mobileQuery.addEventListener("change", syncSliderOrientation);

    // Only autoplay the current slide's video while the section itself is on screen.
    if (sectionEl && "IntersectionObserver" in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            sectionInView = entry.isIntersecting;
            if (entry.isIntersecting) {
              playActiveVideo();
            } else {
              pauseAllVideos();
            }
          });
        },
        {
          threshold: 0.4,
        },
      );
      observer.observe(sectionEl);
    }

    function closeModal($modal) {
      $modal
        .removeClass("is-open")
        .find(".scrolling-content-video-modal-video")
        .each(function () {
          this.pause();
          this.currentTime = 0;
        });
      $openModal = null;
      playActiveVideo();
    }

    $block.on("click", "[data-video-modal-trigger]", function () {
      var itemId = $(this).data("video-modal-trigger");
      var $modal = $block.find("[data-video-modal]").filter(function () {
        return this.id === itemId + "-modal";
      });
      if (!$modal.length) return;

      pauseAllVideos();
      $openModal = $modal;
      $modal
        .addClass("is-open")
        .find(".scrolling-content-video-modal-video")
        .each(function () {
          this.currentTime = 0;
          this.play().catch(function () {});
        });
    });

    $block.on("click", "[data-video-modal-close]", function () {
      closeModal($(this).closest("[data-video-modal]"));
    });

    $(document).on("keydown", function (event) {
      if (event.key === "Escape" && $openModal) {
        closeModal($openModal);
      }
    });
  });

  $(".case-study-stats--slider").each(function () {
    var $stats = $(this);

    if ($stats.length && $.fn.slick && !$stats.hasClass("slick-initialized")) {
      initialise($stats, {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        infinite: false,
        adaptiveHeight: false,
      });
    }
  });

  $(".statistics-module__grid").each(function () {
    var $grid = $(this);
    var mobileQuery = window.matchMedia("(max-width: 980px)");
    function syncStatistics() {
      if (mobileQuery.matches && !$grid.hasClass("slick-initialized")) {
        initialise($grid, {
          slidesToShow: 2.2,
          slidesToScroll: 1,
          infinite: false,
          dots: false,
          arrows: false,
          adaptiveHeight: false,
          responsive: [{ breakpoint: 768, settings: { slidesToShow: 1.2 } }],
        });
      } else if (!mobileQuery.matches && $grid.hasClass("slick-initialized")) {
        destroy($grid);
      }
    }
    syncStatistics();
    mobileQuery.addEventListener("change", syncStatistics);
  });

  $(".archive-recent-insights-grid").each(function () {
    const recentInsights = $(this);
    const mobileQuery = window.matchMedia("(max-width: 768px)");

    function syncRecentInsightsSlider(event) {
      if (!recentInsights.length) {
        return;
      }

      if (event.matches && !recentInsights.hasClass("slick-initialized")) {
        initialise(recentInsights, {
          slidesToShow: 1.1,
          slidesToScroll: 1,
          arrows: false,
          dots: false,
          infinite: false,
          adaptiveHeight: true,
        });
      } else if (
        !event.matches &&
        recentInsights.hasClass("slick-initialized")
      ) {
        destroy(recentInsights);
      }
    }

    syncRecentInsightsSlider(mobileQuery);
    mobileQuery.addEventListener("change", syncRecentInsightsSlider);
  });

  initialise($("#main-product-image"), {
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    asNavFor: ".product-slick-carousel",
  });

  initialise($(".product-slick-carousel"), {
    slidesToShow: 3,
    slidesToScroll: 1,
    asNavFor: "#main-product-image",
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    arrows: false,
    infinite: true,
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
          slidesToShow: 2,
        },
      },
    ],
  });

  // accordion carousel mobile
  initialise($(".carousel-mobile-items"), {
    infinite: true,
    slidesToShow: 2,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    dots: false,
    arrows: false,
    pauseOnHover: true,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  // Gallery item click handler
  $(".gallery-item").on("click", function () {
    var newImageSrc = $(this).data("full");
    var newImageAlt = $(this).find("img").attr("alt");

    $("#main-product-image").attr("src", newImageSrc).attr("alt", newImageAlt);
  });
  $(".articles-block").each(function () {
    var $block = $(this);
    var $slider = $block.find(".articles-slider");
    var mobileQuery = window.matchMedia("(max-width: 979px)");
    function syncArticles() {
      if (mobileQuery.matches && !$slider.hasClass("slick-initialized")) {
        initialise($slider, {
          speed: 300,
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          infinite: false,
          autoplay: false,
          prevArrow: $block.find(".slider-nav.articles .slick-prev"),
          nextArrow: $block.find(".slider-nav.articles .slick-next"),
          appendDots: $block.find(".slider-nav.articles .slider-dots"),
        });
      } else if (
        !mobileQuery.matches &&
        $slider.hasClass("slick-initialized")
      ) {
        destroy($slider);
      }
    }
    syncArticles();
    mobileQuery.addEventListener("change", syncArticles);
  });

  document.querySelectorAll(".sectors-grid").forEach((grid) => {
    const $grid = jQuery(grid);
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const cards = Array.from(grid.children);
    const mobileQuery = window.matchMedia("(max-width: 760px)");

    function syncSectorsSlider() {
      if ($grid.hasClass("slick-initialized")) {
        destroy($grid);
      }
      grid.replaceChildren(...cards);
      const isSlider = mobileQuery.matches || cards.length > 4;
      grid.classList.toggle("sectors-grid--slider", isSlider);
      if (!isSlider) {
        grid.removeAttribute("tabindex");
        return;
      }
      grid.setAttribute("tabindex", "0");

      if (!mobileQuery.matches) {
        for (let index = 0; index < cards.length; index += 2) {
          jQuery(cards.slice(index, index + 2)).wrapAll(
            '<div class="sectors-column"></div>',
          );
        }
      }

      initialise($grid, {
        rows: 0,
        slidesToShow: mobileQuery.matches ? 1.25 : 7 / 3,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        infinite: false,
        swipeToSlide: false,
        draggable: true,
        adaptiveHeight: false,
        speed: reducedMotion.matches ? 0 : 350,
      });
    }
    syncSectorsSlider();
    mobileQuery.addEventListener("change", syncSectorsSlider);
    reducedMotion.addEventListener("change", () => {
      if ($grid.hasClass("slick-initialized")) {
        $grid.slick("slickSetOption", "speed", reducedMotion.matches ? 0 : 350);
      }
    });
    grid.addEventListener("keydown", (event) => {
      if (
        !$grid.hasClass("slick-initialized") ||
        event.target !== grid ||
        !["ArrowLeft", "ArrowRight"].includes(event.key)
      ) {
        return;
      }
      event.preventDefault();
      $grid.slick(event.key === "ArrowLeft" ? "slickPrev" : "slickNext");
    });
  });
});
