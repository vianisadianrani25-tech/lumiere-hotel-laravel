<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProcessPaymentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::in(['bank', 'ewallet', 'card', 'hotel'])],
            'ewallet_type' => ['required_if:payment_method,ewallet', 'nullable', 'string'],
            'card_holder' => ['required_if:payment_method,card', 'nullable', 'string', 'regex:/^[^\d]+$/'],
            'card_number' => ['required_if:payment_method,card', 'nullable', 'digits:16'],
            'card_expiry' => ['required_if:payment_method,card', 'nullable', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card_cvv' => ['required_if:payment_method,card', 'nullable', 'digits_between:3,4'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'card_holder.regex' => 'Nama pemegang kartu tidak valid.',
            'card_number.digits' => 'Nomor kartu harus terdiri dari 16 digit.',
            'card_expiry.regex' => 'Format tanggal kadaluarsa harus MM/YY.',
            'card_cvv.digits_between' => 'CVV harus terdiri dari 3-4 digit angka.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('payment_method') !== 'card') {
                return;
            }

            $expiry = $this->input('card_expiry');

            if (! $expiry || ! preg_match('/^(\d{2})\/(\d{2})$/', $expiry, $matches)) {
                return;
            }

            $expiresAt = Carbon::createFromDate(2000 + (int) $matches[2], (int) $matches[1], 1)->endOfMonth();

            if ($expiresAt->isPast()) {
                $validator->errors()->add('card_expiry', 'Kartu sudah kadaluarsa.');
            }
        });
    }
}
