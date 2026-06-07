<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kurban - {{ $sertifikat->nomor_sertifikat }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 40px;
            color: #333;
        }
        .border-frame {
            border: 3px solid #8B7355;
            padding: 30px;
            position: relative;
        }
        .inner-border {
            border: 1px solid #C4A96A;
            padding: 25px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header .icon {
            font-size: 40px;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 24px;
            color: #8B7355;
            margin: 0 0 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            font-size: 14px;
            color: #666;
            margin: 0;
            font-weight: normal;
        }
        .divider {
            border-top: 2px solid #C4A96A;
            margin: 20px 0;
        }
        .content {
            text-align: center;
            margin: 30px 0;
        }
        .content .label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .content .value {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .content .value-hewan {
            font-size: 18px;
            font-weight: bold;
            color: #8B7355;
            margin-bottom: 20px;
        }
        .content .value-tanggal {
            font-size: 14px;
            color: #666;
        }
        .info-table {
            margin: 20px auto;
            width: 80%;
        }
        .info-table tr td {
            padding: 5px 10px;
            font-size: 12px;
        }
        .info-table tr td:first-child {
            width: 140px;
            font-weight: bold;
            color: #555;
        }
        .info-table tr td:last-child {
            text-align: left;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #999;
        }
        .footer .nomor {
            font-size: 11px;
            color: #8B7355;
            margin-top: 10px;
        }
        .stamp {
            text-align: center;
            margin-top: 30px;
        }
        .stamp .nama {
            margin-top: 60px;
            font-weight: bold;
            font-size: 13px;
        }
        .stamp .jabatan {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="border-frame">
        <div class="inner-border">
            <div class="header">
                <div class="icon">&#x1F3F0;</div>
                <h1>Sertifikat Kurban</h1>
                <h2>1447 H / 2026 M</h2>
            </div>

            <div class="divider"></div>

            <div class="content">
                <div class="label">Diberikan kepada</div>
                <div class="value">{{ $sertifikat->nama_peserta }}</div>

                <div class="label">Sebagai peserta kurban</div>
                <div class="value-hewan">{{ $sertifikat->jenis_hewan }}</div>

                <div class="label">Telah dilaksanakan penyembelihan pada</div>
                <div class="value-tanggal">{{ \Carbon\Carbon::parse($sertifikat->tanggal_penyembelihan)->format('d F Y') }}</div>
            </div>

            <div class="divider"></div>

            <table class="info-table">
                <tr>
                    <td>Nomor Sertifikat</td>
                    <td>: {{ $sertifikat->nomor_sertifikat }}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>: {{ $pelaksanaan->Lokasi ?? '________' }}</td>
                </tr>
                <tr>
                    <td>Nomor Order</td>
                    <td>: #{{ $order->id }}</td>
                </tr>
            </table>

            <div class="stamp">
                <div>Panitia Kurban,</div>
                <div class="nama">{{ $pelaksanaan->Ketuplak ?? 'Panitia' }}</div>
                <div class="jabatan">Ketua Panitia</div>
            </div>

            <div class="footer">
                <p>Dokumen ini adalah bukti sah partisipasi ibadah kurban</p>
                <div class="nomor">{{ $sertifikat->nomor_sertifikat }}</div>
            </div>
        </div>
    </div>
</body>
</html>
