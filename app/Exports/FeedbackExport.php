<?php

namespace App\Exports;

use App\Models\Feedback;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class FeedbackExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;
    private $rowNumber = 0; // Properti untuk nomor urut

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Feedback::query();
        if (!empty($this->filters['rating'])) $query->where('rating', $this->filters['rating']);
        if (!empty($this->filters['category'])) $query->where('category', $this->filters['category']);
        if (!empty($this->filters['status'])) $query->where('status', $this->filters['status']);
        
        // Menggunakan latest() agar data terbaru tetap muncul di atas
        return $query->latest();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN ANALISIS FEEDBACK PENGUNJUNG - KAFE ANORA'],
            ['Dicetak pada: ' . date('d F Y, H:i')],
            [], 
            ['NO', 'NAMA PENGUNJUNG', 'EMAIL', 'RATING', 'KATEGORI', 'KOMENTAR', 'STATUS', 'TANGGAL MASUK', 'RESPON ADMIN', 'TANGGAL RESPON']
        ];
    }

    public function map($feedback): array
    {
        // Increment nomor urut setiap baris
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $feedback->visitor_name,
            $feedback->visitor_email,
            (int)$feedback->rating,
            $feedback->category,
            $feedback->comment,
            $feedback->status,
            $feedback->created_at->format('d/m/Y H:i'),
            $feedback->admin_response ?? '-',
            $feedback->responded_at ? $feedback->responded_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = 'J';

        // 1. Desain Judul Utama & Tanggal Cetak (Merge & Center)
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->getStyle("A1:A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new Color('B45309'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new Color('6B7280'));

        // 2. Header Tabel
        $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'B45309'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // 3. Conditional Formatting Baris (Rating 1-2 = Background Merah)
        for ($i = 5; $i <= $lastRow; $i++) {
            $ratingValue = $sheet->getCell('D' . $i)->getValue();
            $rowRange = "A{$i}:{$lastColumn}{$i}";

            if ($ratingValue <= 2 && !empty($ratingValue)) {
                $sheet->getStyle($rowRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
                $sheet->getStyle($rowRange)->getFont()->setColor(new Color('991B1B'));
            } else if ($i % 2 == 0) {
                $sheet->getStyle($rowRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
            }
        }

        // 4. Perataan & Border
        $sheet->getStyle("A4:{$lastColumn}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');
        $sheet->getStyle("A4:{$lastColumn}{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        // Kolom NO, RATING, KATEGORI, STATUS, TANGGAL di set Center
        $centerColumns = ['A', 'D', 'E', 'G', 'H', 'J'];
        foreach ($centerColumns as $col) {
            $sheet->getStyle("{$col}4:{$col}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // 5. Optimasi Lebar Kolom (Mencegah Space Kosong)
        $sheet->getStyle("F5:F{$lastRow}")->getAlignment()->setWrapText(true); // Kolom Komentar
        $sheet->getStyle("I5:I{$lastRow}")->getAlignment()->setWrapText(true); // Kolom Respon
        $sheet->getColumnDimension('A')->setWidth(5);  // Kolom No
        $sheet->getColumnDimension('D')->setWidth(10); // Kolom Rating
        $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(40);
        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(40);

        return [];
    }
}