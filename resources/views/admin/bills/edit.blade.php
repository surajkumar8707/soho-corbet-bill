{{-- resources/views/admin/bills/index.blade.php --}}
@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bills Management</h5>
            <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create New Bill
            </a>
        </div>

        <div class="card-body">
            {{-- Search and Filter --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.bills.index') }}" method="GET" class="d-flex">
                        <input type="text"
                               name="search"
                               class="form-control me-2"
                               placeholder="Search by bill number, guest name, or description..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary ms-2">
                                <i class="bx bx-reset"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <span class="text-muted">Total Bills: {{ $bills->total() }}</span>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Bill No.</th>
                        <th>Date</th>
                        <th>Guest Name</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                    <tr>
                        <td><strong>{{ $bill->bill_number }}</strong></td>
                        <td>{{ $bill->bill_date->format('d/m/Y') }}</td>
                        <td>{{ $bill->guest_name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($bill->description, 30) }}</td>
                        <td>₹ {{ number_format($bill->total, 2) }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.bills.show', $bill) }}">
                                        <i class="bx bx-show me-1"></i> View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.bills.edit', $bill) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('admin.bill.download.pool', $bill) }}" target="_blank">
                                        <i class="bx bx-file me-1"></i> Pool PDF
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.bill.download.soho', $bill) }}" target="_blank">
                                        <i class="bx bx-file me-1"></i> Soho PDF
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.bills.print', $bill) }}" target="_blank">
                                        <i class="bx bx-printer me-1"></i> Print
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('admin.bills.destroy', $bill) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure you want to delete bill #{{ $bill->bill_number }}? This action cannot be undone.')">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="bx bx-file" style="font-size: 48px;"></i>
                                <h5 class="mt-2">No bills found</h5>
                                @if(request('search'))
                                    <p>No results found for "{{ request('search') }}"</p>
                                    <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">Clear Search</a>
                                @else
                                    <p>Get started by creating your first bill</p>
                                    <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">Create Your First Bill</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bills->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $bills->firstItem() ?? 0 }} to {{ $bills->lastItem() ?? 0 }} of {{ $bills->total() }} entries
                </div>
                <div>
                    {{ $bills->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
