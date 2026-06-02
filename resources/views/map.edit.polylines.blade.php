@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Leaflet Draw CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        #map {
            height: 90vh;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <!-- Map -->
    <div id="map"></div>

    {{-- Modal Form Input Untuk Point --}}
    <div class="modal" tabindex="-1" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@if(isset($polyline)) Edit Polyline @else Create Polyline @endif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if(isset($polyline))
                <form action="{{ route('polylines.update', $polyline->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                @else
                <form action="{{ route('polylines.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail" width="400">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">@if(isset($polyline)) Update @else Save @endif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- leaflet draw js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

{{-- JQuery Js --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('scripts')
    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);


        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                polyline: true,
                polygon: false,
                rectangle: false,
                circle: false,
                marker: false
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });

        map.addControl(drawControl);
        // Handle created feature (when user finishes drawing)
        map.on(L.Draw.Event.CREATED, function (e) {
            var layer = e.layer;
            var geometry = layer.toGeoJSON().geometry;

            // set geometry textarea as GeoJSON geometry string
            document.getElementById('geometry_polyline').value = JSON.stringify(geometry);

            drawnItems.addLayer(layer);

            // show modal to enter attributes
            var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        });

        // Handle edits: update geometry field with last edited layer
        map.on(L.Draw.Event.EDITED, function (e) {
            e.layers.eachLayer(function (layer) {
                var geometry = layer.toGeoJSON().geometry;
                document.getElementById('geometry_polyline').value = JSON.stringify(geometry);
            });
        });
        // GeoJSON Polylines
        var polylines = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);

                layer.on('click', function(e) {
                    var props = feature.properties || {};
                    var popup_content = '<strong>' + (props.name || '') + '</strong><br>' + (props.description || '');
                    layer.bindPopup(popup_content).openPopup();
                });
            }
        });
        @if(isset($polyline))
            $.getJSON("{{ route('geojson_polyline', $polyline->id) }}", function(data) {
                polylines.addData(data);
                map.addLayer(polylines);

                var feature = data.features && data.features[0];
                if (feature) {
                    var layer = L.geoJSON(feature).getLayers()[0];
                    drawnItems.addLayer(layer);
                    if (layer.getBounds) {
                        map.fitBounds(layer.getBounds());
                    } else if (layer.getLatLng) {
                        map.setView(layer.getLatLng(), 15);
                    }

                    // prefill form
                    document.getElementById('geometry_polyline').value = JSON.stringify(feature.geometry);
                    document.getElementById('name').value = feature.properties.name || '';
                    document.getElementById('description').value = feature.properties.description || '';

                    // show modal to edit
                    if (typeof bootstrap !== 'undefined') {
                        var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
                        modal.show();
                    }
                }
            });
        @else
            $.getJSON("{{ route('geojson_polylines') }}", function(data) {
                polylines.addData(data);
                map.addLayer(polylines);
            });
        @endif
    </script>
@endsection
