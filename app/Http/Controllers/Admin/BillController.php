<?php
// app/Http/Controllers/Admin/BillController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class BillController extends Controller
{
    protected $settings;

    public function __construct()
    {
        // Load settings for all methods
        $this->settings = Setting::first();
    }

    /**
     * Get GST rates from settings
     */
    protected function getGstRates()
    {
        $settings = $this->settings ?? Setting::first();
        return [
            'cgst' => $settings ? $settings->cgst : 9.00,
            'sgst' => $settings ? $settings->sgst : 9.00,
        ];
    }

    /**
     * Get validation rules with custom messages
     */
    protected function getValidationRules()
    {
        return [
            'guest_name' => 'nullable|string|max:255',
            'guest_address' => 'nullable|string|max:500',
            'gstin' => 'nullable|string|max:20|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9]{1}[Z]{1}[0-9]{1}$/',
            'guest_document_type' => 'nullable|in:aadhar,pan,voter_id,driving_license',
            'guest_document_number' => 'nullable|required_with:guest_document_type|string|max:50',
            'guest_document_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bill_date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:500',
            'rate' => 'required|numeric|min:0|max:999999.99',
            'quantity' => 'required|integer|min:1|max:999',
            'other_taxes' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    /**
     * Get custom validation messages
     */
    protected function getValidationMessages()
    {
        return [
            // Guest Name
            'guest_name.max' => 'Guest name cannot exceed 255 characters.',

            // Guest Address
            'guest_address.max' => 'Guest address cannot exceed 500 characters.',

            // GSTIN
            'gstin.max' => 'GSTIN cannot exceed 20 characters.',
            'gstin.regex' => 'Please enter a valid GSTIN format (e.g., 22AAAAA0000A1Z5).',

            // Document fields
            'guest_document_type.in' => 'Please select a valid document type.',
            'guest_document_number.required_with' => 'Document number is required when document type is selected.',
            'guest_document_number.max' => 'Document number cannot exceed 50 characters.',
            'guest_document_image.image' => 'Please upload a valid image file.',
            'guest_document_image.mimes' => 'Document image must be a JPEG, PNG, or JPG file.',
            'guest_document_image.max' => 'Document image size cannot exceed 2MB.',

            // Bill Date
            'bill_date.required' => 'Bill date is required.',
            'bill_date.date' => 'Please enter a valid date.',
            'bill_date.before_or_equal' => 'Bill date cannot be in the future.',

            // Description
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a valid text.',
            'description.max' => 'Description cannot exceed 500 characters.',

            // Rate
            'rate.required' => 'Rate is required.',
            'rate.numeric' => 'Rate must be a valid number.',
            'rate.min' => 'Rate cannot be negative.',
            'rate.max' => 'Rate cannot exceed 999,999.99.',

            // Quantity
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 999.',

            // Other Taxes
            'other_taxes.numeric' => 'Other taxes must be a valid number.',
            'other_taxes.min' => 'Other taxes cannot be negative.',
            'other_taxes.max' => 'Other taxes cannot exceed 999,999.99.',
        ];
    }

    /**
     * Upload image to public folder
     */
    protected function uploadImage($file)
    {
        // Create directory if it doesn't exist
        $uploadPath = public_path('uploads/documents');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Generate unique filename
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Move file to public/uploads/documents
        $file->move($uploadPath, $fileName);

        // Return the path relative to public folder
        return 'uploads/documents/' . $fileName;
    }

    /**
     * Delete image from public folder
     */
    protected function deleteImage($imagePath)
    {
        if ($imagePath) {
            $fullPath = public_path($imagePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }

    /**
     * Display a listing of the bills with search.
     */
    public function index(Request $request)
    {
        $query = Bill::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'LIKE', "%{$search}%")
                    ->orWhere('guest_name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('gstin', 'LIKE', "%{$search}%")
                    ->orWhere('guest_document_number', 'LIKE', "%{$search}%");
            });
        }

        $bills = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new bill.
     */
    public function create()
    {
        $gstRates = $this->getGstRates();
        $documentTypes = Bill::getDocumentTypes();
        return view('admin.bills.create', compact('gstRates', 'documentTypes'));
    }

    /**
     * Store a newly created bill in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            $this->getValidationRules(),
            $this->getValidationMessages()
        );

        $gstRates = $this->getGstRates();

        // Calculate amounts with dynamic GST
        $subtotal = $request->rate * $request->quantity;
        $cgst = round($subtotal * ($gstRates['cgst'] / 100), 2);
        $sgst = round($subtotal * ($gstRates['sgst'] / 100), 2);
        $other_taxes = $request->other_taxes ?? 0;
        $total = $subtotal + $cgst + $sgst + $other_taxes;

        $data = $request->except('guest_document_image');
        $data['bill_number'] = Bill::generateBillNumber();
        $data['subtotal'] = $subtotal;
        $data['cgst'] = $cgst;
        $data['sgst'] = $sgst;
        $data['other_taxes'] = $other_taxes;
        $data['total'] = $total;

        // Handle document image upload to public folder
        if ($request->hasFile('guest_document_image')) {
            $data['guest_document_image'] = $this->uploadImage($request->file('guest_document_image'));
        }

        try {
            $bill = Bill::create($data);
            return redirect()->route('admin.bills.index')
                ->with('success', 'Bill created successfully. Bill Number: ' . $bill->bill_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create bill. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified bill.
     */
    public function show(Bill $bill)
    {
        $gstRates = $this->getGstRates();
        $documentTypes = Bill::getDocumentTypes();
        return view('admin.bills.show', compact('bill', 'gstRates', 'documentTypes'));
    }

    /**
     * Show the form for editing the specified bill.
     */
    public function edit(Bill $bill)
    {
        $gstRates = $this->getGstRates();
        $documentTypes = Bill::getDocumentTypes();
        return view('admin.bills.edit', compact('bill', 'gstRates', 'documentTypes'));
    }

    /**
     * Update the specified bill in storage.
     */
    // public function update(Request $request, Bill $bill)
    // {
    //     $request->validate(
    //         $this->getValidationRules(),
    //         $this->getValidationMessages()
    //     );

    //     $gstRates = $this->getGstRates();

    //     // Calculate amounts with dynamic GST
    //     $subtotal = $request->rate * $request->quantity;
    //     $cgst = round($subtotal * ($gstRates['cgst'] / 100), 2);
    //     $sgst = round($subtotal * ($gstRates['sgst'] / 100), 2);
    //     $other_taxes = $request->other_taxes ?? 0;
    //     $total = $subtotal + $cgst + $sgst + $other_taxes;

    //     $data = $request->except('guest_document_image');
    //     $data['subtotal'] = $subtotal;
    //     $data['cgst'] = $cgst;
    //     $data['sgst'] = $sgst;
    //     $data['other_taxes'] = $other_taxes;
    //     $data['total'] = $total;

    //     // Handle document image upload to public folder
    //     if ($request->hasFile('guest_document_image')) {
    //         // Delete old image if exists
    //         if ($bill->guest_document_image) {
    //             $this->deleteImage($bill->guest_document_image);
    //         }

    //         $data['guest_document_image'] = $this->uploadImage($request->file('guest_document_image'));
    //     }

    //     try {
    //         $bill->update($data);
    //         return redirect()->route('admin.bills.index')
    //             ->with('success', 'Bill updated successfully. Bill Number: ' . $bill->bill_number);
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Failed to update bill. Please try again. ' . $e->getMessage());
    //     }
    // }

    /**
     * Update the specified bill in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $request->validate(
            $this->getValidationRules(),
            $this->getValidationMessages()
        );

        $gstRates = $this->getGstRates();

        // Calculate amounts with dynamic GST
        $subtotal = $request->rate * $request->quantity;
        $cgst = round($subtotal * ($gstRates['cgst'] / 100), 2);
        $sgst = round($subtotal * ($gstRates['sgst'] / 100), 2);
        $other_taxes = $request->other_taxes ?? 0;
        $total = $subtotal + $cgst + $sgst + $other_taxes;

        $data = $request->except('guest_document_image');
        $data['subtotal'] = $subtotal;
        $data['cgst'] = $cgst;
        $data['sgst'] = $sgst;
        $data['other_taxes'] = $other_taxes;
        $data['total'] = $total;

        // Handle document image removal
        if ($request->has('remove_document_image') && $request->remove_document_image == '1') {
            if ($bill->guest_document_image) {
                $this->deleteImage($bill->guest_document_image);
                $data['guest_document_image'] = null;
            }
        }

        // Handle new document image upload
        if ($request->hasFile('guest_document_image')) {
            // Delete old image if exists
            if ($bill->guest_document_image) {
                $this->deleteImage($bill->guest_document_image);
            }

            $data['guest_document_image'] = $this->uploadImage($request->file('guest_document_image'));
        }

        try {
            $bill->update($data);
            return redirect()->route('admin.bills.index')
                ->with('success', 'Bill updated successfully. Bill Number: ' . $bill->bill_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update bill. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified bill from storage.
     */
    public function destroy(Bill $bill)
    {
        try {
            // Delete document image if exists
            if ($bill->guest_document_image) {
                $this->deleteImage($bill->guest_document_image);
            }

            $billNumber = $bill->bill_number;
            $bill->delete();
            return redirect()->route('admin.bills.index')
                ->with('success', 'Bill deleted successfully. Bill Number: ' . $billNumber);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete bill. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Download bill as PDF (Pool Cottage Style)
     */
    public function downloadPoolPdf(Bill $bill)
    {
        $settings = $this->settings ?? Setting::first();

        $data = [
            'bill' => $bill,
            'settings' => $settings,
            'resort' => 'Pool & Cottages',
            'address' => 'VIII.- Dharampur, Chunakhan, P.O.- Bailpokhara, Ramnagar (Nainital) Uttarakhand',
            'mobile' => '+9536338199',
            'gstin' => '05ANVPKB380R2ZS'
        ];

        try {
            $pdf = PDF::loadView('admin.bills.pdf.pool-cottage', $data);
            return $pdf->download('bill-' . $bill->bill_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF. Please try again.');
        }
    }

    /**
     * Download bill as PDF (Soho Resort Style)
     */
    public function downloadSohoPdf(Bill $bill)
    {
        $settings = $this->settings ?? Setting::first();

        $data = [
            'bill' => $bill,
            'settings' => $settings,
            'resort' => 'SOHO CORBETT RESORT',
            'address' => 'Vill. Dharampur, Chunakhan, P.O. Bailpokhara, Ranmagar (Nainital) Uttarakhand',
            'mobile' => '+9536338199'
        ];

        try {
            $pdf = PDF::loadView('admin.bills.pdf.soho-resort', $data);
            return $pdf->download('bill-' . $bill->bill_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate PDF. Please try again.');
        }
    }

    /**
     * Print bill view
     */
    public function print(Bill $bill, $type_corbett)
    {
        if ($type_corbett == "SOHO CORBETT RESORT") {
            $type = $type_corbett;
        } else {
            $type = "POOL & COTTAGES";
        }
        $settings = $this->settings ?? Setting::first();
        return view('admin.bills.print', compact('bill', 'settings', 'type'));
    }
}
