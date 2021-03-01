<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class SlotRange implements Rule
{
    const PERIOD_MINUTE = 'minute';
    const PERIOD_HOUR = 'hour';

    private $maxTimeShift;
    private $period;

    public function __construct(int $timeShift = 24, string $period = self::PERIOD_HOUR) {
        $this->maxTimeShift = $timeShift;
        $this->period = $period;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start = Carbon::parse(request()->slot_start);
        switch ($this->period) {
            case self::PERIOD_MINUTE:
                $valid = $start >= Carbon::parse($value)->addMinute(-$this->maxTimeShift);
                break;
            case self::PERIOD_HOUR:
                $valid = $start >= Carbon::parse($value)->addHour(-$this->maxTimeShift);
        }
        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'slot_start slot_end diff can\'t be more than '.$this->maxTimeShift.' '.$this->period.'s.';
    }
}
