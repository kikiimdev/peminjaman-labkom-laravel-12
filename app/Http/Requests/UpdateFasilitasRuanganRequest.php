<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFasilitasRuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'ADMIN';
    }

    public function rules(): array
    {
        return [
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah.required' => 'Jumlah fasilitas harus diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'jumlah.max' => 'Jumlah maksimal 1000.',
        ];
    }
}
