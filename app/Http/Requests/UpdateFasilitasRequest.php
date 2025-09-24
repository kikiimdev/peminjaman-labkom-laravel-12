<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFasilitasRequest extends FormRequest
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
        $fasilitasId = $this->route('fasilitas')->id;

        return [
            'nama' => ['sometimes', 'required', 'string', 'max:100', 'unique:fasilitas,nama,'.$fasilitasId],
            'satuan' => ['sometimes', 'required', 'string', 'max:50'],
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
            'nama.required' => 'Nama fasilitas harus diisi.',
            'nama.string' => 'Nama fasilitas harus berupa teks.',
            'nama.max' => 'Nama fasilitas tidak boleh lebih dari 100 karakter.',
            'nama.unique' => 'Nama fasilitas sudah digunakan.',
            'satuan.required' => 'Satuan harus diisi.',
            'satuan.string' => 'Satuan harus berupa teks.',
            'satuan.max' => 'Satuan tidak boleh lebih dari 50 karakter.',
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
            'nama' => 'Nama Fasilitas',
            'satuan' => 'Satuan',
        ];
    }
}
