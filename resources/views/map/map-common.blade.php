
        var defLat =  35.681167;
        var defLng = 139.767052;
        const icon_url = 'http://dev.openlayers.org/img/marker.png';
        const precision = 7; // 小数点以下桁数

        var map;

        if((document.getElementById('lat')!=null) && (document.getElementById('lng')!=null)){
            // 空欄でないとき
            if(document.getElementById('lat').value.length>0){
                defLat =  document.getElementById('lat').value - 0;
            }
            if(document.getElementById('lng').value.length>0){
                defLng = document.getElementById('lng').value - 0;
            }
        }

        // https://github.com/openlayers/openlayers/releases/tag/v3.20.1
        map = new ol.Map({
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
                center: ol.proj.transform([defLng, defLat], "EPSG:4326", "EPSG:3857"),
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
            if((document.getElementById('lat')!=null) && (document.getElementById('lng')!=null)){
                document.getElementById('lat').value = outstr.split(', ')[1];
                document.getElementById('lng').value = outstr.split(', ')[0];
            }
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

        setOnClick();

        function setOnClick() {
            document.getElementById('show-entered-location').addEventListener('click', showEnteredLocation, false);
            function showEnteredLocation() {
                if((document.getElementById('lat')!=null) && (document.getElementById('lng')!=null)){
                    // 空欄でないとき
                    if((document.getElementById('lat').value.length>0) && (document.getElementById('lng').value.length>0)){
                        if((!isNaN(document.getElementById('lat').value)) && (!isNaN(document.getElementById('lng').value))){
                            map.getView().setCenter(
                                ol.proj.transform([
                                    parseFloat(document.getElementById('lng').value),
                                    parseFloat(document.getElementById('lat').value)
                                ],
                                'EPSG:4326',
                                'EPSG:3857'
                                )
                            );
                            map.getView().setZoom(16);
                        }
                    }
                }
            }
        }