<?php

use App\Models\Category;
use App\Models\Author;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('does not allow adding an item with zero stock to the cart', function () {
    // buat data pendukung
    $category = Category::create(['name' => 'Test Category']);
    $author = Author::create(['name' => 'Test Author']);
    $item = Item::create([
        'name' => 'Out of Stock Book',
        'category_id' => $category->id,
        'author_id' => $author->id,
        'price' => 50000,
        'stok' => 0,
    ]);

    // 1. request normal (non-AJAX)
    $response = $this->post(route('cart.add', $item->id), ['quantity' => 1]);
    $response->assertRedirect();
    $response->assertSessionHas('error', 'Stok buku habis');

    // 2. request AJAX returns JSON error
    $ajax = $this->post(route('cart.add', $item->id), ['quantity' => 1], [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);
    $ajax->assertStatus(400);
    $ajax->assertJson(['success' => false, 'message' => 'Stok tidak tersedia']);
});

it('redirects from checkout when cart contains more quantity than stock', function () {
    $category = Category::create(['name' => 'Test Cat2']);
    $author = Author::create(['name' => 'Test Author2']);
    $item = Item::create([
        'name' => 'Limited Book',
        'category_id' => $category->id,
        'author_id' => $author->id,
        'price' => 100000,
        'stok' => 2,
    ]);

    // tambahkan ke session cart qty 5
    $cartData = [
        $item->id => [
            'name' => $item->name,
            'quantity' => 5,
            'price' => $item->price,
            'image' => $item->image,
        ],
    ];

    $response = $this->withSession(['cart' => $cartData])->get(route('cart.checkout'));
    $response->assertRedirect(route('cart.index'));
    $response->assertSessionHas('error');
    expect(session('error'))->toContain('Stok untuk');
});
