<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.2; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #B45309; padding-bottom: 10px; }
        .header h1 { color: #B45309; margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { font-style: italic; color: #666; margin: 5px 0; font-size: 10px; }

        /* Card Layout dengan Warna */
        .grid { width: 100%; margin-bottom: 15px; border-spacing: 10px; border-collapse: separate; }
        .card { padding: 12px; border-radius: 8px; text-align: center; }
        .card-title { font-size: 9px; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; opacity: 0.8; }
        .card-value { font-size: 18px; font-weight: bold; }

        /* Variasi Warna Card */
        .bg-blue { background-color: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }
        .bg-amber { background-color: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; }
        .bg-red { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
        .bg-green { background-color: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }

        /* Category Grid */
        .cat-grid { width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 20px; }
        .cat-card { background: #F3F4F6; border: 1px solid #D1D5DB; padding: 8px; text-align: center; border-radius: 6px; }
        .cat-name { font-size: 8px; font-weight: bold; color: #4B5563; text-transform: uppercase; }
        .cat-val { font-size: 12px; color: #B45309; font-weight: bold; }

        /* Table Style */
        table.data-table { width: 100%; border-collapse: collapse; font-size: 9px; }
        table.data-table th { background-color: #B45309; color: white; padding: 8px; text-transform: uppercase; border: 1px solid #92400E; }
        table.data-table td { padding: 6px; border: 1px solid #ccc; vertical-align: top; }
        .row-urgent { background-color: #FFF1F2; }
        .text-center { text-align: center; }
        
        .status-label { font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .respon-box { font-style: italic; color: #444; margin-top: 4px; border-top: 1px dashed #ccc; padding-top: 4px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Analisis Feedback Pengunjung</h1>
        <p>Kafe Anora - Periode: {{ $period }}</p> {{-- Menampilkan rentang tanggal --}}
        <p>Dicetak pada: {{ $date }}</p>
    </div>

    {{-- CARD STATUS UTAMA DENGAN WARNA --}}
    <table class="grid">
        <tr>
            <td class="card bg-blue" width="25%">
                <div class="card-title">Total Feedback</div>
                <div class="card-value">{{ $stats['total'] }}</div>
            </td>
            <td class="card bg-amber" width="25%">
                <div class="card-title">Rata-rata Rating</div>
                <div class="card-value">{{ $stats['average_rating'] }} / 5.0</div>
            </td>
            <td class="card bg-red" width="25%">
                <div class="card-title">Perlu Respon</div>
                <div class="card-value">{{ $stats['pending'] }}</div>
            </td>
            <td class="card bg-green" width="25%">
                <div class="card-title">Telah Direspon</div>
                <div class="card-value">{{ $stats['responded'] }}</div>
            </td>
        </tr>
    </table>

    {{-- RATING PER KATEGORI --}}
    <h3 style="font-size: 11px; font-weight: bold; color: #4B5563; text-transform: uppercase; margin-bottom: 5px; margin-left: 5px;">
    Rata-rata Rating per Kategori
    </h3>
    <table class="cat-grid">
        <tr>
            @foreach($categoryStats as $cat => $val)
                @php
                    // Logika Penentuan Warna Berdasarkan Nilai Rating
                    $bgColor = '#F3F4F6'; // Default Abu-abu
                    $textColor = '#4B5563';
                    
                    if ($val >= 4) {
                        $bgColor = '#DCFCE7'; // Hijau (Bagus)
                        $textColor = '#166534';
                    } elseif ($val >= 3) {
                        $bgColor = '#FEF3C7'; // Kuning (Peringatan/Standar)
                        $textColor = '#92400E';
                    } elseif ($val > 0) {
                        $bgColor = '#FEE2E2'; // Merah (Kritis)
                        $textColor = '#991B1B';
                    }
                @endphp
                <td class="cat-card" style="background-color: {{ $bgColor }}; border: 1px solid {{ $textColor }};">
                    <div class="cat-name" style="color: {{ $textColor }};">{{ $cat }}</div>
                    <div class="cat-val" style="color: {{ $textColor }};">{{ $val }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="15%">PENGUNJUNG</th>
                <th width="8%">KATEGORI</th>
                <th width="6%">RATING</th>
                <th width="28%">KOMENTAR PENGUNJUNG</th>
                <th width="28%">RESPON ADMIN</th>
                <th width="12%">STATUS & WAKTU</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feedbacks as $index => $f)
            <tr class="{{ $f->rating <= 2 ? 'row-urgent' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <b>{{ $f->visitor_name }}</b><br>
                    <span style="color: #666;">{{ $f->visitor_email }}</span>
                </td>
                <td class="text-center">{{ $f->category }}</td>
                <td class="text-center"><b>{{ $f->rating }}</b></td>
                <td>{{ $f->comment ?? '-' }}</td>
                <td>
                    @if($f->admin_response)
                        {{ $f->admin_response }}
                    @else
                        <span style="color: #999;">Belum ada respon</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="status-label" style="color: {{ $f->status == 'Responded' ? '#166534' : '#991B1B' }}">
                        {{ $f->status }}
                    </span><br>
                    <span style="font-size: 8px; color: #666;">{{ $f->created_at->format('d/m/y H:i') }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>