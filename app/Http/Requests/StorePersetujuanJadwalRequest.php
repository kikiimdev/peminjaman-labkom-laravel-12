<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersetujuanJadwalRequest extends FormRequest
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
            'jadwal_id' => ['required', 'exists:jadwals,id'],
            'aktor_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'string', 'in:MENUNGGU,DISETUJUI,DITOLAK'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'jadwal_id.exists' => 'Jadwal tidak valid.',
            'aktor_id.required' => 'Aktor harus dipilih.',
            'aktor_id.exists' => 'Aktor tidak valid.',
            'status.required' => 'Status harus diisi.',
            'status.string' => 'Status harus berupa teks.',
            'status.in' => 'Status harus salah satu dari: MENUNGGU, DISETUJUI, DITOLAK.',
            'catatan.nullable' => 'Catatan boleh kosong.',
            'catatan.string' => 'Catatan harus berupa teks.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 1000 karakter.',
        ];
    }

    /**
     * Get the custom attribute names for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'jadwal_id' => 'Jadwal',
            'aktor_id' => 'Aktor',
            'status' => 'Status',
            'catatan' => 'Catatan',
        ];
    }
}
