window.essentaInitOfficeMaps = function () {
  var mapElements = document.querySelectorAll(".js-office-locations-map");
  var mapStyles = [
    {
      featureType: "all",
      elementType: "labels",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "administrative",
      elementType: "geometry.fill",
      stylers: [{ color: "#273259" }],
    },
    {
      featureType: "administrative",
      elementType: "geometry.stroke",
      stylers: [{ color: "#0f1c47" }],
    },
    {
      featureType: "landscape",
      elementType: "geometry.fill",
      stylers: [{ color: "#27335b" }],
    },
    { featureType: "poi", elementType: "all", stylers: [{ color: "#2c3966" }] },
    {
      featureType: "road",
      elementType: "geometry.fill",
      stylers: [{ color: "#0f1c47" }],
    },
    {
      featureType: "road.highway",
      elementType: "geometry.stroke",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "transit",
      elementType: "all",
      stylers: [{ visibility: "off" }],
    },
    {
      featureType: "water",
      elementType: "all",
      stylers: [{ color: "#0f1c47" }, { visibility: "on" }],
    },
  ];
  var markerAnimations = new WeakMap();
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  function fadeMarker(marker, targetOpacity, delay) {
    var currentAnimation = markerAnimations.get(marker);
    if (currentAnimation) {
      window.clearTimeout(currentAnimation.timer);
      window.cancelAnimationFrame(currentAnimation.frame);
    }

    if (reduceMotion.matches) {
      marker.setOpacity(targetOpacity);
      return;
    }

    var animation = { frame: 0, timer: 0 };
    markerAnimations.set(marker, animation);

    animation.timer = window.setTimeout(function () {
      var currentOpacity = marker.getOpacity();
      var startOpacity =
        typeof currentOpacity === "number" ? currentOpacity : 0;
      var startTime = performance.now();

      function updateOpacity(currentTime) {
        var progress = Math.min((currentTime - startTime) / 500, 1);
        var easedProgress = 1 - Math.pow(1 - progress, 3);
        marker.setOpacity(
          startOpacity + (targetOpacity - startOpacity) * easedProgress,
        );

        if (progress < 1) {
          animation.frame = window.requestAnimationFrame(updateOpacity);
        } else {
          markerAnimations.delete(marker);
        }
      }

      animation.frame = window.requestAnimationFrame(updateOpacity);
    }, delay || 0);
  }

  mapElements.forEach(function (mapElement) {
    if (mapElement.dataset.initialized === "true") return;

    var markerElements = mapElement.querySelectorAll(
      ".office-locations__marker",
    );
    var pinIconUrl = mapElement.dataset.pinIcon;
    var officeBounds = new google.maps.LatLngBounds();
    var map = new google.maps.Map(mapElement, {
      center: { lat: 51.5072, lng: -0.1276 },
      fullscreenControl: false,
      mapTypeControl: false,
      minZoom: 3,
      restriction: {
        latLngBounds: {
          east: 180,
          north: 85,
          south: -60,
          west: -180,
        },
        strictBounds: true,
      },
      styles: mapStyles,
      streetViewControl: false,
      zoom: 6,
    });
    var stage = mapElement.closest(".office-locations__stage");
    var drawer = stage.querySelector(".office-locations__drawer");
    var closeButton = stage.querySelector(".office-locations__close");
    var toggles = stage.querySelectorAll(".office-locations__toggle");
    var details = stage.querySelectorAll(".office-locations__details");
    var officeMarkers = {};
    var partnerMarkers = [];
    var drawerTimer;

    function resetViewport() {
      var officeIndexes = Object.keys(officeMarkers);

      if (officeIndexes.length === 1) {
        map.setCenter(officeMarkers[officeIndexes[0]].position);
        map.setZoom(6);
        return;
      }

      if (officeIndexes.length > 1) {
        map.fitBounds(officeBounds, 48);
      }
    }

    function closeDrawer() {
      clearTimeout(drawerTimer);
      drawer.classList.remove("is-open");
      drawer.setAttribute("aria-hidden", "true");
      drawer.setAttribute("inert", "");
      toggles.forEach(function (toggle) {
        toggle.classList.remove("is-active");
        toggle.setAttribute("aria-expanded", "false");
      });
      details.forEach(function (detail) {
        detail.hidden = true;
      });
      resetViewport();
    }

    function selectOffice(locationIndex) {
      var office = officeMarkers[locationIndex];
      if (!office) return;

      clearTimeout(drawerTimer);
      map.panTo(office.position);
      map.setZoom(6);

      toggles.forEach(function (toggle) {
        var isActive = toggle.dataset.locationIndex === locationIndex;
        toggle.classList.toggle("is-active", isActive);
        toggle.setAttribute("aria-expanded", String(isActive));
      });
      details.forEach(function (detail) {
        detail.hidden = detail.dataset.locationIndex !== locationIndex;
      });

      drawerTimer = setTimeout(function () {
        drawer.classList.add("is-open");
        drawer.setAttribute("aria-hidden", "false");
        drawer.removeAttribute("inert");
      }, 300);
    }

    drawer.setAttribute("inert", "");

    markerElements.forEach(function (markerElement) {
      var isOffice = markerElement.dataset.locationType === "office";
      var position = {
        lat: Number(markerElement.dataset.lat),
        lng: Number(markerElement.dataset.lng),
      };
      var marker = new google.maps.Marker({
        clickable: isOffice,
        icon: isOffice
          ? {
              anchor: new google.maps.Point(8, 15),
              scaledSize: new google.maps.Size(24, 24),
              url: pinIconUrl,
            }
          : {
              fillColor: "#00a2aa",
              fillOpacity: 1,
              path: google.maps.SymbolPath.CIRCLE,
              scale: 5,
              strokeColor: "#00a2aa",
              strokeWeight: 0,
            },
        map: map,
        opacity: isOffice ? 1 : 0,
        position: position,
        title: isOffice ? markerElement.dataset.title : "",
      });

      if (isOffice) {
        marker.addListener("click", function () {
          selectOffice(markerElement.dataset.locationIndex);
        });

        officeMarkers[markerElement.dataset.locationIndex] = {
          marker: marker,
          position: position,
        };
      } else {
        partnerMarkers.push(marker);
      }

      if (isOffice) {
        officeBounds.extend(position);
      }
    });

    toggles.forEach(function (toggle) {
      toggle.addEventListener("click", function () {
        selectOffice(toggle.dataset.locationIndex);
      });
    });

    closeButton.addEventListener("click", closeDrawer);
    map.addListener("click", closeDrawer);
    stage.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeDrawer();
        toggles[0]?.focus();
      }
    });

    var observer = new IntersectionObserver(
      function (entries) {
        var isVisible = entries[0].intersectionRatio >= 0.15;
        partnerMarkers.forEach(function (marker, markerIndex) {
          fadeMarker(
            marker,
            isVisible ? 1 : 0,
            isVisible ? markerIndex * 45 : 0,
          );
        });
      },
      { threshold: [0, 0.15] },
    );
    observer.observe(stage);

    resetViewport();

    mapElement.dataset.initialized = "true";
  });
};

// Wrap consecutive images in blog posts into a masonry grid
jQuery(document).ready(function ($) {
  var $content = $("body.single .container.contained");
  if (!$content.length) return;

  function isImageNode(el) {
    var tag = el.tagName;
    // Plain <img>
    if (tag === "IMG") return true;
    // <a> wrapping an <img>
    if (tag === "A" && $(el).children("img").length) return true;
    // <figure> or .wp-block-image containing an <img>
    if (
      (tag === "FIGURE" || $(el).hasClass("wp-block-image")) &&
      $(el).find("img").length
    )
      return true;
    // <p> that only contains an <img> or <a><img></a> (WordPress classic editor pattern)
    if (tag === "P") {
      var $clone = $(el).clone();
      $clone.find("img, a:has(img)").remove();
      if ($.trim($clone.text()) === "" && $(el).find("img").length) return true;
    }
    return false;
  }

  var children = $content.children();
  var i = 0;
  while (i < children.length) {
    if (isImageNode(children[i])) {
      var group = [children[i]];
      var j = i + 1;
      while (j < children.length && isImageNode(children[j])) {
        group.push(children[j]);
        j++;
      }
      if (group.length > 1) {
        var $wrapper = $("<div class='image-masonry-grid'></div>");
        if (group.length >= 3) {
          $wrapper.addClass("masonry-3-plus");
        }
        $(group[0]).before($wrapper);
        for (var k = 0; k < group.length; k++) {
          $wrapper.append(group[k]);
        }
        children = $content.children();
        i = $wrapper.index() + 1;
      } else {
        i = j;
      }
    } else {
      i++;
    }
  }
});

// Articles Block Image Swapping
jQuery(document).ready(function ($) {
  const articleItems = $(".article-item");
  const imageItems = $(".article-image-item");

  if (articleItems.length && imageItems.length) {
    // Add hover event listeners to each article item
    articleItems.each(function () {
      $(this).on("mouseenter", function () {
        const index = $(this).attr("data-index");

        // Remove active class from all images
        imageItems.removeClass("active");

        // Add active class to corresponding image
        const targetImage = $(`.article-image-item[data-index="${index}"]`);
        if (targetImage.length) {
          targetImage.addClass("active");
        }
      });
    });

    // Optional: Reset to first image when mouse leaves the articles area
    const articlesWrapper = $(".articles-wrapper");
    if (articlesWrapper.length) {
      articlesWrapper.on("mouseleave", function () {
        // Remove active class from all images
        imageItems.removeClass("active");

        // Set first image as active
        const firstImage = $(".article-image-item[data-index='0']");
        if (firstImage.length) {
          firstImage.addClass("active");
        }
      });
    }
  }

  // Footer Details Responsive Behavior
  function handleFooterDetails() {
    const footerDetails = $(".footer-details-wrapper");

    if (window.innerWidth > 980) {
      // Desktop: Always keep open and prevent closing
      footerDetails.each(function () {
        this.open = true;
      });

      // Prevent clicking on summary from closing
      $(".footer-details-wrapper summary")
        .off("click.footerToggle")
        .on("click.footerToggle", function (e) {
          if (window.innerWidth > 980) {
            e.preventDefault();
            e.stopPropagation();
          }
        });
    } else {
      // Mobile: Remove click prevention and close all initially
      $(".footer-details-wrapper summary").off("click.footerToggle");

      // Close all on mobile by default
      footerDetails.each(function () {
        this.open = false;
      });
    }
  }

  // Run on load
  handleFooterDetails();

  // Run on resize with debounce
  let resizeTimer;
  $(window).on("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(handleFooterDetails, 250);
  });

  //Product page caorousel
  // Initialize Slick Carousel with navigation
  $("#main-product-image").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    asNavFor: ".product-slick-carousel",
  });

  $(".product-slick-carousel").slick({
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
  $(".carousel-mobile-items").slick({
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

  //top bar mobile carousel

  // $(".top-details").slick({
  //   infinite: true,
  //   slidesToShow: 1,
  //   slidesToScroll: 1,
  //   autoplay: true,
  //   autoplaySpeed: 5000,
  //   dots: false,
  //   arrows: false,
  //   pauseOnHover: true,
  //   settings: "unslick",
  //   responsive: [
  //     {
  //       breakpoint: 768,
  //       settings: "slick",
  //     },
  //   ],
  // });

  // Gallery item click handler
  $(".gallery-item").on("click", function () {
    var newImageSrc = $(this).data("full");
    var newImageAlt = $(this).find("img").attr("alt");

    $("#main-product-image").attr("src", newImageSrc).attr("alt", newImageAlt);
  });

  // Mobile menu drawer
  var $drawer = $("#mm-drawer");
  var $toggle = $(".mm-toggle");
  var $overlay = $(".mm-overlay");
  var $close = $drawer.find(".mm-close");

  if ($drawer.length && $toggle.length && $overlay.length) {
    function openMenu() {
      $drawer.addClass("is-open").attr("aria-hidden", "false");
      $overlay.prop("hidden", false);
      $("body").addClass("mm-locked");
      $toggle.attr("aria-expanded", "true");
    }

    function closeMenu() {
      $drawer.removeClass("is-open").attr("aria-hidden", "true");
      $overlay.prop("hidden", true);
      $("body").removeClass("mm-locked");
      $toggle.attr("aria-expanded", "false");
    }

    $toggle.on("click", openMenu);
    $overlay.on("click", closeMenu);
    $close.on("click", closeMenu);
    $(document).on("keydown", function (event) {
      if (event.key === "Escape" && $drawer.hasClass("is-open")) closeMenu();
    });
  }

  //Article Slider
  // Initialize Slick slider for mobile
  $(".articles-slider").slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    autoplay: false,
    responsive: [
      {
        breakpoint: 9999,
        settings: "unslick",
      },
      {
        breakpoint: 980,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: true,
          arrows: true,
          infinite: false,
          prevArrow: $(".slider-nav.articles .slick-prev"),
          nextArrow: $(".slider-nav.articles .slick-next"),
          appendDots: $(".slider-nav.articles .slider-dots"),
        },
      },
    ],
  });

  // End of jQuery Ready
});

// Ensure the DOM is fully loaded before running the hero video script
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".homepage-hero-image").forEach((hero) => {
    const video = hero.querySelector(".homepage-hero-video");
    const playButton = hero.querySelector(".homepage-hero-play");

    if (!video || !playButton) {
      return;
    }

    playButton.addEventListener("click", (event) => {
      event.stopPropagation();

      video.play();
    });

    // Let clicking the video itself toggle play/pause, alongside the
    // native controls.
    video.addEventListener("click", (event) => {
      event.stopPropagation();

      if (video.paused) {
        video.play();
      } else {
        video.pause();
      }
    });

    video.addEventListener("play", () => {
      playButton.style.display = "none";
    });

    video.addEventListener("pause", () => {
      playButton.style.display = "flex";
    });

    video.addEventListener("ended", () => {
      playButton.style.display = "flex";
    });
  });

  // Scroll-jack the hero video: intercept scroll input to animate the video
  // from its start size to fullscreen (overlapping the hero content), then
  // release the jack once fully expanded so the page scrolls normally.
  // Scrolling back up to the top re-engages the jack in reverse (no replay).
  document
    .querySelectorAll(".homepage-hero-image-wrapper.has-scroll-video")
    .forEach((wrapper) => {
      const heroSection = wrapper.closest(".homepage-hero");
      const video = wrapper.querySelector(".homepage-hero-video");

      if (!heroSection || !video) {
        return;
      }

      const isMobile = () => window.innerWidth <= 980;
      const growPxDistance = 900;
      const navOffset = 120;

      const startBorderRadius = 5;
      let startTop = 0;
      let startLeft = 0;
      let startWidth = 0;
      let startHeight = 0;
      let bottomOffsetInHero = 0;
      let topOffsetInHero = 0;
      let reverseStartRect = null;

      // collapsed | expanding | released | collapsing
      let phase = "collapsed";
      let progress = 0;

      function resetWrapper() {
        wrapper.style.position = "";
        wrapper.style.top = "";
        wrapper.style.left = "";
        wrapper.style.width = "";
        wrapper.style.height = "";
        wrapper.style.borderRadius = "";
      }

      function measure() {
        resetWrapper();
        const rect = wrapper.getBoundingClientRect();
        const heroRect = heroSection.getBoundingClientRect();
        startTop = rect.top;
        startLeft = rect.left;
        startWidth = rect.width;
        startHeight = rect.height;
        // Scroll-position independent: same regardless of current scrollY
        bottomOffsetInHero = rect.top - heroRect.top + rect.height;
        // Where the viewport-relative navOffset lands relative to the hero,
        // so the absolute release state lines up with the fixed rect exactly
        topOffsetInHero = navOffset - heroRect.top;
      }

      function lockScroll() {
        // Compensate for the scrollbar disappearing so locking doesn't
        // shift the viewport width and fire a resize event mid-gesture.
        const scrollbarWidth =
          window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = "hidden";
        document.body.style.paddingRight = `${scrollbarWidth}px`;
      }

      function unlockScroll() {
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
      }

      function applyProgress(p) {
        const vw = window.innerWidth;
        const bottomFixed = startTop + startHeight;

        // Height lags slightly behind width so it visibly grows a touch slower
        const heightP = Math.pow(p, 1.3);

        const left = startLeft * (1 - p);
        const width = startWidth + (vw - startWidth) * p;
        const top = startTop + (navOffset - startTop) * heightP;
        const height = bottomFixed - top;

        // Fixed positioning takes it out of flow so it can grow in place
        // and overlap the hero content above it as it expands.
        wrapper.style.position = "fixed";
        wrapper.style.top = `${top}px`;
        wrapper.style.left = `${left}px`;
        wrapper.style.width = `${width}px`;
        wrapper.style.height = `${height}px`;
        wrapper.style.borderRadius = `${startBorderRadius * (1 - p)}px`;
      }

      function applyReleasedStyles() {
        // Match the fully-expanded rect exactly so it scrolls away
        // naturally with the page instead of staying fixed forever.
        // Scroll-position independent (unlike startTop + startHeight).
        wrapper.style.position = "absolute";
        wrapper.style.top = `${topOffsetInHero}px`;
        wrapper.style.left = "0";
        wrapper.style.width = "100%";
        wrapper.style.height = `${bottomOffsetInHero - topOffsetInHero}px`;
        wrapper.style.borderRadius = "0px";
      }

      function applyReverseProgress(p) {
        if (!reverseStartRect) {
          applyProgress(p);
          return;
        }

        const initialTop = startTop - window.scrollY;
        const initialLeft = startLeft;
        const initialWidth = startWidth;
        const initialHeight = startHeight;

        wrapper.style.position = "fixed";
        wrapper.style.top = `${
          initialTop + (reverseStartRect.top - initialTop) * p
        }px`;
        wrapper.style.left = `${
          initialLeft + (reverseStartRect.left - initialLeft) * p
        }px`;
        wrapper.style.width = `${
          initialWidth + (reverseStartRect.width - initialWidth) * p
        }px`;
        wrapper.style.height = `${
          initialHeight + (reverseStartRect.height - initialHeight) * p
        }px`;
        wrapper.style.borderRadius = `${startBorderRadius * (1 - p)}px`;
      }

      function releaseJack() {
        phase = "released";
        unlockScroll();
        video.play();
        applyReleasedStyles();
      }

      function collapse() {
        phase = "collapsed";
        progress = 0;
        reverseStartRect = null;
        unlockScroll();
        resetWrapper();

        video.pause();
        video.currentTime = 0;
      }

      function resetAtTop() {
        if (
          isMobile() ||
          window.scrollY > 0 ||
          phase === "expanding" ||
          phase === "collapsing"
        ) {
          return;
        }

        phase = "collapsed";
        progress = 0;
        unlockScroll();
        resetWrapper();
        video.pause();
        video.currentTime = 0;
        measure();
      }

      function isHeroInView() {
        const rect = heroSection.getBoundingClientRect();
        return rect.bottom > 0 && rect.top < window.innerHeight;
      }

      function handleWheel(event) {
        if (isMobile()) {
          return;
        }

        if (phase === "released") {
          // Re-engage in reverse while the released hero is still visible.
          if (isHeroInView() && event.deltaY < 0) {
            event.preventDefault();
            reverseStartRect = wrapper.getBoundingClientRect();
            lockScroll();
            phase = "collapsing";
            progress = 1;
            applyReverseProgress(progress);
          }
          return;
        }

        if (phase === "collapsed") {
          if (window.scrollY > 0 || event.deltaY <= 0) {
            return;
          }
          event.preventDefault();
          lockScroll();
          phase = "expanding";
          progress = Math.min(progress + event.deltaY / growPxDistance, 1);
          applyProgress(progress);
          if (progress >= 1) {
            releaseJack();
          }
          return;
        }

        if (phase === "expanding") {
          event.preventDefault();
          progress = Math.min(
            Math.max(progress + event.deltaY / growPxDistance, 0),
            1,
          );
          applyReverseProgress(progress);

          if (progress >= 1) {
            releaseJack();
          } else if (progress <= 0) {
            collapse();
          }
          return;
        }

        if (phase === "collapsing") {
          event.preventDefault();
          progress = Math.min(
            Math.max(progress + event.deltaY / growPxDistance, 0),
            1,
          );
          applyProgress(progress);

          if (progress <= 0) {
            collapse();
          } else if (progress >= 1) {
            releaseJack();
          }
        }
      }

      measure();

      // A restored lower-page load keeps the video at its original size.
      // The animation only becomes active again after returning to the top
      // and starting a new downward scroll.

      window.addEventListener("resize", () => {
        // Locking body scroll can hide the scrollbar and fire a resize event
        // mid-gesture; only re-measure when at rest so it doesn't corrupt
        // the in-flight animation and cause the size to jump.
        if (phase === "collapsed") {
          measure();
        }
      });
      window.addEventListener("wheel", handleWheel, { passive: false });
      window.addEventListener("scroll", resetAtTop, { passive: true });

      // Stop playback once the hero has scrolled fully out of view
      const videoObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) {
              // Leaving the hero always returns it to the initial, paused
              // state. This also handles pages loaded below the hero.
              collapse();
            }
          });
        },
        { threshold: 0 },
      );

      videoObserver.observe(heroSection);
    });

  function updateFooterDetails() {
    const details = document.querySelectorAll(".footer-details-wrapper");

    details.forEach((item) => {
      if (window.innerWidth <= 980) {
        item.setAttribute("name", "detail-item");
      } else {
        item.removeAttribute("name");
        item.open = true;
      }
    });
  }

  updateFooterDetails();

  window.addEventListener("resize", updateFooterDetails);

  document.querySelectorAll("[data-team-filters]").forEach((filters) => {
    const directory = filters.closest(".team-archive__directory");
    const cards = Array.from(directory.querySelectorAll(".team-archive__card"));
    const emptyMessage = directory.querySelector("[data-team-empty]");
    const visibleCountElement = filters.querySelector(
      "[data-team-visible-count]",
    );
    const locationFilter = filters.querySelector(
      '[data-team-filter="location"]',
    );
    const departmentFilter = filters.querySelector(
      '[data-team-filter="department"]',
    );

    function cardHasTerm(card, taxonomy, term) {
      if (!term) return true;
      return card.dataset[taxonomy].split(" ").includes(term);
    }

    function filterTeamMembers() {
      let visibleCount = 0;

      cards.forEach((card, index) => {
        const isVisible =
          cardHasTerm(card, "teamLocation", locationFilter.value) &&
          cardHasTerm(card, "teamDepartment", departmentFilter.value);

        if (isVisible) {
          card.hidden = false;
          card.style.transitionDelay = `${Math.min(0.2, index * 0.03)}s`;

          requestAnimationFrame(() => {
            card.classList.remove("is-filtered-out");
          });

          visibleCount += 1;
        } else {
          card.style.transitionDelay = "0s";
          card.classList.add("is-filtered-out");

          window.setTimeout(() => {
            if (card.classList.contains("is-filtered-out")) {
              card.hidden = true;
            }
          }, 450);
        }
      });

      emptyMessage.hidden = visibleCount !== 0;
      visibleCountElement.textContent = visibleCount;
    }

    locationFilter.addEventListener("change", filterTeamMembers);
    departmentFilter.addEventListener("change", filterTeamMembers);
  });

  document.querySelectorAll("[data-team-drawer]").forEach((drawerShell) => {
    const drawer = drawerShell.querySelector(".team-archive__drawer");
    const drawerContent = drawerShell.querySelector(
      "[data-team-drawer-content]",
    );
    const triggers = document.querySelectorAll(
      `[data-team-profile][aria-controls="${drawer.id}"]`,
    );
    const closeButtons = drawerShell.querySelectorAll(
      "[data-team-drawer-close]",
    );
    let activeTrigger = null;
    let closeTimer;

    function closeDrawer() {
      clearTimeout(closeTimer);
      drawerShell.classList.remove("is-open");
      document.body.classList.remove("team-drawer-open");
      triggers.forEach((trigger) =>
        trigger.setAttribute("aria-expanded", "false"),
      );

      closeTimer = window.setTimeout(() => {
        drawerShell.hidden = true;
        drawerContent.replaceChildren();
      }, 450);

      activeTrigger?.focus();
      activeTrigger = null;
    }

    function openDrawer(trigger) {
      const profileTemplate = document.getElementById(
        trigger.dataset.teamProfile,
      );
      if (!profileTemplate) return;

      clearTimeout(closeTimer);
      drawerContent.replaceChildren(profileTemplate.content.cloneNode(true));
      triggers.forEach((item) => item.setAttribute("aria-expanded", "false"));
      trigger.setAttribute("aria-expanded", "true");
      activeTrigger = trigger;
      drawerShell.hidden = false;
      document.body.classList.add("team-drawer-open");

      requestAnimationFrame(() => {
        drawerShell.classList.add("is-open");
        drawer.focus();
      });
    }

    triggers.forEach((trigger) => {
      trigger.addEventListener("click", () => openDrawer(trigger));
    });
    closeButtons.forEach((button) =>
      button.addEventListener("click", closeDrawer),
    );

    drawerShell.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeDrawer();
        return;
      }

      if (event.key !== "Tab") return;

      const focusable = Array.from(
        drawer.querySelectorAll(
          'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])',
        ),
      ).filter((element) => !element.closest("[hidden]"));
      if (!focusable.length) return;

      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    });
  });

  // Toggle read moe for header

  document.querySelectorAll(".expand").forEach((container) => {
    const text = container.querySelector("p");

    const fullText = text.textContent.trim();
    const words = fullText.split(/\s+/);
    const wordLimit = 30;

    // Don't do anything if text is already 30 words or fewer
    if (words.length <= wordLimit) {
      return;
    }

    const truncatedText = words.slice(0, wordLimit).join(" ") + "...";

    // Create the Read more button
    const toggle = document.createElement("button");

    toggle.type = "button";
    toggle.className = "text-expand-toggle";
    toggle.textContent = "Read more";

    function setMobileState() {
      if (window.innerWidth <= 767) {
        text.innerHTML = truncatedText;
        text.appendChild(toggle);

        // Allow the browser to calculate the collapsed height
        container.style.maxHeight = `${container.scrollHeight}px`;

        requestAnimationFrame(() => {
          container.classList.remove("is-expanded");
        });
      } else {
        container.classList.remove("is-expanded");
        container.style.maxHeight = "none";

        text.textContent = fullText;
      }
    }

    function expand() {
      // Get current collapsed height
      container.style.maxHeight = `${container.scrollHeight}px`;

      // Change content
      text.textContent = fullText;
      text.appendChild(toggle);

      toggle.textContent = "Read less";

      // Force browser to calculate the new height
      requestAnimationFrame(() => {
        container.style.maxHeight = `${container.scrollHeight}px`;
        container.classList.add("is-expanded");
      });
    }

    function collapse() {
      // Set current expanded height first
      container.style.maxHeight = `${container.scrollHeight}px`;

      requestAnimationFrame(() => {
        text.innerHTML = truncatedText;
        text.appendChild(toggle);

        toggle.textContent = "Read more";

        requestAnimationFrame(() => {
          container.style.maxHeight = `${container.scrollHeight}px`;
          container.classList.remove("is-expanded");
        });
      });
    }

    toggle.addEventListener("click", () => {
      if (container.classList.contains("is-expanded")) {
        collapse();
      } else {
        expand();
      }
    });

    setMobileState();

    window.addEventListener("resize", () => {
      setMobileState();
    });
  });
});
