{{-- resources/views/admin/bills/show.blade.php --}}
@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bill Details - {{ $bill->bill_number }}</h5>
                    <div>
                        <a href="{{ route('admin.bills.edit', $bill) }}" class="btn btn-warning">Edit Bill</a>
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Document Information Section --}}
                    @if($bill->guest_document_type || $bill->guest_document_number || $bill->guest_document_image)
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading fw-bold mb-3">Guest Identification Documents</h6>
                        <div class="row">
                            @if($bill->guest_document_type)
                            <div class="col-md-4 mb-2">
                                <strong>Document Type:</strong><br>
                                <span>{{ $bill->document_type_label }}</span>
                            </div>
                            @endif

                            @if($bill->guest_document_number)
                            <div class="col-md-4 mb-2">
                                <strong>Document Number:</strong><br>
                                <span>{{ $bill->guest_document_number }}</span>
                            </div>
                            @endif

                            @if($bill->guest_document_image)
                            <div class="col-md-4 mb-2">
                                <strong>Document Image:</strong><br>
                                <a href="{{ public_asset($bill->guest_document_image) }}" target="_blank" class="btn btn-sm btn-primary mt-1">
                                    <i class="bx bx-show"></i> View Document
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- ================= SOHO CORBETT RESORT BILL ================= --}}
                    <div class="bill-preview border p-4 mb-5" style="font-family: 'Courier New', monospace;">
                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <h3 class="mb-1">SOHO CORBETT RESORT</h3>
                            <p class="mb-0">Vill. Dharampur, Chunakhan, P.O. Bailpokhara</p>
                            <p class="mb-0">Ranmagar (Nainital) Uttarakhand</p>
                            <p class="mb-0">Mob. +9536338199</p>
                        </div>

                        <h4 class="text-center border-top border-bottom py-2 mb-4">BILL</h4>

                        {{-- Bill Details --}}
                        <div class="row mb-4">
                            <div class="col-6">
                                <p class="mb-1"><strong>No.</strong> {{ $bill->bill_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $bill->bill_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Guest Name:</strong> {{ $bill->guest_name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>GSTIN:</strong> {{ $bill->gstin ?? 'N/A' }}</p>
                                @if($bill->guest_document_type && $bill->guest_document_number)
                                <p class="mb-1"><strong>{{ $bill->document_type_label }}:</strong> {{ $bill->guest_document_number }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>QTY.</th>
                                    <th>DESCRIPTION</th>
                                    <th>RATE</th>
                                    <th>AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $bill->quantity }}</td>
                                    <td>{{ $bill->description }}</td>
                                    <td>₹ {{ number_format($bill->rate, 2) }}</td>
                                    <td>₹ {{ number_format($bill->subtotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Calculations --}}
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th>SUB TOTAL:</th>
                                        <td class="text-end">₹ {{ number_format($bill->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>CGST @ 9%:</th>
                                        <td class="text-end">₹ {{ number_format($bill->cgst, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>SGST @ 9%:</th>
                                        <td class="text-end">₹ {{ number_format($bill->sgst, 2) }}</td>
                                    </tr>
                                    @if($bill->other_taxes > 0)
                                    <tr>
                                        <th>OTHER TAXES:</th>
                                        <td class="text-end">₹ {{ number_format($bill->other_taxes, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <th>G. Total:</th>
                                        <td class="text-end"><strong>₹ {{ number_format($bill->total, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="row mt-4">
                            <div class="col-6">
                                <p class="text-muted">E.&O.E.</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>Signature</p>
                            </div>
                        </div>
                    </div>
                    {{-- ================= END SOHO BILL ================= --}}

                    {{-- ================= SOHO CORBETT POOL BILL ================= --}}
                    <div class="bill-preview border p-4 mb-4" style="font-family: 'Courier New', monospace;">
                        {{-- Header --}}
                        <div class="text-center mb-4">
                            <h3 class="mb-1">POOL & COTTAGES</h3>
                            <p class="mb-0">Vill. Dharampur, Chunakhan, P.O. Bailpokhara</p>
                            <p class="mb-0">Ranmagar (Nainital) Uttarakhand</p>
                            <p class="mb-0">Mob. +9536338199</p>
                        </div>

                        <h4 class="text-center border-top border-bottom py-2 mb-4">POOL BILL</h4>

                        {{-- Bill Details --}}
                        <div class="row mb-4">
                            <div class="col-6">
                                <p class="mb-1"><strong>No.</strong> {{ $bill->bill_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $bill->bill_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Guest Name:</strong> {{ $bill->guest_name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>GSTIN:</strong> {{ $bill->gstin ?? 'N/A' }}</p>
                                @if($bill->guest_document_type && $bill->guest_document_number)
                                <p class="mb-1"><strong>{{ $bill->document_type_label }}:</strong> {{ $bill->guest_document_number }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>QTY.</th>
                                    <th>DESCRIPTION</th>
                                    <th>RATE</th>
                                    <th>AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $bill->quantity }}</td>
                                    <td>{{ $bill->description }}</td>
                                    <td>₹ {{ number_format($bill->rate, 2) }}</td>
                                    <td>₹ {{ number_format($bill->subtotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- Calculations --}}
                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th>SUB TOTAL:</th>
                                        <td class="text-end">₹ {{ number_format($bill->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>CGST @ 9%:</th>
                                        <td class="text-end">₹ {{ number_format($bill->cgst, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>SGST @ 9%:</th>
                                        <td class="text-end">₹ {{ number_format($bill->sgst, 2) }}</td>
                                    </tr>
                                    @if($bill->other_taxes > 0)
                                    <tr>
                                        <th>OTHER TAXES:</th>
                                        <td class="text-end">₹ {{ number_format($bill->other_taxes, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <th>G. Total:</th>
                                        <td class="text-end"><strong>₹ {{ number_format($bill->total, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="row mt-4">
                            <div class="col-6">
                                <p class="text-muted">E.&O.E.</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>Signature</p>
                            </div>
                        </div>
                    </div>
                    {{-- ================= END POOL BILL ================= --}}

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.bill.download.pool', $bill) }}" class="btn btn-success" target="_blank">
                            <i class="bx bx-download"></i> Download Pool PDF
                        </a>
                        <a href="{{ route('admin.bill.download.soho', $bill) }}" class="btn btn-primary" target="_blank">
                            <i class="bx bx-download"></i> Download Soho PDF
                        </a>
                        <a href="{{ route('admin.bills.print', [$bill, 'SOHO CORBETT RESORT']) }}" class="btn btn-info" target="_blank">
                            <i class="bx bx-printer"></i> Print Soho Corbett
                        </a>

                        <a href="{{ route('admin.bills.print', [$bill, 'POOL & COTTAGES']) }}" class="btn btn-info" target="_blank">
                            <i class="bx bx-printer"></i> Print Pool & Cottages
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
