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
    var isMobileMap = window.matchMedia("(max-width: 780px)").matches;
    var map = new google.maps.Map(mapElement, {
      center: { lat: 51.5072, lng: -0.1276 },
      fullscreenControl: false,
      mapTypeControl: false,
      minZoom: isMobileMap ? 0 : 3,
      restriction: {
        latLngBounds: {
          east: 180,
          north: 85,
          south: -60,
          west: -180,
        },
        strictBounds: !isMobileMap,
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
      var isMobile = window.matchMedia("(max-width: 780px)").matches;

      if (officeIndexes.length === 1) {
        map.setCenter(officeMarkers[officeIndexes[0]].position);
        map.setZoom(isMobile ? 4 : 6);
        return;
      }

      if (officeIndexes.length > 1) {
        var padding = 48;

        if (isMobile) {
          var intro = stage.querySelector(".office-locations__intro");
          var legend = stage.querySelector(".office-locations__legend");
          padding = {
            top: intro ? intro.offsetTop + intro.offsetHeight + 32 : 64,
            right: 64,
            bottom: legend ? stage.clientHeight - legend.offsetTop + 32 : 64,
            left: 64,
          };
        }

        map.fitBounds(officeBounds, padding);
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
              anchor: new google.maps.Point(12, 22),
              scaledSize: new google.maps.Size(36, 36),
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
      $(".footer-details-wrapper summary").off("click.footerToggle");

      footerDetails.each(function () {
        this.open = Boolean(this.closest(".footer-contact"));
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
    var menuContent = $drawer.find(".mm-menu-content").get(0);
    var rootMenu = menuContent
      ? menuContent.querySelector(".mega-menu")
      : null;
    var panelItems = [];

    if (rootMenu) {
      panelItems = Array.from(rootMenu.children).filter(function (item) {
        return item.matches(".mega-menu-item-has-children") &&
          item.querySelector(":scope > ul.mega-sub-menu");
      });

      panelItems.forEach(function (item) {
        var link = item.querySelector(":scope > a.mega-menu-link");
        var submenu = item.querySelector(":scope > ul.mega-sub-menu");

        if (!link || !submenu) return;

        link.setAttribute("aria-expanded", "false");
        link.setAttribute("aria-haspopup", "true");
        submenu.setAttribute("aria-hidden", "true");
        submenu.inert = true;

        var backItem = document.createElement("li");
        backItem.className = "mm-panel-back-item";

        var backButton = document.createElement("button");
        backButton.className = "mm-panel-back";
        backButton.type = "button";
        backButton.setAttribute("aria-label", "Back to " + link.textContent.trim());

        var backLabel = document.createElement("span");
        backLabel.textContent = link.textContent.trim();
        backButton.appendChild(backLabel);
        backItem.appendChild(backButton);
        submenu.insertBefore(backItem, submenu.firstChild);

        var firstMenuItem = submenu.querySelector(
          ".mega-menu-item:not(.mega-menu-column):not(.nav-cta)",
        );
        if (firstMenuItem) {
          firstMenuItem.classList.add("mm-panel-first-item");
        }

        if (/expertise/i.test(link.textContent)) {
          submenu
            .querySelectorAll(".mega-menu-column")
            .forEach(function (column, index) {
              var columnMenu = column.querySelector(
                ":scope > ul.mega-sub-menu",
              );
              var firstLink = columnMenu
                ? columnMenu.querySelector(":scope > li > a.mega-menu-link")
                : null;

              if (!columnMenu || !firstLink) return;

              var category = firstLink.textContent.match(
                /\b(B2C|B2B|Functions)\b/i,
              );
              var labels = ["B2C", "B2B", "Functions"];
              var accordion = document.createElement("button");
              accordion.className = "mm-accordion-toggle";
              accordion.type = "button";
              accordion.setAttribute("aria-expanded", "false");
              accordion.textContent = category
                ? category[0]
                : labels[index] || firstLink.textContent.trim();
              column.classList.add("mm-expertise-accordion");
              column.insertBefore(accordion, columnMenu);
            });
        }
      });

      menuContent.addEventListener(
        "click",
        function (event) {
          var backButton = event.target.closest(".mm-panel-back");
          if (backButton && menuContent.contains(backButton)) {
            event.preventDefault();
            event.stopPropagation();
            var panelItem = backButton.closest(".mm-panel-back-item")
              .parentElement.parentElement;
            var backParentLink = panelItem.querySelector(
              ":scope > a.mega-menu-link",
            );
            menuContent.classList.remove("has-open-panel");
            menuContent.scrollLeft = 0;
            backParentLink.focus({ preventScroll: true });
            resetMobilePanels();
            return;
          }

          var accordionButton = event.target.closest(".mm-accordion-toggle");
          if (accordionButton && menuContent.contains(accordionButton)) {
            event.preventDefault();
            event.stopPropagation();
            var accordion = accordionButton.closest(".mm-expertise-accordion");
            var isExpanded = accordion.classList.toggle("is-expanded");
            accordionButton.setAttribute("aria-expanded", String(isExpanded));
            return;
          }

          var parentLink = event.target.closest(
            ".mega-menu > li.mega-menu-item-has-children > a.mega-menu-link",
          );
          if (
            !parentLink ||
            !rootMenu ||
            parentLink.parentElement.parentElement !== rootMenu
          ) {
            return;
          }

          event.preventDefault();
          event.stopImmediatePropagation();
          panelItems.forEach(function (item) {
            item.classList.remove("mm-panel-open");
            var submenu = item.querySelector(":scope > ul.mega-sub-menu");
            submenu.setAttribute("aria-hidden", "true");
            submenu.inert = true;
            item
              .querySelector(":scope > a.mega-menu-link")
              .setAttribute("aria-expanded", "false");
          });

          var activeItem = parentLink.parentElement;
          var activeSubmenu = activeItem.querySelector(
            ":scope > ul.mega-sub-menu",
          );
          activeItem.classList.add("mm-panel-open");
          parentLink.setAttribute("aria-expanded", "true");
          activeSubmenu.setAttribute("aria-hidden", "false");
          activeSubmenu.inert = false;
          menuContent.classList.add("has-open-panel");
          menuContent.scrollLeft = 0;
          activeSubmenu
            .querySelector(".mm-panel-back")
            .focus({ preventScroll: true });
        },
        true,
      );
    }

    var resetMobilePanels = function () {
      panelItems.forEach(function (item) {
        item.classList.remove("mm-panel-open");
        var submenu = item.querySelector(":scope > ul.mega-sub-menu");
        submenu.setAttribute("aria-hidden", "true");
        submenu.inert = true;
        item
          .querySelector(":scope > a.mega-menu-link")
          .setAttribute("aria-expanded", "false");
      });

      if (menuContent) {
        menuContent.classList.remove("has-open-panel");
        menuContent
          .querySelectorAll(".mm-expertise-accordion")
          .forEach(function (item) {
            item.classList.remove("is-expanded");
            item
              .querySelector(".mm-accordion-toggle")
              .setAttribute("aria-expanded", "false");
          });
      }
    };

    var openMenu = function () {
      resetMobilePanels();
      $drawer.addClass("is-open").attr("aria-hidden", "false");
      $overlay.prop("hidden", false);
      $("body").addClass("mm-locked");
      $toggle.attr("aria-expanded", "true");
    };

    var closeMenu = function () {
      resetMobilePanels();
      $drawer.removeClass("is-open").attr("aria-hidden", "true");
      $overlay.prop("hidden", true);
      $("body").removeClass("mm-locked");
      $toggle.attr("aria-expanded", "false");
      $toggle.trigger("focus");
    };

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

// Hero video: clicking the inline video (or play button) opens it in a modal
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".homepage-hero-image.has-video").forEach((hero) => {
    const modal = document.getElementById(hero.dataset.heroVideoModal);
    if (!modal) {
      return;
    }

    const modalVideo = modal.querySelector(".homepage-hero-video-modal-video");

    function openModal() {
      modal.classList.add("is-open");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
      modalVideo.currentTime = 0;
      modalVideo.play().catch(() => {});
    }

    function closeModal() {
      modal.classList.remove("is-open");
      modal.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
      modalVideo.pause();
      modalVideo.currentTime = 0;
    }

    hero.addEventListener("click", openModal);

    modal.querySelectorAll("[data-hero-video-modal-close]").forEach((el) => {
      el.addEventListener("click", closeModal);
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && modal.classList.contains("is-open")) {
        closeModal();
      }
    });
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

    document.addEventListener("click", (event) => {
      const trigger = event.target.closest(
        `[data-team-profile][aria-controls="${drawer.id}"]`,
      );
      if (trigger) openDrawer(trigger);
    });
    document.addEventListener("keydown", (event) => {
      const trigger = event.target.closest(
        `[data-team-profile][aria-controls="${drawer.id}"]`,
      );
      if (!trigger || trigger.tagName === "BUTTON") return;
      if (event.key !== "Enter" && event.key !== " ") return;
      event.preventDefault();
      openDrawer(trigger);
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

  document.querySelectorAll(".sectors-grid").forEach((grid) => {
    const $grid = jQuery(grid);
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const cards = Array.from(grid.children);
    const mobileQuery = window.matchMedia("(max-width: 760px)");

    function syncSectorsSlider() {
      if ($grid.hasClass("slick-initialized")) {
        $grid.slick("unslick");
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
            "<div class=\"sectors-column\"></div>",
          );
        }
      }

      $grid.slick({
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
      if (!$grid.hasClass("slick-initialized") || event.target !== grid || !["ArrowLeft", "ArrowRight"].includes(event.key)) {
        return;
      }
      event.preventDefault();
      $grid.slick(event.key === "ArrowLeft" ? "slickPrev" : "slickNext");
    });
  });

  // Keep the original nodes so expanding preserves event listeners and form state.
  const expandableContainers = Array.from(
    document.querySelectorAll(".expand"),
  ).filter((container) => !container.parentElement.closest(".expand"));
  expandableContainers.forEach((container) => {
    const originalContent = Array.from(container.childNodes);
    const fullText = (container.innerText || container.textContent).trim();
    const words = fullText.split(/\s+/);
    const wordLimit = 30;
    const mobileQuery = window.matchMedia("(max-width: 767px)");
    const reducedMotionQuery = window.matchMedia(
      "(prefers-reduced-motion: reduce)",
    );
    let heightAnimation;

    if (!fullText || words.length <= wordLimit) {
      return;
    }

    const truncatedText = words.slice(0, wordLimit).join(" ") + "...";

    const toggle = document.createElement("button");

    toggle.type = "button";
    toggle.className = "text-expand-toggle";
    toggle.textContent = "Read more";
    toggle.setAttribute("aria-expanded", "false");

    if (container.id) {
      toggle.setAttribute("aria-controls", container.id);
    }

    function showCollapsedContent() {
      const text = document.createElement("span");
      text.textContent = truncatedText;
      container.replaceChildren(text, toggle);
      container.classList.remove("is-expanded");
      toggle.textContent = "Read more";
      toggle.setAttribute("aria-expanded", "false");
    }

    function showExpandedContent() {
      container.replaceChildren(...originalContent);
      container.appendChild(toggle);
      container.classList.add("is-expanded");
      toggle.textContent = "Read less";
      toggle.setAttribute("aria-expanded", "true");
    }

    function setMobileState() {
      if (heightAnimation) {
        heightAnimation.cancel();
        heightAnimation = null;
      }

      if (mobileQuery.matches) {
        showCollapsedContent();
      } else {
        container.classList.remove("is-expanded");
        container.replaceChildren(...originalContent);
      }
    }

    toggle.addEventListener("click", () => {
      const startHeight = container.getBoundingClientRect().height;
      const hadFocus = document.activeElement === toggle;

      if (heightAnimation) {
        heightAnimation.cancel();
        heightAnimation = null;
      }

      if (container.classList.contains("is-expanded")) {
        showCollapsedContent();
      } else {
        showExpandedContent();
      }

      if (hadFocus) {
        toggle.focus({ preventScroll: true });
      }

      const endHeight = container.getBoundingClientRect().height;

      if (!reducedMotionQuery.matches && startHeight !== endHeight) {
        const animation = container.animate(
          [
            { height: `${startHeight}px`, boxSizing: "border-box", overflow: "hidden" },
            { height: `${endHeight}px`, boxSizing: "border-box", overflow: "hidden" },
          ],
          { duration: 350, easing: "cubic-bezier(0.4, 0, 0.2, 1)" },
        );
        heightAnimation = animation;
        animation.onfinish = () => {
          if (heightAnimation === animation) {
            heightAnimation = null;
          }
        };
      }
    });

    setMobileState();
    mobileQuery.addEventListener("change", setMobileState);
    reducedMotionQuery.addEventListener("change", () => {
      if (reducedMotionQuery.matches && heightAnimation) {
        heightAnimation.cancel();
        heightAnimation = null;
      }
    });
  });
});
