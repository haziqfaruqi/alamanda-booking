<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Alamanda Houseboat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 30px;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .header h2 {
            font-size: 14px;
            font-weight: normal;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .header .invoice-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .header .invoice-info div {
            text-align: left;
        }

        .header .invoice-info div:last-child {
            text-align: right;
        }

        /* Contact Section */
        .contact {
            text-align: center;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .contact p {
            margin: 2px 0;
            letter-spacing: 1px;
        }

        /* Billed To Section */
        .billed-to {
            margin-bottom: 20px;
        }

        .billed-to h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            text-decoration: underline;
        }

        .billed-to p {
            margin: 3px 0;
            font-size: 12px;
        }

        /* Table Section */
        .table-section {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #333;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        /* Total Section */
        .total-section {
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        .total-row.final {
            border-top: 2px solid #333;
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 12px;
        }

        /* Amount in Words */
        .amount-words {
            margin: 20px 0;
            padding: 10px;
            background: #f5f5f5;
            font-size: 11px;
        }

        .amount-words strong {
            text-decoration: underline;
        }

        /* Due Date Notice */
        .due-notice {
            margin: 20px 0;
            padding: 12px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            font-size: 11px;
        }

        .due-notice strong {
            color: #856404;
        }

        /* Signature Section */
        .signature {
            margin-top: 30px;
        }

        .signature-box {
            text-align: left;
        }

        .signature-box p {
            margin: 5px 0;
            font-size: 11px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin: 30px 0 5px 0;
        }

        /* Bank Info */
        .bank-info {
            margin-top: 25px;
            padding: 15px;
            background: #f9f9f9;
            font-size: 10px;
        }

        .bank-info h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .bank-info p {
            margin: 3px 0;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        /* Status Badge */
        .status-pending {
            background: #ffc107;
            color: #333;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>H O U S E B O A T &nbsp; T A S I K &nbsp; K E N Y I R</h1>
            <h2>a l a m a n d a h o u s e b o a t k e n y i r @ g m a i l . c o m</h2>
            <div class="contact">
                <p>+ 6 0 1 4 - 3 3 4 8 3 4 4</p>
            </div>
        </div>

        <!-- Invoice Info -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
            <div>
                <strong>DATE :</strong> {{ $invoiceDate }}
            </div>
            <div style="text-align: right;">
                <strong>INVOICE NO :</strong> {{ $invoiceNumber }}
            </div>
        </div>

        <!-- Billed To -->
        <div class="billed-to">
            <h3>BILL TO:</h3>
            <p><strong>{{ strtoupper($booking->contact_name) }}</strong></p>
            <p>{{ $booking->contact_email }}</p>
            <p>{{ $booking->contact_phone ?? '-' }}</p>
        </div>

        <!-- Items Table -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>PERKARA (ITEM)</th>
                        <th class="text-right">KADAR (RATE)</th>
                        <th class="text-right">JUMLAH (AMOUNT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $booking->package->name ?? 'PACKAGE' }}</strong><br>
                            <small>Package: {{ strtoupper($booking->duration ?? 'N/A') }}</small><br>
                            <small>Check-In: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</small><br>
                            <small>Check-Out: {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</small>
                        </td>
                        <td class="text-right">RM {{ number_format($booking->total_price / $booking->total_guests, 2) }} x {{ $booking->total_guests }}</td>
                        <td class="text-right">RM {{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                    @if($booking->guests->count() > 0)
                    <tr>
                        <td colspan="2">
                            <strong>GUESTS:</strong>
                            @foreach($booking->guests->take(5) as $guest)
                                {{ $guest->guest_name }}@if(!$loop->last), @endif
                            @endforeach
                            @if($booking->guests->count() > 5)
                                +{{ $booking->guests->count() - 5 }} others
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="total-section">
            @if($booking->original_price && $booking->original_price > $booking->total_price)
                <div class="total-row">
                    <span>ORIGINAL PRICE</span>
                    <span>RM {{ number_format($booking->original_price, 2) }}</span>
                </div>
                @if($booking->coupon_code)
                <div class="total-row" style="color: #28a745;">
                    <span>DISCOUNT ({{ $booking->coupon_code }})</span>
                    <span>-RM {{ number_format($booking->discount_amount, 2) }}</span>
                </div>
                @endif
            @endif
            <div class="total-row final">
                <span>JUMLAH (TOTAL)</span>
                <span>RM {{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        <!-- Amount in Words -->
        <div class="amount-words">
            <strong>RINGGIT MALAYSIA :</strong> {{ $amountInWords }} RINGGIT SAHAJA
        </div>

        <!-- Due Date Notice -->
        <div class="due-notice">
            <strong>TEMPOH PEMBAYARAN:</strong> {{ $dueDate }}<br>
            <em>Sila buat pembayaran dalam tempoh 3 hari dari tarikh invoice.</em>
        </div>

        <!-- Payment Info -->
        <div class="bank-info">
            <h4>PAYMENT INFORMATION - {{ $bankName }}</h4>
            <p><strong>Account Name:</strong> {{ $accountName }}</p>
            <p><strong>Account No.:</strong> {{ $accountNumber }}</p>
            <p style="margin-top: 10px;"><strong>STATUS: <span class="status-pending">UNPAID / BELUM BAYAR</span></strong></p>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-box">
                <p>PENGURUS</p>
                <div class="signature-line"></div>
                <p><strong>ALAMANDA HOUSEBOAT</strong></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice. Please make payment to confirm your booking.</p>
            <p>Thank you for choosing Alamanda Houseboat!</p>
        </div>
    </div>
</body>
</html>
