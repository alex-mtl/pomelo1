<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvailabilityResource;
use App\Http\Resources\ProviderResource;
use App\Models\Availability;
use App\Models\Provider;
use App\Rules\SlotRange;
use App\Rules\SlotStart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvailabilityController extends Controller
{
    const QUERY_FIELDS = [
        'provider_id' => ['int'],
        'patient_id' => ['int'],
        'slot_start' => ['date_format:Y-m-d H:i:s'],
        'slot_end' => ['date_format:Y-m-d H:i:s'],
        'available'  => ['bool'],
    ];

    const SLOT_NEW = [
        'provider_id' => ['required', 'int'],
        'slot_start' => ['required',  'date_format:Y-m-d H:i:s', 'before:slot_end'],
        'slot_end' => ['required',  'date_format:Y-m-d H:i:s'],
    ];

    const SLOT_BOOK = [
        'provider_id' => ['required', 'int'],
        'patient_id' => ['required', 'int'],
        'slot_start' => ['required',  'date_format:Y-m-d H:i:s', 'before:slot_end'],
        'slot_end' => ['required',  'date_format:Y-m-d H:i:s'],
    ];

    /**
     * Display a listing of the slots.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = $this->validate($request, self::QUERY_FIELDS);
        $builder = $this->prepareIndexBuilder($where);
        $slots = $builder->paginate(10);
        return AvailabilityResource::collection($slots);
    }

    private function prepareIndexBuilder(array $where): Builder {
        $builder = Availability::query();
        foreach ($where as $field => $value) {
            switch ($field) {
                case 'available':
                    if ($value) {
                        $builder->whereNull('patient_id');
                    } else {
                        $builder->whereNotNull('patient_id');
                    }
                    break;
                case 'slot_start':
                    $builder->where('slot_start', '>=', $value);
                    break;
                case 'slot_end':
                    $builder->where('slot_end', '<=', $value);
                    break;
                default:
                    $builder->where($field, '=', $value);
            }
        }

        return $builder;
    }

    /**
     * Display the availability slot by id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $slot = Availability::findOrFail($id);
        return new AvailabilityResource($slot);
    }

    /**
     * Store a newly created slot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules= self::SLOT_NEW;
        $rules['slot_start'][] = new SlotStart();
        $rules['slot_end'][] = new SlotRange(15, SlotRange::PERIOD_MINUTE);
        $params = $this->validate($request, $rules);
        $slot = $this->prepareSlot($params);
        $slot = Availability::create($slot);

        return new AvailabilityResource($slot);
    }

    private function prepareSlot(array $params): array {
        $slot_start = Carbon::parse($params['slot_start'])
            ->floorMinute(15);
        $slot_end = $slot_start->clone()->addMinute(14);

        $res = [
            'provider_id' => $params['provider_id'],
            'slot_start' => $slot_start,
            'slot_end' => $slot_end
        ];

        return $res;
    }

    /**
     * Store a newly created slots.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function provide(Request $request)
    {
        $rules= self::SLOT_NEW;
        $rules['slot_end'][] = new SlotRange(12, SlotRange::PERIOD_HOUR);
        $params = $this->validate($request, $rules);
        $slots = $this->prepareSlots($params);

        $result = [];
        DB::transaction(function () use ($slots, &$result) {
            foreach ($slots as $slot) {
                $result[] = Availability::create($slot);
            }
        });
        return AvailabilityResource::collection(collect($result));
    }

    private function prepareSlots(array $params): array {
        $slot_start = Carbon::parse($params['slot_start'])
            ->floorMinute(15);
        $slot_end = Carbon::parse($params['slot_end'])
            ->ceilMinute(15)
            ->addMinute(-1);

        $res = [];
        for ($start = $slot_start; $start < $slot_end; $start->addMinute(15)) {
            $end = $start->clone()->addMinute(14);
            $res[] = [
                'provider_id' => $params['provider_id'],
                'slot_start' => $start->clone(),
                'slot_end' => $end
            ];
        }
        return $res;
    }

    /**
     * Remove the availability slot from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Availability $slot */
        $slot = Availability::findOrFail($id);
        $slot->delete();
        return response('', 204);
    }
}
