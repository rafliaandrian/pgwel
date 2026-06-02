@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
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
    </style>
@endsection

@section('content')
    <div class="edit-toolbar">
        <button id="saveButton" class="btn btn-success" title="Save"><i class="fas fa-check"></i></button>
        <a href="{{ route('peta') }}" class="btn btn-secondary" title="Cancel"><i class="fas fa-times"></i></a>
        <button id="openEditFormButton" class="btn btn-primary" title="Edit Point"><i class="fas fa-pencil-alt"></i></button>
    </div>

    <div id="map"></div>

    <div class="edit-note">
        <strong>Edit mode:</strong>
        <ul class="mb-0 ps-3">
            <li>Drag the marker to move.</li>
            <li>Click the marker or the Edit Point button to open the form.</li>
            <li>Press Save when you are finished.</li>
        </ul>
    </div>

    <form id="editPointForm" action="{{ route('points.update', $point->id) }}" method="post" enctype="multipart/form-data" class="d-none">
        @csrf
        @method('PUT')
        <input type="hidden" name="geometry_point" id="geometry_point" value="{{ json_encode($geojson) }}">
    </form>

    <div class="modal fade" id="modalEditPoint" tabindex="-1" aria-labelledby="modalEditPointLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPointLabel">Edit Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" value="{{ $point->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3">{{ $point->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Geometry</label>
                        <textarea class="form-control" id="edit-geometry" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="edit-image" name="image" onchange="document.getElementById('preview-edit-image').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                    <div class="mb-3">
                        <img src="{{ $point->image ? asset('storage/images/'.$point->image) : '' }}" alt="Preview" id="preview-edit-image" class="img-thumbnail" width="320" style="display: {{ $point->image ? 'block' : 'none' }};">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="submitEditForm">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var pointData = @json($point);
        var pointGeo = @json($geojson);
        var coordinates = [0, 0];

        if (pointGeo && pointGeo.type === 'Point' && Array.isArray(pointGeo.coordinates)) {
            coordinates = [pointGeo.coordinates[1], pointGeo.coordinates[0]];
        }

        var map = L.map('map').setView(coordinates, 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var pointMarker = L.marker(coordinates, {
            draggable: true,
        }).addTo(map).bindPopup('Drag marker to move. Click to open edit form.').openPopup();

        function updateGeometryField(latlng) {
            var geojson = {
                type: 'Point',
                coordinates: [latlng.lng, latlng.lat],
            };
            $('#geometry_point').val(JSON.stringify(geojson));
            $('#edit-geometry').val(JSON.stringify(geojson, null, 2));
        }

        updateGeometryField(pointMarker.getLatLng());

        pointMarker.on('dragend', function(event) {
            var latlng = event.target.getLatLng();
            updateGeometryField(latlng);
            pointMarker.openPopup();
        });

        pointMarker.on('click', function() {
            $('#modalEditPoint').modal('show');
        });

        $('#openEditFormButton').on('click', function() {
            $('#modalEditPoint').modal('show');
        });

        $('#submitEditForm').on('click', function() {
            var form = $('#editPointForm');
            var formData = new FormData(form[0]);
            formData.set('name', $('#edit-name').val());
            formData.set('description', $('#edit-description').val());
            formData.set('geometry_point', $('#geometry_point').val());

            var fileInput = $('#edit-image')[0];
            if (fileInput.files.length > 0) {
                formData.set('image', fileInput.files[0]);
            }

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                },
                success: function() {
                    window.location.href = '{{ route('peta') }}';
                },
                error: function() {
                    alert('Gagal menyimpan perubahan. Silakan coba lagi.');
                }
            });
        });

        $('#saveButton').on('click', function() {
            $('#submitEditForm').trigger('click');
        });

        $('#edit-image').on('change', function() {
            $('#preview-edit-image').css('display', 'block');
        });
    </script>
@endsection
