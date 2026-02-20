<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeeInvoiceRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Child;
use App\Models\FeeInvoice;
use App\Models\FeePlan;
use App\Notifications\InvoiceOverdueNotification;
use App\Notifications\PaymentReceivedNotification;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function __construct(private FeeService $feeService) {}

    public function index(Request $request)
    {
        $invoices = $this->feeService->getInvoices($request->all());
        $children = Child::active()->get();

        return view('admin.fees.index', compact('invoices', 'children'));
    }

    public function create()
    {
        $children = Child::active()->with('stage')->get();
        $feePlans = FeePlan::where('is_active', true)->with('stage')->get();

        return view('admin.fees.create', compact('children', 'feePlans'));
    }

    public function store(StoreFeeInvoiceRequest $request)
    {
        $this->feeService->createInvoice($request->validated());

        return redirect()->route('admin.fees.index')
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(FeeInvoice $invoice)
    {
        $invoice->load(['child.parent', 'feePlan', 'payments.receiver']);
        return view('admin.fees.show', compact('invoice'));
    }

    public function addPayment(StorePaymentRequest $request)
    {
        $payment = $this->feeService->recordPayment($request->validated(), $request->user()->id);

        $invoice = $payment->feeInvoice->load('child.parent');
        $invoice->child->parent->notify(new PaymentReceivedNotification($payment));

        return redirect()->route('admin.fees.show', $payment->fee_invoice_id)
            ->with('success', 'تم تسجيل الدفعة بنجاح');
    }

    public function overdue()
    {
        $invoices = $this->feeService->getOverdueInvoices();
        return view('admin.fees.overdue', compact('invoices'));
    }

    public function sendOverdueReminders()
    {
        $invoices = $this->feeService->getOverdueInvoices();

        foreach ($invoices as $invoice) {
            $invoice->child->parent->notify(new InvoiceOverdueNotification($invoice));
        }

        return redirect()->route('admin.fees.overdue')
            ->with('success', 'تم إرسال التذكيرات بنجاح');
    }

    public function feePlans()
    {
        $feePlans = FeePlan::with('stage')->paginate(15);
        return view('admin.fees.plans', compact('feePlans'));
    }

    public function storeFeePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,quarterly,semi_annual,annual',
            'description' => 'nullable|string',
        ]);

        FeePlan::create($validated);

        return redirect()->route('admin.fee-plans.index')
            ->with('success', 'تم إضافة خطة الرسوم بنجاح');
    }
}
