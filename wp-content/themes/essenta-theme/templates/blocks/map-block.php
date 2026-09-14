<?php

/**
 * Map Block Template
 */

// Get the company location ACF Google Map field
$company_location = get_field('company_location', 'option');

// Get opening hours from site options
$opening_hours = get_field('opening_hours', 'option');
?>

<section class="map-block">
  <div class="container">
    <div class="map-block__grid">
      <!-- Map Section (60%) -->
      <div class="map-block__map">
        <div style="width: 100%; height: 450px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #999;">
          <!-- Add your Google Maps embed code here -->
          [Your Map Goes Here]
        </div>
      </div>

      <!-- Opening Hours Section (40%) -->
      <div class="map-block__hours">
        <div class="hours-content">
          <h3>Opening Hours</h3>
          <?php echo wp_kses_post($opening_hours); ?>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAW0GJXmU70GtFN4p8eyw1ujFSSXhlJRY8"></script>
<script>
  (function($) {
    function new_map($el) {
      var $markers = $el.find('.marker');
      var args = {
        zoom: 16,
        center: new google.maps.LatLng(0, 0),
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var map = new google.maps.Map($el[0], args);
      map.markers = [];
      $markers.each(function() {
        add_marker($(this), map);
      });
      center_map(map);
      return map;
    }

    function add_marker($marker, map) {
      var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
      var marker = new google.maps.Marker({
        position: latlng,
        map: map
      });
      map.markers.push(marker);
      if ($marker.html()) {
        var infowindow = new google.maps.InfoWindow({
          content: $marker.html()
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map, marker);
        });
      }
    }

    function center_map(map) {
      var bounds = new google.maps.LatLngBounds();
      $.each(map.markers, function(i, marker) {
        var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
        bounds.extend(latlng);
      });
      if (map.markers.length == 1) {
        map.setCenter(bounds.getCenter());
        map.setZoom(16);
      } else {
        map.fitBounds(bounds);
      }
    }

    $(document).ready(function() {
      $('.acf-map').each(function() {
        var map = new_map($(this));
      });
    });
  })(jQuery);
</script>