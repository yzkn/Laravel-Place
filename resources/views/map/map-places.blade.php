
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <form id="map_area" action="/place" method="post">
            {{ csrf_field() }}
            <div class="input-group">
                <input type="text" id="desc" name="desc" placeholder="{{__('Description')}}" class="form-control" value="" />
                <input type="text" id="owner" name="owner" placeholder="{{__('Owner')}}" class="form-control" value="" />
                <input type="text" id="lat" name="lat" placeholder="{{__('Latitude')}}" class="form-control" value="" />
                <input type="text" id="lng" name="lng" placeholder="{{__('Longitude')}}" class="form-control" value="" />
                <input type="submit" class="btn btn-outline-primary" value="{{__('AddTheSpotToTheDB')}}">
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
