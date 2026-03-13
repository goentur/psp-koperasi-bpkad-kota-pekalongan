<?php

namespace App\Http\Requests\Transaksi\Persetujuan;

use App\Models\New\SAnggota;
use App\Models\New\SProdSimpanan;
use App\Models\Pinjaman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataPersetujuanTabunganRequest extends FormRequest
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
            'page' => 'required|numeric',
            'anggota' => 'nullable|' . Rule::exists(SAnggota::class, 'id'),
            'simpanan' => 'nullable|' . Rule::exists(SProdSimpanan::class, 'id'),
            'perPage' => 'required|numeric|max:100|min:25',
        ];
    }
}
