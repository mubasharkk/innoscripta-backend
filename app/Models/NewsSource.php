<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\Language;

class NewsSource extends Model
{
    use HasFactory;

    public function items(): HasMany
    {
        return $this->hasMany(NewsSource::class, 'source_slug', 'slug');
    }

    public function countryData(): HasOne
    {
        return $this->hasOne(Country::class, 'iso2', 'country');
    }

    public function languageData(): HasOne
    {
        return $this->hasOne(Language::class, 'code', 'language');
    }
}
