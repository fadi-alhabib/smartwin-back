<?php

namespace App\Http\Requests\Payment\MTN;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_number' => 'required|string|exists:mtn_payments,invoice_number',
            'phone'          => 'required|string|regex:/^(\+?\d{7,15})$/',
        ];
    }
}
