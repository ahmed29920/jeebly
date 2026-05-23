@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6>{{ __('Category Details') }}</h6>
                <a href="{{ route('branch.categories.index') }}" class="btn btn-sm btn-secondary">{{ __('Back') }}</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%">{{ __('ID') }}</th>
                                <td>{{ $category->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name (EN)') }}</th>
                                <td>{{ $category->getTranslation('name', 'en') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Name (AR)') }}</th>
                                <td>{{ $category->getTranslation('name', 'ar') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Slug') }}</th>
                                <td>{{ $category->slug }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Parent') }}</th>
                                <td>
                                    @if($category->parent)
                                        {{ $category->parent->getTranslation('name','en') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Status') }}</th>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Created At') }}</th>
                                <td>{{ $category->created_at?->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Updated At') }}</th>
                                <td>{{ $category->updated_at?->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">{{ __('Children') }}</h6>
                        @if($category->children && $category->children->count())
                            <ul class="list-group">
                                @foreach($category->children as $child)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $child->getTranslation('name','en') }}
                                        <span class="badge bg-light text-dark">{{ $child->slug }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">{{ __('No child categories') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

