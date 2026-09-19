<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $dateFrom;
    protected $dateTo;
    protected $transactions;
    protected $total;

    public function __construct(string $dateFrom, string $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;

        $this->transactions = Transaction::with('customer')
            ->whereBetween('created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo   . ' 23:59:59',
            ])
            ->get();

        $this->total = $this->transactions->sum('total_price');
    }

    public function collection()
    {
        $rows = $this->transactions->map(function ($trx, $index) {
            return [
                'no'          => $index + 1,
                'id'          => '#' . str_pad($trx->id, 5, '0', STR_PAD_LEFT),
                'pelanggan'   => $trx->customer->name ?? '-',
                'status'      => ucfirst(str_replace('_', ' ', $trx->status)),
                'total'       => $trx->total_price,
                'tgl_selesai' => optional($trx->ready_at)->format('d/m/Y H:i') ?? '-',
                'tgl_diambil' => optional($trx->picked_up_at)->format('d/m/Y H:i') ?? '-',
            ];
        });

        // Baris total di akhir
        $rows->push([
            'no'          => '',
            'id'          => '',
            'pelanggan'   => '',
            'status'      => 'TOTAL',
            'total'       => $this->total,
            'tgl_selesai' => '',
            'tgl_diambil' => '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No.',
            'ID Transaksi',
            'Pelanggan',
            'Status',
            'Total (Rp)',
            'Tanggal Selesai',
            'Tanggal Diambil',
        ];
    }

    public function title(): string
    {
        return 'Laporan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 25,
            'D' => 18,
            'E' => 18,
            'F' => 20,
            'G' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->transactions->count() + 4;

        return [
            // Header kolom — biru
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D6EFD'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Baris total
            $lastRow => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F4FD'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $this->transactions->count() + 4;
                $lastCol = 'G';

                // ── Sisipkan 2 baris judul di atas ──
                $sheet->insertNewRowBefore(1, 2);

                // Judul utama
                $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI LAUNDRY');
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1A2E4A']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Sub-judul periode
                $sheet->setCellValue('A2', 'Periode: ' . $this->dateFrom . ' s/d ' . $this->dateTo);
                $sheet->mergeCells('A2:G2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'color' => ['rgb' => '6C757D']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Set SEMUA baris data ke putih dulu (hapus warna default Excel) ──
                $dataStart = 4;
                $dataEnd   = $lastRow - 1;
                for ($row = $dataStart; $row <= $dataEnd; $row++) {
                    $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                        'fill' => [
                            'fillType'   => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFFFFF'],
                        ],
                        'font' => [
                            'bold'  => false,
                            'color' => ['rgb' => '212529'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                        ],
                    ]);

                    // Lalu baris genap diberi warna abu terang
                    if ($row % 2 === 0) {
                        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
                            'fill' => [
                                'fillType'   => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA'],
                            ],
                        ]);
                    }
                }

                // ── Style baris TOTAL ──
                $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '212529']],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F4FD'],
                    ],
                ]);

                // ── Border seluruh tabel ──
                $tableRange = 'A3:' . $lastCol . $lastRow;
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'DEE2E6'],
                        ],
                    ],
                ]);

                // ── Format kolom Total sebagai angka ──
                $sheet->getStyle('E4:E' . $lastRow)
                      ->getNumberFormat()
                      ->setFormatCode('#,##0');

                // ── Rata kanan kolom Total ──
                $sheet->getStyle('E4:E' . $lastRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);

                // ── Rata tengah kolom No., ID, Status ──
                $sheet->getStyle('A4:A' . $lastRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('B4:B' . $lastRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('D4:D' . $lastRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Freeze header ──
                $sheet->freezePane('A4');

                // ── Row height ──
                $sheet->getRowDimension(1)->setRowHeight(26);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(20);
            },
        ];
    }
}