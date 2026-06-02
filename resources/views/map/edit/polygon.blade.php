@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
            height: calc(100vh - 70px);
            width: 100%;
        }

        .edit-toolbar {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .edit-toolbar .btn {
            width: 45px;
            height: 45px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.35rem;
            font-size: 1.1rem;
        }

        .edit-note {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            z-index: 1050;
            background: rgba(255,255,255,0.95);
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            max-width: 320px;
            font-size: 0.95rem;
        }

        .leaflet-control-container .leaflet-draw-toolbar {
            top: 50% !important;
            left: 1rem !important;
            transform: translateY(-50%) !important;
        }

        .leaflet-control-container .leaflet-draw-actions {
            top: 50% !important;
            left: 4.5rem !important;
            transform: translateY(-50%) !important;
        }
    </style>
@endsection

@section('content')
    <div class="edit-toolbar">
        <button id="saveButton" class="btn btn-success" title="Save"><i class="fas fa-check"></i></button>
        <a href="{{ route('peta') }}" class="btn btn-secondary" title="Cancel"><i class="fas fa-times"></i></a>
        <button id="openEditFormButton" class="btn btn-primary" title="Edit Polygon"><i class="fas fa-pencil-alt"></i></button>
    </div>

    <div id="map"></div>

    <div class="edit-note">
        <strong>Edit mode:</strong>
        <ul class="mb-0 ps-3">
            <li>Use the edit tool to move polygon vertices.</li>
            <li>Click the edit button to open the metadata form.</li>
            <li>Save when you are finished editing.</li>
        </ul>
    </div>

    <div class="modal fade" id="modalEditPolygon" tabindex="-1" aria-labelledby="modalEditPolygonLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editPolygonForm" action="{{ route('polygons.update', $polygon->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditPolygonLabel">Edit Polygon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="geometry_polygon" id="geometry_polygon" value="{{ json_encode($geojson) }}">

                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" value="{{ $polygon->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" rows="3">{{ $polygon->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Geometry</label>
                            <textarea class="form-control" id="edit-geometry" rows="4" readonly>{{ json_encode($geojson, JSON_PRETTY_PRINT) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit-image" name="image" onchange="document.getElementById('preview-edit-image').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="{{ $polygon->image ? asset('storage/images/'.$polygon->image) : '' }}" alt="Preview" id="preview-edit-image" class="img-thumbnail" width="400" style="display: {{ $polygon->image ? 'block' : 'none' }};">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('scripts')
    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });

        map.addControl(drawControl);

        function updateGeometryFields(geometry) {
            var jsonGeometry = JSON.stringify(geometry);
            $('#geometry_polygon').val(jsonGeometry);
            $('#edit-geometry').val(JSON.stringify(geometry, null, 2));
        }

        function syncPolygonGeometry() {
            var layers = drawnItems.getLayers();
            if (layers.length > 0) {
                var geometry = layers[0].toGeoJSON().geometry;
                updateGeometryFields(geometry);
            }
        }

        map.on(L.Draw.Event.EDITED, function () {
            syncPolygonGeometry();
        });

        var polygons = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);

                layer.on('click', function() {
                    $('#openEditFormButton').trigger('click');
                });
            }
        });

        $.getJSON("{{ route('geojson_polygon', $polygon->id) }}", function(data) {
            polygons.addData(data);

            if (polygons.getBounds && polygons.getBounds().isValid()) {
                map.fitBounds(polygons.getBounds());
            }

            var feature = data.features && data.features[0];
            if (feature) {
                updateGeometryFields(feature.geometry);
            }
        });

        $('#openEditFormButton').on('click', function() {
            $('#modalEditPolygon').modal('show');
        });

        $('#saveButton').on('click', function(e) {
            e.preventDefault();
            syncPolygonGeometry();
            $('#editPolygonForm').submit();
        });

        $('#edit-image').on('change', function() {
            $('#preview-edit-image').css('display', 'block');
        });
    </script>
@endsection
