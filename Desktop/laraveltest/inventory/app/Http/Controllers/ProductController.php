<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function fetchProducts()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric|min:0',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();

        return response()->json(['status' => 'success', 'message' => 'Product added successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::find($id);
        if ($product) {
            $product->name = $request->name;
            $product->quantity = $request->quantity;
            $product->price = $request->price;
            $product->save();

            return response()->json(['status' => 'success', 'message' => 'Product updated successfully']);
        }

        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json(['status' => 'success', 'message' => 'Product deleted successfully']);
        }

        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }
}
