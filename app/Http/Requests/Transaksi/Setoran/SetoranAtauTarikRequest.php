<?php

namespace App\Http\Requests\Transaksi\Setoran;

use Illuminate\Foundation\Http\FormRequest;

class SetoranAtauTarikRequest extends FormRequest
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
            'tipe' => 'required|in:setoran,tarik',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
        ];
    }
}
