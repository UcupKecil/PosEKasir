<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BuyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return response(
                $request->user()->buy()->get()
            );
        }
        return view('buy.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $product = Product::where('barcode', $barcode)->first();
        $buy = $request->user()->buy()->where('barcode', $barcode)->first();
        if ($buy) {
            // check product quantity
            if($product->quantity <= $buy->pivot->quantity) {
                return response([
                    'message' => 'Product available only: '. $product->quantity,
                ], 400);
            }
            // update only quantity
            $buy->pivot->quantity = $buy->pivot->quantity + 1;
            $buy->pivot->save();
        } else {
            if($product->quantity < 1) {
                return response([
                    'message' => 'Product out of stock',
                ], 400);
            }
            $request->user()->buy()->attach($product->id, ['quantity' => 1]);
        }

        return response('', 204);
    }

    public function changeQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $buy = $request->user()->buy()->where('id', $request->product_id)->first();

        if ($buy) {
            $buy->pivot->quantity = $request->quantity;
            $buy->pivot->save();
        }

        return response([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);
        $request->user()->buy()->detach($request->product_id);

        return response('', 204);
    }

    public function empty(Request $request)
    {
        $request->user()->buy()->detach();

        return response('', 204);
    }
}
