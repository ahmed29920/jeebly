@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>{{__('Add Category')}}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{__('Name')}} (EN) <span class="text-danger">*</span></label>
                                <input type="text" name="name[en]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{__('Name')}} (AR) <span class="text-danger">*</span></label>
                                <input type="text" name="name[ar]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{__('Slug')}} <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>{{__('Description')}} (EN)</label>
                        <textarea name="description[en]" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{__('Description')}} (AR)</label>
                        <textarea name="description[ar]" class="form-control"></textarea>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label>{{__('Image')}}</label>
                        <input type="file" name="image" class="form-control" id="image">
                        <img id="preview_image" src="" alt="Image Preview"
                            class="mt-2"style="max-height:100px; display:none;">
                    </div>

                    <hr>
                    <div class="mb-3">
                        <label class="form-label">{{__('Parent Category')}}</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @php
                                $renderRadios = function ($categories, $prefix = '', $selected = null) use (
                                    &$renderRadios,
                                ) {
                                    foreach ($categories as $cat) {
                                        echo '<div class="form-check">';
                                        echo '<input class="form-check-input" type="radio" name="parent_id" value="' .
                                            $cat['id'] .
                                            '" ' .
                                            ($selected == $cat['id'] ? 'checked' : '') .
                                            '>';
                                        echo '<label class="form-check-label">' .
                                            $prefix .
                                            ($cat['name']['en'] ?? 'No Name') .
                                            '</label>';
                                        echo '</div>';

                                        if (!empty($cat['children'])) {
                                            echo '<div class="ms-4">';
                                            $renderRadios($cat['children'], $prefix . '— ', $selected);
                                            echo '</div>';
                                        }
                                    }
                                };
                            @endphp

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="parent_id" value=""
                                    {{ empty($category->parent_id) ? 'checked' : '' }}>
                                <label class="form-check-label">-- None --</label>
                            </div>

                            {!! $renderRadios($categories, '', $category->parent_id ?? null) !!}
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>{{__('Sort Order')}}</label>
                                <input type="number" name="sort_order" class="form-control" value="1">
                            </div>
                        </div>
                        <div class="col-md-4 align-content-center">
                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="is_active" id="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    {{__('Active')}}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 align-content-center">

                            <div class="form-check pt-3">
                                <input class="form-check-input border" type="checkbox" name="view_in_home" id="view_in_home"
                                    value="1">
                                <label class="form-check-label" for="view_in_home">
                                    {{__('View in Home')}}
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- SEO fields --}}
                    <h6 class="mt-4">{{__('SEO')}}</h6>

                    <div class="mb-3">
                        <label>{{__('Meta Title')}} (EN)</label>
                        <input type="text" name="meta_title[en]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>{{__('Meta Title')}} (AR)</label>
                        <input type="text" name="meta_title[ar]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>{{__('Meta Description')}} (EN)</label>
                        <textarea name="meta_description[en]" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>{{__('Meta Description')}} (AR)</label>
                        <textarea name="meta_description[ar]" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{__('Meta Keywords')}} (EN)</label>
                        <input type="text" name="meta_keywords[en]" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>{{__('Meta Keywords')}} (AR)</label>
                        <input type="text" name="meta_keywords[ar]" class="form-control">
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">{{__('Save')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('preview_image');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });

        // Generate slug from name[en]
        const nameInput = document.querySelector('input[name="name[en]"]');
        const slugInput = document.querySelector('input[name="slug"]');

        nameInput.addEventListener('input', function() {
            let slug = this.value.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, ''); // remove special characters
            slugInput.value = slug;
        });
    </script>
@endpush
