<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seasonIds = Season::query()->pluck('id', 'name');

        $products = [
            [
                'name' => 'キウイ',
                'price' => 800,
                'image' => 'products/kiwi.png',
                'description' => 'キウイは甘みと酸味のバランスが絶妙なフルーツです。ビタミンCなどの栄養素も豊富なため、美肌効果や疲労回復効果も期待できます。もぎたてフルーツのスムージーをお召し上がりください。',
                'seasons' => ['秋', '冬'],
            ],
            [
                'name' => 'ストロベリー',
                'price' => 1200,
                'image' => 'products/strawberry.png',
                'description' => '大人から子供まで大人気のストロベリー。当店では鮮度抜群の完熟いちごを使用しています。ビタミンCはもちろん食物繊維も豊富なため、腸内環境の改善も期待できます。',
                'seasons' => ['春'],
            ],
            [
                'name' => 'オレンジ',
                'price' => 850,
                'image' => 'products/orange.png',
                'description' => '当店では酸味と甘みのバランスが抜群のネーブルオレンジを使用しています。酸味は控えめで、甘さと濃厚な果汁が魅力の商品です。',
                'seasons' => ['冬'],
            ],
            [
                'name' => 'スイカ',
                'price' => 700,
                'image' => 'products/watermelon.png',
                'description' => 'シャリシャリ食感が魅力のスイカ。全体の90%が水分のため、暑い日の水分補給や熱中症予防、カロリーが気になる方にもおすすめです。',
                'seasons' => ['夏'],
            ],
            [
                'name' => 'ピーチ',
                'price' => 1000,
                'image' => 'products/peach.png',
                'description' => '豊潤な香りととろけるような甘さが魅力のピーチ。美味しさはもちろん見た目の可愛さも抜群の商品です。',
                'seasons' => ['夏'],
            ],
            [
                'name' => 'シャインマスカット',
                'price' => 1400,
                'image' => 'products/muscat.png',
                'description' => '爽やかな香りと上品な甘みが特徴的なシャインマスカットは大人から子どもまで大人気のフルーツです。',
                'seasons' => ['夏', '秋'],
            ],
            [
                'name' => 'パイナップル',
                'price' => 800,
                'image' => 'products/pineapple.png',
                'description' => '甘酸っぱさとトロピカルな香りが特徴のパイナップル。当店では甘さと酸味のバランスが絶妙な果実を使用しています。',
                'seasons' => ['春', '夏'],
            ],
            [
                'name' => 'ブドウ',
                'price' => 1100,
                'image' => 'products/grapes.png',
                'description' => 'ブドウの中でも人気の高い国産品種を使用しています。高い糖度と適度な酸味が魅力で、鮮やかなパープルで見た目も可愛い商品です。',
                'seasons' => ['夏', '秋'],
            ],
            [
                'name' => 'バナナ',
                'price' => 600,
                'image' => 'products/banana.png',
                'description' => '低カロリーでありながら栄養満点のため、ダイエット中の方にもおすすめの商品です。1杯でバナナの濃厚な甘みを存分に堪能できます。',
                'seasons' => ['夏'],
            ],
            [
                'name' => 'メロン',
                'price' => 900,
                'image' => 'products/melon.png',
                'description' => '香りがよくジューシーで上品な甘さが人気のメロンスムージー。カリウムが多く含まれているため、むくみ解消効果も抜群です。',
                'seasons' => ['春', '夏'],
            ],
        ];

        foreach ($products as $item) {
            $product = Product::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'description' => $item['description'],
                ]
            );

            $ids = collect($item['seasons'])
                ->map(function ($season) use ($seasonIds) {
                    return $seasonIds->get($season);
                })
                ->filter()
                ->values()
                ->all();

            $product->seasons()->sync($ids);
        }
    }
}
