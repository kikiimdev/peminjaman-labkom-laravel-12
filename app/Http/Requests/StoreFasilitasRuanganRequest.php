<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFasilitasRuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'ADMIN';
    }

    public function rules(): array
    {
        return [
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'fasilitas_id' => ['required', 'exists:fasilitas,id'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruangan_id.required' => 'Ruangan harus dipilih.',
            'ruangan_id.exists' => 'Ruangan yang dipilih tidak valid.',
            'fasilitas_id.required' => 'Fasilitas harus dipilih.',
            'fasilitas_id.exists' => 'Fasilitas yang dipilih tidak valid.',
            'jumlah.required' => 'Jumlah fasilitas harus diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'jumlah.max' => 'Jumlah maksimal 1000.',
        ];
    }
}
