<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'kode_unit' => $this->kode_unit,
            'id_parent' => $this->id_parent,
            'type' => $this->type,
            'status' => $this->status,
            'periode_pembayaran' => $this->periode_pembayaran,
            'nama_penghuni' => $this->nama_penghuni,
            'no_identitas' => $this->no_identitas,
            'alamat' => $this->alamat,
            'provinsi' => $this->provinsi,
            'kota' => $this->kota,
            'kode_pos' => $this->kode_pos,
            'tanggal_mulai' => $this->tanggal_mulai,
        ];
    }
}
