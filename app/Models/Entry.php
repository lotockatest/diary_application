<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    //Used for seeding and tests
    use HasFactory;
    //These fields can be filled
    protected $fillable = [
        'user_id',
        'date',
        'mood',
        'activities',
        'routines',
        'notes',
    ];
    //Make sure correct data types are used
    protected $casts = [
        'activities' => 'array',
        'routines' => 'array',
        'date' => 'date:Y-m-d',
    ];
    //An entry belongs to a user (relationship)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
