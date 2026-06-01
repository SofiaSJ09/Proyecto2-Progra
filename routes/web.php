<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Vue Frontend
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('app');
});

/*
|--------------------------------------------------------------------------
| Administración (se mantiene por ahora)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return view('admin-login');
})->name('admin.login');

Route::post('/admin/login', function (Request $request) {

    if ($request->user === 'admin' && $request->password === '1234') {
        session(['admin_logged' => true]);

        return redirect()->route('admin.products');
    }

    return back()->with('error', 'Usuario o contraseña incorrectos');
})->name('admin.login.submit');

Route::get('/admin/productos', function () {

    if (!session('admin_logged')) {
        return redirect()->route('admin.login');
    }

    $products = Product::with('category')->paginate(7);
    $categories = Category::all();

    return view('admin-products', compact('products', 'categories'));

})->name('admin.products');

Route::post('/admin/productos', function (Request $request) {

    Product::create($request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'brand' => 'required|string',
        'ram' => 'required|string',
        'storage' => 'required|string',
        'operating_system' => 'required|string',
        'image' => 'required|string',
        'category_id' => 'required|exists:categories,id'
    ]));

    return redirect()
        ->route('admin.products')
        ->with('success', 'Producto agregado correctamente');

})->name('admin.products.store');

Route::put('/admin/productos/{id}', function (Request $request, $id) {

    $product = Product::findOrFail($id);

    $product->update($request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'brand' => 'required|string',
        'ram' => 'required|string',
        'storage' => 'required|string',
        'operating_system' => 'required|string',
        'image' => 'required|string',
        'category_id' => 'required|exists:categories,id'
    ]));

    return redirect()
        ->route('admin.products')
        ->with('success', 'Producto actualizado correctamente');

})->name('admin.products.update');

Route::delete('/admin/productos/{id}', function ($id) {

    Product::findOrFail($id)->delete();

    return redirect()
        ->route('admin.products')
        ->with('success', 'Producto eliminado correctamente');

})->name('admin.products.destroy');