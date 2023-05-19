<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NewsArticle extends Model
{
    use HasFactory;

    public function source(): HasOne
    {
        return $this->hasOne(NewsSource::class, 'slug', 'source_slug');
    }
}
