<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
{
    return response()->json([
        'session_id' => session()->getId(),
        'favorites' => session()->get('favorites', [])
    ]);
}

    public function add($id)
    {
        $product = Product::findOrFail($id);

        $favorites = session()->get('favorites', []);

        if (!isset($favorites[$id])) {
            $favorites[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('favorites', $favorites);

        return response()->json([
            'message' => 'Producto agregado a favoritos',
            'favorites' => $favorites
        ]);
    }

    public function remove($id)
    {
        $favorites = session()->get('favorites', []);

        if (isset($favorites[$id])) {
            unset($favorites[$id]);
            session()->put('favorites', $favorites);
        }

        return response()->json([
            'message' => 'Producto eliminado de favoritos',
            'favorites' => $favorites
        ]);
    }
}