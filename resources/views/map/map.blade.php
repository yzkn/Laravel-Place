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

    var points = [
        [139.767052, 35.681167],
        [139.773114, 35.698353]
    ];

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
                // maxZoom: 18,
                zoom: 14
            })
        });

        //ズームスライダー表示
        map.addControl(new ol.control.ZoomSlider());

        // マーカー表示
        var markers = [];
        var i = 0;
        // points.forEach(point => {
        @foreach($items as $item)
        markers[i] = new ol.Feature({
            // geometry: new ol.geom.Point(ol.proj.fromLonLat(point)),
            geometry: new ol.geom.Point(ol.proj.fromLonLat([{{$item->lng}}, {{$item->lat}}])),
        });
        map.addLayer(new ol.layer.Vector({
            source: new ol.source.Vector({
                features: [markers[i]]
            })
        }));
        // });
        i++;
        @endforeach
    }

</script>
