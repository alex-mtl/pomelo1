<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'provider_id' => $this->provider_id,
            'patient_id' => $this->patient_id,
            'available'=> empty($this->patient_id),
            'slot_start' => Carbon::parse($this->slot_start)->format('Y-m-d H:i:s'),
            'slot_end' => Carbon::parse($this->slot_end)->format('Y-m-d H:i:s'),
        ];
    }
}
