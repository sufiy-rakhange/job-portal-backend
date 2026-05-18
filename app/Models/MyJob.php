<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyJob extends Model
{
    protected $fillable = [
        'title',
        'description',
        'company',
        'location',
        'logo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
