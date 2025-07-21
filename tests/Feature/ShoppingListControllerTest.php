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
      $shopping = ShoppingList::factory()
        ->hasAttached(
            Item::factory()->create(['name' => 'test item', 'user_id' => $this->user->id]),
            ['quantity' => 10, 'is_checked' => false]
        )
        ->create(['user_id' => $this->user->id, 'name' => 'test list']);

      $this->get(route('shopping_lists.show', $shopping))
        ->assertOk()
        ->assertSee('test list')
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
          ],
        ],
      ];

      $response = $this->post(route('shopping_lists.store'), $data);
      $shoppingList = ShoppingList::latest('id')->first();

      $response->assertRedirect(route('shopping_lists.index'));

      $this->assertEquals('test list', $shoppingList->name);
      $this->assertEquals($this->user->id, $shoppingList->user_id);

      $this->assertCount(1, $shoppingList->items);
      $item = $shoppingList->items->first();
      $this->assertEquals('りんご', $item->name);
      $this->assertEquals(2, $item->pivot->quantity);
      $this->assertFalse($item->pivot->is_checked);
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
      beforeEach(function () {
          $this->shopping = ShoppingList::factory()->create([
              'user_id' => $this->user->id,
              'name' => 'test list name'
          ]);
      });

      it('ショッピングリストの名前を変更できること', function () {
          $data = [
              'name' => 'changed list name'
          ];

          $response = $this->put(route('shopping_lists.update', $this->shopping), $data);

          $response->assertRedirect((route('shopping_lists.show', $this->shopping)));
          $this->shopping->refresh();
          $this->assertEquals('changed list name', $this->shopping->name);
      });

      it('アイテムを追加できること', function () {
          $data = [
              'name' => 'list name',
              'items' => [
                  [
                      'name' => 'changed item name',
                      'quantity' => 5,
                      'is_checked' => true
                  ]
              ]
          ];

          $response = $this->put(route('shopping_lists.update', $this->shopping), $data);

          $response->assertRedirect(route('shopping_lists.show', $this->shopping));

          $item = $this->shopping->items->first();
          $this->assertCount(1, $this->shopping->items);
          $this->assertEquals('changed item name', $item->name);
          $this->assertEquals(5, $item->pivot->quantity);
          $this->assertTrue($item->pivot->is_checked);
      });
  });

  describe('DELETE /destroy', function () {
      it('ショッピングリストを削除できること', function () {
          $shopping = ShoppingList::factory()->create([
              'user_id' => $this->user->id
          ]);

          $response = $this->delete(route('shopping_lists.destroy', $shopping));

          $response->assertRedirect(route('shopping_lists.index'));
          $this->assertModelMissing($shopping);
      });
  });

  describe('GET /index', function () {
      it('ショッピングリスト一覧が表示されること', function () {
          ShoppingList::factory()->count(2)->sequence(
              ['name' => 'first shopping list'],
              ['name' => 'second shopping list']
          )->create([
              'user_id' => $this->user->id
          ]);

          $this->get(route('shopping_lists.index'))
            ->assertOk()
            ->assertSee('first shopping list')
            ->assertSee('second shopping list');
      });
  });
});
