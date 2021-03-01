<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class SlotStart implements Rule
{
    const PERIOD_MINUTE = 'minute';
    const PERIOD_HOUR = 'hour';

    private $maxTimeShift;
    private $period;

    public function __construct(int $timeShift = 15, string $period = self::PERIOD_MINUTE) {
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
        $time = Carbon::parse($value);

        switch ($this->period) {
            case self::PERIOD_MINUTE:
                $valid = Carbon::parse($value)->floorMinute($this->maxTimeShift)->eq($time);
                break;
            case self::PERIOD_HOUR:
                $valid = Carbon::parse($value)->floorHour($this->maxTimeShift)->eq($time);
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
        return 'Not rounded to '.$this->maxTimeShift.' '.$this->period.'s.';
    }
}
