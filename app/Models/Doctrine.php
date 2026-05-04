<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctrine extends Model
{
    protected $fillable = [
        'title',
        'topic',
        'source_language',
        'language',
        'excerpt',
        'content',
        'translator',
        'ai_available',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'ai_available' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
