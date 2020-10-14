<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'organizers'
    ];

    protected $casts = [
        'start_date_time' => 'datetime:' . DATE_RFC3339,
        'end_date_time'   => 'datetime:' . DATE_RFC3339,
        'organizers'      => 'json'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $query) {
            $query->orderBy('start_date_time', 'asc');
        });
    }
}
