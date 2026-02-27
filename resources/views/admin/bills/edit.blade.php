{{-- resources/views/admin/bills/edit.blade.php --}}
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

            <form action="{{ route('admin.bills.update', $bill) }}" method="POST" id="billEditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Guest Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guest Name</label>
                        <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror"
                               value="{{ old('guest_name', $bill->guest_name) }}">
                        @error('guest_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bill Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bill Date</label>
                        <input type="date" name="bill_date" class="form-control @error('bill_date') is-invalid @enderror"
                               value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}">
                        @error('bill_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Guest Address --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Guest Address</label>
                        <textarea name="guest_address" class="form-control @error('guest_address') is-invalid @enderror" rows="2">{{ old('guest_address', $bill->guest_address) }}</textarea>
                        @error('guest_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- GSTIN --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">GSTIN</label>
                        <input type="text" name="gstin" class="form-control @error('gstin') is-invalid @enderror"
                               value="{{ old('gstin', $bill->gstin) }}">
                        @error('gstin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Guest Identification Documents Section --}}
                    <div class="col-12 mt-3 mb-2">
                        <h6 class="fw-bold border-bottom pb-2">Guest Identification Documents</h6>
                        <p class="text-muted small">Upload or update guest ID proof</p>
                    </div>

                    {{-- Document Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Document Type</label>
                        <select name="guest_document_type"
                                class="form-control @error('guest_document_type') is-invalid @enderror"
                                id="guest_document_type">
                            <option value="">Select Document Type</option>
                            @foreach($documentTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('guest_document_type', $bill->guest_document_type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('guest_document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Document Number --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Document Number</label>
                        <input type="text"
                               name="guest_document_number"
                               id="guest_document_number"
                               class="form-control @error('guest_document_number') is-invalid @enderror"
                               value="{{ old('guest_document_number', $bill->guest_document_number) }}"
                               placeholder="Enter document number">
                        @error('guest_document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Required if document type is selected</small>
                    </div>

                    {{-- Document Image Upload --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Document Image</label>
                        <input type="file"
                               name="guest_document_image"
                               id="guest_document_image"
                               class="form-control @error('guest_document_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg">
                        @error('guest_document_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max: 2MB (JPEG, PNG, JPG only)</small>
                    </div>

                    {{-- Current Document Image Preview --}}
                    @if($bill->guest_document_image)
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6>Current Document Image:</h6>
                                <img src="{{ public_asset($bill->guest_document_image) }}" alt="Current Document" style="max-width: 200px; max-height: 200px;" class="img-thumbnail mb-2">
                                <div>
                                    <label class="text-muted">
                                        <input type="checkbox" name="remove_document_image" value="1">
                                        Remove current image
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- New Image Preview --}}
                    <div class="col-md-12 mb-3" id="image_preview_container" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <h6>New Document Image Preview:</h6>
                                <img id="image_preview" src="#" alt="Document Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="remove_image">Remove</button>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description', $bill->description) }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity"
                               class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', $bill->quantity) }}" min="1" max="999">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rate --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Rate (₹)</label>
                        <input type="number" step="0.01" name="rate" id="rate"
                               class="form-control @error('rate') is-invalid @enderror"
                               value="{{ old('rate', $bill->rate) }}" min="0" max="999999.99">
                        @error('rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Other Taxes --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Other Taxes (₹)</label>
                        <input type="number" step="0.01" name="other_taxes" id="other_taxes"
                               class="form-control @error('other_taxes') is-invalid @enderror"
                               value="{{ old('other_taxes', $bill->other_taxes) }}" min="0" max="999999.99">
                        @error('other_taxes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- GST Info --}}
                <div class="alert alert-info">
                    CGST: {{ $gstRates['cgst'] }}% | SGST: {{ $gstRates['sgst'] }}%
                </div>

                {{-- Calculation Preview Table --}}
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
                <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">Cancel</a>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Quantity, Rate, Tax calculation
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

    // Initial calculation
    calculatePreview();

    // Document type and number validation
    const docTypeSelect = document.getElementById('guest_document_type');
    const docNumberInput = document.getElementById('guest_document_number');

    function validateDocumentNumber() {
        if (docTypeSelect.value && !docNumberInput.value) {
            docNumberInput.classList.add('is-invalid');
            if (!docNumberInput.nextElementSibling || !docNumberInput.nextElementSibling.classList.contains('invalid-feedback')) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Document number is required when document type is selected';
                docNumberInput.parentNode.appendChild(errorDiv);
            }
        } else {
            docNumberInput.classList.remove('is-invalid');
        }
    }

    docTypeSelect.addEventListener('change', validateDocumentNumber);
    docNumberInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
        }
    });

    // Image preview
    const imageInput = document.getElementById('guest_document_image');
    const previewContainer = document.getElementById('image_preview_container');
    const previewImage = document.getElementById('image_preview');
    const removeBtn = document.getElementById('remove_image');

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            imageInput.value = '';
            previewContainer.style.display = 'none';
            previewImage.src = '#';
        });
    }
});
</script>
@endpush

@endsection
