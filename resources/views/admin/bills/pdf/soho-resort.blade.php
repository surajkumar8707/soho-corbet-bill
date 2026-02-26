{{-- resources/views/admin/bills/pdf/soho-resort.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bill - {{ $bill->bill_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .bill-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
        }
        .bill-details {
            margin: 15px 0;
        }
        .bill-details p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .amount-row {
            text-align: right;
            margin: 5px 0;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $resort }}</h1>
        <div>{{ $address }}</div>
        <div>Mob. {{ $mobile }}</div>
    </div>

    <div class="bill-title">BILL</div>

    <div class="bill-details">
        <p>No. {{ $bill->bill_number }}</p>
        <p>Guest Name......{{ $bill->guest_name ?? '..........................' }}</p>
        <p>Address......{{ $bill->guest_address ?? '..............................' }}</p>
        <p>GSTIN-{{ $bill->gstin ?? '..........................' }}</p>
    </div>

    <table>
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

    <div class="amount-row">
        <p>SUB TOTAL: ₹ {{ number_format($bill->subtotal, 2) }}</p>
        <p>CGST @ 9%: ₹ {{ number_format($bill->cgst, 2) }}</p>
        <p>SGST @ 9%: ₹ {{ number_format($bill->sgst, 2) }}</p>
        <p>OTHER TAXES: ₹ {{ number_format($bill->other_taxes, 2) }}</p>
        <p class="total">G. Total: ₹ {{ number_format($bill->total, 2) }}</p>
    </div>
</body>
</html>
