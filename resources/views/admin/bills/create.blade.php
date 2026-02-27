{{-- resources/views/admin/bills/create.blade.php --}}
@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create New Bill</h5>
            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="card-body">
            {{-- Display Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Display Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Display Validation Errors Summary --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.bills.store') }}" method="POST" id="billForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Guest Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Guest Name <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text"
                               name="guest_name"
                               class="form-control @error('guest_name') is-invalid @enderror"
                               value="{{ old('guest_name') }}"
                               placeholder="Enter guest name">
                        @error('guest_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum 255 characters</small>
                    </div>

                    {{-- Bill Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Bill Date <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               name="bill_date"
                               class="form-control @error('bill_date') is-invalid @enderror"
                               value="{{ old('bill_date', date('Y-m-d')) }}"
                               required>
                        @error('bill_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Cannot be a future date</small>
                    </div>

                    {{-- Guest Address --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Guest Address <span class="text-muted">(Optional)</span>
                        </label>
                        <textarea name="guest_address"
                                  class="form-control @error('guest_address') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Enter complete address">{{ old('guest_address') }}</textarea>
                        @error('guest_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum 500 characters</small>
                    </div>

                    {{-- GSTIN --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            GSTIN <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text"
                               name="gstin"
                               class="form-control @error('gstin') is-invalid @enderror"
                               value="{{ old('gstin') }}"
                               placeholder="22AAAAA0000A1Z5"
                               pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[Z]{1}[0-9]{1}">
                        @error('gstin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: 22AAAAA0000A1Z5</small>
                    </div>

                    {{-- Guest Identification Documents Section --}}
                    <div class="col-12 mt-3 mb-2">
                        <h6 class="fw-bold border-bottom pb-2">Guest Identification Documents</h6>
                        <p class="text-muted small">Upload guest ID proof (Aadhar, PAN, Voter ID, or Driving License)</p>
                    </div>

                    {{-- Document Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Document Type <span class="text-muted">(Optional)</span>
                        </label>
                        <select name="guest_document_type"
                                class="form-control @error('guest_document_type') is-invalid @enderror"
                                id="guest_document_type">
                            <option value="">Select Document Type</option>
                            @foreach($documentTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('guest_document_type') == $value ? 'selected' : '' }}>
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
                        <label class="form-label">
                            Document Number <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text"
                               name="guest_document_number"
                               id="guest_document_number"
                               class="form-control @error('guest_document_number') is-invalid @enderror"
                               value="{{ old('guest_document_number') }}"
                               placeholder="Enter document number">
                        @error('guest_document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Required if document type is selected</small>
                    </div>

                    {{-- Document Image Upload --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Document Image <span class="text-muted">(Optional)</span>
                        </label>
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

                    {{-- Image Preview --}}
                    <div class="col-md-12 mb-3" id="image_preview_container" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <h6>Document Image Preview:</h6>
                                <img id="image_preview" src="#" alt="Document Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="remove_image">Remove</button>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Description <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}"
                               required
                               placeholder="e.g., Room Rent, Food Bill, etc.">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum 500 characters</small>
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', 1) }}"
                               min="1"
                               max="999"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Min: 1, Max: 999</small>
                    </div>

                    {{-- Rate --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Rate (₹) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="rate"
                               id="rate"
                               class="form-control @error('rate') is-invalid @enderror"
                               value="{{ old('rate') }}"
                               step="0.01"
                               min="0"
                               max="999999.99"
                               required
                               placeholder="0.00">
                        @error('rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Max: 999,999.99</small>
                    </div>

                    {{-- Other Taxes --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Other Taxes (₹) <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="number"
                               name="other_taxes"
                               id="other_taxes"
                               class="form-control @error('other_taxes') is-invalid @enderror"
                               value="{{ old('other_taxes', 0) }}"
                               step="0.01"
                               min="0"
                               max="999999.99"
                               placeholder="0.00">
                        @error('other_taxes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Additional charges if any</small>
                    </div>
                </div>

                {{-- GST Information --}}
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i>
                    <strong>Current GST Rates:</strong>
                    CGST: {{ $gstRates['cgst'] }}% | SGST: {{ $gstRates['sgst'] }}%
                </div>

                {{-- Calculation Preview --}}
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

                {{-- Form Actions --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Create Bill
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="bx bx-reset"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // GSTIN validation
        const gstinInput = document.querySelector('input[name="gstin"]');
        if (gstinInput) {
            gstinInput.addEventListener('blur', function() {
                const gstinPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[Z]{1}[0-9]{1}$/;
                if (this.value && !gstinPattern.test(this.value)) {
                    this.classList.add('is-invalid');
                    // Add custom error message if not already present
                    if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Please enter a valid GSTIN format (e.g., 22AAAAA0000A1Z5)';
                        this.parentNode.appendChild(errorDiv);
                    }
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }

        // Document type and number validation
        const docTypeSelect = document.getElementById('guest_document_type');
        const docNumberInput = document.getElementById('guest_document_number');

        // function validateDocumentNumber() {
        //     if (docTypeSelect.value && !docNumberInput.value) {
        //         docNumberInput.classList.add('is-invalid');
        //         if (!docNumberInput.nextElementSibling || !docNumberInput.nextElementSibling.classList.contains('invalid-feedback')) {
        //             const errorDiv = document.createElement('div');
        //             errorDiv.className = 'invalid-feedback';
        //             errorDiv.textContent = 'Document number is required when document type is selected';
        //             docNumberInput.parentNode.appendChild(errorDiv);
        //         }
        //     } else {
        //         docNumberInput.classList.remove('is-invalid');
        //     }
        // }
        function validateDocumentNumber() {
            const feedback = docNumberInput.parentNode.querySelector('.invalid-feedback');

            if (docTypeSelect.value && !docNumberInput.value) {
                docNumberInput.classList.add('is-invalid');

                if (feedback) {
                    feedback.textContent = 'Document number is required when document type is selected';
                }
            } else {
                docNumberInput.classList.remove('is-invalid');

                if (feedback) {
                    feedback.textContent = '';
                }
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

        removeBtn.addEventListener('click', function() {
            imageInput.value = '';
            previewContainer.style.display = 'none';
            previewImage.src = '#';
        });
    });
</script>
@endpush
@endsection
