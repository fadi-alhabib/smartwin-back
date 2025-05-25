<?php

namespace App\Http\Controllers\Api\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MTN\ConfirmPaymentRequest;
use App\Http\Requests\Payment\MTN\CreateInvoiceRequest;
use App\Http\Requests\Payment\MTN\InitiatePaymentRequest;
use App\Models\MtnTerminal;
use App\Models\MtnPayment;
use App\Models\MtnRefund;
use App\Services\Gateway\Mtn\SignatureService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class MtnPaymentController extends Controller
{
    protected $baseUrl;
    protected $sig;

    public function __construct(SignatureService $sig)
    {
        $this->baseUrl = config("mtn.base_url");
        $this->sig     = $sig;
    }

    /** 1. تفعيل التيرمنال مرة واحدة */
    public function activateTerminal(Request $req)
    {
        $body = [
            'Key'    => $this->sig->getPublicKeyParameter(),
            'Secret' => config("mtn.terminal_secret"),
            'Serial' => config("mtn.terminal_serial"),
        ];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/pos/activate',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/pos/activate", $body);

        if ($res->successful()) {
            $data = $res->json('Settings', []);
            MtnTerminal::updateOrCreate(
                ['terminal_id' => config("mtn.terminal_id")],
                ['settings' => $data, 'activated_at' => now()]
            );
            return response()->json(['message' => 'Activated', 'settings' => $data]);
        }

        return response()->json($res->json(), $res->status());
    }

    /** 2. إنشاء فاتورة */
    public function createInvoice(CreateInvoiceRequest $req)
    {
        $inv  = Str::uuid()->toString();
        $sess = rand(100000, 999999);
        $amt  = $req->amount * 100;
        $ttl  = $req->ttl ?? 15;

        $body = ['Amount' => $amt, 'Invoice' => $inv, 'TTL' => $ttl, 'Session' => $sess];
        $xSig = $this->sig->sign($body);
        Log::alert($xSig);
        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/invoice/create',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $xSig,
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/invoice/create", $body);

        Log::alert($res->json());

        if ($res->successful()) {
            MtnPayment::create([
                'invoice_number' => $inv,
                'session_number' => $sess,
                'amount'         => $amt,
                'status'         => 1,
            ]);
            return response()->json(['invoice' => $inv, 'resp' => $res->json()]);
        }

        return response()->json($res->json(), 400);
    }

    /** 3. بدء الدفع (initiate) */
    public function initiatePayment(InitiatePaymentRequest $req)
    {
        $p    = MtnPayment::where('invoice_number', $req->invoice_number)->firstOrFail();
        $guid = Str::uuid()->toString();
        $p->update(['guid' => $guid, 'phone' => $req->phone]);

        $body = ['Invoice' => $p->invoice_number, 'Phone' => $p->phone, 'Guid' => $guid];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/payment_phone/initiate',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/payment_phone/initiate", $body);

        return response()->json($res->json(), $res->status());
    }

    /** 4. تأكيد الدفع (confirm) */
    public function confirmPayment(ConfirmPaymentRequest $req)
    {
        $user = $req->user();
        $p    = MtnPayment::where('guid', $req->guid)->firstOrFail();
        $body = [
            'Phone'           => $p->phone,
            'Guid'            => $p->guid,
            'OperationNumber' => $req->operation_number,
            'Invoice'         => $p->invoice_number,
            'Code'            => $this->sig->signOtpCode($req->code),
        ];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/payment_phone/confirm',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/payment_phone/confirm", $body);

        if ($res->successful()) {
            $p->update(['status' => 9, 'transaction_number' => $res->json('Transaction')]);
        }

        return response()->json($res->json(), $res->status());
    }

    /** 5.1 بدء ريفوند (refund initiate) */
    public function refundInitiate(Request $req)
    {
        $p    = MtnPayment::where('invoice_number', $req->invoice_number)->firstOrFail();
        $body = ['Invoice' => $p->invoice_number];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/invoice/refund/initiate',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/invoice/refund/initiate", $body);

        if ($res->successful()) {
            $params = $res->json();
            $refund = MtnRefund::create([
                'mtn_payment_id' => $p->id,
                'base_invoice'   => $p->invoice_number,
                'parameters'     => $params,
                'status'         => 0,
            ]);
            return response()->json(['refund' => $refund, 'resp' => $params]);
        }

        return response()->json($res->json(), $res->status());
    }

    /** 5.2 تأكيد ريفوند (refund confirm) */
    public function refundConfirm(Request $req)
    {
        $r    = MtnRefund::findOrFail($req->refund_id);
        $body = [
            'BaseInvoice'   => $r->base_invoice,
            'RefundInvoice' => $req->refund_invoice,
        ];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/invoice/refund/confirm',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/invoice/refund/confirm", $body);

        if ($res->successful()) {
            $data = $res->json();
            $r->update([
                'refund_invoice' => $data['RefundInvoice'],
                'refund_amount'  => $data['Amount'],
                'commission'     => $data['Commission'] ?? null,
                'status'         => 1,
            ]);
        }

        return response()->json($res->json(), $res->status());
    }

    /** 5.3 إلغاء ريفوند (refund cancel) */
    public function refundCancel(Request $req)
    {
        $r    = MtnRefund::findOrFail($req->refund_id);
        $body = ['InvoiceCancelID' => $r->refund_invoice];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/invoice/refund/cancel',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/invoice/refund/cancel", $body);

        if ($res->successful()) {
            $r->update(['status' => 2]);
        }

        return response()->json($res->json(), $res->status());
    }
}
