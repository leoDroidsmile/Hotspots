<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'city',
        'state',
        'country',
        'address',
        'owner_id',
        'percentage',
    ];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'owner_id');
    }
}
