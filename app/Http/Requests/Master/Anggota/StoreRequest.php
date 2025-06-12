<?php

namespace App\Http\Requests\Master\Anggota;

use App\Enums\StatusKepegawaian;
use App\Models\JenisTabungan;
use App\Models\SatuanKerja;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('anggota-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nik' => 'nullable|numeric|max_digits:16|min_digits:16',
            'nama' => 'required|string|max:255',
            'statusKepegawaian' => ['required', 'string', new Enum(StatusKepegawaian::class)],
            'jenisTabungan' => 'required|numeric|' . Rule::exists(JenisTabungan::class, 'id'),
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric',
            'satuanKerja' => 'required|numeric|' . Rule::exists(SatuanKerja::class, 'id'),
        ];
    }
}
