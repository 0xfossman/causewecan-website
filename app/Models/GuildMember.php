<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_name',
        'spec_name',
        'level',
        'race',
        'rank_name',
        'avatar_url',
        'armory_url',
        'source_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'source_updated_at' => 'datetime',
        ];
    }
}
