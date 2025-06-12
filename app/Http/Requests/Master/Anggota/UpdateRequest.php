<?php

namespace App\Http\Requests\Master\Anggota;

use App\Enums\StatusKepegawaian;
use App\Models\Anggota;
use App\Models\SatuanKerja;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('anggota-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|numeric|' . Rule::exists(Anggota::class, 'id'),
            'nik' => 'nullable|numeric|max_digits:16|min_digits:16',
            'nama' => 'required|string|max:255',
            'statusKepegawaian' => ['required', 'string', new Enum(StatusKepegawaian::class)],
            'satuanKerja' => 'required|numeric|' . Rule::exists(SatuanKerja::class, 'id'),
        ];
    }
}
