if (window.addEventListener) {
    window.addEventListener('load', startWatch, false);
} else if (window.attachEvent) {
    window.attachEvent('onload', startWatch);
} else {
    window.onload = startWatch;
}

function startWatch() {
    if (navigator.geolocation) {
        var watchID = navigator.geolocation.watchPosition(function (position) {
            debugPosition(position);

            // 空欄のとき
            if (document.getElementById('lat') != null) {
                if (document.getElementById('lat').value.length == 0) {
                    document.getElementById('lat').value = position.coords.latitude;
                }
            }
            if (document.getElementById('lng') != null) {
                if (document.getElementById('lng').value.length == 0) {
                    document.getElementById('lng').value = position.coords.longitude;
                }
            }
            if (document.getElementById('search_lat') != null) {
                if (document.getElementById('search_lat').value.length == 0) {
                    document.getElementById('search_lat').value = position.coords.latitude;
                }
            }
            if (document.getElementById('search_lng') != null) {
                if (document.getElementById('search_lng').value.length == 0) {
                    document.getElementById('search_lng').value = position.coords.longitude;
                }
            }
        });
    }
}

function getPosition() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                debugPosition(position);
            },
            function (error) {
                console.log(error.code);
            }
        );
    }
}

function debugPosition(position) {
    console.log(position.coords.latitude + ' , ' + position.coords.longitude + ' (' + position.coords.accuracy + ')');
    console.log(position.coords.heading + ' , ' + position.coords.speed + ' , ' + position.coords.altitude + ' , ' + position.coords.altitudeAccuracy);
    console.log(position.timestamp);
}
