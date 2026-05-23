@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('Zone Details') }}</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.zones.edit', $zone) }}" class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('admin.zones.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('Name') }}:</strong>
                        <div>{{ $zone->name }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('Active') }}:</strong>
                        <div>
                            @if ($zone->is_active)
                                <span class="badge bg-success">{{ __('Yes') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('No') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>{{ __('Points') }}:</strong>
                        <div>
                            <span class="badge bg-info">{{ is_array($zone->polygon) ? count($zone->polygon) : 0 }}</span>
                        </div>
                    </div>
                </div>


                <div class="mb-0">
                    <strong class="d-block mb-2">{{ __('Zone on Map') }}:</strong>
                    <div id="zone-show-map"
                        style="height: 420px; width: 100%; border: 1px solid #dee2e6; border-radius: 0.375rem; overflow: hidden;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Deliveries') }}</h6>
            </div>
            <div class="card-body">
                @php
                    $deliveries = $zone->deliveries ?? collect();
                @endphp

                @if ($deliveries->isEmpty())
                    <div class="text-muted">{{ __('No deliveries assigned to this zone.') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td class="text-center align-middle">{{ $delivery->id }}</td>
                                        <td class="align-middle">{{ optional($delivery->user)->name ?? '-' }}</td>
                                        <td class="align-middle">{{ optional($delivery->user)->email ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<style>
    /* Prevent global Bootstrap/Argon img rules from breaking Leaflet tiles */
    .leaflet-container img {
        max-width: none !important;
        max-height: none !important;
    }
</style>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var polygon = @json($zone->polygon ?? []);
    var defaultCenter = [30.0444, 31.2357];

    var map = L.map('zone-show-map').setView(defaultCenter, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    function normalizePoints(arr) {
        if (!Array.isArray(arr)) return [];
        return arr.map(function(p) {
            var lat = (p && typeof p === 'object') ? (p.lat ?? p[0]) : null;
            var lng = (p && typeof p === 'object') ? (p.lng ?? p[1]) : null;
            return { lat: parseFloat(lat), lng: parseFloat(lng) };
        }).filter(function(p) {
            return Number.isFinite(p.lat) && Number.isFinite(p.lng);
        });
    }

    var points = normalizePoints(polygon);
    if (points.length >= 3) {
        var latlngs = points.map(function(p) { return [p.lat, p.lng]; });
        var layer = L.polygon(latlngs, { color: '#0d6efd', fillOpacity: 0.2 }).addTo(map);
        try { map.fitBounds(layer.getBounds(), { padding: [20, 20] }); } catch (e) {}
    } else if (points.length > 0) {
        // If not a valid polygon yet, show markers
        points.forEach(function(p) { L.marker([p.lat, p.lng]).addTo(map); });
        try { map.fitBounds(L.latLngBounds(points.map(function(p){ return [p.lat, p.lng]; })), { padding: [20, 20] }); } catch (e) {}
    }

    // Ensure proper render in dashboard layouts
    function safeInvalidate() { try { map.invalidateSize(true); } catch (e) {} }
    requestAnimationFrame(function() {
        safeInvalidate();
        setTimeout(safeInvalidate, 250);
        setTimeout(safeInvalidate, 800);
    });
});
</script>
@endpush

