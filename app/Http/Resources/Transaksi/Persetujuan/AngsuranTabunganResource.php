<?php

namespace App\Http\Resources\Transaksi\Persetujuan;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AngsuranTabunganResource extends JsonResource
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
               'anggota' => $this->TPinjaman?->SAnggota?->nama,
               'nominal' => Helpers::ribuan($this->nominal),
               'tgl_trans' => Carbon::parse($this->tgl_trans)->isoFormat('D MMM Y'),
          ];
     }
}
