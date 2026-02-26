<?php
// database/seeders/EnhancedBillSeeder.php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnhancedBillSeeder extends Seeder
{
    public function run(): void
    {
        $billTemplates = [
            // Budget Room Stays
            ['rate' => 2500, 'desc' => 'Standard Room', 'qty' => 1, 'tax' => 0],
            ['rate' => 3500, 'desc' => 'Deluxe Room', 'qty' => 1, 'tax' => 200],
            ['rate' => 4500, 'desc' => 'Executive Room', 'qty' => 2, 'tax' => 350],

            // Cottage Stays
            ['rate' => 6000, 'desc' => 'Pool Cottage', 'qty' => 1, 'tax' => 500],
            ['rate' => 7500, 'desc' => 'Garden Cottage', 'qty' => 1, 'tax' => 600],
            ['rate' => 9000, 'desc' => 'Lake View Cottage', 'qty' => 1, 'tax' => 750],

            // Suite Stays
            ['rate' => 12000, 'desc' => 'Honeymoon Suite', 'qty' => 1, 'tax' => 1000],
            ['rate' => 15000, 'desc' => 'Presidential Suite', 'qty' => 1, 'tax' => 1200],
            ['rate' => 8000, 'desc' => 'Family Suite', 'qty' => 2, 'tax' => 800],

            // Long term stays (multiple months)
            ['rate' => 5000, 'desc' => 'Corporate Lease', 'qty' => 3, 'tax' => 1500],
            ['rate' => 4500, 'desc' => 'Monthly Rental', 'qty' => 6, 'tax' => 2000],

            // Special packages
            ['rate' => 8500, 'desc' => 'Weekend Getaway', 'qty' => 1, 'tax' => 400],
            ['rate' => 9500, 'desc' => 'Spa Package', 'qty' => 1, 'tax' => 850],
            ['rate' => 11000, 'desc' => 'Adventure Package', 'qty' => 2, 'tax' => 950],
        ];

        $guestNames = [
            'Rakesh Kapoor', 'Sunita Williams', 'Arjun Reddy', 'Meera Nair',
            'David John', 'Fatima Sheikh', 'Gurpreet Singh', 'Lakshmi Prasad',
            'Mohammed Ali', 'Nitin Deshmukh', 'Olivia Thomas', 'Pankaj Tripathi',
            'Qamar Hussain', 'Radhika Apte', 'Siddharth Malhotra'
        ];

        $addresses = [
            'Green Park, Delhi', 'Salt Lake, Kolkata', 'Koramangala, Bangalore',
            'Andheri East, Mumbai', 'Jubilee Hills, Hyderabad', 'Shivajinagar, Pune',
            'Civil Lines, Jaipur', 'Anna Nagar, Chennai', 'Gomti Nagar, Lucknow',
            'Vastrapur, Ahmedabad', 'Boring Road, Patna', 'Vijay Nagar, Indore',
            'Sector 62, Noida', 'Sector 15, Gurgaon', 'Sadar Bazaar, Agra'
        ];

        // Generate bills for last 3 months with varying amounts
        for ($i = 0; $i < 50; $i++) {
            $template = $billTemplates[array_rand($billTemplates)];
            $date = Carbon::now()->subDays(rand(1, 90));

            $subtotal = $template['rate'] * $template['qty'];
            $cgst = round($subtotal * 0.09, 2);
            $sgst = round($subtotal * 0.09, 2);
            $otherTaxes = $template['tax'];
            $total = $subtotal + $cgst + $sgst + $otherTaxes;

            Bill::create([
                'bill_number' => Bill::generateBillNumber(),
                'guest_name' => $guestNames[array_rand($guestNames)],
                'guest_address' => $addresses[array_rand($addresses)],
                'gstin' => 'GSTIN' . rand(100000, 999999),
                'bill_date' => $date,
                'description' => $template['desc'] . ' - ' . $date->format('F Y'),
                'rate' => $template['rate'],
                'quantity' => $template['qty'],
                'subtotal' => $subtotal,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'other_taxes' => $otherTaxes,
                'total' => $total,
            ]);
        }

        // Generate bills with zero other taxes (like your PDF examples)
        for ($i = 0; $i < 20; $i++) {
            $date = Carbon::now()->subMonths(rand(1, 6));
            $rate = rand(8000, 50000);
            $qty = 1;

            $subtotal = $rate;
            $cgst = round($subtotal * 0.09, 2);
            $sgst = round($subtotal * 0.09, 2);
            $total = $subtotal + $cgst + $sgst;

            Bill::create([
                'bill_number' => Bill::generateBillNumber(),
                'guest_name' => $guestNames[array_rand($guestNames)],
                'guest_address' => $addresses[array_rand($addresses)],
                'gstin' => null,
                'bill_date' => $date,
                'description' => 'Monthly Rent - ' . $date->format('F Y'),
                'rate' => $rate,
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'other_taxes' => 0,
                'total' => $total,
            ]);
        }
    }
}
