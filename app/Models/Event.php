<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'start_date_time',
        'end_date_time',
    ];

    protected $casts = [
        'start_date_time' => 'datetime:' . DATE_RFC3339,
        'end_date_time'   => 'datetime:' . DATE_RFC3339
    ];
}
