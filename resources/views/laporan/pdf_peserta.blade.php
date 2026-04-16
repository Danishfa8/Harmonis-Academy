<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peserta Kursus Barbershop</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #333; margin: 25px; }
        h1 { font-size: 20px; color: #059669; margin: 0 0 5px 0; font-weight: bold; }
        .subtitle { font-size: 10px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background: #f0fdf4; color: #059669; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; border-bottom: 2px solid #059669; }
        table td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        table tr:nth-child(even) td { background: #fafafa; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <h1>Laporan Peserta Kursus Barbershop</h1>
    <div class="subtitle">
        Dicetak: {{ now()->format('d/m/Y') }} | Total: {{ $peserta->count() }} peserta
        @if(!empty($search))
            | Pencarian: "{{ $search }}"
        @endif
        @if(!empty($status))
            | Status: {{ ucfirst($status) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Paket</th>
                <th>Tgl Daftar</th>
                <th>Status</th>
                <th>No HP</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peserta as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->paketKursus->nama_paket ?? '-' }}</td>
                    <td>{{ $p->tgl_masuk->format('d M Y') }}</td>
                    <td>{{ ucfirst($p->status_pendaftaran) }}</td>
                    <td>{{ $p->no_hp }}</td>
                    <td>{{ $p->alamat }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #6b7280;">Tidak ada data peserta untuk diekspor.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Harmonis Academy — Management System</div>
</body>
</html>
