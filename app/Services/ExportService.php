<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportService
{
    protected $companySettings;
    protected $brandColors;

    public function __construct()
    {
        $this->companySettings = [
            'name' => 'شركة فينكس للحلول الرقمية',
            'logo' => public_path('images/logo.png'),
            'address' => 'العراق - الأنبار - الرمادي',
            'phone' => '+964 780 053 3950',
            'email' => 'info@phoenixitiq.com',
            'website' => 'www.phoenixitiq.com'
        ];

        $this->brandColors = [
            'primary' => '#2C3E50',
            'secondary' => '#E74C3C',
            'accent' => '#3498DB',
            'text' => '#2C3E50',
            'background' => '#ECF0F1'
        ];
    }

    public function toExcel($data, $type)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // إعداد ترويسة الشركة
        $this->setExcelHeader($sheet);

        // إعداد أنماط الجدول
        $this->setExcelStyles($sheet);

        // إضافة البيانات حسب النوع
        switch ($type) {
            case 'attendance':
                $this->exportAttendance($sheet, $data);
                break;
            case 'employees':
                $this->exportEmployees($sheet, $data);
                break;
            case 'projects':
                $this->exportProjects($sheet, $data);
                break;
            // ... المزيد من أنواع التصدير
        }

        // إضافة تذييل الصفحة
        $this->setExcelFooter($sheet);

        // حفظ الملف
        $writer = new Xlsx($spreadsheet);
        $filename = $type . '_' . date('Y-m-d_His') . '.xlsx';
        $path = storage_path('app/exports/' . $filename);
        $writer->save($path);

        return $path;
    }

    public function toPDF($data, $type)
    {
        // تحضير البيانات للقالب
        $viewData = [
            'data' => $data,
            'company' => $this->companySettings,
            'colors' => $this->brandColors,
            'date' => Carbon::now()->format('Y-m-d'),
            'reportType' => $type
        ];

        // إنشاء PDF باستخدام قالب مخصص
        $pdf = PDF::loadView('exports.pdf.' . $type, $viewData);

        // تخصيص PDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'XB Riyaz'
        ]);

        // حفظ الملف
        $filename = $type . '_' . date('Y-m-d_His') . '.pdf';
        $path = storage_path('app/exports/' . $filename);
        $pdf->save($path);

        return $path;
    }

    protected function setExcelHeader($sheet)
    {
        // إضافة شعار الشركة
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath($this->companySettings['logo']);
        $drawing->setHeight(60);
        $drawing->setCoordinates('B2');
        $drawing->setWorksheet($sheet);

        // معلومات الشركة
        $sheet->setCellValue('D2', $this->companySettings['name']);
        $sheet->setCellValue('D3', $this->companySettings['address']);
        $sheet->setCellValue('D4', $this->companySettings['phone']);
        $sheet->setCellValue('D5', $this->companySettings['email']);

        // تنسيق الترويسة
        $sheet->getStyle('D2:D5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => substr($this->brandColors['primary'], 1)]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT
            ]
        ]);
    }

    protected function setExcelStyles($sheet)
    {
        // أنماط الجدول
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => substr($this->brandColors['text'], 1)]
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => substr($this->brandColors['background'], 1)]
            ]
        ];

        // أنماط رأس الجدول
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => substr($this->brandColors['primary'], 1)]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];

        $sheet->getStyle('A7:Z7')->applyFromArray($headerStyle);
        $sheet->getStyle('A8:Z100')->applyFromArray($tableStyle);
    }

    protected function setExcelFooter($sheet)
    {
        $lastRow = $sheet->getHighestRow() + 2;
        
        $sheet->setCellValue('B' . $lastRow, 'تم إنشاء هذا التقرير بواسطة نظام فينكس');
        $sheet->setCellValue('B' . ($lastRow + 1), 'تاريخ التقرير: ' . Carbon::now()->format('Y-m-d H:i'));
        
        $sheet->getStyle('B' . $lastRow . ':B' . ($lastRow + 1))->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => substr($this->brandColors['text'], 1)]
            ]
        ]);
    }
} 