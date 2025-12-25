<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class InvoiceController extends Controller
{
    /**
     * Show the invoice page for a booking
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['package', 'user', 'guests'])
            ->where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(strval($booking->id), 3, '0', STR_PAD_LEFT);

        // Bank details
        $bankDetails = [
            'bankName' => 'HONG LEONG BANK',
            'accountName' => 'SYAHDINA AIMAN BT KAMARUDDIN',
            'accountNumber' => '305-51-060609',
        ];

        return view('user.invoice', compact('booking', 'invoiceNumber', 'bankDetails'));
    }

    /**
     * Generate and download PDF invoice
     */
    public function generate($id)
    {
        $booking = Booking::with(['package', 'user', 'guests'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(strval($booking->id), 3, '0', STR_PAD_LEFT);

        // Prepare data for invoice
        $data = [
            'invoiceNumber' => $invoiceNumber,
            'invoiceDate' => strtoupper(\Carbon\Carbon::parse($booking->created_at)->format('d F Y')),
            'dueDate' => strtoupper(\Carbon\Carbon::parse($booking->created_at)->addDays(3)->format('d F Y')),
            'booking' => $booking,
            'amountInWords' => $this->numberToWords(round($booking->total_price)),
            'bankName' => 'HONG LEONG BANK',
            'accountName' => 'SYAHDINA AIMAN BT KAMARUDDIN',
            'accountNumber' => '305-51-060609',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('invoices.invoice', $data);
        $pdf->setPaper('A4');

        // Save the PDF
        $filename = "invoice_{$booking->id}_" . time() . ".pdf";
        $path = "invoices/{$filename}";

        // Ensure directory exists
        Storage::disk('public')->makeDirectory('invoices');

        // Save to storage
        Storage::disk('public')->put($path, $pdf->output());

        // Update booking with invoice path
        $booking->invoice_path = $path;
        $booking->save();

        // Download the PDF
        return $pdf->download("invoice_{$booking->id}.pdf");
    }

    /**
     * Download existing invoice
     */
    public function download($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!$booking->invoice_path) {
            return back()->with('error', 'Invoice not found. Please generate invoice first.');
        }

        $path = $booking->invoice_path;

        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Invoice file not found.');
        }

        $file = Storage::disk('public')->get($path);

        return Response::make($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice_' . $booking->id . '.pdf"',
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
