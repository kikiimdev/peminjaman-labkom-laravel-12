<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInsidenRequest extends FormRequest
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
        $insidenId = $this->route('insiden')->id;

        return [
            'jadwal_id' => ['sometimes', 'required', 'exists:jadwals,id'],
            'ruangan_id' => ['sometimes', 'required', 'exists:ruangans,id'],
            'pelapor_id' => ['sometimes', 'required', 'exists:users,id'],
            'tingkat' => ['sometimes', 'required', 'string', 'in:RENDAH,SEDANG,TINGGI,KRITIS'],
            'deskripsi' => ['sometimes', 'required', 'string', 'max:1000'],
            'ditangani_oleh' => ['nullable', 'string', 'max:255'],
            'selesai_pada' => ['nullable', 'date'],
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
            'pelapor_id.required' => 'Pelapor harus dipilih.',
            'pelapor_id.exists' => 'Pelapor tidak valid.',
            'tingkat.required' => 'Tingkat harus diisi.',
            'tingkat.string' => 'Tingkat harus berupa teks.',
            'tingkat.in' => 'Tingkat harus salah satu dari: RENDAH, SEDANG, TINGGI, KRITIS.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            'ditangani_oleh.nullable' => 'Penanggung jawab boleh kosong.',
            'ditangani_oleh.string' => 'Penanggung jawab harus berupa teks.',
            'ditangani_oleh.max' => 'Penanggung jawab maksimal 255 karakter.',
            'selesai_pada.nullable' => 'Tanggal selesai boleh kosong.',
            'selesai_pada.date' => 'Format tanggal selesai tidak valid.',
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
            'pelapor_id' => 'Pelapor',
            'tingkat' => 'Tingkat',
            'deskripsi' => 'Deskripsi',
            'ditangani_oleh' => 'Ditangani Oleh',
            'selesai_pada' => 'Tanggal Selesai',
        ];
    }
}
