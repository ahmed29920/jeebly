@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Slider') }}
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Sliders') }}</h6>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="sliders-table_length"></div>
                    <div id="sliders-table_filter"></div>
                </div>
                <table id="sliders-table" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('ID') }}</th>
                            <th class="text-center">{{ __('Image') }}</th>
                            <th class="text-center">{{ __('Active') }}</th>
                            <th class="text-center">{{ __('Created At') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $slider)
                            <tr>
                                <td class="align-content-center text-center">{{ $slider->id }}</td>
                                <td class="align-content-center text-center">
                                    @if($slider->image_url)
                                        <img src="{{ $slider->image_url }}" alt="Slider Image" 
                                            class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    @if($slider->is_active)
                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="align-content-center text-center">
                                    {{ $slider->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="align-content-center text-center">
                                    <a href="{{ route('admin.sliders.show', $slider->id) }}"
                                        class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}"
                                        class="text-warning mx-2 btn-sm"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-btn text-danger bg-white border-0 btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sliders-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-6"l><"col-md-6"f>>rtip',
            });
        });
        handleDeleteAjax('.delete-btn', 'Slider has been deleted successfully.');
    </script>
@endpush

