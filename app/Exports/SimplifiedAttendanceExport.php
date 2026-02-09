<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\oms_employee;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SimplifiedAttendanceExport implements FromCollection, WithMapping, WithEvents, WithCustomStartCell
{

    protected $attendance_logs;
    protected $emp_id;
    protected $emp_name;

    public function __construct($emp_id, $emp_name, $attendance_logs)
    {

        $this->attendance_logs = $attendance_logs;
        $this->emp_id = $emp_id;
        $this->emp_name = $emp_name;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->attendance_logs);
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function map($row) : array
    {
        return [
            $row['period'],
            $row['day'],
            $row['morning_in'],
            $row['morning_out'],
            $row['lunch_in'],
            $row['evening_out'],
            $row['undertime'],
            $row['late'],
        ];
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /* =========================
                   EMPLOYEE NAME (TOP LEFT)
                ========================== */
                $sheet->setCellValue('A1', strtoupper($this->emp_id . '-' . $this->emp_name ));
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FF0000'],
                        'size' => 14,
                    ],
                ]);

                /* =========================
                   MAIN HEADERS
                ========================== */
                $sheet->mergeCells('A2:A3');
                $sheet->mergeCells('B2:B3');
                $sheet->mergeCells('C2:F2');
                $sheet->mergeCells('G2:H2');

                $sheet->setCellValue('A2', 'DATE');
                $sheet->setCellValue('B2', 'DAY');
                $sheet->setCellValue('C2', 'ATTENDANCE RECORD');
                $sheet->setCellValue('G2', 'DURATION STATUS');

                /* =========================
                   SUB HEADERS
                ========================== */
                $sheet->setCellValue('C3', 'MORNING IN');
                $sheet->setCellValue('D3', 'LUNCH OUT');
                $sheet->setCellValue('E3', 'LUNCH IN');
                $sheet->setCellValue('F3', 'EVENING OUT');
                $sheet->setCellValue('G3', 'UNDERTIME');
                $sheet->setCellValue('H3', 'LATE');

                /* =========================
                   STYLING
                ========================== */
                $headerRange = 'A2:H3';

                $sheet->getStyle($headerRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9DDE2'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                /* =========================
                   COLUMN WIDTHS
                ========================== */
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setWidth(18);
                }

                /* =========================
                   DATA START ROW
                ========================== */
                $sheet->freezePane('A4');

                $sheet->freezePaneByColumnAndRow(1, 4);

                // Adjust Color for Saturday and sunday
                $highestRow = $sheet->getHighestRow();

                for ($row = 4; $row <= $highestRow; $row++) {

                    $day = strtolower(trim($sheet->getCell("B{$row}")->getValue()));

                    if (in_array($day, ['saturday', 'sunday'])) {
                        $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'E41F1F', // 🔴 red like your image
                                ],
                            ],
                            'font' => [
                                'color' => [
                                    'rgb' => 'FFFFFF',
                                ],
                                'bold' => true,
                            ],
                        ]);
                    }
                }
            }
        ];
    }

}
