<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $data = [
                [
                  
                    'brand_id' => '1',
                    'title' => 'Casio MQ-24-7 BUL KXF - Касио МК',
                    'cost' => 200000,
                    'cover_image' => '/product/2.png',
                ],
                [
                  
                    'brand_id' => '1',
                    'title' => 'Casio GA-1000-1 AER KXF - Касио ДЖА АЕ К',
                    'cost' => 400000,
                    'cover_image' => '/product/3.png',
                ],
                [
                  
                    'brand_id' => '1',
                    'title' => 'Casio GA-1000-1 AER KXF - Касио ДЖА АЕ К',
                    'cost' => 400000,
                    'cover_image' => '/product/3.png',
                ],
                [
                  
                    'brand_id' => '2',
                    'title' => 'Citizen JP1010-00E KXF - Ситизен Дж П К ИКС Ф',
                    'cost' => 300050,
                    'cover_image' => '/product/4.png',
                ],


                [
                  
                    'brand_id' => '2',
                    'title' => 'Citizen BJ2111-08E KXF - Ситизен БДж211 ФБ',
                    'cost' => 300020,
                    'cover_image' => '/product/5.png',
                ],
                [
                  
                    'brand_id' => '2',
                    'title' => 'Citizen AT0696-59E KX - Ситизен АТС ФВ',
                    'cost' => 300070,
                    'cover_image' => '/product/6.png',
                ],


                [
                    'brand_id' => '3',
                    'title' => 'Q&Q Water Resistance VFQ - Кью Кью Вотер Резинтент ФВ',
                    'cost' => 300020,
                    'cover_image' => '/product/7.png',
                ],
                [
                    'brand_id' => '4',
                    'title' => 'Royal London 41040-01VQ - Ройял Лондон Часы 410 ВКью',
                    'cost' => 300070,
                    'cover_image' => '/product/8.png',
                ],
                [
                  
                    'brand_id' => '4',
                    'title' => 'Royal London 20034-02 VQ - Ройял Лондон Часы 900',
                    'cost' => 300020,
                    'cover_image' => '/product/9.png',
                ],
                [
                  
                    'brand_id' => '4',
                    'title' => 'Royal London 41156-02 KVQ - - Ройял Лондон Часы ФВ 8',
                    'cost' => 300070,
                    'cover_image' => '/product/10.png',
                ],


                [
                  
                    'brand_id' => '2',
                    'title' => 'Swimming Watch VQ-01 - Часы для плавание в бассейне',
                    'cost' => 300070,
                    'cover_image' => '/product/11.png',
                ],
                [
                  
                    'brand_id' => '2',
                    'title' => 'Running Watch VQ-9 - Беговые часы на андроиде',
                    'cost' => 300070,
                    'cover_image' => '/product/12.png',
                ],

                

            ];

        foreach ($data as $item) {
            \App\Product::updateOrCreate(['title' => $item['title']],$item);           
        }

    }
}
