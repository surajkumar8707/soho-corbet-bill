<?php
// database/seeders/BillSeeder.php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing bills
        Bill::truncate();

        // Define sample guest names
        $guests = [
            'Rajesh Kumar',
            'Priya Sharma',
            'Amit Patel',
            'Neha Singh',
            'Vikram Mehta',
            'Pooja Gupta',
            'Sanjay Joshi',
            'Deepa Nair',
            'Rahul Verma',
            'Anjali Desai',
            'Manoj Tiwari',
            'Kavita Reddy',
            'Suresh Iyer',
            'Meena Kumari',
            'Ravi Shankar'
        ];

        // Define sample addresses
        $addresses = [
            '123, MG Road, Bangalore, Karnataka - 560001',
            '456, Park Street, Kolkata, West Bengal - 700016',
            '789, Linking Road, Mumbai, Maharashtra - 400052',
            '321, Connaught Place, New Delhi - 110001',
            '654, Banjara Hills, Hyderabad, Telangana - 500034',
            '987, Boat Club Road, Pune, Maharashtra - 411001',
            '147, Civil Lines, Jaipur, Rajasthan - 302006',
            '258, Marine Drive, Chennai, Tamil Nadu - 600001',
            '369, Hazratganj, Lucknow, Uttar Pradesh - 226001',
            '741, FC Road, Ahmedabad, Gujarat - 380009'
        ];

        // Define sample descriptions
        $descriptions = [
            'Pool Cottage Room - Monthly Rent',
            'Lake View Suite - Monthly Rent',
            'Garden View Room - Monthly Rent',
            'Deluxe Room - Monthly Lease',
            'Executive Suite - Monthly Rent',
            'Family Cottage - Monthly Booking',
            'Honeymoon Suite - Special Package',
            'Presidential Suite - Monthly Lease',
            'Standard Room - Monthly Rent',
            'Premium Cottage - Monthly Rent',
            'Jacuzzi Suite - Monthly Rent',
            'Balcony Room - Monthly Package',
            'Terrace Suite - Monthly Booking',
            'Heritage Room - Monthly Stay',
            'Royal Cottage - Lease Rent'
        ];

        // Define sample GSTINs
        $gstins = [
            '05ANVPKB380R2ZS',
            '07ABCDE1234F1Z5',
            '09PQRST5678G2H6',
            '03JKLMN9012I3J7',
            '08UVWXY3456K4L8',
            '12ZABCD7890M5N9',
            '10EFGHI2345O6P1',
            '15JKLMN6789Q7R2',
            '22PQRST0123S8T3',
            '18UVWXY4567U9V4'
        ];

        $bills = [];
        $counter = 1;

        // Generate bills for different months
        $currentYear = 2024;
        $months = [1, 2, 3, 4, 5, 6]; // January to June 2024

        foreach ($months as $month) {
            $billsPerMonth = rand(5, 10);

            for ($i = 0; $i < $billsPerMonth; $i++) {
                $day = rand(1, 28);
                $billDate = Carbon::create($currentYear, $month, $day);

                $quantity = rand(1, 3);
                $rate = rand(2000, 15000);

                $subtotal = $rate * $quantity;
                $cgst = round($subtotal * 0.09, 2);
                $sgst = round($subtotal * 0.09, 2);
                $otherTaxes = rand(0, 1) ? rand(100, 500) : 0;
                $total = $subtotal + $cgst + $sgst + $otherTaxes;

                // Generate bill number using counter
                $yearMonth = $billDate->format('Ym');
                $billNumber = 'BILL-' . $yearMonth . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

                $bills[] = [
                    'bill_number' => $billNumber,
                    'guest_name' => $guests[array_rand($guests)],
                    'guest_address' => $addresses[array_rand($addresses)],
                    'gstin' => $gstins[array_rand($gstins)],
                    'bill_date' => $billDate->format('Y-m-d H:i:s'),
                    'description' => $descriptions[array_rand($descriptions)] . ' ' . $billDate->format('F Y'),
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'other_taxes' => $otherTaxes,
                    'total' => $total,
                    'created_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s'),
                    'updated_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s')
                ];

                $counter++;
            }
        }

        // Generate bills for previous year
        $previousYear = 2023;
        $prevYearMonths = [6, 7, 8, 9, 10, 11, 12];

        foreach ($prevYearMonths as $month) {
            $billsPerMonth = rand(3, 7);

            for ($i = 0; $i < $billsPerMonth; $i++) {
                $day = rand(1, 28);
                $billDate = Carbon::create($previousYear, $month, $day);

                $quantity = rand(1, 2);
                $rate = rand(1500, 12000);

                $subtotal = $rate * $quantity;
                $cgst = round($subtotal * 0.09, 2);
                $sgst = round($subtotal * 0.09, 2);
                $otherTaxes = rand(0, 1) ? rand(50, 400) : 0;
                $total = $subtotal + $cgst + $sgst + $otherTaxes;

                // Generate bill number using counter
                $yearMonth = $billDate->format('Ym');
                $billNumber = 'BILL-' . $yearMonth . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

                $bills[] = [
                    'bill_number' => $billNumber,
                    'guest_name' => $guests[array_rand($guests)],
                    'guest_address' => $addresses[array_rand($addresses)],
                    'gstin' => $gstins[array_rand($gstins)],
                    'bill_date' => $billDate->format('Y-m-d H:i:s'),
                    'description' => $descriptions[array_rand($descriptions)] . ' ' . $billDate->format('F Y'),
                    'rate' => $rate,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'other_taxes' => $otherTaxes,
                    'total' => $total,
                    'created_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s'),
                    'updated_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s')
                ];

                $counter++;
            }
        }

        // Add specific bills matching your PDF examples
        $specificDates = [
            ['date' => '2023-07-18', 'guest' => 'Leake Point Tune', 'address' => 'Ramnagar, Nainital', 'gstin' => '05ANVPKB380R2ZS'],
            ['date' => '2023-07-18', 'guest' => 'Man Mohan', 'address' => 'GSTIN-0511TECR7U0LZEE', 'gstin' => '0511TECR7U0LZEE']
        ];

        foreach ($specificDates as $index => $specific) {
            $billDate = Carbon::parse($specific['date']);
            $yearMonth = $billDate->format('Ym');
            $billNumber = 'BILL-' . $yearMonth . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

            $subtotal = 50000;
            $cgst = round($subtotal * 0.09, 2);
            $sgst = round($subtotal * 0.09, 2);

            $bills[] = [
                'bill_number' => $billNumber,
                'guest_name' => $specific['guest'],
                'guest_address' => $specific['address'],
                'gstin' => $specific['gstin'],
                'bill_date' => $billDate->format('Y-m-d H:i:s'),
                'description' => ($index == 0 ? 'Leake Point Tune' : 'Levele Rent Jute') . ' - June Rent',
                'rate' => 50000,
                'quantity' => 1,
                'subtotal' => $subtotal,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'other_taxes' => 5000,
                'total' => $subtotal + $cgst + $sgst + 5000,
                'created_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s'),
                'updated_at' => $billDate->copy()->setTime(rand(9, 18), rand(0, 59))->format('Y-m-d H:i:s')
            ];

            $counter++;
        }

        // Insert all bills in chunks
        foreach (array_chunk($bills, 50) as $chunk) {
            Bill::insert($chunk);
        }

        $this->command->info('Successfully seeded ' . count($bills) . ' bills!');
    }
}
