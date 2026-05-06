<?php
/**
 * Projects archive map (theme options driven)
 */

$projects_opts = isset($args['projects_opts']) && is_array($args['projects_opts']) ? $args['projects_opts'] : [];
if (empty($projects_opts['show_projects_map'])) {
    return;
}

$map_lat   = isset($projects_opts['projects_map_center_lat']) && $projects_opts['projects_map_center_lat'] !== '' ? (float) $projects_opts['projects_map_center_lat'] : 53.349805;
$map_lng   = isset($projects_opts['projects_map_center_lng']) && $projects_opts['projects_map_center_lng'] !== '' ? (float) $projects_opts['projects_map_center_lng'] : -6.26031;
$map_zoom  = isset($projects_opts['projects_map_zoom']) && $projects_opts['projects_map_zoom'] !== '' ? (int) $projects_opts['projects_map_zoom'] : 7;
$map_provider = !empty($projects_opts['projects_map_tile_provider']) ? (string) $projects_opts['projects_map_tile_provider'] : 'cartodb_voyager';
$map_token    = !empty($projects_opts['projects_map_tile_api_key']) ? (string) $projects_opts['projects_map_tile_api_key'] : '';
$map_jawg_style_id = !empty($projects_opts['projects_map_jawg_style_id']) ? trim((string) $projects_opts['projects_map_jawg_style_id']) : '';
$map_jawg_vector_style_id = !empty($projects_opts['projects_map_jawg_vector_style_id']) ? trim((string) $projects_opts['projects_map_jawg_vector_style_id']) : '';
$map_blue = !array_key_exists('projects_map_style_blue', $projects_opts) || !empty($projects_opts['projects_map_style_blue']);
$map_height = isset($projects_opts['projects_map_height']) && (int) $projects_opts['projects_map_height'] > 0 ? (int) $projects_opts['projects_map_height'] : 533;

$section_id = 'projects-archive-map';
$locations_id = $section_id . '-locations';

$marker_icon_url = '';
if (!empty($projects_opts['projects_map_marker_image']) && is_array($projects_opts['projects_map_marker_image']) && !empty($projects_opts['projects_map_marker_image']['url'])) {
    $marker_icon_url = esc_url($projects_opts['projects_map_marker_image']['url']);
} else {
    $default_pin = get_template_directory_uri() . '/assets/images/location-pin.png';
    if (file_exists(get_template_directory() . '/assets/images/location-pin.png')) {
        $marker_icon_url = esc_url($default_pin);
    }
}

$locations_payload = [];
$project_ids = get_posts([
    'post_type'      => 'projects',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

foreach ($project_ids as $pid) {
    $lat_raw = get_field('map_latitude', $pid);
    $lng_raw = get_field('map_longitude', $pid);
    $lat = is_string($lat_raw) ? str_replace(',', '.', trim((string) $lat_raw)) : $lat_raw;
    $lng = is_string($lng_raw) ? str_replace(',', '.', trim((string) $lng_raw)) : $lng_raw;
    if ($lat === '' || $lng === '' || $lat === null || $lng === null || !is_numeric($lat) || !is_numeric($lng)) {
        continue;
    }
    $popup_image = get_the_post_thumbnail_url($pid, 'medium_large');
    if (!$popup_image) {
        $popup_image = get_the_post_thumbnail_url($pid, 'medium');
    }
    $locations_payload[] = [
        'id'    => (int) $pid,
        'title' => (string) get_the_title($pid),
        'lat'   => (float) $lat,
        'lng'   => (float) $lng,
        'image' => (string) $popup_image,
        'url'   => (string) get_permalink($pid),
    ];
}
?>

<section id="<?php echo esc_attr($section_id); ?>" class="flex overflow-hidden relative hero-slider-section">
    <div class="flex flex-col mt-[6rem] items-center w-full">
        <div class="hero-slider-container relative w-full overflow-hidden h-[<?php echo esc_attr((string) $map_height); ?>px]">
            <div
                class="hero-slider-map w-full h-full <?php echo $map_blue ? 'hero-slider-map--blue' : ''; ?>"
                data-hero-map
                data-lat="<?php echo esc_attr((string) $map_lat); ?>"
                data-lng="<?php echo esc_attr((string) $map_lng); ?>"
                data-zoom="<?php echo esc_attr((string) $map_zoom); ?>"
                data-provider="<?php echo esc_attr($map_provider); ?>"
                data-token="<?php echo esc_attr($map_token); ?>"
                <?php if ($map_jawg_style_id !== ''): ?>data-jawg-style-id="<?php echo esc_attr($map_jawg_style_id); ?>"<?php endif; ?>
                <?php if ($map_jawg_vector_style_id !== ''): ?>data-jawg-vector-style-id="<?php echo esc_attr($map_jawg_vector_style_id); ?>"<?php endif; ?>
                data-locations-id="<?php echo esc_attr($locations_id); ?>"
                <?php if ($marker_icon_url !== ''): ?>data-marker-icon="<?php echo esc_url($marker_icon_url); ?>"<?php endif; ?>
                role="application"
                aria-label="<?php echo esc_attr__('Interactive map of projects', 'matrix-starter'); ?>">
            </div>
        </div>
    </div>
</section>

<script type="application/json" id="<?php echo esc_attr($locations_id); ?>"><?php echo wp_json_encode($locations_payload); ?></script>
<script>
(function () {
  var LEAFLET_CSS = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css";
  var LEAFLET_JS  = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js";
  var MAPLIBRE_CSS = "https://unpkg.com/maplibre-gl@5.7.0/dist/maplibre-gl.css";
  var MAPLIBRE_JS  = "https://unpkg.com/maplibre-gl@5.7.0/dist/maplibre-gl.js";
  var MAPLIBRE_RTL = "https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.3.0/dist/mapbox-gl-rtl-text.js";
  var sectionId = <?php echo wp_json_encode($section_id); ?>;

  function loadLeaflet(cb) {
    if (typeof window.L !== "undefined") { cb(); return; }
    if (!document.querySelector('link[data-leaflet-css]')) {
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = LEAFLET_CSS;
      link.setAttribute("data-leaflet-css", "1");
      document.head.appendChild(link);
    }
    if (document.querySelector('script[data-leaflet-js]')) {
      var t = setInterval(function() { if (typeof window.L !== "undefined") { clearInterval(t); cb(); } }, 30);
      setTimeout(function() { clearInterval(t); }, 8000);
      return;
    }
    var script = document.createElement("script");
    script.src = LEAFLET_JS;
    script.defer = true;
    script.setAttribute("data-leaflet-js", "1");
    script.onload = cb;
    document.body.appendChild(script);
  }

  function loadMapLibre(cb) {
    if (typeof window.maplibregl !== "undefined") { cb(); return; }
    if (!document.querySelector('link[data-maplibre-css]')) {
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = MAPLIBRE_CSS;
      link.setAttribute("data-maplibre-css", "1");
      document.head.appendChild(link);
    }
    if (document.querySelector('script[data-maplibre-js]')) {
      var t = setInterval(function() { if (typeof window.maplibregl !== "undefined") { clearInterval(t); cb(); } }, 30);
      setTimeout(function() { clearInterval(t); }, 8000);
      return;
    }
    var script = document.createElement("script");
    script.src = MAPLIBRE_JS;
    script.defer = true;
    script.setAttribute("data-maplibre-js", "1");
    script.onload = function() {
      if (typeof window.maplibregl === "undefined") { cb(); return; }
      var status = window.maplibregl.getRTLTextPluginStatus && window.maplibregl.getRTLTextPluginStatus();
      if (status === "loaded") { cb(); return; }
      var done = false;
      function finish() { if (!done) { done = true; cb(); } }
      var t = setTimeout(finish, 2500);
      try {
        var p = window.maplibregl.setRTLTextPlugin(MAPLIBRE_RTL);
        if (p && typeof p.then === "function") {
          p.then(function() { clearTimeout(t); finish(); }).catch(function() { clearTimeout(t); finish(); });
        } else {
          clearTimeout(t);
          finish();
        }
      } catch (e) {
        clearTimeout(t);
        finish();
      }
    };
    document.body.appendChild(script);
  }

  function escapeHtml(str) {
    return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
  }

  function markerPopupHtml(g) {
    if (!g) return "";
    var title = String(g.title || "").trim();
    var url = String(g.url || "").trim();
    var image = String(g.image || "").trim();
    var html = '<div class="hero-map-popup" style="max-width:260px;">';
    if (url) {
      html += '<a href="' + escapeHtml(encodeURI(url)) + '" target="_self" rel="noopener" class="hero-map-popup-item">';
    } else {
      html += '<div class="hero-map-popup-item">';
    }
    if (image) {
      html += '<img src="' + escapeHtml(encodeURI(image)) + '" alt="" style="display:block;width:56px;height:56px;object-fit:cover;border-radius:6px;flex:0 0 56px;" />';
    }
    if (title) {
      html += '<span class="hero-map-popup-title">' + escapeHtml(title) + '</span>';
    }
    html += url ? '</a>' : '</div>';
    html += "</div>";
    return html;
  }

  function getGroups(container) {
    var locationsId = container.getAttribute("data-locations-id");
    var groups = [];
    if (locationsId) {
      var jsonEl = document.getElementById(locationsId);
      if (jsonEl) try { groups = JSON.parse(jsonEl.textContent || "[]"); } catch (e) {}
    }
    return groups;
  }

  function initVectorMap(container) {
    if (!container || container.dataset.heroMapInit === "1") return;
    if (typeof window.maplibregl === "undefined") return;
    var vectorStyleId = (container.getAttribute("data-jawg-vector-style-id") || "").trim();
    var token = (container.getAttribute("data-token") || "").trim();
    if (!vectorStyleId || !token) return;
    var lat = parseFloat(container.getAttribute("data-lat")) || 53.349805;
    var lng = parseFloat(container.getAttribute("data-lng")) || -6.26031;
    var zoom = parseInt(container.getAttribute("data-zoom"), 10) || 7;
    var markerIconUrl = container.getAttribute("data-marker-icon") || "";
    var groups = getGroups(container);
    var styleUrl = "https://api.jawg.io/styles/" + encodeURIComponent(vectorStyleId) + ".json?access-token=" + encodeURIComponent(token);
    var map = new maplibregl.Map({
      container: container,
      style: styleUrl,
      center: [lng, lat],
      zoom: zoom,
      minZoom: 4,
      maxBounds: [[-31, 27], [40, 71]]
    });
    map.scrollZoom.disable();
    var initialCenter = [lng, lat];
    var initialZoom = zoom;
    groups.forEach(function(g) {
      if (!g || !Number.isFinite(g.lat) || !Number.isFinite(g.lng)) return;
      var el = document.createElement("div");
      el.className = "hero-map-marker";
      if (markerIconUrl) {
        var img = document.createElement("img");
        img.src = markerIconUrl;
        img.alt = "";
        img.style.width = "40px";
        img.style.height = "50px";
        img.style.pointerEvents = "none";
        el.appendChild(img);
      } else {
        el.style.width = "24px";
        el.style.height = "24px";
        el.style.borderRadius = "50%";
        el.style.background = "#2563eb";
        el.style.border = "2px solid #fff";
        el.style.boxShadow = "0 2px 4px rgba(0,0,0,0.3)";
      }
      var marker = new maplibregl.Marker({ element: el }).setLngLat([g.lng, g.lat]);
      var popupHtml = markerPopupHtml(g);
      if (popupHtml) {
        var popup = new maplibregl.Popup({ offset: 20 }).setHTML(popupHtml);
        marker.setPopup(popup);
      }
      marker.addTo(map);
    });
    function applyVectorView() {
      map.setCenter(initialCenter);
      map.setZoom(Math.max(4, initialZoom - 2));
    }
    map.on("load", function() {
      applyVectorView();
      container.dataset.heroMapInit = "1";
      setTimeout(function() {
        map.resize();
        applyVectorView();
      }, 150);
    });
    container.addEventListener("click", function() {
      map.scrollZoom.enable();
    });
    container.addEventListener("mouseleave", function() {
      map.scrollZoom.disable();
    });
  }

  function initRasterMap(container) {
    if (!container || container.dataset.heroMapInit === "1") return;
    if (typeof window.L === "undefined") return;
    var provider = container.getAttribute("data-provider") || "osm";
    if (provider === "jawg-vector") return;
    var lat = parseFloat(container.getAttribute("data-lat")) || 53.349805;
    var lng = parseFloat(container.getAttribute("data-lng")) || -6.26031;
    var zoom = parseInt(container.getAttribute("data-zoom"), 10) || 7;
    var token = container.getAttribute("data-token") || "";
    var markerIconUrl = container.getAttribute("data-marker-icon") || "";
    var groups = getGroups(container);

    var markerIcon = null;
    if (markerIconUrl) {
      markerIcon = L.icon({
        iconUrl: markerIconUrl,
        iconSize: [40, 50],
        iconAnchor: [20, 50],
        popupAnchor: [0, -50]
      });
    }

    var map = L.map(container, { scrollWheelZoom: false, minZoom: 4 }).setView([lat, lng], Math.max(4, zoom - 2));
    var tileUrl, tileOpts = {};
    var jawgStyleId = (container.getAttribute("data-jawg-style-id") || "").trim();
    var wantsJawg = (provider === "jawg-light" || provider === "jawg-dark" || provider === "jawg-custom");
    var useOsmFallback = provider === "osm" || (wantsJawg && !token) || (provider === "jawg-custom" && !jawgStyleId);
    if (useOsmFallback) {
      tileUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
      tileOpts = { maxZoom: 19, attribution: "&copy; OpenStreetMap" };
    } else if (provider === "cartodb_voyager") {
      tileUrl = "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png";
      tileOpts = { maxZoom: 20, attribution: "&copy; OpenStreetMap &copy; CARTO" };
    } else if (provider === "jawg-custom" && jawgStyleId) {
      tileUrl = "https://tile.jawg.io/" + encodeURIComponent(jawgStyleId) + "/{z}/{x}/{y}{r}.png?access-token=" + encodeURIComponent(token);
      tileOpts = { maxZoom: 22, attribution: "&copy; Jawg" };
    } else if (provider === "jawg-dark") {
      tileUrl = "https://tile.jawg.io/jawg-dark/{z}/{x}/{y}{r}.png?access-token=" + encodeURIComponent(token);
      tileOpts = { maxZoom: 22, attribution: "&copy; Jawg" };
    } else {
      tileUrl = "https://tile.jawg.io/jawg-light/{z}/{x}/{y}{r}.png?access-token=" + encodeURIComponent(token);
      tileOpts = { maxZoom: 22, attribution: "&copy; Jawg" };
    }
    L.tileLayer(tileUrl, tileOpts).addTo(map);

    var bounds = [];
    groups.forEach(function(g) {
      if (!g || !Number.isFinite(g.lat) || !Number.isFinite(g.lng)) return;
      var marker = L.marker([g.lat, g.lng], markerIcon ? { icon: markerIcon } : {}).addTo(map);
      var popupHtml = markerPopupHtml(g);
      if (popupHtml) marker.bindPopup(popupHtml);
      bounds.push([g.lat, g.lng]);
    });
    if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30], maxZoom: 12, minZoom: Math.max(4, zoom - 2) });
    if (bounds.length === 1) map.setView(bounds[0], Math.max(zoom, 12));
    if (bounds.length === 0) map.setView([lat, lng], Math.max(4, zoom - 2));
    container.dataset.heroMapInit = "1";
    container.addEventListener("click", function() {
      map.scrollWheelZoom.enable();
    });
    container.addEventListener("mouseleave", function() {
      map.scrollWheelZoom.disable();
    });
    setTimeout(function() { map.invalidateSize(); }, 50);
    setTimeout(function() { map.invalidateSize(); }, 250);
  }

  document.addEventListener("DOMContentLoaded", function() {
    var section = document.getElementById(sectionId);
    if (!section) return;
    var container = section.querySelector("[data-hero-map]");
    if (!container) return;
    var provider = container.getAttribute("data-provider") || "";
    if (provider === "jawg-vector") {
      loadMapLibre(function() { initVectorMap(container); });
    } else {
      loadLeaflet(function() { initRasterMap(container); });
    }
  });
})();
</script>
