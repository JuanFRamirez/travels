<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price'
    ];

    public $incrementing = false;

    public function getPriceAttribute($value)
    {
        return $value  / 100;
    }

    public function setPriceAttribute($value)
    {
        return $this->attributes['price'] = $value * 100;
    }
}
