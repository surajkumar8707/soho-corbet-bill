{{-- resources/views/admin/bills/index.blade.php --}}
@extends('admin.layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Bills Management</h5>
            <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">Create New Bill</a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
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
                    @foreach($bills as $bill)
                    <tr>
                        <td>{{ $bill->bill_number }}</td>
                        <td>{{ $bill->bill_date->format('d/m/Y') }}</td>
                        <td>{{ $bill->guest_name ?? 'N/A' }}</td>
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
@endsection
