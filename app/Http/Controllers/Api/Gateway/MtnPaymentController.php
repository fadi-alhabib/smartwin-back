<?php

namespace App\Http\Controllers\Api\Gateway;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MTN\ConfirmPaymentRequest;
use App\Http\Requests\Payment\MTN\CreateInvoiceRequest;
use App\Http\Requests\Payment\MTN\InitiatePaymentRequest;
use App\Models\MtnTerminal;
use App\Models\MtnPayment;
use App\Models\MtnRefund;
use App\Models\User;
use App\Services\Gateway\Mtn\SignatureService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api/mtn-payments')]
class MtnPaymentController extends Controller
{
    protected $baseUrl;
    protected $sig;

    public function __construct(SignatureService $sig)
    {
        $this->baseUrl = config("mtn.base_url");
        $this->sig     = $sig;
    }

    // /** 1. تفعيل التيرمنال مرة واحدة */
    // public function activateTerminal(Request $req)
    // {
    //     $body = [
    //         'Key'    => $this->sig->getPublicKeyParameter(),
    //         'Secret' => config("mtn.terminal_secret"),
    //         'Serial' => config("mtn.terminal_serial"),
    //     ];

    //     $res = Http::withHeaders([
    //         'Request-Name'    => 'pos_web/pos/activate',
    //         'Subject'         => config("mtn.terminal_id"),
    //         'X-Signature'     => $this->sig->sign($body),
    //         'Accept-Language' => 'en',
    //     ])->post("{$this->baseUrl}/pos_web/pos/activate", $body);

    //     if ($res->successful()) {
    //         $data = $res->json('Settings', []);
    //         MtnTerminal::updateOrCreate(
    //             ['terminal_id' => config("mtn.terminal_id")],
    //             ['settings' => $data, 'activated_at' => now()]
    //         );
    //         return response()->json(['message' => 'Activated', 'settings' => $data]);
    //     }

    //     return response()->json($res->json(), $res->status());
    // }

    #[Post("create-invoice", middleware: "auth:sanctum")]
    public function createInvoice(CreateInvoiceRequest $req)
    {
        $amt = $req->amount * 100;
        $ttl = $req->ttl ?? 15;

        // Create payment and get ID
        $payment = MtnPayment::create([
            'amount' => $amt,
            'user_id' => $req->user('sanctum')->id,
        ]);


        $invoice =  $payment->id;

        $body = ['Amount' => $amt, 'Invoice' => $invoice, 'TTL' => $ttl];
        $xSig = $this->sig->sign($body);

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/invoice/create',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $xSig,
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/invoice/create", $body);

        if ($res->successful()) {
            return response()->json(['invoice' => $invoice, 'resp' => $res->json()]);
        }

        return response()->json($res->json(), 400);
    }

    // #[Get('/invoice/{id}')]
    // public function getInvoiceById(Request $req, int $id) {

    // }


    #[Post('/initiate', middleware: ["auth:sanctum"])]
    public function initiatePayment(InitiatePaymentRequest $req)
    {

        $p    = MtnPayment::where('user_id', $req->user()->id)->latest()->first();
        $guid = Str::uuid()->toString();
        $p->update(['guid' => $guid, 'phone' => $req->phone]);

        $body = ['Invoice' => $p->id, 'Phone' => $p->phone, 'Guid' => $p->guid];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/payment_phone/initiate',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/payment_phone/initiate", $body);
        $resBody = $res->json();
        if ($resBody['Errno'] == 0) {
            return response()->json($res->json(), $res->status());
        } elseif ($resBody['Errno'] == 400) {
            return response()->json(["message" => "الرقم المدخل غير صحيح"], 400);
        } elseif ($resBody['Errno'] == 409) {
            return response()->json(["message" => "قد تم دفع الفاتورة من قبل"], 400);
        } elseif ($resBody['Errno'] == 403) {
            return response()->json(["message" => "قد تم حظر حسابك من mtn"], 400);
        } elseif ($resBody["Errno"] == 404) {
            return response()->json(["message" => "ليس لديك حساب في mtn cash"], 400);
        } else {
            return response()->json(["message" => "لقد حدث خطأ يرجى إعادة المحاولة", "body" => $res->json()], 400);
        }
    }

    #[Post('/confirm', middleware: ["auth:sanctum"])]
    public function confirmPayment(ConfirmPaymentRequest $req)
    {
        $user = User::find($req->user()->id);
        $p    = MtnPayment::where('user_id', $user->id)->latest()->first();
        $body = [
            'Phone'           => $p->phone,
            'Guid'            => $p->guid,
            'OperationNumber' => $req->operation_number,
            'Invoice'         => $p->id,
            'Code'            => $this->sig->signOtpCode($req->code),
        ];

        $res = Http::withHeaders([
            'Request-Name'    => 'pos_web/payment_phone/confirm',
            'Subject'         => config("mtn.terminal_id"),
            'X-Signature'     => $this->sig->sign($body),
            'Accept-Language' => 'en',
        ])->post("{$this->baseUrl}/pos_web/payment_phone/confirm", $body);
        $resBody = $res->json();
        if ($resBody['Errno'] == 0) {
            if ($p->amount == 1200 || $p->amount == 1200000) {
                $user->points += 100;
            } elseif ($p->amount == 2200 || $p->amount == 2200000) {
                $user->points += 300;
            } elseif ($p->amount == 5300 || $p->amount == 5300000) {
                $user->points += 500;
            } elseif ($p->amount == 10500 || $p->amount == 10500000) {
                $user->points += 1000;
            }
            $user->save();
            return response()->json($res->json(), $res->status());
        } elseif ($resBody['Errno'] == 409) {
            return response()->json(["message" => "قد تم دفع الفاتورة من قبل"], 400);
        } elseif ($resBody['Errno'] == 403) {
            return response()->json(["message" => "قد تم حظر حسابك من mtn"], 400);
        } elseif ($resBody["Errno"] == 404) {
            return response()->json(["message" => "ليس لديك حساب في mtn cash"], 400);
        } elseif ($resBody["Errno"] == 661) {
            return response()->json(["message" => "لقد انتهى وقت كود التفعيل"]);
        } elseif ($resBody["Errno"] == 662) {
            return response()->json(["message" => "كود التفعيل خاطئ يرجى التحقق منه و إعادة المحاولة"]);
        } else {
            return response()->json(["message" => "لقد حدث خطأ يرجى إعادة المحاولة"], 400);
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
