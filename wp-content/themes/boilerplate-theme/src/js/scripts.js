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

    if (window.innerWidth > 768) {
      // Desktop: Always keep open and prevent closing
      footerDetails.each(function () {
        this.open = true;
      });

      // Prevent clicking on summary from closing
      $(".footer-details-wrapper summary")
        .off("click.footerToggle")
        .on("click.footerToggle", function (e) {
          if (window.innerWidth > 768) {
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

  //mobile menu functions
  var $drawer = $("#mm-drawer");
  var $toggle = $(".mm-toggle");
  var $overlay = $(".mm-overlay");
  var $close = $drawer.find(".mm-close");
  var $panels = $drawer.find(".mm-panels");

  if (!$drawer.length || !$toggle.length || !$overlay.length || !$panels.length)
    return;

  var panelStack = ["root"];

  function setActivePanel(panelId) {
    var $all = $panels.find(".mm-panel");
    $all.removeClass("mm-panel--active mm-panel--left");

    var $active = $panels.find(".mm-panel[data-panel='" + panelId + "']");
    if (!$active.length) return;

    // mark previous as left (nice slide-back feel)
    if (panelStack.length > 1) {
      var prevId = panelStack[panelStack.length - 2];
      $panels
        .find(".mm-panel[data-panel='" + prevId + "']")
        .addClass("mm-panel--left");
    }

    $active.addClass("mm-panel--active");
  }

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

    panelStack = ["root"];
    setActivePanel("root");
  }

  $toggle.on("click", openMenu);
  $overlay.on("click", closeMenu);
  $close.on("click", closeMenu);

  function makePanel(id, title, $submenuUL) {
    var $panel = $("<div/>", { class: "mm-panel", "data-panel": id });

    var $back = $("<button/>", {
      class: "mm-back",
      type: "button",
      html: "<span>‹</span> Back",
    });
    $back.on("click", function () {
      if (panelStack.length > 1) panelStack.pop();
      setActivePanel(panelStack[panelStack.length - 1]);
    });

    var $list = $submenuUL.clone(true, true);
    $list.addClass("mm-submenu");

    $panel.append($back, $list);
    $panels.append($panel);

    return $panel;
  }

  // Converts a list's direct children to "row + chevron", recursively for new panels.
  function convertList($ul) {
    $ul.children("li").each(function () {
      var $li = $(this);
      var $a = $li.children("a").first();
      var $submenu = $li.children("ul").first();

      if (!$a.length || !$submenu.length) return;

      var panelId = "p-" + Math.random().toString(16).slice(2);
      makePanel(panelId, $.trim($a.text()), $submenu);

      // Build row: [link navigates] [chevron opens submenu]
      var $row = $("<div/>", { class: "mm-row" });
      var $link = $a.clone(true, true); // keep navigation
      var $next = $("<button/>", {
        class: "mm-next",
        type: "button",
        text: "›",
      }).attr("aria-label", "Open " + $.trim($a.text()) + " submenu");

      $next.on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        var currentId =
          $panels.find(".mm-panel--active").data("panel") || "root";
        if (panelStack[panelStack.length - 1] !== currentId)
          panelStack.push(currentId);

        panelStack.push(panelId);
        setActivePanel(panelId);

        // Convert nested items inside this panel once
        var $newPanel = $panels.find(".mm-panel[data-panel='" + panelId + "']");
        var $newUL = $newPanel.find("ul").first();

        if ($newUL.length && !$newUL.data("converted")) {
          $newUL.data("converted", 1);
          convertList($newUL);
        }
      });

      $row.append($link, $next);

      // Replace li contents with row and remove submenu at this level
      $li.empty().append($row);
    });
  }

  // Init from root panel's UL
  var $rootPanel = $panels.find(".mm-panel[data-panel='root']");
  var $rootUL = $rootPanel.find("ul").first();

  if (!$rootUL.length) return;

  $rootUL.data("converted", 1);
  convertList($rootUL);
  setActivePanel("root");

  // ESC closes
  $(document).on("keydown", function (e) {
    if (e.key === "Escape" && $drawer.hasClass("is-open")) closeMenu();
  });

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
document.addEventListener('DOMContentLoaded', () => {

  document.querySelectorAll('.homepage-hero-image').forEach((hero) => {
    const video = hero.querySelector('.homepage-hero-video');
    const playButton = hero.querySelector('.homepage-hero-play');

    if (!video || !playButton) {
      return;
    }

    playButton.addEventListener('click', (event) => {
      event.stopPropagation();

      video.play();
    });

    video.addEventListener('play', () => {
      playButton.style.display = 'none';
    });

    video.addEventListener('pause', () => {
      playButton.style.display = 'flex';
    });

    video.addEventListener('ended', () => {
      playButton.style.display = 'flex';
    });
  });
});