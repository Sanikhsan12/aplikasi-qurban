<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #{{ $order->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0 0 5px; }
        .header h2 { font-size: 14px; margin: 0; font-weight: normal; }
        .header .nomor { font-size: 12px; margin-top: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; border-bottom: 1px solid #999; padding-bottom: 3px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        table.info tr td:first-child { width: 180px; font-weight: bold; }
        table.info tr td { padding: 3px 5px; vertical-align: top; }
        table.items { border: 1px solid #ccc; }
        table.items th { background: #f0f0f0; padding: 6px 8px; text-align: left; border: 1px solid #ccc; font-size: 11px; }
        table.items td { padding: 5px 8px; border: 1px solid #ccc; font-size: 11px; }
        .total-row td { font-weight: bold; background: #f9f9f9; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE PEMBAYARAN KURBAN</h1>
        <h2>{{ $pelaksanaan->Lokasi ?? 'Masjid / DKM' }}</h2>
        <div class="nomor">Invoice #{{ $order->id }}</div>
    </div>

    <div class="section">
        <div class="section-title">Informasi Order</div>
        <table class="info">
            <tr><td>Nomor Order</td><td>: #{{ $order->id }}</td></tr>
            <tr><td>Tanggal Order</td><td>: {{ $order->created_at->format('d F Y H:i') }}</td></tr>
            <tr><td>Status</td><td>: {{ $order->getStatusFormattedAttribute() }}</td></tr>
            <tr><td>Tipe Pendaftaran</td><td>: {{ ucfirst($order->tipe_pendaftaran) }}</td></tr>
            <tr><td>Jenis Hewan</td><td>: {{ $order->jenis_hewan }}</td></tr>
            <tr><td>Berat Hewan</td><td>: {{ number_format($order->berat_hewan, 1, ',', '.') }} kg</td></tr>
            <tr><td>Total Hewan</td><td>: {{ $order->total_hewan }} ekor</td></tr>
            <tr><td>Perkiraan Daging</td><td>: {{ number_format($order->perkiraan_daging, 1, ',', '.') }} kg</td></tr>
            <tr><td>Lokasi Penyembelihan</td><td>: {{ $pelaksanaan->Lokasi ?? '________' }}</td></tr>
            <tr><td>Tanggal Penyembelihan</td><td>: {{ $pelaksanaan && $pelaksanaan->Penyembelihan ? \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)->format('d F Y') : '________' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Data Pembeli</div>
        <table class="info">
            <tr><td>Nama</td><td>: {{ $order->user->name }}</td></tr>
            <tr><td>Email</td><td>: {{ $order->user->email }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Daftar Peserta Kurban</div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Peserta</th>
                    <th style="width: 100px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peserta as $index => $p)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $p->nama_peserta }}</td>
                        <td>{{ $p->is_buyer ? 'Pembeli' : 'Patungan' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Rincian Pembayaran</div>
        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="width: 150px; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Biaya {{ $order->jenis_hewan }} ({{ $order->total_hewan }} ekor)</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Pembayaran</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini adalah bukti invoice pembayaran kurban</p>
        <p>Invoice #{{ $order->id }} | {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
