<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi - {{ $kwitansi->nomor_order }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }

        .kwitansi-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 15mm 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            min-height: 297mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10mm;
        }

        .left-section {
            text-align: left;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        .company-tagline {
            font-size: 8pt;
            color: #666;
            letter-spacing: 4px;
            margin-bottom: 5mm;
        }

        .kwitansi-title {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 0;
            line-height: 1;
        }

        .right-section {
            text-align: right;
        }

        .invoice-number {
            font-size: 10pt;
            margin-bottom: 2mm;
        }

        .date-row {
            font-size: 10pt;
        }

        .main-content {
            margin: 8mm 0;
        }

        .info-row {
            display: flex;
            margin-bottom: 4mm;
            font-size: 11pt;
            line-height: 1.4;
        }

        .info-label {
            font-weight: normal;
            min-width: 45mm;
            flex-shrink: 0;
        }

        .info-separator {
            margin: 0 3mm;
        }

        .info-value {
            flex: 1;
            font-weight: normal;
        }

        .keterangan-section {
            margin: 10mm 0;
            border: 1.5pt solid #000;
            padding: 0;
        }

        .keterangan-header {
            background: #000;
            color: white;
            padding: 2mm 4mm;
            font-weight: bold;
            font-size: 10pt;
        }

        .keterangan-content {
            padding: 5mm 4mm;
        }

        .keterangan-row {
            display: flex;
            margin-bottom: 3mm;
            font-size: 11pt;
            line-height: 1.4;
        }

        .keterangan-row:last-child {
            margin-bottom: 0;
        }

        .keterangan-label {
            min-width: 40mm;
            flex-shrink: 0;
        }

        .keterangan-separator {
            margin: 0 3mm;
        }

        .keterangan-value {
            flex: 1;
        }

        .totals-section {
            margin: 8mm 0;
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1.4;
        }

        .total-label {
            margin-right: 3mm;
        }

        .total-separator {
            margin: 0 3mm;
        }

        .total-value {
            min-width: 35mm;
            text-align: left;
        }

        .payment-info {
            margin-top: 12mm;
            border: 1pt solid #ccc;
            padding: 5mm;
        }

        .payment-info-title {
            font-weight: bold;
            margin-bottom: 3mm;
            font-size: 10pt;
        }

        .bank-info {
            font-size: 10pt;
        }

        .bank-row {
            margin-bottom: 2mm;
            display: flex;
        }

        .bank-row:last-child {
            margin-bottom: 0;
        }

        .bank-label {
            display: inline-block;
            min-width: 30mm;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .kwitansi-container {
                box-shadow: none;
                padding: 15mm 20mm;
                max-width: 210mm;
                min-height: auto;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #333;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Cetak Kwitansi</button>

    <div class="kwitansi-container">
        <div class="header">
            <div class="left-section">
                <div class="company-name">Udizital</div>
                <div class="company-tagline">C R E A T I V E&nbsp;&nbsp;&nbsp;&nbsp;A G E N C Y</div>
                <h1 class="kwitansi-title">Kwitansi</h1>
            </div>
            <div class="right-section">
                <div class="invoice-number">#{{ $kwitansi->nomor_order }}</div>
                <div class="date-row">Tgl: {{ $tanggal }}</div>
            </div>
        </div>

        <div class="main-content">
            <div class="info-row">
                <div class="info-label">Telah Diterima dari</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $kwitansi->nama_klien }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Uang Sejumlah</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ number_format($kwitansi->total / 1000, 1) }}jt</div>
            </div>

            <div class="info-row">
                <div class="info-label">Untuk</div>
                <div class="info-separator">:</div>
                <div class="info-value">{{ $kwitansi->deskripsi }}</div>
            </div>
        </div>

        <div class="keterangan-section">
            <div class="keterangan-header">KETERANGAN</div>
            <div class="keterangan-content">
                <div class="keterangan-row">
                    <div class="keterangan-label">SUB-TOTAL</div>
                    <div class="keterangan-separator">:</div>
                    <div class="keterangan-value">{{ number_format($kwitansi->sub_total / 1000000, 0) }} JT</div>
                </div>
                <div class="keterangan-row">
                    <div class="keterangan-label">FEE MAINTENANCE</div>
                    <div class="keterangan-separator">:</div>
                    <div class="keterangan-value">{{ number_format(($kwitansi->fee_maintenance / $kwitansi->sub_total) * 100, 0) }}%</div>
                </div>
            </div>
        </div>

        <div class="totals-section">
            <div class="total-row">
                <div class="total-label">TOTAL</div>
                <div class="total-separator">:</div>
                <div class="total-value">{{ number_format($kwitansi->total / 1000000, 1) }} JT</div>
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-info-title">INFO PEMBAYARAN :</div>
            <div class="bank-info">
                <div class="bank-row">
                    <span class="bank-label">BANK</span>
                    <span>: BRI</span>
                </div>
                <div class="bank-row">
                    <span class="bank-label">No. Rekening</span>
                    <span>: 4030-01-011093-53-6</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print ketika halaman selesai loading (opsional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>