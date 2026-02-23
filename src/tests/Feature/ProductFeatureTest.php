<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_products_index_shows_six_items_per_page()
    {
        $seasons = $this->createSeasons();

        for ($i = 1; $i <= 10; $i++) {
            $product = Product::query()->create([
                'name' => '商品' . $i,
                'price' => 500 + $i,
                'image' => 'products/sample' . $i . '.jpg',
                'description' => '説明' . $i,
            ]);
            $product->seasons()->sync([$seasons[0]->id]);
        }

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('商品1');
        $response->assertDontSee('商品7');
    }

    /**
     * @return void
     */
    public function test_products_can_be_filtered_by_keyword()
    {
        $seasons = $this->createSeasons();

        $apple = Product::query()->create([
            'name' => 'アップル',
            'price' => 500,
            'image' => 'products/apple.jpg',
            'description' => 'りんご',
        ]);
        $banana = Product::query()->create([
            'name' => 'バナナ',
            'price' => 600,
            'image' => 'products/banana.jpg',
            'description' => 'ばなな',
        ]);
        $apple->seasons()->sync([$seasons[0]->id]);
        $banana->seasons()->sync([$seasons[0]->id]);

        $response = $this->get('/products/search?keyword=バナ');

        $response->assertStatus(200);
        $response->assertSee('バナナ');
        $response->assertDontSee('アップル');
    }

    /**
     * @return void
     */
    public function test_products_can_be_sorted_by_price_descending()
    {
        $seasons = $this->createSeasons();

        $first = Product::query()->create([
            'name' => '低価格',
            'price' => 100,
            'image' => 'products/a.jpg',
            'description' => 'a',
        ]);
        $second = Product::query()->create([
            'name' => '高価格',
            'price' => 1000,
            'image' => 'products/b.jpg',
            'description' => 'b',
        ]);

        $first->seasons()->sync([$seasons[0]->id]);
        $second->seasons()->sync([$seasons[0]->id]);

        $response = $this->get('/products/search?sort=high');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['高価格', '低価格']);
    }

    /**
     * @return void
     */
    public function test_product_can_be_stored()
    {
        Storage::fake('public');
        $seasons = $this->createSeasons();

        $response = $this->post('/products/register', [
            'name' => 'テスト商品',
            'price' => 999,
            'description' => '説明文',
            'seasons' => [$seasons[0]->id, $seasons[1]->id],
            'image' => UploadedFile::fake()->create('test.jpeg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'price' => 999,
            'description' => '説明文',
        ]);

        $product = Product::query()->where('name', 'テスト商品')->firstOrFail();
        $this->assertCount(2, $product->seasons);
    }

    /**
     * @return void
     */
    public function test_store_requires_required_fields()
    {
        $this->createSeasons();

        $response = $this->from('/products/register')->post('/products/register', []);

        $response->assertRedirect('/products/register');
        $response->assertSessionHasErrors(['name', 'price', 'description', 'image', 'seasons']);
    }

    /**
     * @return void
     */
    public function test_store_validates_price_range()
    {
        Storage::fake('public');
        $seasons = $this->createSeasons();

        $response = $this->from('/products/register')->post('/products/register', [
            'name' => 'テスト商品',
            'price' => 20000,
            'description' => '説明文',
            'seasons' => [$seasons[0]->id],
            'image' => UploadedFile::fake()->create('test.jpeg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/products/register');
        $response->assertSessionHasErrors(['price']);
    }

    /**
     * @return void
     */
    public function test_product_can_be_updated()
    {
        Storage::fake('public');
        $seasons = $this->createSeasons();

        $product = Product::query()->create([
            'name' => '更新前',
            'price' => 300,
            'image' => 'products/old.jpg',
            'description' => '更新前説明',
        ]);
        $product->seasons()->sync([$seasons[0]->id]);

        $response = $this->put('/products/' . $product->id . '/update', [
            'name' => '更新後',
            'price' => 400,
            'description' => '更新後説明',
            'seasons' => [$seasons[2]->id],
            'image' => UploadedFile::fake()->create('updated.jpeg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => '更新後',
            'price' => 400,
            'description' => '更新後説明',
        ]);

        $product->refresh();
        $this->assertSame([$seasons[2]->id], $product->seasons()->pluck('seasons.id')->all());
    }

    /**
     * @return void
     */
    public function test_update_requires_image()
    {
        $seasons = $this->createSeasons();

        $product = Product::query()->create([
            'name' => '更新前',
            'price' => 300,
            'image' => 'products/old.jpg',
            'description' => '更新前説明',
        ]);
        $product->seasons()->sync([$seasons[0]->id]);

        $response = $this->from('/products/detail/' . $product->id)->put('/products/' . $product->id . '/update', [
            'name' => '更新後',
            'price' => 400,
            'description' => '更新後説明',
            'seasons' => [$seasons[1]->id],
        ]);

        $response->assertRedirect('/products/detail/' . $product->id);
        $response->assertSessionHasErrors(['image']);
    }

    /**
     * @return void
     */
    public function test_store_validates_description_max_length()
    {
        Storage::fake('public');
        $seasons = $this->createSeasons();

        $response = $this->from('/products/register')->post('/products/register', [
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => str_repeat('あ', 121),
            'seasons' => [$seasons[0]->id],
            'image' => UploadedFile::fake()->create('test.jpeg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect('/products/register');
        $response->assertSessionHasErrors(['description']);
    }

    /**
     * @return array<int, \App\Models\Season>
     */
    private function createSeasons()
    {
        return [
            Season::query()->create(['name' => '春']),
            Season::query()->create(['name' => '夏']),
            Season::query()->create(['name' => '秋']),
            Season::query()->create(['name' => '冬']),
        ];
    }
}
