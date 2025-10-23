<?php

namespace Database\Seeders;

use App\Models\StudioRule;
use Illuminate\Database\Seeder;

class StudioRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            'rule_01' => [
                'ru' => 'Возвращаем на место всё, чем пользовались (микрофоны, комбики, стойки, барабаны, тарелки, стулья). Чистим за собой, моем кружки.',
                'en' => 'Return everything you used (microphones, amps, stands, drums, cymbals, chairs). Clean up after yourself and wash the mugs.',
            ],
            'rule_02' => [
                'ru' => 'Окна во время занятий не открываем — используем кондиционер.',
                'en' => 'Do not open the windows during rehearsals—use the air conditioner.',
            ],
            'rule_03' => [
                'ru' => 'В конце занятия: выключаем электроприборы, сетевые фильтры, свет; у микрофонов/пульта/комбиков — Volume на 0.',
                'en' => 'At the end of the session: turn off electrical devices, power strips, and lights; set microphones, mixer, and amps to Volume 0.',
            ],
            'rule_04' => [
                'ru' => 'Никакой еды и напитков на оборудовании. Бережное отношение ко всему в студии обязательно.',
                'en' => 'Keep food and drinks away from the gear. Treat everything in the studio with care.',
            ],
            'rule_05' => [
                'ru' => 'О поломках и нарушениях предыдущих гостей сразу сообщаем администратору Михаилу: +995 596 173 001.',
                'en' => 'Report any damage or issues left by previous guests to administrator Mikhail: +995 596 173 001.',
            ],
            'rule_06' => [
                'ru' => 'Отмена бесплатно — при уведомлении не менее чем за 2 суток.',
                'en' => 'Cancellation is free if you notify us at least 48 hours in advance.',
            ],
            'rule_07' => [
                'ru' => 'Оплату приносим без сдачи.',
                'en' => 'Bring exact change for payments.',
            ],
            'rule_08' => [
                'ru' => 'За нарушения — предупреждение/штраф (уборщику); за систематические или циничные — бан.',
                'en' => 'Violations lead to a warning or a fine (for the cleaner); repeated or blatant ones result in a ban.',
            ],
            'rule_09' => [
                'ru' => 'Используя помещение, вы автоматически принимаете правила и несёте ответственность за их соблюдение.',
                'en' => 'By using the studio you automatically accept the rules and are responsible for following them.',
            ],
            'rule_10' => [
                'ru' => 'Будем рады, если отметите нас в соцсетях, расскажете друзьям и поделитесь идеями по улучшению студии.',
                'en' => 'We appreciate it if you tag us on social media, tell your friends, and share ideas to improve the studio.',
            ],
        ];

        $position = 1;

        foreach ($rules as $property => $texts) {
            StudioRule::query()->updateOrCreate(
                ['property' => $property],
                [
                    'value_ru' => $texts['ru'],
                    'value_en' => $texts['en'],
                    'is_active' => true,
                    'sort' => $position++,
                ]
            );
        }
    }
}
