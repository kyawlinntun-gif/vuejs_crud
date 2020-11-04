<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class ListsController extends Controller
{

    public function index()
    {
        $items = Item::all();
        return response(
           $items
        );
    }

    public function store(Request $request)
    {
        // Validate items

        $request->validate([
            'name' => 'required|string',
            'age' => 'required|numeric',
            'profession' => 'required|string'
        ]);

        // Insert item
        $item = new Item();
        $item->name = ucwords($request->name);
        $item->age = $request->age;
        $item->profession = ucwords($request->profession);
        $item->save();

        return response(['success' => 'Items was stored successfully!']);
    }

    public function destroy(Request $request)
    {
        Item::find($request->itemId)->delete();
        return response(['success' => 'Delete successfully!']);
    }

    public function update(Request $request)
    {
        // Validate item

        $request->validate([
            'name' => 'required|string',
            'age' => 'required|numeric',
            'profession' => 'required|string'
        ]);

        // Update item

        $item = Item::find($request->id);
        $item->name = ucwords($request->name);
        $item->age = $request->age;
        $item->profession = ucwords($request->profession);
        $item->update(); 

        return response(['success' => 'Item was updated successfully!']);
    }
}
