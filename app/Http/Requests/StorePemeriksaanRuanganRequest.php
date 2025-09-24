<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemeriksaanRuanganRequest extends FormRequest
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
            'ruangan_id' => ['required', 'exists:ruangans,id'],
            'petugas_id' => ['required', 'exists:users,id'],
            'kondisi' => ['required', 'string', 'in:BAIK,BUTUH_PERBAIKAN,RUSAK'],
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
            'ruangan_id.required' => 'Ruangan harus dipilih.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'petugas_id.required' => 'Petugas harus dipilih.',
            'petugas_id.exists' => 'Petugas tidak valid.',
            'kondisi.required' => 'Kondisi harus diisi.',
            'kondisi.string' => 'Kondisi harus berupa teks.',
            'kondisi.in' => 'Kondisi harus salah satu dari: BAIK, BUTUH_PERBAIKAN, RUSAK.',
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
            'ruangan_id' => 'Ruangan',
            'petugas_id' => 'Petugas',
            'kondisi' => 'Kondisi',
        ];
    }
}
