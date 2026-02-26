@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Bill - {{ $bill->bill_number }}</h5>
            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.bills.update', $bill) }}" method="POST" id="billEditForm">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Guest Name</label>
                        <input type="text" name="guest_name" class="form-control"
                               value="{{ old('guest_name', $bill->guest_name) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Bill Date</label>
                        <input type="date" name="bill_date" class="form-control"
                               value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Guest Address</label>
                        <textarea name="guest_address" class="form-control">{{ old('guest_address', $bill->guest_address) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>GSTIN</label>
                        <input type="text" name="gstin" class="form-control"
                               value="{{ old('gstin', $bill->gstin) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control"
                               value="{{ old('description', $bill->description) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" id="quantity"
                               class="form-control"
                               value="{{ old('quantity', $bill->quantity) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Rate</label>
                        <input type="number" step="0.01" name="rate" id="rate"
                               class="form-control"
                               value="{{ old('rate', $bill->rate) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Other Taxes</label>
                        <input type="number" step="0.01" name="other_taxes" id="other_taxes"
                               class="form-control"
                               value="{{ old('other_taxes', $bill->other_taxes) }}">
                    </div>
                </div>

                {{-- GST Info --}}
                <div class="alert alert-info">
                    CGST: {{ $gstRates['cgst'] }}% | SGST: {{ $gstRates['sgst'] }}%
                </div>

                {{-- ✅ Calculation Preview Table (ADDED) --}}
                <div class="card mt-3 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Bill Calculation Preview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td>₹ <span id="preview_subtotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>CGST ({{ $gstRates['cgst'] }}%):</th>
                                        <td>₹ <span id="preview_cgst">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>SGST ({{ $gstRates['sgst'] }}%):</th>
                                        <td>₹ <span id="preview_sgst">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Other Taxes:</th>
                                        <td>₹ <span id="preview_other_taxes">0.00</span></td>
                                    </tr>
                                    <tr class="border-top">
                                        <th>Grand Total:</th>
                                        <td><strong>₹ <span id="preview_total">0.00</span></strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Bill
                </button>

            </form>
        </div>
    </div>
</div>

{{-- ✅ Live calculation JS --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const quantityInput = document.getElementById('quantity');
    const rateInput = document.getElementById('rate');
    const otherTaxesInput = document.getElementById('other_taxes');

    const cgstRate = {{ $gstRates['cgst'] }};
    const sgstRate = {{ $gstRates['sgst'] }};

    function calculatePreview() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const otherTaxes = parseFloat(otherTaxesInput.value) || 0;

        const subtotal = quantity * rate;
        const cgst = subtotal * (cgstRate / 100);
        const sgst = subtotal * (sgstRate / 100);
        const total = subtotal + cgst + sgst + otherTaxes;

        document.getElementById('preview_subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('preview_cgst').textContent = cgst.toFixed(2);
        document.getElementById('preview_sgst').textContent = sgst.toFixed(2);
        document.getElementById('preview_other_taxes').textContent = otherTaxes.toFixed(2);
        document.getElementById('preview_total').textContent = total.toFixed(2);
    }

    quantityInput.addEventListener('input', calculatePreview);
    rateInput.addEventListener('input', calculatePreview);
    otherTaxesInput.addEventListener('input', calculatePreview);

    // initial load values
    calculatePreview();
});
</script>
@endpush

@endsection
