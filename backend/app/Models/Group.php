<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'invite_code',
        'created_by'
    ];
    protected $hidden = [
        'pivot', // Always hides the pivot object from JSON output
    ];
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function members()
    {
        return $this->belongsToMany(
            User::class
        )->withTimestamps();
    }

    public function bills()
    {
        return $this->hasMany(
            Bill::class
        );
    }

    public function messages()
    {
        return $this->hasMany(
            Message::class
        );
    }
}
