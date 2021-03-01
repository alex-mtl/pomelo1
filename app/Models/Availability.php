<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Availability
 * @package App\Models
 * @mixin Builder
 */
class Availability extends Model
{
    use HasFactory;

    protected $table = 'availability';

    protected $fillable = [
        'provider_id',
        'patient_id',
        'slot_start',
        'slot_end'
    ];
}
