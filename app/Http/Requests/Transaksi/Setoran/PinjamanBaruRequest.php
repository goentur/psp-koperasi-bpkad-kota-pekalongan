<?php

namespace App\Http\Requests\Transaksi\Setoran;

use App\Models\Anggota;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PinjamanBaruRequest extends FormRequest
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
            'anggota' => 'required|' . Rule::exists(Anggota::class, 'id'),
            'nominal' => 'required|numeric',
            'tanggalPendaftaran' => 'required|date',
            'tanggalPersetujuan' => 'required|date',
        ];
    }
}
