<p>
    <form id="map_area">
        <input type="text" id="desc" name="desc" title="名称" value="" />
        <input type="text" id="lat" name="lat" title="緯度" value="" />
        <input type="text" id="lng" name="lng" title="経度" value="" />
        <input type="button" id="add" name="add" value="地図の中心地点をDBに追加する">
    </form>
</p>

<p>
    <div id="map" class="map"></div>
</p>

<script>
    const defLat = 139.767052;
    const defLng = 35.681167;
    const defLoc = [defLat, defLng];
    const icon_url = 'http://dev.openlayers.org/img/marker.png';

    window.onload = function () {
        // https://github.com/openlayers/openlayers/releases/tag/v3.20.1
        var map = new ol.Map({
            target: "map",
            renderer: ['canvas', 'dom'],
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.XYZ({
                        attributions: [
                            new ol.Attribution({
                                html: "<a href='https://maps.gsi.go.jp/development/ichiran.html' target='_blank'>地理院タイル</a>"
                            })
                        ],
                        url: "https://cyberjapandata.gsi.go.jp/xyz/std/{z}/{x}/{y}.png",
                        projection: "EPSG:3857"
                    })
                })
            ],
            controls: ol.control.defaults({
                attributionOptions: ({
                    collapsible: false
                })
            }),
            view: new ol.View({
                projection: "EPSG:3857",
                center: ol.proj.transform(defLoc, "EPSG:4326", "EPSG:3857"),
                maxZoom: 30,
                zoom: 14
            })
        });

        //ズームスライダー表示
        map.addControl(new ol.control.ZoomSlider());

        // マーカー表示
        function makeMarkerOverlay(imgSrc, coordinate) {
            coordinate = ol.proj.transform(coordinate, "EPSG:4326","EPSG:3857");
            var imgElement = document.createElement('img');
            imgElement.setAttribute('src', imgSrc);
            var markerOverlay = new ol.Overlay({
                element: imgElement,
                position: coordinate,
                positioning: 'bottom-center'
            });
            return markerOverlay;
        }

        @foreach($items as $item)
            map.addOverlay(makeMarkerOverlay(icon_url, [{{$item->lng}}, {{$item->lat}}]));
        @endforeach
    }
</script>
