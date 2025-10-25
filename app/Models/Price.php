<?php

namespace App\Models;

use App\Support\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

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
        return $this->localizedAttribute('name', $locale) ?? '';
    }

    /**
     * Get the localized column value for the given index (1-3).
     */
    public function localizedColumn(int $index, ?string $locale = null): ?string
    {
        return $this->localizedAttribute('col' . $index, $locale);
    }

    /**
     * Get all non-empty localized columns while preserving order.
     *
     * @return array<int, string>
     */
    public function localizedColumns(?string $locale = null): array
    {
        $locale = $this->resolveLocale($locale);

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

}
