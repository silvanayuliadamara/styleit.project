<?php

namespace App\Http\Requests\Checkout;

use App\Models\ServicePackage;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $fullCart = session('cart', []);
        $checkoutKeys = session('checkout_keys');

        // Determine which items to process
        if ($checkoutKeys) {
            $cart = collect($fullCart)->filter(fn ($i) => in_array($i['key'], $checkoutKeys))->values()->all();
        } else {
            $cart = $fullCart;
        }

        $needsAddress = false;
        foreach ($cart as $item) {
            $package = ServicePackage::with('category')->find($item['package_id']);
            if ($package) {
                $slug = $package->category->slug ?? '';
                if ($slug === 'wedding' || $slug === 'baju') {
                    $needsAddress = true;
                    break;
                }
            }
        }

        $rules = [
            'phone' => ['required', 'string', 'max:20'],
            'instagram' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:va,qris,wallet'],
        ];

        if ($needsAddress) {
            $rules['address'] = ['required', 'string', 'max:500'];
        } else {
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'instagram.required' => 'Username Instagram wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi untuk layanan Wedding / Khusus Baju.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ];
    }
}