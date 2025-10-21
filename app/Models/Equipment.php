<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ru',
        'name_en',
        'description_ru',
        'description_en',
        'photo_path',
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

        return match ($locale) {
            'en' => $this->name_en ?: ($this->name_ru ?? ''),
            default => $this->name_ru ?: ($this->name_en ?? ''),
        };
    }

    /**
     * Get the localized description for the current or provided locale.
     */
    public function localizedDescription(?string $locale = null): ?string
    {
        $locale = $this->normalizeLocale($locale);

        return match ($locale) {
            'en' => $this->description_en ?: $this->description_ru,
            default => $this->description_ru ?: $this->description_en,
        };
    }

    /**
     * Accessor to expose the localized name via the legacy "name" attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->localizedName();
    }

    /**
     * Accessor to expose the localized description via the legacy "description" attribute.
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->localizedDescription();
    }

    /**
     * Get the public URL for the stored photo.
     */
    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        return Storage::disk('public')->url($this->photo_path);
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
