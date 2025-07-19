<?php

use App\Models\ShoppingList;
use App\Models\User;
use App\Models\Item;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('ShoppingListController', function () {
  beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
  });

  describe('GET /show', function () {
    it('ショッピングリストが表示されること', function () {
      $shopping = ShoppingList::factory()->create([
        'user_id' => $this->user->id,
      ]);
      $item = Item::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'test item'
      ]);
      $shopping->items()->attach($item->id, [
        'quantity' => 1,
        'is_checked' => false,
      ]);

        $this->get(route('shopping_lists.show', $shopping))
        ->assertStatus(200)
        ->assertSee($shopping->name)
        ->assertSee('test item');
    });
  });

  describe('POST /store', function () {
    it('ショッピングリストを作成できること', function () {
      $data = [
        'name' => 'test list',
        'items' => [
          [
            'name' => 'りんご',
            'quantity' => 2,
            'is_checked' => false,
          ],
        ],
      ];

      $response = $this->post(route('shopping_lists.store'), $data);
      $shoppingList = ShoppingList::where('user_id', $this->user->id)->first();
      $item = Item::where('name', 'りんご')->first();

      $response->assertStatus(302);
      $this->assertDatabaseHas('shopping_lists', [
        'name' => 'test list',
        'user_id' => $this->user->id
      ]);
      $this->assertDatabaseHas('items', [
        'name' => 'りんご',
        'user_id' => $this->user->id
      ]);
      $this->assertDatabaseHas('item_shopping_list', [
        'item_id' => $item->id,
        'shopping_list_id' => $shoppingList->id,
        'is_checked' => false,
        'quantity' => 2,
      ]);
    });
  });

  describe('GET /edit', function () {
    it('ショッピングリストが表示されること', function () {
    $shopping = ShoppingList::factory()->create([
      'user_id' => $this->user->id,
      'name' => 'test list name'
    ]);
    $item = Item::factory()->create([
      'user_id' => $this->user->id,
      'name' => 'test item'
    ]);
    $shopping->items()->attach($item->id, [
      'quantity' => 32,
      'is_checked' => false
    ]);

    $this->get(route('shopping_lists.edit', $shopping))
      ->assertStatus(200)
      ->assertSee('test list name')
      ->assertSee('test item')
      ->assertSee(32);
    });
  });

  describe('PUT /update', function () {
      it('ショッピングリストの名前を変更できること', function () {
          $data = [
              'name' => 'changed list name'
          ];
          $shopping = ShoppingList::factory()->create([
              'user_id' => $this->user->id,
              'name' => 'test list name'
          ]);

          $response = $this->put(route('shopping_lists.update', $shopping), $data);
          $response->assertRedirect((route('shopping_lists.show', $shopping)));
      });

      it('アイテムを追加できること', function () {
          $data = [
              'name' => 'list name',
              'items' => [
                  [
                      'name' => 'changed item name',
                      'quantity' => 5
                  ]
              ]
          ];
          $shopping = ShoppingList::factory()->create([
              'user_id' => $this->user->id,
              'name' => 'list name'
          ]);

          $response = $this->put(route('shopping_lists.update', $shopping), $data);

          $shopping->refresh();

          $response->assertRedirect(route('shopping_lists.show', $shopping));

          $item = $shopping->items->first();
          $this->assertCount(1, $shopping->items);
          $this->assertEquals('changed item name', $item->name);
          $this->assertEquals(5, $item->pivot->quantity);
      });
  });
});
