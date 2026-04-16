<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan {{ $year }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #333; margin: 25px; }
        h1 { font-size: 20px; color: #059669; margin: 0 0 5px 0; }
        .subtitle { font-size: 10px; color: #666; margin-bottom: 15px; }
        .summary { background: #f0fdf4; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .summary .amount { font-size: 20px; font-weight: bold; color: #059669; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f0fdf4; color: #059669; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; border-bottom: 2px solid #059669; }
        table td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        table tr:nth-child(even) td { background: #fafafa; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <h1>Laporan Pendapatan Tahun {{ $year }}</h1>
    <div class="subtitle">Dicetak: {{ now()->format('d/m/Y') }}</div>
    <div class="summary"><p>Total Pendapatan</p><p class="amount">Rp {{ number_format($totalTahunan, 0, ',', '.') }}</p></div>
    <table>
        <thead><tr><th>No</th><th>Tanggal</th><th>Peserta</th><th>Paket</th><th>Metode</th><th class="text-right">Nominal</th></tr></thead>
        <tbody>
            @foreach($transactions as $i => $t)
            <tr><td>{{ $i+1 }}</td><td>{{ $t->tanggal->format('d/m/Y') }}</td><td>{{ $t->peserta->nama ?? '-' }}</td><td>{{ $t->peserta->paketKursus->nama_paket ?? '-' }}</td><td>{{ ucfirst($t->metode) }}</td><td class="text-right">Rp {{ number_format($t->nominal,0,',','.') }}</td></tr>
            @endforeach
        </tbody>
        <tfoot><tr><td colspan="5" style="font-weight:bold;text-align:right;padding-top:10px">Total:</td><td class="text-right" style="font-weight:bold;padding-top:10px">Rp {{ number_format($totalTahunan,0,',','.') }}</td></tr></tfoot>
    </table>
    <div class="footer">Harmonis Academy — Management System</div>
</body>
</html>
