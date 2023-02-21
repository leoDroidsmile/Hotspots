<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'during',
        'amount',
        'random',
        'status_id',
        'paid_at'
    ];

    public $timestamps = false;

    // public function owner()
    // {
    //     return $this->belongsTo('App\Models\User', 'owner_id');
    // }
}
