<p>
    <form id="map_area" action="/place" method="post">
        {{ csrf_field() }}
        <input type="text" id="desc" name="desc" placeholder="名称" value="" />
        <input type="text" id="owner" name="owner" placeholder="オーナー" value="" />
        <input type="text" id="lat" name="lat" placeholder="緯度" value="" />
        <input type="text" id="lng" name="lng" placeholder="経度" value="" />
        <input type="submit" value="地点をDBに追加する">
    </form>
</p>

<p>
    <div id="cursor_position"></div>
    <br>
    <div id="map" class="map"></div>
</p>

<script>
    window.onload = function () {
        @include('map.map-common')

        @foreach($items as $item)
            map.addOverlay(makeMarkerOverlay(icon_url, [{{$item->lng}}, {{$item->lat}}]));
        @endforeach
    }
</script>
