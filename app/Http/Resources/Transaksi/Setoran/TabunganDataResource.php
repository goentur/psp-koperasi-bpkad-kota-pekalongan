<?php

namespace App\Http\Resources\Transaksi\Setoran;

use App\Support\Facades\Helpers;
use Carbon\Carbon;
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
        $maxSimpan = $this->TTransSimpanan->max('tgl_trans');
        $maxTarik  = $this->TTransTarik->max('tgl_trans');
        $lastTransactionDate = max($maxSimpan, $maxTarik);
        return [
            'id' => $this->id,
            'jenis_tabungan' => $this->id,
            'tabungan' => $this->SProdSimpanan?->nama,
            'nominal' => 'Rp ' . Helpers::ribuan($this->TTransSimpanan->sum('nominal') - $this->TTransTarik->sum('nominal')),
            'last_transaction_date' => Carbon::parse($lastTransactionDate)->isoFormat('D MMMM YYYY'),
            'nominal_standar' => $this->SProdSimpanan?->nominal_standar,
        ];
    }
}
