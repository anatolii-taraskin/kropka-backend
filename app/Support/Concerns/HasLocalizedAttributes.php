<?php

namespace App\Support\Concerns;

trait HasLocalizedAttributes
{
    use NormalizesLocale;

    /**
     * Retrieve the localized value for the given attribute base name.
     */
    protected function localizedAttribute(string $attributeBase, ?string $locale = null): mixed
    {
        $locale = $this->resolveLocale($locale);

        foreach ($this->candidateLocales($locale) as $candidate) {
            $attribute = sprintf('%s_%s', $attributeBase, $candidate);

            $value = $this->getLocalizedAttributeValue($attribute);

            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Determine the prioritized locales for the lookup.
     *
     * @return array<int, string>
     */
    protected function candidateLocales(?string $locale = null): array
    {
        $locale = $this->resolveLocale($locale);

        return array_values(array_unique(array_merge([$locale], $this->fallbackLocales($locale))));
    }

    /**
     * Retrieve the value for the provided localized attribute.
     */
    protected function getLocalizedAttributeValue(string $attribute): mixed
    {
        if (method_exists($this, 'getAttribute')) {
            return $this->getAttribute($attribute);
        }

        return $this->{$attribute} ?? null;
    }
}

