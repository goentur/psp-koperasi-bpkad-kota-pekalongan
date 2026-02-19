<?php

namespace App\Http\Resources\Transaksi\Setoran;

use App\Support\Facades\Helpers;
use Carbon\Carbon;
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
            'nominal' => 'Rp ' . Helpers::ribuan($this->plafon),
            'angsuran' => 'Rp ' . Helpers::ribuan($this->TTransPinjaman->sum('nominal')),
            'kekurangan' => 'Rp ' . Helpers::ribuan($this->plafon - $this->TTransPinjaman->sum('nominal')),
            'last_transaction_date' => Carbon::parse($this->TTransPinjaman->max('tgl_trans'))->isoFormat('D MMMM YYYY'),
            'jangka_waktu' => $this->jangka_waktu . ' Bulan',
            'jumlah_angsuran' => $this->TTransPinjaman->count() . 'X',
        ];
    }
}
