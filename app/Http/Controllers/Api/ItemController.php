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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
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
