<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceivedNotification;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show the payment page for a booking
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['package', 'guests'])
            ->where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Redirect if already paid
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings')->with('success', 'Payment already completed!');
        }

        return view('user.payment', compact('booking'));
    }

    /**
     * Process payment
     */
    public function process(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($booking->payment_status === 'paid') {
            return back()->withErrors(['error' => 'Payment already completed.']);
        }

        $paymentMethod = $request->input('payment_method');

        // Validate based on payment method
        $rules = [
            'payment_method' => 'required|in:bank_transfer,fpx,toyyibpay',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'reference_number' => 'nullable|string|max:255',
        ];

        // Add bank transfer specific rules
        if ($paymentMethod === 'bank_transfer') {
            $rules['receipt'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['reference_number'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, [
            'receipt.uploaded' => 'Please upload your receipt.',
            'receipt.required' => 'Please upload your receipt.',
            'receipt.mimes' => 'Receipt must be a JPG, PNG, or PDF file.',
            'receipt.max' => 'Receipt file size must be less than 5MB.',
            'reference_number.required' => 'Please enter the reference number.',
        ]);

        $uploadedReceiptPath = null;
        $paymentReference = null;
        $paymentStatus = 'unpaid';

        // Handle based on payment method
        switch ($paymentMethod) {
            case 'bank_transfer':
                // Upload receipt as proof of payment
                if ($request->hasFile('receipt')) {
                    $uploadedReceiptPath = $request->file('receipt')->store('payment_proofs', 'public');
                }
                $paymentReference = $validated['reference_number'] ?? null;
                $paymentStatus = 'paid'; // Set to paid after receipt upload
                break;

            case 'fpx':
                // In real implementation, redirect to FPX gateway
                // For now, mark as paid
                $paymentStatus = 'paid';
                $paymentReference = 'FPX_' . strtoupper(uniqid());
                break;

            case 'toyyibpay':
                // In real implementation, redirect to ToyyibPay gateway
                // For now, mark as paid
                $paymentStatus = 'paid';
                $paymentReference = 'TOYYIBPAY_' . strtoupper(uniqid());
                break;
        }

        // Update booking with payment info
        $booking->update([
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'payment_proof_path' => $uploadedReceiptPath,
            'payment_status' => $paymentStatus,
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Generate receipt PDF after successful payment
        if ($paymentStatus === 'paid') {
            $this->generateReceipt($booking);

            // Send email notification to staff
            $this->notifyStaffPaymentReceived($booking);
        }

        return redirect()->route('bookings')->with('success', 'Payment submitted successfully! Your booking is confirmed.');
    }

    /**
     * Send email notification to staff about new payment
     */
    private function notifyStaffPaymentReceived($booking)
    {
        $staffEmail = config('mail.staff_email', env('STAFF_EMAIL', 'alamandahouseboatkenyir@gmail.com'));
        $receiptUrl = $booking->receipt_path ? url('/receipt/' . $booking->id . '/view') : '';

        Mail::to($staffEmail)->send(new PaymentReceivedNotification($booking, $receiptUrl));
    }

    /**
     * Generate receipt PDF after payment
     */
    private function generateReceipt($booking)
    {
        // Reload booking with relationships
        $booking = Booking::with(['package', 'user', 'guests'])->find($booking->id);

        // Generate receipt number
        $receiptNumber = 'RCP-' . date('Ymd') . '-' . str_pad(strval($booking->id), 3, '0', STR_PAD_LEFT);

        // Prepare data for receipt
        $data = [
            'invoiceNumber' => $receiptNumber,
            'invoiceDate' => strtoupper(\Carbon\Carbon::parse($booking->confirmed_at)->format('d F Y')),
            'booking' => $booking,
            'amountInWords' => $this->numberToWords(round($booking->total_price)),
            'bankName' => 'HONG LEONG BANK',
            'accountName' => 'SYAHDINA AIMAN BT KAMARUDDIN',
            'accountNumber' => '305-51-060609',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('receipts.receipt', $data);
        $pdf->setPaper('A4');

        // Save the PDF
        $filename = "receipt_{$booking->id}_" . time() . ".pdf";
        $path = "receipts/{$filename}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory('receipts');

        // Save to storage
        Storage::disk('public')->put($path, $pdf->output());

        // Update booking with receipt path
        $booking->receipt_path = $path;
        $booking->save();
    }

    /**
     * Convert number to words (Malaysian Ringgit format)
     */
    private function numberToWords($number)
    {
        $ones = [
            '', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'LAPAN', 'SEMBILAN',
            'SEPULUH', 'SEBELAS', 'DUA BELAS', 'TIGA BELAS', 'EMPAT BELAS', 'LIMA BELAS',
            'ENAM BELAS', 'TUJUH BELAS', 'LAPAN BELAS', 'SEMBILAN BELAS'
        ];

        $tens = [
            '', 'SEPULUH', 'DUA PULUH', 'TIGA PULUH', 'EMPAT PULUH', 'LIMA PULUH',
            'ENAM PULUH', 'TUJUH PULUH', 'LAPAN PULUH', 'SEMBILAN PULUH'
        ];

        $scales = ['RINGGIT', 'RIBU', 'JUTA', 'BILLION'];

        if ($number == 0) {
            return 'KOSONG';
        }

        $num = intval($number);
        $words = [];
        $scaleIndex = 0;

        while ($num > 0) {
            $chunk = $num % 1000;
            $num = floor($num / 1000);

            if ($chunk > 0) {
                $chunkWords = $this->convertChunk($chunk, $ones, $tens);
                if (isset($scales[$scaleIndex])) {
                    $chunkWords .= ' ' . $scales[$scaleIndex];
                }
                array_unshift($words, $chunkWords);
            }
            $scaleIndex++;
        }

        return implode(' ', $words);
    }

    /**
     * Convert a number chunk (less than 1000) to words
     */
    private function convertChunk($number, $ones, $tens)
    {
        $words = '';

        if ($number >= 100) {
            $hundreds = floor($number / 100);
            $words .= ($hundreds == 1 ? 'SERIBU' : $ones[$hundreds] . ' RATUS');
            $number %= 100;
        }

        if ($number >= 20) {
            $tenWords = $tens[floor($number / 10)];
            $remainder = $number % 10;
            if ($remainder > 0) {
                $tenWords = $tenWords . ' ' . $ones[$remainder];
            }
            $words = $words . ' ' . $tenWords;
        } elseif ($number > 0) {
            $words = $words . ' ' . $ones[$number];
        }

        return trim($words);
    }
}
