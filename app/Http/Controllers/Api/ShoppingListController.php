<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingList;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShoppingListController extends Controller
{
    public function index()
    {
        $shoppingLists = auth()->user()->shoppingLists()->latest()->get();

        return Inertia::render('shopping-list/index', [
            'shoppingLists' => $shoppingLists
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'items' => ['array'],
            'items.*.name' => ['required_with:items', 'string', 'max:255'],
            'items.*.memo' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
        ]);

        $shoppingList = ShoppingList::create([
            'user_id' => auth()->id(),
            'name' => $validated['name']
        ]);

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $itemData) {
                if (!empty(trim($itemData['name']))) {
                    $item = Item::firstOrCreate([
                        'user_id' => auth()->id(),
                        'name' => $itemData['name'],
                    ], [
                        'memo' => $itemData['memo'] ?? '',
                    ]);

                    $shoppingList->items()->attach($item->id, [
                        'quantity' => $itemData['quantity'],
                        'is_checked' => false,
                    ]);
                }
            }
        }

        return redirect()->route('shopping_lists.index');
    }

    public function show(ShoppingList $shoppingList)
    {
        // 関連するアイテムとピボットテーブルの情報も含めて取得
        $shoppingList->load(['items' => function ($query) {
            $query->withPivot('quantity', 'is_checked', 'created_at', 'updated_at');
        }]);

        return Inertia::render('shopping-list/show', [
            'shoppingList' => $shoppingList
        ]);
    }

    public function edit(ShoppingList $shoppingList)
    {
        $shoppingList->load(['items' => function ($query) {
            $query->withPivot('quantity', 'is_checked', 'created_at', 'updated_at');
        }]);

        return Inertia::render('shopping-list/edit', [
            'shoppingList' => $shoppingList
        ]);
    }

    public function update(Request $request, ShoppingList $shoppingList)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'items' => ['array'],
            'items.*.name' => ['required_with:items', 'string', 'max:255'],
            'items.*.memo' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
        ]);
        $shoppingList->update([
            'name' => $validated['name'],
        ]);

        $items = $validated['items'] ?? [];
        $pivotData = [];
        foreach ($items as $itemData) {
            if (!empty(trim($itemData['name']))) {
                $item = Item::firstOrCreate([
                    'user_id' => auth()->id(),
                    'name' => $itemData['name'],
                ], [
                    'memo' => $itemData['memo'] ?? '',
                ]);
                $pivotData[$item->id] = [
                    'quantity' => $itemData['quantity'],
                    'is_checked' => false,
                ];
            }
        }
        $shoppingList->items()->sync($pivotData);

        return redirect()->route('shopping_lists.show', $shoppingList);
    }

    public function destroy(ShoppingList $shoppingList)
    {
        $shoppingList->delete();

        return redirect()->route('shopping_lists.index');
    }
}
