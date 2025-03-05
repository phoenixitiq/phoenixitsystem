<?php

namespace App\Services;

use App\Models\LetterTemplate;
use Carbon\Carbon;

class DocumentService
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function generateContract($data)
    {
        // تحضير بيانات العقد
        $contractData = [
            'contract' => $data,
            'company' => config('company'),
            'date' => Carbon::now()->format('Y-m-d'),
            'type' => 'contract'
        ];

        // إنشاء PDF للعقد
        return $this->exportService->toPDF($contractData, 'contract');
    }

    public function generateVoucher($type, $data)
    {
        $voucherData = [
            'voucher' => $data,
            'company' => config('company'),
            'date' => Carbon::now()->format('Y-m-d'),
            'type' => $type
        ];

        return $this->exportService->toPDF($voucherData, 'voucher_' . $type);
    }

    public function generateOfficialLetter($type, $data)
    {
        // جلب قالب الكتاب
        $template = LetterTemplate::where('type', $type)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            throw new \Exception('قالب الكتاب غير موجود');
        }

        // استبدال المتغيرات في القالب
        $content = $this->replaceVariables($template->content, $data);

        $letterData = [
            'letter' => array_merge($data, ['content' => $content]),
            'company' => config('company'),
            'date' => Carbon::now()->format('Y-m-d'),
            'type' => 'letter_' . $type
        ];

        return $this->exportService->toPDF($letterData, 'letter');
    }

    protected function replaceVariables($content, $data)
    {
        $variables = [
            '{employee_name}' => $data['employee']->name,
            '{employee_id}' => $data['employee']->id,
            '{position}' => $data['employee']->position,
            '{date}' => Carbon::now()->format('Y-m-d'),
            '{amount}' => $data['amount'] ?? '',
            '{reason}' => $data['reason'] ?? '',
            // ... المزيد من المتغيرات
        ];

        return str_replace(
            array_keys($variables),
            array_values($variables),
            $content
        );
    }
} 