<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipmentItems = [
            [
                'name_ru' => 'Басовый комбоусилитель «Fender Rumble 500»',
                'name_en' => 'Bass combo “Fender Rumble 500”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/bass-combo-fender-rumble-500.jpg',
                'sort' => 1,
            ],
            [
                'name_ru' => 'Гитарный комбоусилитель «Kustom Quad 65 DFX»',
                'name_en' => 'Guitar combo “Kustom Quad 65 DFX”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/guitar-combo-kustom-quad-65-dfx.jpg',
                'sort' => 2,
            ],
            [
                'name_ru' => 'Гитарный комбоусилитель «Marshall MG50FX»',
                'name_en' => 'Guitar combo “Marshall MG50FX”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/guitar-combo-marshall-mg50fx.jpg',
                'sort' => 3,
            ],
            [
                'name_ru' => 'Ударная установка «Gretsch Energy»',
                'name_en' => 'Drum kit “Gretsch Energy”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/drum-kit-gretsch-energy.jpg',
                'sort' => 4,
            ],
            [
                'name_ru' => 'Ударная установка «Yamaha»',
                'name_en' => 'Drum kit “Yamaha”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/drum-kit-yamaha.jpg',
                'sort' => 5,
            ],
            [
                'name_ru' => 'Микшер «Behringer Xenyx 1202FX»',
                'name_en' => 'Mixer “Behringer Xenyx 1202FX”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/mixer-behringer-xenyx-1202fx.jpg',
                'sort' => 6,
            ],
            [
                'name_ru' => 'Активная акустическая система «EV» 12" 1kW',
                'name_en' => 'Powered speaker “EV” 12” 1kW',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/powered-speaker-ev-12-1kw.jpg',
                'sort' => 7,
            ],
            [
                'name_ru' => 'Микрофоны «Shure SM-58»',
                'name_en' => 'Microphones “Shure SM-58”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/microphones-shure-sm-58.jpg',
                'sort' => 8,
            ],
            [
                'name_ru' => 'Электрогитара «Ibanez»',
                'name_en' => 'Electro guitar “Ibanez”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/electro-guitar-ibanez.jpg',
                'sort' => 9,
            ],
            [
                'name_ru' => 'Бас-гитара «Washburn»',
                'name_en' => 'Bass guitar “Washburn”',
                'description_ru' => 'описание',
                'description_en' => 'описание',
                'photo_path' => 'equipment/bass-guitar-washburn.jpg',
                'sort' => 10,
            ],
        ];

        foreach ($equipmentItems as $item) {
            Equipment::updateOrCreate(
                ['name_en' => $item['name_en']],
                [
                    'name_ru' => $item['name_ru'],
                    'description_ru' => $item['description_ru'],
                    'description_en' => $item['description_en'],
                    'photo_path' => $item['photo_path'],
                    'is_active' => true,
                    'sort' => $item['sort'],
                ]
            );
        }
    }
}
