<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Kontrak - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px;
        }
        .header h2 {
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        .header .nomor {
            font-size: 12px;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #999;
            padding-bottom: 3px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table.info tr td:first-child {
            width: 180px;
            font-weight: bold;
        }
        table.info tr td {
            padding: 3px 5px;
            vertical-align: top;
        }
        table.peserta {
            border: 1px solid #ccc;
        }
        table.peserta th {
            background: #f0f0f0;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #ccc;
            font-size: 11px;
        }
        table.peserta td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            font-size: 11px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .ttd {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-col {
            text-align: center;
            width: 45%;
        }
        .ttd-col .nama {
            margin-top: 60px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SURAT KONTRAK KURBAN</h1>
        <h2>Masjid / DKM {{ $pelaksanaan->Lokasi ?? '________' }}</h2>
        <div class="nomor">Nomor: {{ $kontrak->nomor_kontrak }}</div>
    </div>

    <div class="section">
        <div class="section-title">Data Transaksi</div>
        <table class="info">
            <tr>
                <td>Nomor Order</td>
                <td>: #{{ $order->id }}</td>
            </tr>
            <tr>
                <td>Jenis Hewan</td>
                <td>: {{ $order->jenis_hewan }}</td>
            </tr>
            <tr>
                <td>Berat Hewan</td>
                <td>: {{ number_format($order->berat_hewan, 1, ',', '.') }} kg</td>
            </tr>
            <tr>
                <td>Total Harga</td>
                <td>: Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: {{ $order->getPaymentStatusLabel() }}</td>
            </tr>
            <tr>
                <td>Tanggal Kontrak</td>
                <td>: {{ $kontrak->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lokasi Penyembelihan</td>
                <td>: {{ $pelaksanaan->Lokasi ?? '________' }}</td>
            </tr>
            <tr>
                <td>Tanggal Penyembelihan</td>
                <td>: {{ $pelaksanaan && $pelaksanaan->Penyembelihan ? \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)->format('d F Y') : '________' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Daftar Peserta Kurban</div>
        <table class="peserta">
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
        <div class="section-title">Ketentuan</div>
        <ol style="font-size: 11px;">
            <li>Peserta menyatakan kesediaan untuk berpartisipasi dalam ibadah kurban.</li>
            <li>Pembayaran dilakukan sesuai dengan ketentuan yang telah disepakati.</li>
            <li>Hewan kurban akan disembelih pada tanggal yang telah ditentukan oleh panitia.</li>
            <li>Daging kurban akan didistribusikan kepada yang berhak menerima.</li>
            <li>Sertifikat kurban akan diberikan setelah hewan disembelih.</li>
        </ol>
    </div>

    <div class="ttd">
        <div class="ttd-col">
            <div>Panitia Kurban,</div>
            <div class="nama">{{ $pelaksanaan->Ketuplak ?? 'Panitia' }}</div>
        </div>
        <div class="ttd-col">
            <div>Pembeli,</div>
            <div class="nama">
                @foreach ($peserta as $p)
                    @if ($p->is_buyer)
                        {{ $p->nama_peserta }}
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Manajemen Kurban</p>
        <p>{{ $kontrak->nomor_kontrak }} | {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
