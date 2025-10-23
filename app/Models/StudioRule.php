<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property',
        'value_ru',
        'value_en',
        'is_active',
        'sort',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    /**
     * Get the localized rule text for the current or provided locale.
     */
    public function localizedValue(?string $locale = null): string
    {
        $locale = $this->normalizeLocale($locale);

        if ($locale === 'en') {
            if (filled($this->value_en)) {
                return $this->value_en;
            }

            return $this->value_ru ?? '';
        }

        if (filled($this->value_ru)) {
            return $this->value_ru;
        }

        return $this->value_en ?? '';
    }

    /**
     * Accessor to expose the localized value via the legacy "value" attribute.
     */
    public function getValueAttribute(): string
    {
        return $this->localizedValue();
    }

    private function normalizeLocale(?string $locale): string
    {
        $locale = $locale
            ?? app()->getLocale()
            ?? config('app.fallback_locale')
            ?? config('app.locale');

        return strtolower($locale ?? '');
    }
}
