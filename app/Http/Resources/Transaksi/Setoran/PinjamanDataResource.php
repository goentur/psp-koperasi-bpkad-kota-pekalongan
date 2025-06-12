<?php

namespace App\Http\Resources\Transaksi\Setoran;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PinjamanDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nominal' => 'Rp ' . Helpers::ribuan($this->nominal),
            'angsuran' => 'Rp ' . Helpers::ribuan($this->angsuran->sum('nominal')),
            'jangka_waktu' => $this->jangka_waktu . ' bulan',
        ];
    }
}
