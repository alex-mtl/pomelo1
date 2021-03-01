<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Patient
 * @package App\Models
 * @mixin Builder
 */
class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient';

    protected $fillable = [
        'first_name',
        'last_name',
    ];
}
