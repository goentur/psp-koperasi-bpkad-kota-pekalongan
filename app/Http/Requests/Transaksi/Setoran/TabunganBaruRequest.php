<?php

namespace App\Http\Requests\Transaksi\Setoran;

use App\Models\Anggota;
use App\Models\JenisTabungan;
use App\Models\New\SAnggota;
use App\Models\New\SProdSimpanan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TabunganBaruRequest extends FormRequest
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
            'anggota' => 'required|' . Rule::exists(SAnggota::class, 'id'),
            'jenisTabungan' => 'required|' . Rule::exists(SProdSimpanan::class, 'id'),
            'tanggalPendaftaran' => 'required|date',
        ];
    }
}
