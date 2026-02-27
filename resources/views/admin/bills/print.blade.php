{{-- resources/views/admin/bills/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Bill - {{ $bill->bill_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            width: 80mm;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .header h3 {
            font-size: 18px;
            margin: 5px 0;
        }
        .bill-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            border-top: 2px dashed #000;
            border-bottom: 2px dashed #000;
            padding: 5px 0;
        }
        .details {
            margin: 10px 0;
        }
        .details p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 13px;
        }
        th {
            background-color: #f0f0f0;
        }
        .amount-section {
            margin-top: 10px;
            text-align: right;
        }
        .amount-row {
            margin: 2px 0;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        .document-info {
            margin: 10px 0;
            padding: 5px;
            border: 1px dashed #999;
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        @media print {
            body {
                width: 100%;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $type }}</h1>
        <div>Vill. Dharampur, Chunakhan, P.O. Bailpokhara</div>
        <div>Ranmagar (Nainital) Uttarakhand</div>
        <div>Mob. +9536338199</div>
    </div>

    <div class="bill-title">BILL</div>

    <div class="details">
        <p><strong>No.</strong> {{ $bill->bill_number }}</p>
        <p><strong>Date:</strong> {{ $bill->bill_date->format('d/m/Y') }}</p>
        <p><strong>Guest Name:</strong> {{ $bill->guest_name ?? '____________________' }}</p>
        <p><strong>Address:</strong> {{ $bill->guest_address ?? '____________________' }}</p>
        @if($bill->gstin)
        <p><strong>GSTIN:</strong> {{ $bill->gstin }}</p>
        @endif

        {{-- Document Information --}}
        @if($bill->guest_document_type && $bill->guest_document_number)
        <div class="document-info">
            <p><strong>{{ $bill->document_type_label }}:</strong> {{ $bill->guest_document_number }}</p>
        </div>
        @endif
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

    <div class="amount-section">
        <div class="amount-row">SUB TOTAL: ₹ {{ number_format($bill->subtotal, 2) }}</div>
        <div class="amount-row">CGST @ 9%: ₹ {{ number_format($bill->cgst, 2) }}</div>
        <div class="amount-row">SGST @ 9%: ₹ {{ number_format($bill->sgst, 2) }}</div>
        @if($bill->other_taxes > 0)
        <div class="amount-row">OTHER TAXES: ₹ {{ number_format($bill->other_taxes, 2) }}</div>
        @endif
        <div class="total">G. Total: ₹ {{ number_format($bill->total, 2) }}</div>
    </div>

    <div class="footer">
        <div>E.&O.E.</div>
        <div>Signature</div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Bill</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">Close</button>
    </div>
</body>
</html>
