<?php
// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
    ];

    // Get CGST rate as percentage
    public function getCgstRateAttribute()
    {
        return $this->cgst ?? 9.00;
    }

    // Get SGST rate as percentage
    public function getSgstRateAttribute()
    {
        return $this->sgst ?? 9.00;
    }

    // Get combined GST rate
    public function getTotalGstRateAttribute()
    {
        return ($this->cgst ?? 9.00) + ($this->sgst ?? 9.00);
    }
}
