@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Add Coupon</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Discount Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="percentage">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="coupon_discount_value" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Usage Limit</label>
                                <input type="number" name="usage_limit" class="form-control" value="1">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Minimum Cart Amount</label>
                                <input type="number" step="0.01" name="min_cart_amount" class="form-control"
                                    value="0">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Expires At</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4 align-content-center">
                            <div class="form-check pt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Optional: Description --}}
                    <div class="mb-3 mt-3">
                        <label>Description En</label>
                        <textarea name="description[en]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Description Ar</label>
                        <textarea name="description[ar]" class="form-control" rows="2"></textarea>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
{{--
@push('scripts')
    <script>
        // auto-generate code (optional) if user didn’t type
        const codeInput = document.querySelector('input[name="code"]');

        if (codeInput) {
            codeInput.addEventListener('focus', function() {
                if (!this.value) {
                    this.value = 'CPN-' + Math.random().toString(36).substring(2, 8).toUpperCase();
                }
            });
        }
    </script>
@endpush --}}
