<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KontrakanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Get the count of units owned by the user
        $units = $this->units()->where('id_parent', $this->id)->where('type', 'kontrakan')->get();
        $unitCount = $units->count();
        $unitAvailable = $units->where('status', 'empty')->count();
        $unitFilled = $units->where('status', 'filled')->count();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'provinsi' => $this->provinsi,
            'kota' => $this->kota,
            'kode_pos' => $this->kode_pos,
            'jml_unit' => $this->jml_unit,
            'user_id' => $this->user_id,
            'periode_pembayaran' => $this->periode_pembayaran,
            'kode_unit' => $this->kode_unit,
            'unit_count' => $unitCount,
            'unit_available' => $unitAvailable,
            'unit_filled' => $unitFilled,
        ];
    }
}
