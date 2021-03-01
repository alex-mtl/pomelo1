<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvailabilityResource;
use App\Http\Resources\ProviderResource;
use App\Models\Availability;
use App\Models\Provider;
use App\Rules\SlotRange;
use App\Rules\SlotStart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    const SLOT_BOOK = [
        'provider_id' => ['required_with:slot_start,slot_end', 'int'],
        'patient_id' => ['required', 'int'],
        'id' => ['required_without_all:provider_id,slot_start,slot_end','int'],
        'slot_start' => ['required_with:provider_id,slot_end',  'date_format:Y-m-d H:i:s', 'before:slot_end'],
        'slot_end' => ['required_with:provider_id,slot_start',  'date_format:Y-m-d H:i:s'],
    ];

    /**
     * Book available slot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules= self::SLOT_BOOK;
        $rules['slot_start'][] = new SlotStart();
        $rules['slot_end'][] = new SlotRange(15, SlotRange::PERIOD_MINUTE);
        $params = $this->validate($request, $rules);

        $slot = $this->getSlot($params);

        if (empty($slot) || !empty($slot->patient_id)) {
            return response()->json(['message' => 'This slot does not exist or already booked'], 409);
        } else {
            $slot->update(['patient_id' => $params['patient_id']]);
        }

        return new AvailabilityResource($slot);
    }

    /**
     * Get slot by id or by set of filters
     */
    private function getSlot(array $where) {
        if (array_key_exists('id', $where)) {
            return Availability::findOrFail($where['id']);
        } else {
            unset($where['patient_id']);
            return Availability::where($where)->first();
        }
    }

    /**
     * Release previously booked appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slot = Availability::findOrFail($id);
        if (empty($slot->patient_id)) {
            return response()->json(['message' => 'This slot does not exist or not booked'], 409);
        } else {
            $slot->update(['patient_id' => null]);
        }

        return new AvailabilityResource($slot);
    }
}
