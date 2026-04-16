<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Peserta — {{ $peserta->nama }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12px; color: #333; margin: 30px; }
        .header { margin-bottom: 15px; }
        .header .date { font-size: 10px; color: #666; margin-bottom: 5px; }
        .header h1 { font-size: 18px; color: #059669; margin: 0 0 15px 0; font-weight: bold; }
        .header h1::after { content:''; display:block; width:100%; height:2px; background:#059669; margin-top:8px; }
        .info-date { font-size: 10px; color: #666; margin-bottom: 20px; background: #f0fdf4; padding: 5px 10px; border-radius: 4px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table td { padding: 10px 12px; border: 1px solid #e5e7eb; vertical-align: top; }
        table td:first-child { font-weight: bold; color: #374151; width: 35%; background: #f9fafb; }
        table td:last-child { color: #111827; }
        .photo-section { margin: 20px 0; }
        .photo-section h3 { font-size: 14px; color: #059669; margin-bottom: 10px; }
        .photo-section img { max-width: 200px; max-height: 200px; border: 2px solid #e5e7eb; border-radius: 8px; }
        .photos-grid { display: flex; gap: 20px; }
        .photo-box { text-align: center; }
        .photo-box p { font-size: 10px; color: #666; margin-top: 5px; }
        .footer { text-align: center; margin-top: 30px; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="date">{{ now()->format('d/m/Y H:i') }}</div>
        <h1>Data Peserta Kursus</h1>
    </div>
    <div class="info-date">Dicetak: {{ now()->format('d/m/Y') }}</div>

    <table>
        <tr><td>Nama</td><td>{{ $peserta->nama }}</td></tr>
        <tr><td>NIK</td><td>{{ $peserta->nik }}</td></tr>
        <tr><td>No HP</td><td>{{ $peserta->no_hp }}</td></tr>
        <tr><td>Email</td><td>{{ $peserta->email ?? '-' }}</td></tr>
        <tr><td>Alamat</td><td>{{ $peserta->alamat }}</td></tr>
        <tr><td>Tgl Masuk</td><td>{{ $peserta->tgl_masuk->format('d F Y') }}</td></tr>
        <tr><td>Paket</td><td>{{ $peserta->paketKursus->nama_paket ?? '-' }}</td></tr>
        <tr><td>Status Bayar</td><td>{{ ucfirst(str_replace('_', ' ', $peserta->status_bayar)) }}</td></tr>
        <tr><td>Biaya Pengajar</td><td>Rp {{ number_format($peserta->biaya_pengajar, 0, ',', '.') }}</td></tr>
        <tr><td>Status</td><td>{{ ucfirst($peserta->status_pendaftaran) }}</td></tr>
        <tr><td>Deskripsi</td><td>{{ $peserta->deskripsi ?? '-' }}</td></tr>
    </table>

    @if($fotoBase64 || $ktpBase64)
    <div class="photo-section">
        <h3>Dokumen</h3>
        <div class="photos-grid">
            @if($fotoBase64)
            <div class="photo-box">
                <img src="{{ $fotoBase64 }}" alt="Foto Profil">
                <p>Foto Profil</p>
            </div>
            @endif
            @if($ktpBase64)
            <div class="photo-box">
                <img src="{{ $ktpBase64 }}" alt="KTP/SIM">
                <p>KTP/SIM</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">Harmonis Academy — Management System</div>
</body>
</html>
