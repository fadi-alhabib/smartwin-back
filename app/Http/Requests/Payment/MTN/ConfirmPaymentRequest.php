<?php

namespace App\Http\Requests\Payment\MTN;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'guid'             => 'required|string|exists:mtn_payments,guid',
            'operation_number' => 'required|number',
            'code'             => 'required|string',
        ];
    }
}
