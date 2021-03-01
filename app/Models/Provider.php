<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 * @package App\Models
 * @mixin Builder
 */
class Provider extends Model
{
    use HasFactory;

    protected $table = 'provider';

    protected $fillable = [
        'first_name',
        'last_name',
    ];
}
