<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
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
