<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuanganRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:100', 'unique:ruangans,nama'],
            'lokasi' => ['required', 'string', 'max:255'],
            'pemilik_id' => ['required', 'exists:users,id'],
            'fasilitas' => ['sometimes', 'array'],
            'fasilitas.*.fasilitas_id' => ['required_with:fasilitas', 'exists:fasilitas,id'],
            'fasilitas.*.jumlah' => ['required_with:fasilitas', 'integer', 'min:1'],
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
            'nama.required' => 'Nama ruangan harus diisi.',
            'nama.string' => 'Nama ruangan harus berupa teks.',
            'nama.max' => 'Nama ruangan tidak boleh lebih dari 100 karakter.',
            'nama.unique' => 'Nama ruangan sudah digunakan.',
            'lokasi.required' => 'Lokasi harus diisi.',
            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',
            'pemilik_id.required' => 'Pemilik ruangan harus dipilih.',
            'pemilik_id.exists' => 'Pemilik ruangan tidak valid.',
            'fasilitas.array' => 'Fasilitas harus berupa array.',
            'fasilitas.*.fasilitas_id.required_with' => 'Fasilitas harus dipilih.',
            'fasilitas.*.fasilitas_id.exists' => 'Fasilitas tidak valid.',
            'fasilitas.*.jumlah.required_with' => 'Jumlah fasilitas harus diisi.',
            'fasilitas.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'fasilitas.*.jumlah.min' => 'Jumlah minimal adalah 1.',
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
            'nama' => 'Nama Ruangan',
            'lokasi' => 'Lokasi',
            'pemilik_id' => 'Pemilik',
            'fasilitas' => 'Fasilitas',
            'fasilitas.*.fasilitas_id' => 'Fasilitas',
            'fasilitas.*.jumlah' => 'Jumlah',
        ];
    }
}
