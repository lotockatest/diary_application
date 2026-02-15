<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'mood',
        'activities',
        'routines',
        'notes',
    ];

    protected $casts = [
        'activities' => 'array',
        'routines' => 'array',
        'date' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
