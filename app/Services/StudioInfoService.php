<?php

namespace App\Services;

use App\Models\StudioInfo;

class StudioInfoService
{
    public function __construct(private readonly StudioInfo $studioInfo)
    {
    }

    public function fieldGroups(): array
    {
        $storedValues = $this->studioInfo->newQuery()->pluck('value', 'property')->all();

        $groups = [];

        $groups['shared'] = collect($this->sharedFields())
            ->map(function (array $config, string $property) use ($storedValues) {
                return $this->buildFieldDefinition($property, $config, $storedValues[$property] ?? '');
            })
            ->values()
            ->all();

        foreach ($this->locales() as $locale) {
            $groups[$locale] = collect($this->localizedFields())
                ->map(function (array $config, string $key) use ($locale, $storedValues) {
                    $property = sprintf('%s_%s', $key, $locale);

                    return $this->buildFieldDefinition($property, $config, $storedValues[$property] ?? '');
                })
                ->values()
                ->all();
        }

        return $groups;
    }

    public function validationRules(): array
    {
        $rules = [
            'studio_infos' => ['required', 'array'],
        ];

        foreach ($this->flattenedFields() as $property => $config) {
            $rules["studio_infos.{$property}"] = $config['rules'];
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        $attributes = [];

        foreach (array_keys($this->flattenedFields()) as $property) {
            $attributes["studio_infos.{$property}"] = __('admin.studio_infos.fields.' . $property);
        }

        return $attributes;
    }

    public function save(array $values): void
    {
        foreach (array_keys($this->flattenedFields()) as $property) {
            $value = $values[$property] ?? '';
            $value = $value ?? '';

            $this->studioInfo->newQuery()->updateOrCreate(
                ['property' => $property],
                ['value' => $value]
            );
        }
    }

    public function flattenedFields(): array
    {
        $fields = [];

        foreach ($this->sharedFields() as $property => $config) {
            $fields[$property] = $config;
        }

        foreach ($this->locales() as $locale) {
            foreach ($this->localizedFields() as $key => $config) {
                $property = sprintf('%s_%s', $key, $locale);
                $fields[$property] = $config;
            }
        }

        return $fields;
    }

    private function buildFieldDefinition(string $property, array $config, string $value): array
    {
        return [
            'property' => $property,
            'label' => __('admin.studio_infos.fields.' . $property),
            'type' => $config['type'],
            'required' => (bool) ($config['required'] ?? false),
            'value' => $value,
        ];
    }

    private function sharedFields(): array
    {
        return config('studio-info.shared', []);
    }

    private function localizedFields(): array
    {
        return config('studio-info.localized', []);
    }

    private function locales(): array
    {
        return config('studio-info.locales', []);
    }
}

