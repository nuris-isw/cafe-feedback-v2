<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #444; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { background: #d97706; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; background: #fffbeb; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 20px; }
        .quote { font-style: italic; border-left: 4px solid #d97706; padding-left: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Respon Feedback - Kafe Anora</h2>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $feedback->visitor_name }}</strong>,</p>
            <p>Terima kasih telah meluangkan waktu untuk memberikan ulasan kepada kami. Berikut adalah tanggapan dari tim kami mengenai ulasan Anda:</p>
            
            <div class="quote">
                "{{ $feedback->admin_response }}"
            </div>

            <p>Kami berharap dapat memberikan layanan yang lebih baik lagi pada kunjungan Anda berikutnya!</p>
            <p>Salam hangat,<br><strong>Manajemen Kafe Anora</strong></p>
        </div>
        <div class="footer">
            Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>