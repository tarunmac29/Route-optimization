<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BestRoute extends Model
{
    use HasFactory;

    protected $table = 'best_routes';

    protected $fillable = [
        'start_location',
        'end_location',
        'distance',
        'time',
    ];
}
