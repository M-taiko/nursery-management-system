<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'fee_invoice_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference',
        'notes',
        'received_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function feeInvoice(): BelongsTo
    {
        return $this->belongsTo(FeeInvoice::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'online' => 'إلكتروني',
            default => $this->payment_method,
        };
    }

    public static function generateReceiptNumber(): string
    {
        $latest = static::withTrashed()->latest('id')->first();
        $number = $latest ? intval(substr($latest->receipt_number, 4)) + 1 : 1;
        return 'RCP-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
