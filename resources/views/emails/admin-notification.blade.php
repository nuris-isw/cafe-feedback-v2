<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .header { padding: 20px; text-align: center; color: white; }
        .urgent { background-color: #dc2626; } /* Merah */
        .normal { background-color: #d97706; } /* Amber */
        .content { padding: 20px; line-height: 1.6; color: #374151; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-transform: uppercase; background: #f3f4f6; }
        .footer { padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; background: #f9fafb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $feedback->rating <= 2 ? 'urgent' : 'normal' }}">
            <h2>{{ $feedback->rating <= 2 ? 'PERLU TINDAKAN SEGERA' : 'Feedback Baru Masuk' }}</h2>
        </div>
        
        <div class="content">
            <p>Halo Admin, ada feedback baru dari <strong>{{ $feedback->visitor_name }}</strong>.</p>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Rating:</td>
                    <td style="padding: 8px 0; font-weight: bold; color: {{ $feedback->rating <= 2 ? '#dc2626' : '#d97706' }}">
                        {{ $feedback->rating }} / 5 ⭐
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Dimensi:</td>
                    <td style="padding: 8px 0;"><span class="badge">{{ $feedback->category }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #6b7280;">Komentar:</td>
                    <td style="padding: 8px 0;">"{{ $feedback->comment ?? '-' }}"</td>
                </tr>
            </table>

            <div style="margin-top: 25px; text-align: center;">
                <a href="{{ url('/dashboard') }}" style="background-color: #1f2937; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    Buka Dashboard Admin
                </a>
            </div>
        </div>
        
        <div class="footer">
            Sistem Notifikasi Otomatis - Kafe Anora
        </div>
    </div>
</body>
</html>