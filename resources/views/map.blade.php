@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
body{
    margin:0;
}
#map{
    height:calc(100vh - 50px);
}
</style>
@endsection


@section('content')

<div id="map"></div>

<!-- MODAL POINT -->
<div class="modal" tabindex="-1" id="modalInputPoint">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('points.store') }}" method="post">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Fill Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Geometry</label>
                        <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- MODAL POLYLINE -->
<div class="modal" tabindex="-1" id="modalInputPolyline">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Polyline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('polylines.store') }}" method="post">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Fill name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Geometry</label>
                        <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- MODAL POLYGON -->
<div class="modal" tabindex="-1" id="modalInputPolygon">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Polygon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('polygon.store') }}" method="post">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Fill name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Geometry</label>
                        <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://unpkg.com/@terraformer/wkt"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

var map = L.map('map').setView([-7.7956,110.3695],12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:19,
    attribution:'© OpenStreetMap'
}).addTo(map);


/* Digitize Function */

var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    draw:{
        position:'topleft',
        polyline:true,
        polygon:true,
        rectangle:true,
        circle:false,
        marker:true,
        circlemarker:false
    },
    edit:false
});

map.addControl(drawControl);


map.on('draw:created', function(e){

    var type = e.layerType,
        layer = e.layer;

    console.log(type);

    var drawnJSONObject = layer.toGeoJSON();
    var objectGeometry  = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

    console.log(drawnJSONObject);
    console.log(objectGeometry);


    if(type === 'polyline'){

        $('#geometry_polyline').val(objectGeometry);
        $('#modalInputPolyline').modal('show');

        $('#modalInputPolyline').on('hidden.bs.modal', function () {
            location.reload();
        });

    }
    else if(type === 'polygon' || type === 'rectangle'){

        $('#geometry_polygon').val(objectGeometry);
        $('#modalInputPolygon').modal('show');

        $('#modalInputPolygon').on('hidden.bs.modal', function () {
            location.reload();
        });

    }
    else if(type === 'marker'){

        $('#geometry_point').val(objectGeometry);
        $('#modalInputPoint').modal('show');

        $('#modalInputPoint').on('hidden.bs.modal', function () {
            location.reload();
        });

    }
    else{
        console.log('__undefined__');
    }

    drawnItems.addLayer(layer);

}); // ✅ Fix: kurung tutup draw:created


// GeoJSON Points
var points = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
                            "Deskripsi: " + feature.properties.description + "<br>" +
                            "Dibuat: " + feature.properties.created_at + "<br>";
        layer.on({
            click: function(e) {
                points.bindPopup(popup_content);
                layer.openPopup();
            }
        });
    }
});

$.getJSON("{{ route('geojson_points') }}", function(data) {
    points.addData(data);
    map.addLayer(points);
});


// Base Maps
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
});

var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles © Esri'
});

var rupabumiindonesia = L.tileLayer('https://tile.openstreetmap.id/styles/rubi/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap Indonesia'
});

// Tambahkan salah satu basemap default ke map
osm.addTo(map);


// GeoJSON Points
var points = L.geoJSON(null, {
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
                            "Deskripsi: " + feature.properties.description + "<br>" +
                            "Dibuat: " + feature.properties.created_at + "<br>";
        layer.on({
            click: function(e) {
                points.bindPopup(popup_content);
                layer.openPopup();
            }
        });
    }
});

$.getJSON("{{ route('geojson_points') }}", function(data) {
    points.addData(data);
    map.addLayer(points);
});


// GeoJSON Polylines
var polylines = L.geoJSON(null, {
    style: {
        color: 'blue',
        weight: 3
    },
    onEachFeature: function(feature, layer) {
        var tooltip_content = "Nama: " + feature.properties.name + "<br>" +
                              "Deskripsi: " + feature.properties.description + "<br>" +
                              "Dibuat: " + feature.properties.created_at + "<br>";
        layer.bindTooltip(tooltip_content, {
            sticky: true
        });
    }
});

$.getJSON("{{ route('geojson_polylines') }}", function(data) {
    polylines.addData(data);
    map.addLayer(polylines);
});


// GeoJSON Polygons
var polygons = L.geoJSON(null, {
    style: {
        color: 'red',
        weight: 2,
        fillOpacity: 0.3
    },
    onEachFeature: function(feature, layer) {
        var popup_content = "Nama: " + feature.properties.name + "<br>" +
                            "Deskripsi: " + feature.properties.description + "<br>" +
                            "Dibuat: " + feature.properties.created_at + "<br>";
        layer.on({
            click: function(e) {
                polygons.bindPopup(popup_content);
                layer.openPopup();
            }
        });
    }
});

$.getJSON("{{ route('geojson_polygons') }}", function(data) {
    polygons.addData(data);
    map.addLayer(polygons);
});


// Control Layer
var baseMaps = {
    "OpenStreetMap": osm,
    "Esri World Imagery": Esri_WorldImagery,
    "Rupa Bumi Indonesia": rupabumiindonesia,
};

var overlayMaps = {
    "Points": points,
    "Polylines": polylines,
    "Polygons": polygons,
};

var controllayer = L.control.layers(baseMaps, overlayMaps);
controllayer.addTo(map);
</script>

@endsection
