<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gateway_id' => ['required', 'exists:gateways,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'system_module_id' => ['required', 'exists:system_modules,id'],
            'amount' => ['required', 'numeric', 'min:0.1'],
            'currency' => ['sometimes', 'string', 'size:3'],
        ];
    }
}
