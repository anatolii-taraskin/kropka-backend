<?php

namespace App\Http\Services;

use App\Models\StudioInfo;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

final class StudioInfoService
{
    public function __construct(private readonly StudioInfo $studioInfo)
    {
    }

    /**
     * Retrieve grouped field definitions with their persisted values.
     *
     * @return array<string, array<int, FieldDefinition>>
     */
    public function fieldGroups(): array
    {
        $storedValues = $this->all();

        $groups = [];

        $groups['shared'] = collect($this->sharedFieldConfigs())
            ->map(function (array $config, string $property) use ($storedValues) {
                return $this->makeDefinition($property, $config, $storedValues[$property] ?? null);
            })
            ->values()
            ->all();

        foreach ($this->locales() as $locale) {
            $groups[$locale] = collect($this->localizedFieldConfigs())
                ->map(function (array $config, string $key) use ($locale, $storedValues) {
                    $property = sprintf('%s_%s', $key, $locale);

                    return $this->makeDefinition($property, $config, $storedValues[$property] ?? null);
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

        foreach ($this->definitions() as $definition) {
            $rules['studio_infos.' . $definition->property()] = $definition->rules();
        }

        return $rules;
    }

    public function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->definitions() as $definition) {
            $attributes['studio_infos.' . $definition->property()] = $definition->label();
        }

        return $attributes;
    }

    public function save(array $values): void
    {
        $records = [];

        foreach ($this->definitions() as $definition) {
            $value = $values[$definition->property()] ?? '';
            $records[] = [
                'property' => $definition->property(),
                'value' => $value ?? '',
            ];
        }

        if ($records === []) {
            return;
        }

        $this->studioInfo->newQuery()->upsert($records, ['property'], ['value']);
    }

    public function all(): array
    {
        return $this->studioInfo->newQuery()->pluck('value', 'property')->all();
    }

    /**
     * @return Collection<int, FieldDefinition>
     */
    private function definitions(): Collection
    {
        $definitions = collect();

        foreach ($this->sharedFieldConfigs() as $property => $config) {
            $definitions->push($this->makeDefinition($property, $config));
        }

        foreach ($this->locales() as $locale) {
            foreach ($this->localizedFieldConfigs() as $key => $config) {
                $property = sprintf('%s_%s', $key, $locale);
                $definitions->push($this->makeDefinition($property, $config));
            }
        }

        return $definitions;
    }

    private function makeDefinition(string $property, array $config, mixed $value = null): FieldDefinition
    {
        return new FieldDefinition(
            property: $property,
            label: __('admin.studio_infos.fields.' . $property),
            type: $config['type'],
            required: (bool) ($config['required'] ?? false),
            rules: $config['rules'] ?? [],
            value: $value ?? '',
        );
    }

    private function sharedFieldConfigs(): array
    {
        return config('studio-info.shared', []);
    }

    private function localizedFieldConfigs(): array
    {
        return config('studio-info.localized', []);
    }

    private function locales(): array
    {
        return config('studio-info.locales', []);
    }
}

final class FieldDefinition implements Arrayable
{
    public function __construct(
        private readonly string $property,
        private readonly string $label,
        private readonly string $type,
        private readonly bool $required,
        private readonly array $rules,
        private readonly mixed $value = '',
    ) {
    }

    public function property(): string
    {
        return $this->property;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function required(): bool
    {
        return $this->required;
    }

    public function rules(): array
    {
        return $this->rules;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'property' => $this->property(),
            'label' => $this->label(),
            'type' => $this->type(),
            'required' => $this->required(),
            'value' => $this->value(),
        ];
    }
}
