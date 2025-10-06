<?php

namespace App\Http\Resources\Transaksi\Setoran;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TabunganDataResource extends JsonResource
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
            'jenis_tabungan' => $this->jenis_tabungan_id,
            'tabungan' => $this->jenisTabungan->nama,
            'nominal' => 'Rp ' . Helpers::ribuan($this->nominal),
        ];
    }
}
