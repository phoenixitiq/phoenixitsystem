<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Invoice;
use App\Models\Payment;

class DaftraImportService
{
    protected $daftraData;
    protected $mappedData = [];

    public function importFromFile($filePath)
    {
        try {
            $this->daftraData = json_decode(file_get_contents($filePath), true);
            
            DB::beginTransaction();

            // استيراد البيانات بالترتيب الصحيح
            $this->importClients();
            $this->importServices();
            $this->importInvoices();
            $this->importPayments();
            $this->importSettings();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function importClients()
    {
        foreach ($this->daftraData['clients'] as $client) {
            $newClient = Client::create([
                'name' => $client['name'],
                'email' => $client['email'] ?? null,
                'phone' => $client['phone'] ?? null,
                'address' => $client['address'] ?? null,
                'company_name' => $client['company_name'] ?? null,
                'vat_number' => $client['tax_number'] ?? null,
                'cr_number' => $client['cr_number'] ?? null,
                'status' => $this->mapClientStatus($client['status']),
                'notes' => $client['notes'] ?? null,
                'created_at' => Carbon::parse($client['created_at'])
            ]);

            // حفظ العلاقة بين معرف دفترة والمعرف الجديد
            $this->mappedData['clients'][$client['id']] = $newClient->id;
        }
    }

    protected function importServices()
    {
        foreach ($this->daftraData['items'] as $item) {
            Service::create([
                'name' => $item['name'],
                'name_en' => $item['name_en'] ?? null,
                'description' => $item['description'] ?? null,
                'description_en' => $item['description_en'] ?? null,
                'price' => $item['price'],
                'category' => $this->mapServiceCategory($item['category']),
                'is_active' => $item['active'] ?? true,
                'created_at' => Carbon::parse($item['created_at'])
            ]);
        }
    }

    protected function importInvoices()
    {
        foreach ($this->daftraData['invoices'] as $invoice) {
            $newInvoice = Invoice::create([
                'client_id' => $this->mappedData['clients'][$invoice['client_id']],
                'invoice_number' => $invoice['number'],
                'date' => Carbon::parse($invoice['date']),
                'due_date' => Carbon::parse($invoice['due_date']),
                'subtotal' => $invoice['subtotal'],
                'tax' => $invoice['tax_amount'],
                'total' => $invoice['total'],
                'status' => $this->mapInvoiceStatus($invoice['status']),
                'notes' => $invoice['notes'] ?? null,
                'terms' => $invoice['terms'] ?? null,
                'created_at' => Carbon::parse($invoice['created_at'])
            ]);

            // استيراد تفاصيل الفاتورة
            foreach ($invoice['items'] as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $newInvoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);
            }
        }
    }

    protected function importPayments()
    {
        foreach ($this->daftraData['payments'] as $payment) {
            Payment::create([
                'invoice_id' => $this->mappedData['invoices'][$payment['invoice_id']],
                'amount' => $payment['amount'],
                'payment_date' => Carbon::parse($payment['date']),
                'payment_method' => $this->mapPaymentMethod($payment['method']),
                'reference_number' => $payment['reference'] ?? null,
                'notes' => $payment['notes'] ?? null,
                'created_at' => Carbon::parse($payment['created_at'])
            ]);
        }
    }

    protected function importSettings()
    {
        if (isset($this->daftraData['settings'])) {
            foreach ($this->daftraData['settings'] as $key => $value) {
                DB::table('company_settings')->updateOrInsert(
                    ['key_name' => $this->mapSettingKey($key)],
                    [
                        'value' => $value,
                        'group_name' => $this->getSettingGroup($key),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }

    protected function mapClientStatus($daftraStatus)
    {
        $statusMap = [
            'active' => 'active',
            'inactive' => 'inactive',
            'blocked' => 'suspended'
        ];
        return $statusMap[$daftraStatus] ?? 'active';
    }

    protected function mapServiceCategory($daftraCategory)
    {
        $categoryMap = [
            'products' => 'product',
            'services' => 'service',
            'digital' => 'digital'
        ];
        return $categoryMap[$daftraCategory] ?? 'other';
    }

    protected function mapInvoiceStatus($daftraStatus)
    {
        $statusMap = [
            'draft' => 'draft',
            'sent' => 'sent',
            'paid' => 'paid',
            'overdue' => 'overdue',
            'cancelled' => 'cancelled'
        ];
        return $statusMap[$daftraStatus] ?? 'draft';
    }

    protected function mapPaymentMethod($daftraMethod)
    {
        $methodMap = [
            'cash' => 'cash',
            'bank_transfer' => 'bank_transfer',
            'credit_card' => 'credit_card',
            'cheque' => 'cheque'
        ];
        return $methodMap[$daftraMethod] ?? 'other';
    }

    protected function mapSettingKey($daftraKey)
    {
        $keyMap = [
            'company_name' => 'company_name',
            'company_email' => 'company_email',
            'company_phone' => 'company_phone',
            'company_address' => 'company_address',
            'tax_number' => 'company_vat',
            'cr_number' => 'company_cr'
        ];
        return $keyMap[$daftraKey] ?? $daftraKey;
    }

    protected function getSettingGroup($key)
    {
        $groupMap = [
            'company_' => 'general',
            'tax_' => 'legal',
            'payment_' => 'payment',
            'invoice_' => 'invoice',
            'email_' => 'notification'
        ];

        foreach ($groupMap as $prefix => $group) {
            if (strpos($key, $prefix) === 0) {
                return $group;
            }
        }

        return 'general';
    }
} 