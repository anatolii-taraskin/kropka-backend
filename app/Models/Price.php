<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ru',
        'name_en',
        'col1_ru',
        'col1_en',
        'col2_ru',
        'col2_en',
        'col3_ru',
        'col3_en',
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
     * Get the localized name for the current or provided locale.
     */
    public function localizedName(?string $locale = null): string
    {
        $locale = $this->normalizeLocale($locale);

        return $this->attributeForLocale('name', $locale) ?? '';
    }

    /**
     * Get the localized column value for the given index (1-3).
     */
    public function localizedColumn(int $index, ?string $locale = null): ?string
    {
        $locale = $this->normalizeLocale($locale);

        return $this->attributeForLocale('col' . $index, $locale);
    }

    /**
     * Get all non-empty localized columns while preserving order.
     *
     * @return array<int, string>
     */
    public function localizedColumns(?string $locale = null): array
    {
        $locale = $this->normalizeLocale($locale);

        return collect([1, 2, 3])
            ->map(fn (int $index) => $this->localizedColumn($index, $locale))
            ->filter(fn ($value) => filled($value))
            ->values()
            ->all();
    }

    /**
     * Accessor to expose the localized name via the legacy "name" attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->localizedName();
    }

    private function attributeForLocale(string $attribute, string $locale): ?string
    {
        $primaryAttribute = $attribute . '_' . $locale;

        $value = $this->{$primaryAttribute} ?? null;

        if (filled($value)) {
            return $value;
        }

        $fallbackAttribute = $locale === 'en'
            ? $attribute . '_ru'
            : $attribute . '_en';

        $fallbackValue = $this->{$fallbackAttribute} ?? null;

        return filled($fallbackValue) ? $fallbackValue : null;
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
