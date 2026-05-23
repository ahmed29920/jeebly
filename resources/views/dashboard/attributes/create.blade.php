@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h6>Add Attribute</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attributes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <label>Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Name (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="name[en]" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Name (AR) <span class="text-danger">*</span></label>
                        <input type="text" name="name[ar]" class="form-control" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Select</option>
                            <option value="multiselect">Multi Select</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active">
                            <label for="is_active" class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="is_required" value="1" id="is_required">
                            <label for="is_required" class="form-check-label">Required</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="is_filterable" value="1" id="is_filterable">
                            <label for="is_filterable" class="form-check-label">Filterable</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3" id="options-container" style="display: none;">
                    <label>Options</label>
                    <div id="options-list"></div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-option">+ Add Option</button>
                </div>

                <button class="btn btn-primary mt-4">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        $('#type').on('change', function(){
            if($(this).val() === 'select' || $(this).val() === 'multiselect' || $(this).val() === 'radio'){
                $('#options-container').show();
            } else {
                $('#options-container').hide();
                $('#options-list').empty();
            }
        });

        $('#add-option').on('click', function(){
            $('#options-list').append(`
                <div class="input-group mb-2">
                    <input type="text" name="options[][value]" class="form-control" placeholder="Option value">
                    <button type="button" class="btn btn-danger remove-option m-0">X</button>
                </div>
            `);
        });

        $(document).on('click', '.remove-option', function(){
            $(this).closest('.input-group').remove();
        });
    });
</script>
@endpush
