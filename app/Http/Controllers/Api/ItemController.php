<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index()
    {
        $items = auth()->user()->items()->latest()->get();

        return Inertia::render('items/index', [
            'items' => $items
        ]);
    }

    public function create()
    {
        return Inertia::render('items/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'memo' => ['nullable', 'string', 'max:255']
        ]);

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'memo' => $validated['memo']
        ]);

        return Inertia::render('items/show', [
            'item' => $item
        ]);
    }

    public function show(Item $item)
    {
        return Inertia::render('items/show', [
            'item' => $item
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index');
    }
}
