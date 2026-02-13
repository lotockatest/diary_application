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
        'mood',
        'activities',
        'routines',
        'notes',
    ];

    protected $casts = [
        'activities' => 'array',
        'routines' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
