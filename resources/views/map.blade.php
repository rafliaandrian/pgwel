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
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPointTitle">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pointForm" action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                    <input type="hidden" name="_method" id="pointMethod" value="POST">
                    <input type="hidden" name="point_id" id="pointId">
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
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
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
                        <button type="submit" class="btn btn-primary" id="pointSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Untuk Polyline --}}
    <div class="modal" tabindex="-1" id="modalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post" enctype="multipart/form-data">
                @csrf
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
                            <input type="file" class="form-control" id="image" name="image" onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail" width="400">
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

    {{-- Modal Form Input Untuk Polygon --}}
    <div class="modal" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygon.store') }}" method="post" enctype="multipart/form-data">
                @csrf
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
                            <label for="geometry_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <div class="mb-3">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail" width="400">
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
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = JSON.stringify(drawnJSONObject.geometry);

            if (type === 'polyline') {
                // Set Value Geometry Polyline To Modal Form Input (GeoJSON geometry)
                $('#geometry_polyline').val(objectGeometry);
                $('#modalInputPolyline').modal('show');
                $('#modalInputPolyline').on('hidden.bs.modal', function () { location.reload(); });

            } else if (type === 'polygon' || type === 'rectangle') {
                // Set Value Geometry Polygon To Modal Form Input
                $('#geometry_polygon').val(objectGeometry);
                $('#modalInputPolygon').modal('show');
                $('#modalInputPolygon').on('hidden.bs.modal', function () { location.reload(); });

            } else if (type === 'marker') {
                // Set Value Geometry Point To Modal Form Input
                $('#geometry_point').val(objectGeometry);
                $('#modalInputPoint').modal('show');
                $('#modalInputPoint').on('hidden.bs.modal', function () { location.reload(); });
            }

            drawnItems.addLayer(layer);
        });

        // GeoJSON Point
        var points = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable route delete
                var routedelete = "{{ route('points.delete', ':id') }}"; routedelete = routedelete.replace(':id', feature.properties.id);
                var routeedit = "{{ route('points.edit', ':id') }}"; routeedit = routeedit.replace(':id', feature.properties.id);
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at + "<br>" +
                    "Diperbarui: " + feature.properties.updated_at;

                if (feature.properties.image) {
                    popup_content += "<br><img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                        "' alt='' class='img-thumbnail' width='300'>" +
                        "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                            "<form action='" + routedelete + "' method='post' class='m-0'>" +
                                '@csrf' + '@method("delete")' +
                                "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Point' onclick='return confirm(\"Apakah Anda yakin ingin menghapus point ini?\")'>" +
                                    "<i class='fa-solid fa-trash'></i> Delete</button>" +
                            "</form>" +
                            "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                                "<i class='fas fa-edit'></i> Edit</a>" +
                        "</div>";

                } else {
                    popup_content += "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                        "<form action='" + routedelete + "' method='post' class='m-0'>" +
                            '@csrf' + '@method("delete")' +
                            "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Feature' onclick='return confirm(\"Apakah Anda yakin ingin menghapus point ini?\")'>" +
                                "<i class='fa-solid fa-trash'></i> Delete</button>" +
                        "</form>" +
                        "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                            "<i class='fa-solid fa-pen-to-square'></i> Edit</a>" +
                    "</div>";
                }
                layer.on('click', function(e) {
                    layer.bindPopup(popup_content).openPopup();
                });
            },

        });
        $.getJSON("{{ route('geojson_points') }}", function(data) {
            points.addData(data);
            map.addLayer(points);
        });

        // GeoJSON Polyline
        var polylines = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable route delete & edit
                var routedelete = "{{ route('polylines.delete', ':id') }}"; routedelete = routedelete.replace(':id', feature.properties.id);
                var routeedit = "{{ route('polylines.edit', ':id') }}"; routeedit = routeedit.replace(':id', feature.properties.id);
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at + "<br>" +
                    "Diperbarui: " + feature.properties.updated_at;

                if (feature.properties.image) {
                    popup_content += "<br><img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                        "' alt='' class='img-thumbnail' width='300'>" + "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                            "<form action='" + routedelete + "' method='post' class='m-0'>" + '@csrf' + '@method("delete")' +
                                "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Feature' onclick='return confirm(\"Apakah Anda yakin ingin menghapus polyline ini?\")'>" +
                                    "<i class='fa-solid fa-trash'></i>" + "</button>" +
                            "</form>" +
                            "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                                "<i class='fas fa-edit'></i> Edit</a>" +
                        "</div>";

                } else {
                    popup_content += "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                        "<form action='" + routedelete + "' method='post' class='m-0'>" + '@csrf' + '@method("delete")' +
                            "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Feature' onclick='return confirm(\"Apakah Anda yakin ingin menghapus polyline ini?\")'>" +
                                "<i class='fa-solid fa-trash'></i> Delete</button>" +
                        "</form>" +
                        "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                            "<i class='fas fa-edit'></i> Edit</a>" +
                    "</div>";
                }

                layer.on('click', function(e) {
                    layer.bindPopup(popup_content).openPopup();
                });
            },

        });
        $.getJSON("{{ route('geojson_polylines') }}", function(data) {
            polylines.addData(data);
            map.addLayer(polylines);
        });

        // GeoJSON Polygon
        var polygons = L.geoJSON(null, {
            // Style

            // onEachFeature
            onEachFeature: function(feature, layer) {
                // variable route delete
                var routedelete = "{{ route('polygons.delete', ':id') }}"; routedelete = routedelete.replace(':id', feature.properties.id);
                // variable popup content
                var popup_content = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Dibuat: " + feature.properties.created_at + "<br>" +
                    "Diperbarui: " + feature.properties.updated_at;

                var routeedit = "{{ route('polygons.edit', ':id') }}"; routeedit = routeedit.replace(':id', feature.properties.id);

                if (feature.properties.image) {
                    popup_content += "<br><img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                        "' alt='' class='img-thumbnail' width='300'>" + "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                            "<form action='" + routedelete + "' method='post' class='m-0'>" + '@csrf' + '@method("delete")' + "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Feature' onclick='return confirm(\"Apakah Anda yakin ingin menghapus polygon ini?\")'>" +
                                "<i class='fa-solid fa-trash'></i> Delete</button>" +
                            "</form>" +
                            "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                                "<i class='fas fa-edit'></i> Edit</a>" +
                        "</div>";
                } else {
                    popup_content += "<br><div class='d-flex gap-2 flex-wrap align-items-start'>" +
                        "<form action='" + routedelete + "' method='post' class='m-0'>" + '@csrf' + '@method("delete")' + "<button type='submit' class='btn btn-sm btn-danger w-100' title='Delete Feature' onclick='return confirm(\"Apakah Anda yakin ingin menghapus polygon ini?\")'>" +
                            "<i class='fa-solid fa-trash'></i> Delete</button>" +
                        "</form>" +
                        "<a href='" + routeedit + "' class='btn btn-sm btn-warning text-white w-100'>" +
                            "<i class='fas fa-edit'></i> Edit</a>" +
                    "</div>";
                }

                layer.on('click', function(e) {
                    layer.bindPopup(popup_content).openPopup();
                });
            },

        });
        $.getJSON("{{ route('geojson_polygons') }}", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);
        });

        // Control Layer
        var baseMaps = {

        };

        var overlayMaps = {
            "Point": points,
            "Polyline": polylines,
            "Polygon": polygons,
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
    </script>
@endsection
