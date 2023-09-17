<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'url',
        'link',
        'type',
        'share_able',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
