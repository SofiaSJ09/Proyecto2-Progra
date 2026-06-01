<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
{
    return response()->json([
        'session_id' => session()->getId(),
        'cart' => session()->get('cart', [])
    ]);
}

    public function add($id)
{
    $product = Product::findOrFail($id);

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "name" => $product->name,
            "price" => $product->price,
            "quantity" => 1,
            "image" => $product->image
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'session_id' => session()->getId(),
        'cart' => session()->get('cart')
    ]);
}

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'message' => 'Producto eliminado del carrito',
            'cart' => $cart
        ]);
    }

    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'message' => 'Carrito vaciado'
        ]);
    }
}