@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Edit Coupon</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control"
                                    value="{{ old('code', $coupon->code) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Discount Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percent</option>
                                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="coupon_discount_value" class="form-control"
                                    value="{{ old('coupon_discount_value', $coupon->coupon_discount_value) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Usage Limit</label>
                                <input type="number" name="usage_limit" class="form-control"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Minimum Cart Amount</label>
                                <input type="number" step="0.01" name="min_cart_amount" class="form-control"
                                    value="{{ old('min_cart_amount', $coupon->min_cart_amount) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ old('end_date', $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>



                    <hr>

                    <div class="row">
                        <div class="col-md-4 align-content-center">
                            <div class="form-check pt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Optional: Description --}}
                    <div class="mb-3 mt-3">
                        <label>Description En</label>
                        <textarea name="description[en]" class="form-control" rows="2">{{ old('description.en', $coupon->getTranslation('description', 'en')) }}</textarea>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Description Ar</label>
                        <textarea name="description[ar]" class="form-control" rows="2">{{ old('description.ar', $coupon->getTranslation('description', 'ar')) }}</textarea>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
