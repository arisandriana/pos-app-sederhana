<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ 'INV-' . str_pad($penjualan->PenjualanID, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            color: #000;
        }
        .invoice-container {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
        }
        .info {
            margin-bottom: 15px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 3px 0;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .totals p {
            margin: 2px 0;
            text-align: right;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer p {
            margin: 2px 0;
            font-size: 10px;
        }
        .bold {
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .totals .amount {
            font-weight: bold;
        }
        .status {
            font-weight: bold;
            margin-top: 5px;
            font-size: 14px;
            text-align: center;
            text-transform: uppercase;
        }
        .status.cancelled {
            color: #cc0000;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0mm;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 0;
                size: 80mm auto;
            }
        }
        .print-button {
            display: block;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            margin: 10px 0;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h2>{{ config('app.name', 'Laravel POS') }}</h2>
            <p>Jl. Contoh No. 123, Kota, 12345</p>
            <p>Telp: (021) 123-4567</p>
        </div>
        
        <div class="info">
            <p><strong>No. Invoice:</strong> {{ 'INV-' . str_pad($penjualan->PenjualanID, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Tanggal:</strong> 
                @if(is_object($penjualan->TanggalPenjualan))
                    {{ $penjualan->TanggalPenjualan->format('d/m/Y H:i') }}
                @else
                    {{ $penjualan->TanggalPenjualan }}
                @endif
            </p>
            <p><strong>Kasir:</strong> {{ $penjualan->pengguna->NamaPengguna ?? '-' }}</p>
            <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->NamaPelanggan ?? 'Umum' }}</p>
            
            @if($penjualan->Status === 'batal')
            <p class="status cancelled">DIBATALKAN</p>
            @endif
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detailPenjualan as $detail)
                <tr>
                    <td>{{ Str::limit($detail->produk->NamaProduk ?? 'Produk Tidak Ada', 20) }}</td>
                    <td class="text-right">{{ number_format($detail->HargaSatuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $detail->JumlahProduk }}</td>
                    <td class="text-right">{{ number_format($detail->Subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <p><strong>Total Item:</strong> <span class="amount">{{ $penjualan->detailPenjualan->sum('JumlahProduk') }} item</span></p>
            <p><strong>Total:</strong> <span class="amount">Rp {{ number_format($penjualan->TotalHarga, 0, ',', '.') }}</span></p>
            <p><strong>Bayar:</strong> <span class="amount">Rp {{ number_format($penjualan->Bayar, 0, ',', '.') }}</span></p>
            <p><strong>Kembali:</strong> <span class="amount">Rp {{ number_format($penjualan->Kembali, 0, ',', '.') }}</span></p>
        </div>
        
        <div class="footer">
            <p><strong>Terima Kasih</strong></p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan</p>
            <p>*{{ date('d/m/Y H:i:s') }}*</p>
        </div>
        
        <button class="print-button no-print" onclick="window.print()">
            Cetak Nota
        </button>
        
        <a href="{{ route('penjualan.show', $penjualan->PenjualanID) }}" class="print-button no-print" style="background-color: #6c757d;">
            Kembali
        </a>
    </div>
    
    <script>
        window.onload = function() {
            // Auto print when page loads
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>