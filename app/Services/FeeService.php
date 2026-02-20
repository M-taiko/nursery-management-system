<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\Payment;

class FeeService
{
    public function getInvoices(array $filters = [])
    {
        $query = FeeInvoice::with(['child', 'feePlan', 'payments']);

        if (!empty($filters['child_id'])) {
            $query->where('child_id', $filters['child_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('due_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('due_date', '<=', $filters['to_date']);
        }

        return $query->latest('due_date')->paginate(15);
    }

    public function createInvoice(array $data): FeeInvoice
    {
        $data['invoice_number'] = FeeInvoice::generateInvoiceNumber();
        $data['total'] = $data['amount'] - ($data['discount'] ?? 0);

        return FeeInvoice::create($data);
    }

    public function recordPayment(array $data, $receiverId): Payment
    {
        $data['receipt_number'] = Payment::generateReceiptNumber();
        $data['received_by'] = $receiverId;

        $payment = Payment::create($data);

        $invoice = FeeInvoice::findOrFail($data['fee_invoice_id']);
        $totalPaid = $invoice->payments()->sum('amount');

        if ($totalPaid >= $invoice->total) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partial']);
        }

        return $payment;
    }

    public function getOverdueInvoices()
    {
        return FeeInvoice::overdue()
            ->with(['child.parent', 'feePlan'])
            ->get();
    }

    public function getChildFeeHistory($childId)
    {
        return FeeInvoice::where('child_id', $childId)
            ->with(['feePlan', 'payments'])
            ->latest('due_date')
            ->get();
    }
}
