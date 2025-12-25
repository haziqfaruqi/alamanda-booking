<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ReceiptController extends Controller
{
    /**
     * Generate PDF receipt for a booking
     */
    public function generate($id)
    {
        $booking = Booking::with(['package', 'user', 'guests'])->findOrFail($id);

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(strval($booking->id), 3, '0', STR_PAD_LEFT);

        // Prepare data for receipt
        $data = [
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => strtoupper(\Carbon\Carbon::parse($booking->created_at)->format('d F Y')),
            'booking' => $booking,
            'amountInWords' => $this->numberToWords(round($booking->total_price)),
            'bankName' => 'HONG LEONG BANK',
            'accountName' => 'SYAHDINA AIMAN BT KAMARUDDIN',
            'accountNumber' => '305-51-060609',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('receipts.receipt', $data);

        // Set paper size
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

        // Download the PDF
        return $pdf->download("receipt_{$booking->id}.pdf");
    }

    /**
     * Download existing receipt
     */
    public function download($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->receipt_path) {
            return back()->with('error', 'Receipt not found. Please generate receipt first.');
        }

        $path = $booking->receipt_path;

        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Receipt file not found.');
        }

        $file = Storage::disk('public')->get($path);

        return Response::make($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="receipt_' . $booking->id . '.pdf"',
        ]);
    }

    /**
     * View receipt in browser
     */
    public function view($id)
    {
        $booking = Booking::with(['package', 'user', 'guests'])->findOrFail($id);

        if (!$booking->receipt_path) {
            // Generate receipt if it doesn't exist
            return $this->generate($id);
        }

        $path = $booking->receipt_path;

        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Receipt file not found.');
        }

        $file = Storage::disk('public')->get($path);

        return Response::make($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="receipt_' . $booking->id . '.pdf"',
        ]);
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
