@extends('layouts.master')

@section('content')
    <div class="title">Middle Earth Map</div>
    <div id="map-canvas" style="width: 100%; height: 500px; background-color:#A7906E !important;"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.key') }}"></script>
<script type="text/javascript">
    var mapImage = {
        path : '/maps/middle-earth-map.jpg',
        getUrl : function (z,n,b) {
            return this.path +
                '?zoom=' + z +
                '&x=' + n.x +
                '&y=' + (b - n.y - 1) +
                '&v=' + (new Date).getTime();
        }
    };

    var middleEarthMapType = new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            var normalizedCoord = getNormalizedCoord(coord, zoom);
            if (!normalizedCoord) { return null; }
            var bound = Math.pow(2, zoom);
            return mapImage.getUrl(zoom, normalizedCoord, bound);
        },
        tileSize: new google.maps.Size(500, 500),
        maxZoom: 5,
        minZoom: 0,
        radius: 100,
        name: 'Middle Earth'
    });

    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(-89.72913337211915, 80.16425000000005),
            zoom: 1,
            streetViewControl: false,
            mapTypeControlOptions: {
                mapTypeIds: ['middleearth']
            }
        };

        var map = new google.maps.Map(
            document.getElementById('map-canvas'),
            mapOptions
        );

        var logCenterChange = function() {
            //console.log(map.getCenter());
        };

        map.mapTypes.set('middleearth', middleEarthMapType);
        map.setMapTypeId('middleearth');
        google.maps.event.addListener(map, 'center_changed', logCenterChange);
    }

    function getNormalizedCoord(coord, zoom) {
        var y = coord.y,
            x = coord.x,
            tileRange = 1 << zoom;

        if (y < 0 || y >= tileRange) { return null; }

        if (x < 0 || x >= tileRange) {
            x = (x % tileRange + tileRange) % tileRange;
        }

        return { x: x, y: y };
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
