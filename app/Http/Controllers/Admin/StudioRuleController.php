<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudioRuleController extends Controller
{
    /**
     * Display the form for editing studio rules.
     */
    public function edit()
    {
        $storedValues = StudioRule::query()
            ->orderBy('property')
            ->pluck('value', 'property')
            ->all();

        $fields = [];

        foreach ($this->fields() as $property => $config) {
            $fields[] = [
                'property' => $property,
                'label' => $config['label'],
                'type' => $config['type'],
                'required' => $config['required'],
                'value' => $storedValues[$property] ?? '',
            ];
        }

        return view('admin.studio-rules.edit', [
            'fields' => $fields,
        ]);
    }

    /**
     * Persist studio rule updates.
     */
    public function update(Request $request)
    {
        $fields = $this->fields();

        $input = $request->all();

        if (isset($input['studio_rules']) && is_array($input['studio_rules'])) {
            foreach ($input['studio_rules'] as $key => $value) {
                if ($value === '') {
                    $input['studio_rules'][$key] = null;
                }
            }
        }

        $rules = [
            'studio_rules' => ['required', 'array'],
        ];

        $attributes = [];

        foreach ($fields as $property => $config) {
            $rules["studio_rules.{$property}"] = $config['rules'];
            $attributes["studio_rules.{$property}"] = $config['label'];
        }

        $validated = Validator::make($input, $rules, [], $attributes)->validate();

        foreach ($fields as $property => $config) {
            $value = $validated['studio_rules'][$property] ?? '';

            if ($value === null) {
                $value = '';
            }

            StudioRule::query()->updateOrCreate(
                ['property' => $property],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.studio-rules.edit')
            ->with('status', 'studio-rules-updated');
    }

    /**
     * Field configuration for studio rules.
     */
    private function fields(): array
    {
        return [
            'rule_01' => [
                'label' => __('admin.studio_rules.fields.rule_01'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_02' => [
                'label' => __('admin.studio_rules.fields.rule_02'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_03' => [
                'label' => __('admin.studio_rules.fields.rule_03'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_04' => [
                'label' => __('admin.studio_rules.fields.rule_04'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_05' => [
                'label' => __('admin.studio_rules.fields.rule_05'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_06' => [
                'label' => __('admin.studio_rules.fields.rule_06'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_07' => [
                'label' => __('admin.studio_rules.fields.rule_07'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_08' => [
                'label' => __('admin.studio_rules.fields.rule_08'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_09' => [
                'label' => __('admin.studio_rules.fields.rule_09'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
            'rule_10' => [
                'label' => __('admin.studio_rules.fields.rule_10'),
                'rules' => ['required', 'string', 'max:2000'],
                'type' => 'textarea',
                'required' => true,
            ],
        ];
    }
}
