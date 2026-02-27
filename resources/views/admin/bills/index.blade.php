{{-- resources/views/admin/bills/index.blade.php --}}
@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bills Management</h5>
            <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">Create New Bill</a>
        </div>

        <div class="card-body">
            {{-- Search Form --}}
            <form action="{{ route('admin.bills.index') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by bill number, guest name, description, GSTIN, or document number..."
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bx bx-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">
                            <i class="bx bx-reset"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bill No.</th>
                        <th>Date</th>
                        <th>Guest Name</th>
                        <th>Document</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $bill)
                    <tr>
                        <td>{{ $bill->bill_number }}</td>
                        <td>{{ $bill->bill_date->format('d/m/Y') }}</td>
                        <td>{{ $bill->guest_name ?? 'N/A' }}</td>
                        <td>
                            @if($bill->guest_document_type && $bill->guest_document_number)
                                <span class="badge bg-info">
                                    {{ $bill->document_type_label }}
                                </span>
                                <small class="d-block">{{ $bill->guest_document_number }}</small>
                                @if($bill->guest_document_image)
                                    <a href="{{ public_asset($bill->guest_document_image) }}" target="_blank" class="btn btn-xs btn-outline-primary mt-1">
                                        <i class="bx bx-image"></i> View
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>{{ $bill->description }}</td>
                        <td>₹ {{ number_format($bill->total, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.bills.show', $bill) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.bills.edit', $bill) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('admin.bill.download.pool', $bill) }}" class="btn btn-sm btn-success">Pool PDF</a>
                            <a href="{{ route('admin.bill.download.soho', $bill) }}" class="btn btn-sm btn-primary">Soho PDF</a>
                            <form action="{{ route('admin.bills.destroy', $bill) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $bills->links() }}
        </div>
    </div>
</div>

{{-- Add this CSS to fix dropdown arrow and styling --}}
@push('styles')
<style>
    /* Keep your original button styles */
    .btn-group .dropdown-toggle::after {
        display: none; /* Hide the default dropdown arrow if you want icon-only */
    }

    /* Optional: Add a small arrow if you want */
    .btn-group .dropdown-toggle i {
        margin-right: 2px;
    }

    /* Fix dropdown menu positioning */
    .dropdown-menu {
        margin-top: 0.25rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Style for dropdown items */
    .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .dropdown-item i {
        font-size: 1rem;
        width: 1.25rem;
    }

    /* Badge styling */
    .badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.5rem;
    }

    /* Small button for document view */
    .btn-xs {
        padding: 0.15rem 0.4rem;
        font-size: 0.7rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns with proper configuration to avoid Popper warnings
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    dropdownElementList.forEach(function(dropdownToggleEl) {
        // Use the Bootstrap Dropdown with custom options
        new bootstrap.Dropdown(dropdownToggleEl, {
            offset: [0, 2], // This helps with positioning
            popperConfig: {
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: [0, 2]
                        }
                    },
                    {
                        name: 'preventOverflow',
                        options: {
                            padding: 0
                        }
                    },
                    {
                        name: 'flip',
                        options: {
                            padding: 0
                        }
                    }
                ]
            }
        });
    });
});
</script>
@endpush
@endsection
