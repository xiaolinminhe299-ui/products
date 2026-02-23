<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_root_redirects_to_products_index()
    {
        $response = $this->get('/');

        $response->assertRedirect('/products');
    }
}
