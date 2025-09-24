<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
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
        $jadwalId = $this->route('jadwal')->id;

        return [
            'keperluan' => ['sometimes', 'required', 'string', 'max:255'],
            'ruangan_id' => ['sometimes', 'required', 'exists:ruangans,id'],
            'tanggal_jadwals' => ['sometimes', 'array'],
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
            'keperluan.required' => 'Keperluan harus diisi.',
            'keperluan.string' => 'Keperluan harus berupa teks.',
            'keperluan.max' => 'Keperluan tidak boleh lebih dari 255 karakter.',
            'ruangan_id.required' => 'Ruangan harus dipilih.',
            'ruangan_id.exists' => 'Ruangan yang dipilih tidak valid.',
            'tanggal_jadwals.required' => 'Tanggal jadwal harus diisi.',
            'tanggal_jadwals.array' => 'Tanggal jadwal harus berupa array.',
            'tanggal_jadwals.min' => 'Minimal harus ada 1 tanggal jadwal.',
            'tanggal_jadwals.*.id.exists' => 'Tanggal jadwal tidak valid.',
            'tanggal_jadwals.*.tanggal.required' => 'Tanggal harus diisi.',
            'tanggal_jadwals.*.tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal_jadwals.*.tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
            'tanggal_jadwals.*.jam_mulai.date_format' => 'Format jam mulai harus HH:MM.',
            'tanggal_jadwals.*.jam_berakhir.date_format' => 'Format jam berakhir harus HH:MM.',
            'tanggal_jadwals.*.jam_berakhir.required_with' => 'Jam berakhir harus diisi jika jam mulai diisi.',
            'tanggal_jadwals.*.jam_berakhir.after' => 'Jam berakhir harus setelah jam mulai.',
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
            'keperluan' => 'Keperluan',
            'ruangan_id' => 'Ruangan',
            'tanggal_jadwals' => 'Tanggal Jadwal',
            'tanggal_jadwals.*.id' => 'ID Tanggal',
            'tanggal_jadwals.*.tanggal' => 'Tanggal',
            'tanggal_jadwals.*.jam_mulai' => 'Jam Mulai',
            'tanggal_jadwals.*.jam_berakhir' => 'Jam Berakhir',
        ];
    }
}
