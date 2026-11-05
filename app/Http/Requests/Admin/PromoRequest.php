<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PromoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        foreach ([1, 2] as $slot) {
            $rules["promos.{$slot}.link"] = ['nullable', 'string', 'max:255'];
            $rules["promos.{$slot}.image"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
        }

        return $rules;
    }
}
