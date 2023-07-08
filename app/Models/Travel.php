<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    use HasFactory, Sluggable, UUID;

    protected $table = 'travels';

    protected $fillable = [
        'is_public',
        'slug',
        'name',
        'description',
        'number_of_days'
    ];

    protected $appends = [
        'number_of_nights'
    ];

    public $incrementing = false;

    public function tours():HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    /*public function numberOfNights():Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes)=>$attributes['number_of_days'] - 1
        );
    }*/

    public function getNumberOfNightsAttribute()
    {
        return $this->attributes['number_of_days'] - 1;
    }
}
