<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'bio',
        'profile_picture',
        'cover_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function linkDetails()
    {
        return $this->hasMany(LinkDetail::class);
    }
}
