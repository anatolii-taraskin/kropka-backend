<?php

namespace App\Models;

use App\Support\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioRule extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

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
        return $this->localizedAttribute('value', $locale) ?? '';
    }

    /**
     * Accessor to expose the localized value via the legacy "value" attribute.
     */
    public function getValueAttribute(): string
    {
        return $this->localizedValue();
    }

}
