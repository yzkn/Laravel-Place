
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <form id="map_area" action="/place" method="post">
            {{ csrf_field() }}
            <div class="input-group">
                <input type="text" id="desc" name="desc" placeholder="名称" class="form-control" value="" />
                <input type="text" id="owner" name="owner" placeholder="オーナー" class="form-control" value="" />
                <input type="text" id="lat" name="lat" placeholder="緯度" class="form-control" value="" />
                <input type="text" id="lng" name="lng" placeholder="経度" class="form-control" value="" />
                <input type="submit" class="btn btn-primary" value="地点をDBに追加する">
            </div>
        </form>
    </div>
</div>

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

        @foreach($items as $item)
            map.addOverlay(makeMarkerOverlay(icon_url, [{{$item->lng}}, {{$item->lat}}]));
        @endforeach
    }
</script>
