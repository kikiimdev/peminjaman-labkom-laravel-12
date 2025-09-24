<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRiwayatStatusJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false; // Riwayat status tidak dapat diubah, hanya dibaca
    }

    public function rules(): array
    {
        return [];
    }
}
