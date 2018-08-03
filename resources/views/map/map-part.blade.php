<p>
    <div id="cursor_position"></div>
    <br>
    <div id="map" class="map"></div>
</p>

<script>
    window.onload = function () {
        @include('map.map-common')

        map.addOverlay(makeMarkerOverlay(icon_url, [defLng, defLat]));
    }
</script>
