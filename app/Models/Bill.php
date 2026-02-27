<?php
// app/Models/Bill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'guest_name',
        'guest_address',
        'gstin',
        'guest_document_type',
        'guest_document_number',
        'guest_document_image',
        'bill_date',
        'description',
        'rate',
        'quantity',
        'subtotal',
        'cgst',
        'sgst',
        'other_taxes',
        'total'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
        'other_taxes' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public static function getDocumentTypes()
    {
        return [
            'aadhar' => 'Aadhar Card',
            'pan' => 'PAN Card',
            'voter_id' => 'Voter ID',
            'driving_license' => 'Driving License',
            'passport' => 'Passport'
        ];
    }

    public function getDocumentTypeLabelAttribute()
    {
        $types = self::getDocumentTypes();
        return $types[$this->guest_document_type] ?? 'N/A';
    }

    // Generate unique bill number with counter to avoid duplicates
    public static function generateBillNumber($counter = null)
    {
        $year = date('Y');
        $month = date('m');

        if ($counter) {
            $newNumber = str_pad($counter, 3, '0', STR_PAD_LEFT);
            return 'BILL-' . $year . $month . '-' . $newNumber;
        }

        $lastBill = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBill) {
            $lastNumber = intval(substr($lastBill->bill_number, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'BILL-' . $year . $month . '-' . $newNumber;
    }
}
