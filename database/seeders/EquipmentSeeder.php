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
                'description_ru' => 'Лёгкий басовый комбо мощностью 500 Вт с двумя 10-дюймовыми динамиками и твитером, четырёхполосным эквалайзером и встроенным овердрайвом — готов к громким репетициям.',
                'description_en' => 'Lightweight 500-watt bass combo with dual 10-inch speakers and horn, 4-band EQ and onboard overdrive that is ready for loud rehearsals.',
                'photo_path' => 'equipment/bass-combo-fender-rumble-500.jpg',
                'sort' => 1,
            ],
            [
                'name_ru' => 'Гитарный комбоусилитель «Kustom Quad 65 DFX»',
                'name_en' => 'Guitar combo “Kustom Quad 65 DFX”',
                'description_ru' => '65-ваттный гитарный комбо с 12-дюймовым динамиком Celestion, четырьмя каналами и цифровыми эффектами DFX (реверб, дилэй, хорус).',
                'description_en' => '65-watt guitar combo with a 12-inch Celestion speaker, four channels and DFX digital effects such as reverb, delay and chorus.',
                'photo_path' => 'equipment/guitar-combo-kustom-quad-65-dfx.jpg',
                'sort' => 2,
            ],
            [
                'name_ru' => 'Гитарный комбоусилитель «Marshall MG50FX»',
                'name_en' => 'Guitar combo “Marshall MG50FX”',
                'description_ru' => '50-ваттный Marshall с 12-дюймовым динамиком, четырьмя режимами (Clean, Crunch, OD1, OD2) и процессором эффектов с реверберацией и модуляциями.',
                'description_en' => '50-watt Marshall combo with a 12-inch speaker, four modes (Clean, Crunch, OD1, OD2) and a digital effects engine with reverb and modulation.',
                'photo_path' => 'equipment/guitar-combo-marshall-mg50fx.jpg',
                'sort' => 3,
            ],
            [
                'name_ru' => 'Ударная установка «Gretsch Energy»',
                'name_en' => 'Drum kit “Gretsch Energy”',
                'description_ru' => 'Пятикомпонентная установка с 7-слойными тополиновыми барабанами, двойными стойками и комплектом тарелок Sabian SBR — надёжный старт для репетиций.',
                'description_en' => 'Five-piece kit with 7-ply poplar shells, double-braced hardware and Sabian SBR cymbals, giving a reliable rehearsal setup.',
                'photo_path' => 'equipment/drum-kit-gretsch-energy.jpg',
                'sort' => 4,
            ],
            [
                'name_ru' => 'Ударная установка «Yamaha»',
                'name_en' => 'Drum kit “Yamaha”',
                'description_ru' => 'Классическая акустическая установка Yamaha с прочной фурнитурой и ровным, сбалансированным звучанием — универсальна для репетиций и живых сетов.',
                'description_en' => 'Classic Yamaha acoustic drum set with sturdy hardware and balanced tone, making it a versatile choice for rehearsals and live shows.',
                'photo_path' => 'equipment/drum-kit-yamaha.jpg',
                'sort' => 5,
            ],
            [
                'name_ru' => 'Микшер «Behringer Xenyx 1202FX»',
                'name_en' => 'Mixer “Behringer Xenyx 1202FX”',
                'description_ru' => '12-канальный микшер с четырьмя преампами Xenyx, британскими эквалайзерами и 24-битным процессором эффектов, удобный для небольших сетапов.',
                'description_en' => '12-input mixer with four Xenyx mic preamps, British-style EQs and a 24-bit effects processor, ideal for compact rigs.',
                'photo_path' => 'equipment/mixer-behringer-xenyx-1202fx.jpg',
                'sort' => 6,
            ],
            [
                'name_ru' => 'Активная акустическая система «EV» 12" 1kW',
                'name_en' => 'Powered speaker “EV” 12” 1kW',
                'description_ru' => 'Активная акустика Electro-Voice мощностью 1000 Вт с 12-дюймовым НЧ-динамиком, ВЧ-драйвером и DSP-предустановками для быстрого звучания.',
                'description_en' => 'Electro-Voice powered speaker delivering 1000 watts through a 12-inch woofer, HF driver and DSP presets for quick tuning.',
                'photo_path' => 'equipment/powered-speaker-ev-12-1kw.jpg',
                'sort' => 7,
            ],
            [
                'name_ru' => 'Микрофоны «Shure SM-58»',
                'name_en' => 'Microphones “Shure SM-58”',
                'description_ru' => 'Классические динамические вокальные микрофоны с кардиоидной диаграммой, встроенным поп-фильтром и антивибрационной подвеской.',
                'description_en' => 'Classic dynamic vocal microphones with a cardioid pattern, built-in pop filter and pneumatic shock mount.',
                'photo_path' => 'equipment/microphones-shure-sm-58.jpg',
                'sort' => 8,
            ],
            [
                'name_ru' => 'Электрогитара «Ibanez»',
                'name_en' => 'Electro guitar “Ibanez”',
                'description_ru' => 'Универсальная электрогитара Ibanez с тонким комфортным грифом и парой мощных звукоснимателей для рока и металла.',
                'description_en' => 'Versatile Ibanez electric guitar featuring a slim comfortable neck and a pair of powerful pickups for rock and metal.',
                'photo_path' => 'equipment/electro-guitar-ibanez.jpg',
                'sort' => 9,
            ],
            [
                'name_ru' => 'Бас-гитара «Washburn»',
                'name_en' => 'Bass guitar “Washburn”',
                'description_ru' => 'Четырёхструнная бас-гитара Washburn с удобным грифом и уверенным пассивным звуком, который хорошо сидит в миксе.',
                'description_en' => 'Four-string Washburn bass with an easy-playing neck and solid passive tone that sits well in the mix.',
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
