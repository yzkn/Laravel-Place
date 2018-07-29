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
    const defLat = 139.767052;
    const defLng = 35.681167;
    const defLoc = [defLat, defLng];
    const icon_url = 'http://dev.openlayers.org/img/marker.png';
    const precision = 6; // 小数点以下桁数

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

        // マウス座標表示
        var mousePosition = new ol.control.MousePosition({
            coordinateFormat: function(coordinate) {
                return ol.coordinate.format(coordinate, '{y}, {x}', 4);
            },
            projection: 'EPSG:4326',
            target: document.getElementById('cursor_position'),
            undefinedHTML: '&nbsp;'
        });
        map.addControl(mousePosition);

        // マウスクリックイベント
        map.on('click', function(evt) {
            var coordinate = evt.coordinate;
            var stringifyFunc = ol.coordinate.createStringXY(precision);
            var outstr = stringifyFunc(ol.proj.transform(coordinate, "EPSG:3857", "EPSG:4326"));
            document.getElementById('lat').value = outstr.split(', ')[1];
            document.getElementById('lng').value = outstr.split(', ')[0];
        });

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
