<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class SaleController extends Controller

{
public function create()
{
    $products = Product::all();
    return view('sales.create', compact('products'));
}

public function store(Request $request)
{
    $request->validate([    
        'sale_date' => 'required|date',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    $sale = Sale::create([
        'sale_date' => $request->sale_date,
        'total_price' => 0
    ]);

    $total = 0;

    foreach ($request->products as $item) {
        $product = Product::find($item['product_id']);
        $quantity = $item['quantity'];
        $price = $product->price;

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price
        ]);

        $product->stock -= $quantity;
        $product->save();

        $total += $price * $quantity;
    }

    $sale->total_price = $total;
    $sale->save();

    return redirect()->route('sales.create')->with('success', 'Transaksi berhasil disimpan.');
    }
}