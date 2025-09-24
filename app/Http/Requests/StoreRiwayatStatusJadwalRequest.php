<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiwayatStatusJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\RiwayatStatusJadwal::class);
    }

    public function rules(): array
    {
        return [
            'jadwal_id' => ['required', 'exists:jadwals,id'],
            'dari' => ['required', 'string', 'max:50'],
            'menjadi' => ['required', 'string', 'max:50', 'different:dari'],
        ];
    }

    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak valid.',
            'dari.required' => 'Status dari harus diisi.',
            'dari.string' => 'Status dari harus berupa teks.',
            'dari.max' => 'Status dari maksimal 50 karakter.',
            'menjadi.required' => 'Status menjadi harus diisi.',
            'menjadi.string' => 'Status menjadi harus berupa teks.',
            'menjadi.max' => 'Status menjadi maksimal 50 karakter.',
            'menjadi.different' => 'Status menjadi harus berbeda dengan status dari.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $jadwal = \App\Models\Jadwal::find($this->jadwal_id);
            if ($jadwal && $jadwal->status !== $this->dari) {
                $validator->errors()->add('dari', 'Status dari tidak sesuai dengan status jadwal saat ini.');
            }
        });
    }
}
