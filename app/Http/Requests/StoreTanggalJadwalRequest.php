<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreTanggalJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\TanggalJadwal::class);
    }

    public function rules(): array
    {
        return [
            'jadwal_id' => ['required', 'exists:jadwals,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_berakhir' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ];
    }

    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal harus dipilih.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
            'jam_mulai.required' => 'Jam mulai harus diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus HH:MM.',
            'jam_berakhir.required' => 'Jam berakhir harus diisi.',
            'jam_berakhir.date_format' => 'Format jam berakhir harus HH:MM.',
            'jam_berakhir.after' => 'Jam berakhir harus setelah jam mulai.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasConflict()) {
                $validator->errors()->add('tanggal', 'Jadwal bertentangan dengan jadwal yang sudah ada di ruangan ini.');
            }
        });
    }

    private function hasConflict(): bool
    {
        $jadwal = \App\Models\Jadwal::find($this->jadwal_id);
        if (! $jadwal) {
            return false;
        }

        $tanggal = Carbon::parse($this->tanggal);
        $jamMulai = $this->jam_mulai;
        $jamBerakhir = $this->jam_berakhir;

        return \App\Models\TanggalJadwal::whereHas('jadwal', function ($query) use ($jadwal) {
            $query->where('ruangan_id', $jadwal->ruangan_id)
                ->where('status', 'DISETUJUI')
                ->where('id', '!=', $jadwal->id);
        })
            ->where('tanggal', $tanggal)
            ->where(function ($query) use ($jamMulai, $jamBerakhir) {
                $query->where(function ($q) use ($jamMulai) {
                    $q->where('jam_mulai', '<=', $jamMulai)
                        ->where('jam_berakhir', '>', $jamMulai);
                })->orWhere(function ($q) use ($jamBerakhir) {
                    $q->where('jam_mulai', '<', $jamBerakhir)
                        ->where('jam_berakhir', '>=', $jamBerakhir);
                })->orWhere(function ($q) use ($jamMulai, $jamBerakhir) {
                    $q->where('jam_mulai', '>=', $jamMulai)
                        ->where('jam_berakhir', '<=', $jamBerakhir);
                });
            })
            ->exists();
    }
}
