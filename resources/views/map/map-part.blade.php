<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <div id="cursor_position"></div>
        <br>
        <div id="map" class="map"></div>
    </div>
</div>

<script>
    window.onload = function () {
        @include('map.map-common')

        map.addOverlay(makeMarkerOverlay(icon_url, [defLng, defLat]));
    }
</script>
