<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();
        $filtered = $items->filter(function ($item) use ($request) {
            return $item->isVisible() && $item->category_id === $request->input('category_id');
        });
        return response()->json($filtered);
    }
}