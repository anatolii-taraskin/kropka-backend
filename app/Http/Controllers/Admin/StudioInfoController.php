<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudioInfoController extends Controller
{
    /**
     * Display the form for editing studio information entries.
     */
    public function edit()
    {
        $storedValues = StudioInfo::query()->pluck('value', 'property')->all();

        $fieldGroups = [];

        foreach ($this->fields() as $section => $fields) {
            $fieldGroups[$section] = [];

            foreach ($fields as $property => $config) {
                $fieldGroups[$section][] = [
                    'property' => $property,
                    'label' => $config['label'],
                    'type' => $config['type'],
                    'required' => $config['required'],
                    'value' => $storedValues[$property] ?? '',
                ];
            }
        }

        return view('admin.studio-infos.edit', [
            'fieldGroups' => $fieldGroups,
        ]);
    }

    /**
     * Persist studio information updates.
     */
    public function update(Request $request)
    {
        $fields = $this->flattenedFields();

        $input = $request->all();

        if (isset($input['studio_infos']) && is_array($input['studio_infos'])) {
            foreach ($input['studio_infos'] as $key => $value) {
                if ($value === '') {
                    $input['studio_infos'][$key] = null;
                }
            }
        }

        $rules = [
            'studio_infos' => ['required', 'array'],
        ];

        $attributes = [];

        foreach ($fields as $property => $config) {
            $rules["studio_infos.{$property}"] = $config['rules'];
            $attributes["studio_infos.{$property}"] = $config['label'];
        }

        $validated = Validator::make($input, $rules, [], $attributes)->validate();

        foreach ($fields as $property => $config) {
            $value = $validated['studio_infos'][$property] ?? '';

            if ($value === null) {
                $value = '';
            }

            StudioInfo::query()->updateOrCreate(
                ['property' => $property],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.studio-infos.edit')
            ->with('status', 'studio-infos-updated');
    }

    /**
     * Field configuration for studio information entries.
     */
    private function fields(): array
    {
        $definitions = [
            'shared' => [],
        ];

        foreach ($this->sharedFields() as $property => $config) {
            $definitions['shared'][$property] = [
                'label' => __('admin.studio_infos.fields.' . $property),
                'rules' => $config['rules'],
                'type' => $config['type'],
                'required' => $config['required'],
            ];
        }

        foreach ($this->locales() as $locale) {
            $definitions[$locale] = [];

            foreach ($this->localizedFields() as $key => $config) {
                $property = sprintf('%s_%s', $key, $locale);

                $definitions[$locale][$property] = [
                    'label' => __('admin.studio_infos.fields.' . $property),
                    'rules' => $config['rules'],
                    'type' => $config['type'],
                    'required' => $config['required'],
                ];
            }
        }

        return $definitions;
    }

    private function flattenedFields(): array
    {
        $flattened = [];

        foreach ($this->fields() as $fields) {
            foreach ($fields as $property => $config) {
                $flattened[$property] = $config;
            }
        }

        return $flattened;
    }

    private function locales(): array
    {
        return ['ru', 'en'];
    }

    private function sharedFields(): array
    {
        return [
            'phone' => [
                'rules' => ['required', 'string', 'max:255'],
                'type' => 'text',
                'required' => true,
            ],
            'email' => [
                'rules' => ['required', 'email', 'max:255'],
                'type' => 'email',
                'required' => true,
            ],
            'instagram_url' => [
                'rules' => ['nullable', 'url', 'max:255'],
                'type' => 'url',
                'required' => false,
            ],
            'facebook_url' => [
                'rules' => ['nullable', 'url', 'max:255'],
                'type' => 'url',
                'required' => false,
            ],
            'telegram_channel_url' => [
                'rules' => ['nullable', 'url', 'max:255'],
                'type' => 'url',
                'required' => false,
            ],
            'telegram_admin_url' => [
                'rules' => ['nullable', 'url', 'max:255'],
                'type' => 'url',
                'required' => false,
            ],
        ];
    }

    private function localizedFields(): array
    {
        return [
            'name' => [
                'rules' => ['required', 'string', 'max:255'],
                'type' => 'text',
                'required' => true,
            ],
            'address' => [
                'rules' => ['required', 'string', 'max:1000'],
                'type' => 'textarea',
                'required' => true,
            ],
        ];
    }
}
