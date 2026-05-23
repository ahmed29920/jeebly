@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Import Products</h6>
            <a href="{{ route('admin.products.template') }}" class="btn btn-primary btn-sm">
                Download Template
            </a>
        </div>

        <div class="card-body ">
            <div class="alert border shadow-sm">
                <i class="fa fa-info-circle"></i>
                <strong>Important Instructions Before Importing:</strong>
                <ul class="mt-2 mb-0">
                    <li>📥 <strong>Download the Excel template</strong> first and fill it carefully.</li>

                    <li>🎯 <strong>About the dropdown columns in the template:</strong>
                        <br>- Some columns (like <code>categories_list</code>) include dropdowns.
                        <br>- These dropdowns are — <u>for reference only</u> — to help you see available options and their IDs.
                        <br>- You must manually write the correct <code>ID</code> in the next column (e.g. <code>categories</code>).
                    </li>

                    <li>🏷️ <strong>Categories Column:</strong>
                        <br>- Each product can belong to multiple categories.
                        <br>- Write category <code>IDs</code> only (you can check them in <code>categories_list</code>).
                        <br>- Separate multiple IDs using <code>|</code>.
                        <br>- Example: <code>1|3|5</code>
                    </li>

                    <li>🖼️ <strong>Images Column:</strong>
                        <br>- You can add multiple image URLs separated by <code>|</code>.</li>

                    <li>✅ Make sure all required fields (e.g. name, sku, price, etc.) are filled correctly before importing.</li>
                </ul>

            </div>

            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Upload Products File</label>
                    <input type="file" name="products" class="form-control" accept=".csv,.xlsx,.xls" required>
                </div>

                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>
</div>
@endsection
