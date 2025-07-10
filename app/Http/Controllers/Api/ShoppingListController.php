<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shoppingLists = auth()->user()->shoppingLists()->latest()->get();

        return Inertia::render('shopping-list/index', [
            'shoppingLists' => $shoppingLists
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $shoppingList = ShoppingList::create([
            'user_id' => auth()->id(),
            'name' => $validated['name']
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingList $shoppingList)
    {
        return Inertia::render('shopping-list/show', [
            'shoppingList' => $shoppingList
        ]);
    }

    public function edit(ShoppingList $shoppingList)
    {
        return Inertia::render('shopping-list/edit', [
            'shoppingList' => $shoppingList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShoppingList $shoppingList)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $shoppingList->update($validated);

        return redirect()->route('shopping_lists.show', $shoppingList);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoppingList $shoppingList)
    {
        $shoppingList->delete();

        return redirect()->route('shopping_lists.index');
    }
}
