<?php

namespace App\Http\Requests\Transaksi\Setoran;

use App\Models\Anggota;
use App\Models\JenisTabungan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetoranBaruRequest extends FormRequest
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
            'anggota' => 'required',
            'jenisTabungan' => 'required',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
        ];
    }
}
