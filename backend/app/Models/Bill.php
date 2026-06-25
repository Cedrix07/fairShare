<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'group_id',
        'created_by',
        'description',
        'total_amount',
        'member_count',
        'amount_per_member'
    ];
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function group()
    {
        return $this->belongsTo(
            Group::class
        );
    }
}
