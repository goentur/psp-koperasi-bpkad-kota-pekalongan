<?php

namespace App\Http\Requests\Transaksi\Setoran;

use App\Models\Pinjaman;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AngsuranRequest extends FormRequest
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
            'pinjaman' => 'required|' . Rule::exists(Pinjaman::class, 'id'),
            'tanggalPembayaran' => 'required|date',
            'nominal' => 'required|numeric',
        ];
    }
}
